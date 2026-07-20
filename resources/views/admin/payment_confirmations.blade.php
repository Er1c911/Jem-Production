@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - Admin')

@section('content')
<div class="max-w-7xl w-full mx-auto space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-zinc-200 dark:border-zinc-800 pb-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest opacity-50 font-display">Notification</span>
            <h1 class="font-display text-3xl sm:text-4xl font-black uppercase tracking-tight mt-1">Konfirmasi Pembayaran</h1>
            <p class="text-sm text-zinc-500">Pending: <span class="font-semibold text-black dark:text-white">{{ $pendingCount }}</span></p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold opacity-70 hover:opacity-100 transition">&larr; Kembali ke Dashboard</a>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('payment'))
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            {{ $errors->first('payment') }}
        </div>
    @endif

    @if ($payments->isEmpty())
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-500 dark:text-zinc-400">
            Belum ada konfirmasi pembayaran.
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($payments as $payment)
                <div class="rounded-2xl border {{ $payment->status === 'pending' ? 'border-amber-300 dark:border-amber-800' : 'border-zinc-200 dark:border-zinc-800' }} bg-white dark:bg-zinc-900 p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-widest opacity-50">Email Pembeli</p>
                            <p class="font-semibold break-all">{{ $payment->customer_email }}</p>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded {{ $payment->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' }}">
                            {{ $payment->status }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-widest opacity-50 mb-2">Item Dibeli</p>
                        <ul class="space-y-1 text-sm">
                            @foreach (($payment->items ?? []) as $item)
                                <li>
                                    {{ $item['name'] ?? '-' }} ({{ ucfirst($item['type'] ?? 'item') }}) x{{ (int) ($item['quantity'] ?? 1) }} - Rp {{ number_format(((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 1)), 0, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        <span class="font-semibold">Total: Rp {{ number_format((float) $payment->total_amount, 0, ',', '.') }}</span>
                        @php
                            $proofUrl = preg_match('/^https?:\/\//i', (string) $payment->payment_proof_path)
                                ? (string) $payment->payment_proof_path
                                : asset('storage/' . $payment->payment_proof_path);
                        @endphp
                        <a href="{{ $proofUrl }}" target="_blank" rel="noopener noreferrer" class="underline underline-offset-4">Lihat bukti pembayaran</a>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        @if ($payment->status === 'pending')
                            <form action="{{ route('admin.payments.acceptById') }}" method="POST" onsubmit="return confirmAcceptTwice(event)">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white hover:opacity-90 dark:bg-white dark:text-black transition">
                                    Accepted
                                </button>
                            </form>

                            <form action="{{ route('admin.payments.cancel', $payment) }}" method="POST" class="flex-1 min-w-[240px]">
                                @csrf
                                <div class="flex flex-col gap-2">
                                    <input
                                        type="text"
                                        name="cancel_reason"
                                        placeholder="Alasan cancel (contoh: bukti pembayaran tidak sesuai)"
                                        required
                                        class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm outline-none transition focus:border-black dark:border-zinc-700 dark:bg-zinc-950 dark:focus:border-white"
                                    >
                                    <button type="submit" onclick="return confirm('Yakin ingin membatalkan pembayaran ini?')" class="inline-flex w-fit items-center justify-center rounded-lg border border-amber-200 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-50 dark:border-amber-900/40 dark:text-amber-300 dark:hover:bg-amber-950/30 transition">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        @else
                            @if ($payment->status === 'accepted')
                                <p class="text-xs opacity-60">Diproses oleh admin pada {{ optional($payment->accepted_at)->format('d M Y H:i') ?? '-' }}</p>
                            @endif

                            @if ($payment->status === 'cancelled')
                                <div class="text-xs space-y-1">
                                    <p class="opacity-60">Dibatalkan pada {{ optional($payment->cancelled_at)->format('d M Y H:i') ?? '-' }}</p>
                                    @if (!empty($payment->cancel_reason))
                                        <p class="text-red-600 dark:text-red-400">Alasan: {{ $payment->cancel_reason }}</p>
                                    @endif
                                </div>
                            @endif
                        @endif

                        @if ($payment->status !== 'pending')
                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')" class="inline-flex items-center justify-center rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:text-red-400 dark:hover:bg-red-950/30 transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmAcceptTwice(event) {
    if (!confirm('Yakin ingin menerima pembayaran ini?')) {
        if (event) event.preventDefault();
        return false;
    }

    if (!confirm('Konfirmasi sekali lagi: lanjutkan proses ACCEPTED untuk pembayaran ini?')) {
        if (event) event.preventDefault();
        return false;
    }

    return true;
}
</script>
@endpush
