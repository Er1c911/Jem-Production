@extends('layouts.app')

@section('title', 'JEM Production | Sequencer')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-6">
        <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Kembali ke Shop</a>

        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-[0.95] uppercase">Sequencer</h1>
            <p class="mt-4 text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-3xl leading-relaxed">
                Halaman Sequencer sedang disiapkan. Konten dan katalog akan segera ditambahkan.
            </p>
        </div>
    </div>
</div>
@endsection
