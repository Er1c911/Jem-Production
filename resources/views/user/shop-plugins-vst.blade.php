@extends('layouts.app')

@section('title', 'JEM Production | Plugins & VST')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-6">
        <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Back to Shop</a>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-[0.95] uppercase">Plugins &amp; VST</h1>
                    <p class="mt-4 text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-3xl leading-relaxed">
                        Halaman Plugins & VST sedang disiapkan. Daftar produk akan segera tersedia.
                    </p>
                </div>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-4 py-2 text-sm font-semibold hover:border-black dark:border-zinc-700 dark:hover:border-white transition">
                    View Chart
                </a>
            </div>

            <div class="mt-8 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-display text-2xl font-black tracking-tight uppercase">Bundle Essential Synth</h2>
                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Plugin VST siap pakai untuk produksi musik modern.</p>
                        <p class="mt-2 font-semibold">Rp 350.000</p>
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="plugin-bundle-essential">
                        <input type="hidden" name="name" value="Bundle Essential Synth">
                        <input type="hidden" name="price" value="350000">
                        <input type="hidden" name="type" value="plugin">
                        <button type="submit" class="rounded-full bg-black px-5 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-black dark:hover:bg-zinc-200 transition">
                            + Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection