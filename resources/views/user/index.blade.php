@extends('layouts.app')

@section('title', 'JEM Production | Home')

@section('content')
<div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-7 space-y-8">
        <h1 class="font-display text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
            JEM<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-black via-zinc-400 to-black dark:from-white dark:via-zinc-500 dark:to-white">Production</span>
        </h1>
        <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-xl leading-relaxed">
            Turning your audio into reality. From song arrangement to the final master, we deliver exceptional music production.
        </p>
    </div>    
</div>
@endsection