@extends('layouts.app')

@section('title', 'JEM Production | Keranjang')

@section('content')
<div class="max-w-6xl mx-auto w-full space-y-6">
    <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Back to Shop</a>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="mb-4">
                    <img src="{{ asset('logo/Logo Toogle Terang.png') }}" alt="JEM Logo" class="h-16 w-auto dark:hidden">
                    <img src="{{ asset('logo/Logo Toogle Gelap.png') }}" alt="JEM Logo" class="hidden h-16 w-auto dark:block">
                </div>
                <p class="mt-3 text-zinc-600 dark:text-zinc-400">List of items</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 px-4 py-3 text-sm">
                Total: <span class="font-semibold">Rp {{ number_format((float) ($cart['total'] ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        @if (empty($cart['items']))
            <div class="mt-8 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center text-zinc-600 dark:text-zinc-400">
                Your cart is empty.
            </div>
        @else
            <div class="mt-8 space-y-4">
                @foreach ($cart['items'] as $item)
                    <div class="flex flex-col gap-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="font-semibold text-lg">{{ $item['name'] }}</h2>
                            <p class="text-sm text-zinc-500">{{ ucfirst($item['type'] ?? 'item') }} &middot; Qty {{ $item['quantity'] ?? 1 }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-semibold">Rp {{ number_format((float) (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 0, ',', '.') }}</span>
                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:border-red-900/40 dark:text-red-400 dark:hover:bg-red-950/30 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('cart.buy') }}" method="POST" enctype="multipart/form-data" class="mt-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-[1fr_auto] sm:items-end">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold">Masukkan email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-black dark:border-zinc-700 dark:bg-zinc-950 dark:focus:border-white"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="button" id="show-qris" class="inline-flex items-center justify-center rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white hover:opacity-90 dark:bg-white dark:text-black transition">
                        Buy
                    </button>
                </div>

                <div id="qris-panel" class="hidden rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 space-y-4">
                    <div>
                        <p class="text-sm font-semibold">Lakukan pembayaran</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Nominal harus sesuai total belanja, lalu upload bukti pembayaran.</p>
                    </div>

                    <div class="rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 p-3">
                        @if (!empty($qrisSvg))
                            <div class="mx-auto w-fit" aria-label="QRIS Dinamis">
                                {!! $qrisSvg !!}
                            </div>
                            <p class="mt-3 text-center text-xs text-zinc-500 dark:text-zinc-400">Scan QRIS dinamis dengan nominal otomatis.</p>
                        @else
                            <div class="space-y-2 text-sm">
                                <p class="font-semibold">Transfer Bank</p>
                                <p>Bank: <span class="font-semibold">{{ $bankTransfer['bank_name'] }}</span></p>
                                <p>Atas Nama: <span class="font-semibold">{{ $bankTransfer['account_name'] }}</span></p>
                                <p>No. Rekening: <span class="font-semibold">{{ $bankTransfer['account_number'] !== '' ? $bankTransfer['account_number'] : 'Belum diatur admin' }}</span></p>
                                @if ($bankTransfer['account_number'] === '')
                                    <p class="text-amber-700 dark:text-amber-300 text-xs">Admin belum mengatur nomor rekening. Isi .env: PAYMENT_ACCOUNT_NUMBER</p>
                                @endif
                            </div>
                        @endif

                        <p class="mt-3 text-center text-sm font-semibold">Nominal Bayar: Rp {{ number_format((float) ($cart['total'] ?? 0), 0, ',', '.') }}</p>
                    </div>

                    <div>
                        <label for="payment_proof" class="mb-2 block text-sm font-semibold">Upload bukti pembayaran</label>
                        <input
                            id="payment_proof"
                            name="payment_proof"
                            type="file"
                            accept="image/png,image/jpeg,image/webp"
                            required
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-black dark:border-zinc-700 dark:bg-zinc-950 dark:focus:border-white"
                        >
                        @error('payment_proof')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition">
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    const showQrisButton = document.getElementById('show-qris');
    const qrisPanel = document.getElementById('qris-panel');
    const shouldOpenQris = @json($errors->has('email') || $errors->has('payment_proof'));

    if (showQrisButton && qrisPanel) {
        showQrisButton.addEventListener('click', () => {
            qrisPanel.classList.remove('hidden');
            qrisPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        if (shouldOpenQris) {
            qrisPanel.classList.remove('hidden');
        }
    }
</script>
@endsection
