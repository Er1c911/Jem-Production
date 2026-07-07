@extends('layouts.app')

@section('title', 'Admin Gateway')

@section('content')
<div class="max-w-md w-full mx-auto border border-black dark:border-white p-6 sm:p-8 bg-neutral-50 dark:bg-neutral-900">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black uppercase tracking-wider">Admin Portal</h2>
        <p class="text-xs opacity-60 uppercase mt-1">Gunakan kredensial jem-production</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-black text-white dark:bg-white dark:text-black p-4 text-sm uppercase tracking-wider">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-xs uppercase tracking-widest font-bold mb-2">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                class="w-full bg-transparent border border-black dark:border-white p-3 text-sm focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">
        </div>

        <div>
            <label class="block text-xs uppercase tracking-widest font-bold mb-2">Password</label>
            <input type="password" name="password" required
                class="w-full bg-transparent border border-black dark:border-white p-3 text-sm focus:outline-none focus:ring-1 focus:ring-black dark:focus:ring-white">
        </div>

        <button type="submit" 
            class="w-full bg-black text-white dark:bg-white dark:text-black p-4 text-xs uppercase tracking-widest font-black hover:bg-neutral-800 dark:hover:bg-neutral-200 transition">
            Authorize Entry
        </button>
    </form>
</div>
@endsection