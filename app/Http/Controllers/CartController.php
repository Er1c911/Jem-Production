<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QrisService;

class CartController extends Controller
{
    public function index(QrisService $qrisService)
    {
        $cart = session('cart', ['items' => [], 'total' => 0]);

        $qrisSvg = null;
        $qrisPayload = null;
        $bankTransfer = [
            'bank_name' => (string) config('payment.bank.name'),
            'account_name' => (string) config('payment.bank.account_name'),
            'account_number' => (string) config('payment.bank.account_number'),
        ];

        $totalAmount = (float) ($cart['total'] ?? 0);
        if ($totalAmount > 0) {
            $qrisPayloadFile = (string) config('payment.qris_payload_file', 'pay/qris.txt');
            $staticPayload = $qrisService->loadStaticPayloadFromFile(public_path($qrisPayloadFile));

            if ($staticPayload !== null) {
                $qrisPayload = $qrisService->buildDynamicPayload($staticPayload, $totalAmount);
                $qrisSvg = $qrisService->renderSvg($qrisPayload, 320);
            }
        }

        return view('user.cart', compact('cart', 'qrisSvg', 'qrisPayload', 'bankTransfer'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $cart = session('cart', ['items' => [], 'total' => 0]);
        $items = $cart['items'] ?? [];
        $found = false;

        foreach ($items as $index => $item) {
            if (($item['id'] ?? null) === $validated['id'] && ($item['type'] ?? null) === $validated['type']) {
                $items[$index]['quantity'] = (int) ($item['quantity'] ?? 1) + 1;
                $found = true;
                break;
            }
        }

        if (! $found) {
            $items[] = [
                'id' => $validated['id'],
                'name' => $validated['name'],
                'price' => (float) $validated['price'],
                'type' => $validated['type'],
                'quantity' => 1,
            ];
        }

        $total = 0;
        foreach ($items as $item) {
            $total += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
        }

        session()->put('cart', [
            'items' => array_values($items),
            'total' => $total,
        ]);

        return redirect()->route('cart.index')->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function remove(Request $request, string $id)
    {
        $cart = session('cart', ['items' => [], 'total' => 0]);
        $items = $cart['items'] ?? [];

        $filtered = array_values(array_filter($items, function ($item) use ($id) {
            return ($item['id'] ?? null) !== $id;
        }));

        $total = 0;
        foreach ($filtered as $item) {
            $total += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
        }

        session()->put('cart', [
            'items' => $filtered,
            'total' => $total,
        ]);

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function buy(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $cart = session('cart', ['items' => [], 'total' => 0]);
        $items = $cart['items'] ?? [];

        if (empty($items)) {
            return back()->withErrors([
                'email' => 'Keranjang masih kosong. Tambahkan item terlebih dahulu.',
            ])->withInput();
        }

        session()->put('cart', [
            'items' => [],
            'total' => 0,
        ]);

        return redirect()->route('cart.index')->with(
            'success',
            'Pembelian berhasil diproses untuk email ' . $validated['email'] . '.'
        );
    }
}
