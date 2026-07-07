@extends('layouts.app')

@section('title', 'JEM Production | Portfolio')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-12">
        <div class="space-y-4">
            <h1 class="font-display text-4xl sm:text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
                Portfolio
            </h1>
            <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-2xl leading-relaxed">
                Karya-karya produksi yang telah kami wujudkan dengan standar kualitas tertinggi dan fokus pada efisiensi.
            </p>
        </div>

        @if ($portfolios->isEmpty())
            <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center">
                <p class="text-zinc-500 dark:text-zinc-400">Belum ada data portofolio yang ditampilkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($portfolios as $item)
                    <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                        @if (!empty($item->external_link))
                            <a href="{{ $item->external_link }}" target="_blank" rel="noopener noreferrer" class="block aspect-video" aria-label="Buka link {{ $item->title }}">
                                @if (!empty($item->image))
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                                        <div class="text-white dark:text-black text-center">
                                            <div class="text-4xl font-display font-black mb-2">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                                            <div class="text-sm uppercase tracking-widest opacity-80">{{ $item->label }}</div>
                                        </div>
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="aspect-video">
                                @if (!empty($item->image))
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                                        <div class="text-white dark:text-black text-center">
                                            <div class="text-4xl font-display font-black mb-2">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</div>
                                            <div class="text-sm uppercase tracking-widest opacity-80">{{ $item->label }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold uppercase tracking-wide mb-2">{{ $item->title }}</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                                {{ $item->description }}
                            </p>
                            @if (!empty($item->tech_stack))
                                <div class="flex flex-wrap gap-2">
                                    @foreach (collect(explode(',', $item->tech_stack))->map(fn($v) => trim($v))->filter() as $tag)
                                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
