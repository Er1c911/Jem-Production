@extends('layouts.app')

@section('title', 'JEM Production | Teams')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-12">
        <div class="space-y-4">
            <h1 class="font-display text-4xl sm:text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
                Our Teams
            </h1>
        </div>

        @if ($teams->isEmpty())
            <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center">
                <p class="text-zinc-500 dark:text-zinc-400">Belum ada anggota tim yang ditampilkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($teams as $member)
                    <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300 flex flex-col">
                        <!-- Photo Section - Portrait -->
                        @if ($member->photo)
                            <div class="relative w-full h-80">
                                <img src="{{ route('team.photo', ['path' => $member->photo], false) }}" alt="{{ $member->name }}" class="w-full h-80 object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden w-full h-80 bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center text-white dark:text-black font-display text-6xl font-bold">
                                    {{ $member->initials }}
                                </div>
                            </div>
                        @else
                            <div class="w-full h-80 bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center text-white dark:text-black font-display text-6xl font-bold">
                                {{ $member->initials }}
                            </div>
                        @endif
                        
                        <!-- Info Section -->
                        <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold uppercase tracking-wide mb-2">{{ $member->name }}</h3>
                                <p class="text-sm opacity-70 mb-4 font-medium">{{ $member->position }}</p>
                            </div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ $member->description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
