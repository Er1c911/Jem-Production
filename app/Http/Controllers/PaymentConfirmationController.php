<?php

namespace App\Http\Controllers;

use App\Mail\AdminPaymentSubmitted;
use App\Mail\PaymentCancelledNotice;
use App\Mail\PurchaseItemsDelivered;
use App\Models\PaymentConfirmation;
use App\Models\Plugin;
use App\Models\Sequencer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentConfirmationController extends Controller
{
    private function isVercel(): bool
    {
        return (bool) env('VERCEL');
    }

    public function index()
    {
        $payments = PaymentConfirmation::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->get();

        $pendingCount = PaymentConfirmation::where('status', 'pending')->count();

        return view('admin.payment_confirmations', compact('payments', 'pendingCount'));
    }

    public function acceptById(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|integer',
        ]);

        $payment = PaymentConfirmation::find((int) $validated['payment_id']);
        if (! $payment) {
            return back()->withErrors([
                'payment' => 'Data pembayaran tidak ditemukan.',
            ]);
        }

        return $this->processAccept($payment);
    }

    public function accept(PaymentConfirmation $payment)
    {
        return $this->processAccept($payment);
    }

    private function processAccept(PaymentConfirmation $payment)
    {
        $mailer = $this->resolveTransactionalMailer();

        if ($mailer === null) {
            return back()->withErrors([
                'payment' => 'Email belum aktif di server (mailer masih log/array). Atur SMTP terlebih dahulu.',
            ]);
        }

        if ($payment->status !== 'pending') {
            return back()->withErrors([
                'payment' => 'Pembayaran ini sudah diproses sebelumnya.',
            ]);
        }

        try {
            DB::transaction(function () use ($payment, $mailer) {
                $itemsWithLinks = $this->enrichPurchasedItems($payment->items ?? []);

                $payment->update([
                    'items' => $itemsWithLinks,
                    'status' => 'accepted',
                    'accepted_at' => now(),
                    'accepted_by' => Auth::id(),
                ]);

                Mail::mailer($mailer)->to($payment->customer_email)->send(new PurchaseItemsDelivered(
                    items: $itemsWithLinks,
                    customerEmail: $payment->customer_email,
                    totalAmount: (float) $payment->total_amount,
                ));
            });
        } catch (\Throwable $e) {
            Log::error('Accept payment failed', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Gagal memproses acceptance atau kirim email. Silakan coba lagi.',
            ]);
        }

        return back()->with('success', 'Pembayaran diterima. Item telah dikirim ke email pembeli.');
    }

    public function cancel(Request $request, PaymentConfirmation $payment)
    {
        $mailer = $this->resolveTransactionalMailer();

        if ($mailer === null) {
            return back()->withErrors([
                'payment' => 'Email belum aktif di server (mailer masih log/array). Atur SMTP terlebih dahulu.',
            ]);
        }

        if ($payment->status !== 'pending') {
            return back()->withErrors([
                'payment' => 'Pembayaran ini sudah diproses sebelumnya.',
            ]);
        }

        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($payment, $validated, $mailer) {
                $payment->update([
                    'status' => 'cancelled',
                    'cancel_reason' => $validated['cancel_reason'],
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id(),
                ]);

                Mail::mailer($mailer)->to($payment->customer_email)->send(new PaymentCancelledNotice(
                    payment: $payment->fresh(),
                    cancelReason: $validated['cancel_reason'],
                ));
            });
        } catch (\Throwable $e) {
            Log::error('Cancel payment failed', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Gagal membatalkan pembayaran atau kirim email. Silakan coba lagi.',
            ]);
        }

        return back()->with('success', 'Pembayaran ditolak dan email pembatalan sudah dikirim ke user.');
    }

    public function storeFromCart(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:255',
        ];

        if ($this->isVercel()) {
            $rules['proof_url'] = 'required|url|max:2048';
        } else {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        $validated = $request->validate($rules);

        $cart = session('cart', ['items' => [], 'total' => 0]);
        $items = $cart['items'] ?? [];

        if (empty($items)) {
            return back()->withErrors([
                'payment_proof' => 'Keranjang kosong. Tambahkan item sebelum konfirmasi pembayaran.',
            ])->withInput();
        }

        $proofPath = $this->isVercel()
            ? (string) ($validated['proof_url'] ?? '')
            : $request->file('payment_proof')->store('payments/proofs', 'public');
        $itemsWithLinks = $this->enrichPurchasedItems(array_values($items));

        $payment = PaymentConfirmation::create([
            'customer_email' => $validated['email'],
            'total_amount' => (float) ($cart['total'] ?? 0),
            'items' => $itemsWithLinks,
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        $mailer = $this->resolveTransactionalMailer();

        try {
            if ($mailer !== null) {
                Mail::mailer($mailer)
                    ->to((string) config('payment.notification_email', 'jem.production26@gmail.com'))
                    ->send(new AdminPaymentSubmitted($payment));
            }
        } catch (\Throwable $e) {
            Log::error('Send admin payment notification failed', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);
        }

        if ($mailer === null) {
            Log::warning('Admin payment notification skipped because SMTP is not configured', [
                'payment_id' => $payment->id,
            ]);
        }

        session()->put('cart', [
            'items' => [],
            'total' => 0,
        ]);

        return redirect()->route('cart.index')->with('success', 'Konfirmasi pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function destroy(PaymentConfirmation $payment)
    {
        try {
            $isExternalProofUrl = ! empty($payment->payment_proof_path)
                && preg_match('/^https?:\/\//i', $payment->payment_proof_path) === 1;

            if (! $isExternalProofUrl && ! empty($payment->payment_proof_path) && Storage::disk('public')->exists($payment->payment_proof_path)) {
                Storage::disk('public')->delete($payment->payment_proof_path);
            }

            $payment->delete();
        } catch (\Throwable $e) {
            Log::error('Delete payment confirmation failed', [
                'payment_id' => $payment->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Gagal menghapus data pembayaran. Silakan coba lagi.',
            ]);
        }

        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }

    private function enrichPurchasedItems(array $items): array
    {
        return array_map(function (array $item) {
            $type = (string) ($item['type'] ?? 'item');
            $rawId = (string) ($item['id'] ?? '');
            $numericId = $this->extractNumericId($rawId);
            $deliveryLink = null;

            if ($type === 'sequencer') {
                if ($numericId !== null) {
                    $sequencer = Sequencer::find($numericId);
                    $deliveryLink = $sequencer?->sequencer_link ?: $sequencer?->video_url;
                }

                $deliveryLink = $deliveryLink ?: route('shop.sequencer');
            }

            if ($type === 'plugin') {
                if ($numericId !== null) {
                    $plugin = Plugin::find($numericId);

                    if ($plugin && isset($plugin->link) && !empty($plugin->link)) {
                        $deliveryLink = (string) $plugin->link;
                    }
                }

                $deliveryLink = $deliveryLink ?: route('shop.plugins-vst');
            }

            $item['delivery_link'] = $deliveryLink;

            return $item;
        }, $items);
    }

    private function extractNumericId(string $rawId): ?int
    {
        if (preg_match('/(\d+)$/', $rawId, $matches) !== 1) {
            return null;
        }

        return (int) $matches[1];
    }

    private function resolveTransactionalMailer(): ?string
    {
        $defaultMailer = (string) config('mail.default', 'log');

        if (! in_array($defaultMailer, ['log', 'array'], true)) {
            return $defaultMailer;
        }

        $smtpHost = (string) config('mail.mailers.smtp.host', '');
        $smtpPort = (int) config('mail.mailers.smtp.port', 0);
        $smtpUsername = (string) config('mail.mailers.smtp.username', '');
        $smtpPassword = (string) config('mail.mailers.smtp.password', '');

        if ($smtpHost === '' || $smtpPort <= 0 || $smtpUsername === '' || $smtpPassword === '') {
            return null;
        }

        return 'smtp';
    }
}
