@extends('layouts.app')

@section('title', 'JEM Production | Keranjang')

@section('content')
<div class="max-w-6xl mx-auto w-full space-y-6">
    <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Kembali ke Shop</a>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="font-display text-4xl sm:text-5xl font-black tracking-tight leading-[0.95] uppercase">Chart</h1>
                <p class="mt-3 text-zinc-600 dark:text-zinc-400">List of items</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 px-4 py-3 text-sm">
                Total: <span class="font-semibold">Rp {{ number_format((float) ($cart['total'] ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        @if (empty($cart['items']))
            <div class="mt-8 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center text-zinc-600 dark:text-zinc-400">
                Keranjang Anda masih kosong.
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
        @endif
    </div>
</div>
@endsection
