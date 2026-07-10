@extends('layouts.app')

@section('title', 'JEM Production | Sequencer')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-6">
        <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Kembali ke Shop</a>

        <div class="space-y-4">
            <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-[0.95] uppercase">Sequencer</h1>
            <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-3xl leading-relaxed">
                Katalog sequencer terbaru dari JEM Production.
            </p>
        </div>

        @if ($sequencers->isEmpty())
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
                <p class="text-zinc-600 dark:text-zinc-400">Belum ada produk sequencer yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($sequencers as $item)
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 space-y-4">
                        <div class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800 bg-black">
                            @if (!empty($item->video_path))
                                <video controls class="w-full h-52 object-cover">
                                    <source src="{{ asset('storage/' . $item->video_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            @else
                                <div class="h-52 flex items-center justify-center text-zinc-300 text-sm uppercase tracking-widest">Tanpa Video</div>
                            @endif
                        </div>

                        <div>
                            <h2 class="font-display text-2xl font-black tracking-tight uppercase">{{ $item->title }}</h2>
                            <p class="text-sm opacity-70 mt-1">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                        </div>

                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $item->description }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
