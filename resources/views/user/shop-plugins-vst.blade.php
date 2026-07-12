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
                        Plugins & VST catalog from JEM Production.
                    </p>
                </div>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center rounded-full border border-zinc-300 px-4 py-2 text-sm font-semibold hover:border-black dark:border-zinc-700 dark:hover:border-white transition">
                    View Chart
                </a>
            </div>

            @if ($plugins->isEmpty())
                <div class="mt-8 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center text-zinc-600 dark:text-zinc-400">
                    Belum ada plugin yang tersedia.
                </div>
            @else
                <div class="mt-8 space-y-6">
                    @foreach ($plugins as $item)
                        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 p-0 overflow-hidden bg-white dark:bg-zinc-900 shadow-sm group transform transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row">
                                <div class="sm:w-1/3 w-full bg-zinc-50 dark:bg-zinc-950 overflow-hidden">
                                    @php
                                        $disk = config('filesystems.default') ?? env('FILESYSTEM_DISK', 'local');
                                        if ($disk === 'local') {
                                            $disk = 'public';
                                        }
                                        $imageExists = false;
                                        $imageSrc = null;
                                        if (!empty($item->image)) {
                                            // remote URL?
                                            if (preg_match('/^https?:\/\//i', $item->image)) {
                                                $imageExists = true;
                                                $imageSrc = $item->image;
                                            } else {
                                                try {
                                                    $imageExists = \Illuminate\Support\Facades\Storage::disk($disk)->exists($item->image);
                                                    if ($imageExists && $disk === 'public') {
                                                        $publicPath = public_path('storage/' . $item->image);
                                                        $imageExists = $imageExists && file_exists($publicPath);
                                                    }
                                                    if ($imageExists) {
                                                        $imageSrc = \Illuminate\Support\Facades\Storage::url($item->image);
                                                    }
                                                } catch (\Exception $e) {
                                                    $imageExists = false;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($imageExists)
                                        <img src="{{ $imageSrc }}" alt="{{ $item->title }}" class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <div class="h-44 w-full flex items-center justify-center text-zinc-400">No image</div>
                                    @endif
                                </div>

                                <div class="sm:w-2/3 w-full p-6 flex flex-col justify-between gap-4">
                                    <div>
                                        <h2 class="font-display text-2xl font-black tracking-tight uppercase leading-tight">{{ $item->title }}</h2>
                                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-none">{{ $item->description }}</p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                        
                                            <p class="mt-1 text-lg font-bold">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                                        </div>

                                        <form action="{{ route('cart.add') }}" method="POST" class="ml-4">
                                            @csrf
                                            <input type="hidden" name="id" value="plugin-{{ $item->id }}">
                                            <input type="hidden" name="name" value="{{ $item->title }}">
                                            <input type="hidden" name="price" value="{{ $item->price }}">
                                            <input type="hidden" name="type" value="plugin">
                                            <button type="submit" class="rounded-full bg-black px-6 py-2 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-black dark:hover:bg-zinc-200 transition">
                                                + Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection