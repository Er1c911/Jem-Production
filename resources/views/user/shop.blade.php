@extends('layouts.app')

@section('title', 'JEM Production | Shop')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-4">
                <h1 class="font-display text-4xl sm:text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
                    Shop
                </h1>            
            </div>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-4 py-2 text-sm font-semibold hover:border-black dark:border-zinc-700 dark:hover:border-white transition">
                View Chart
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('shop.plugins-vst') }}" class="group block rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 hover:border-black dark:hover:border-white transition duration-300">
                <h2 class="font-display text-2xl font-black tracking-tight">Plugins &amp; VST</h2>
                <p class="mt-3 text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    Plugins & VST Catalog
                </p>
                <span class="mt-5 inline-block text-sm font-semibold tracking-wide opacity-70 group-hover:opacity-100 transition">View page &rarr;</span>
            </a>

            <a href="{{ route('shop.sequencer') }}" class="group block rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 hover:border-black dark:hover:border-white transition duration-300">
                <h2 class="font-display text-2xl font-black tracking-tight">Sequencer</h2>
                <p class="mt-3 text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    Sequencer Catalog
                </p>
                <span class="mt-5 inline-block text-sm font-semibold tracking-wide opacity-70 group-hover:opacity-100 transition">View page &rarr;</span>
            </a>
        </div>
    </div>
</div>
@endsection