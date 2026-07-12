@extends('layouts.app')

@section('title', 'JEM Production | Sequencer')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-6">
        <a href="{{ route('shop') }}" class="inline-flex items-center text-sm font-semibold tracking-wide opacity-70 hover:opacity-100 transition">&larr; Back to Shop</a>

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="space-y-4">
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-black tracking-tight leading-[0.95] uppercase">Sequencer</h1>
                <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-3xl leading-relaxed">
                    Sequencer catalog from JEM Production.
                </p>
            </div>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-4 py-2 text-sm font-semibold hover:border-black dark:border-zinc-700 dark:hover:border-white transition">
                View Chart
            </a>
        </div>

        @if ($sequencers->isEmpty())
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 md:p-10">
                <p class="text-zinc-600 dark:text-zinc-400">Belum ada produk sequencer yang tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($sequencers as $item)
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 space-y-4">
                        @include('partials.video-player', ['videoUrl' => $item->video_url, 'videoPath' => $item->video_path])

                        <div>
                            <h2 class="font-display text-2xl font-black tracking-tight uppercase">{{ $item->title }}</h2>
                            <p class="text-sm font-semibold opacity-70 mt-1">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                        </div>

                        <p class="text-zinc-600 dark:text-zinc-400 leading-tight whitespace-pre-line">{!! nl2br(e($item->description)) !!}</p>

                        <form action="{{ route('cart.add') }}" method="POST" class="pt-2">
                            @csrf
                            <input type="hidden" name="id" value="sequencer-{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->title }}">
                            <input type="hidden" name="price" value="{{ $item->price }}">
                            <input type="hidden" name="type" value="sequencer">
                            <button type="submit" class="rounded-full bg-black px-5 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-black dark:hover:bg-zinc-200 transition">
                                + Add to Cart
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
