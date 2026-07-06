@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl w-full mx-auto space-y-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-zinc-200 dark:border-zinc-800 pb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest opacity-50 font-display">Overview</span>
            <h1 class="font-display text-4xl font-black uppercase tracking-tight mt-1">Control Room</h1>
            <p class="text-sm text-zinc-500">Welcome back, <span class="font-semibold text-black dark:text-white">{{ Auth::user()->name }}</span></p>
        </div>
        <div class="flex items-center gap-3 bg-zinc-100 dark:bg-zinc-900 p-2 rounded-xl border border-zinc-200 dark:border-zinc-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block ml-2"></span>
            <span class="text-xs font-bold uppercase tracking-wider pr-2">Secure node active</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('admin.teams.index') }}" class="group bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800/80 p-8 rounded-2xl shadow-sm hover:border-black dark:hover:border-white transition duration-300 flex flex-col justify-between">
            <div>
                <div class="text-xs uppercase tracking-widest font-bold opacity-40">Management</div>
                <div class="font-display text-3xl font-black tracking-tight mt-4">Teams</div>
            </div>
            <span class="text-xs font-medium text-zinc-500 mt-6 group-hover:text-black dark:group-hover:text-white transition">Kelola anggota tim →</span>
        </a>
    </div>
</div>
@endsection