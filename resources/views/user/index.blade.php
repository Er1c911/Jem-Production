@extends('layouts.app')

@section('title', 'JEM Production | Home')

@section('content')
<div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-7 space-y-8">
        <div class="flex flex-col items-start gap-5 sm:gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="order-1 lg:order-2 pt-1 sm:pt-2 md:pt-3 shrink-0 self-center lg:self-auto">
                <img src="{{ asset('logo/Logo Toogle Terang.png') }}" alt="JEM Logo" class="h-24 sm:h-28 md:h-32 lg:h-36 w-auto dark:hidden">
                <img src="{{ asset('logo/Logo Toogle Gelap.png') }}" alt="JEM Logo" class="hidden h-24 sm:h-28 md:h-32 lg:h-36 w-auto dark:block">
            </div>
            <h1 class="order-2 lg:order-1 text-center lg:text-left font-display text-4xl sm:text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
                JEM<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-black via-zinc-400 to-black dark:from-white dark:via-zinc-500 dark:to-white">Production</span>
            </h1>
        </div>
        <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-xl leading-relaxed">
            Turning your audio into reality. From song arrangement to the final master, we deliver exceptional music production.
        </p>
    </div>    
</div>
@endsection