<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jem Production')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-50 text-black dark:bg-zinc-950 dark:text-white transition-colors duration-500 font-sans min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-50 backdrop-blur-md bg-zinc-50/70 dark:bg-zinc-950/70 border-b border-zinc-200 dark:border-zinc-800 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 transition-colors duration-500">
        <div class="flex justify-between items-center w-full">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-display text-xl font-extrabold tracking-tight uppercase hover:opacity-80 transition">
            <span class="inline-flex items-center">
                <img src="{{ asset('logo/Logo Toogle Terang.png') }}" alt="JEM Logo" class="h-7 w-auto dark:hidden">
                <img src="{{ asset('logo/Logo Toogle Gelap.png') }}" alt="JEM Logo" class="hidden h-7 w-auto dark:block">
            </span>
            <span>jem<span class="font-light opacity-50">.prod</span></span>
        </a>

        <div class="hidden md:flex items-center gap-8">
            <nav class="flex gap-6 text-sm font-medium tracking-wide">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Home</a>
                <a href="{{ route('teams') }}" class="{{ request()->routeIs('teams') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Teams</a>
                <a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Portofolio</a>
                <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop*') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Shop</a>
                @auth
                    @php
                        $pendingPaymentsCount = 0;
                        if (\Illuminate\Support\Facades\Schema::hasTable('payment_confirmations')) {
                            $pendingPaymentsCount = \App\Models\PaymentConfirmation::where('status', 'pending')->count();
                        }
                    @endphp
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Dashboard</a>
                    <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'text-black dark:text-white font-semibold underline decoration-2 underline-offset-4' : 'text-zinc-600 dark:text-zinc-400 hover:text-black dark:hover:text-white hover:underline underline-offset-4' }}">Payments @if($pendingPaymentsCount > 0)<span class="ml-1 rounded-full bg-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">{{ $pendingPaymentsCount }}</span>@endif</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="opacity-60 hover:opacity-100 transition">Logout</button>
                    </form>
                @endauth
            </nav>

            <button id="theme-toggle" class="relative inline-flex h-6 w-11 items-center rounded-full border border-black dark:border-white transition-colors duration-300 focus:outline-none">
                <span id="toggle-circle" class="inline-block h-4 w-4 transform rounded-full bg-black dark:bg-white transition-transform duration-300 translate-x-1 dark:translate-x-6"></span>
            </button>
        </div>

        <div class="md:hidden flex items-center gap-3">
            <button id="theme-toggle-mobile" class="relative inline-flex h-6 w-11 items-center rounded-full border border-black dark:border-white transition-colors duration-300 focus:outline-none" aria-label="Toggle dark mode">
                <span id="toggle-circle-mobile" class="inline-block h-4 w-4 transform rounded-full bg-black dark:bg-white transition-transform duration-300 translate-x-1 dark:translate-x-6"></span>
            </button>
            <button id="mobile-menu-button" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-zinc-300 dark:border-zinc-700" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        </div>

        <nav id="mobile-menu" class="md:hidden hidden mt-4 border-t border-zinc-200 dark:border-zinc-800 pt-4 space-y-3 text-sm font-medium tracking-wide">
            <a href="{{ route('home') }}" class="block py-1 {{ request()->routeIs('home') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Home</a>
            <a href="{{ route('teams') }}" class="block py-1 {{ request()->routeIs('teams') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Teams</a>
            <a href="{{ route('portfolio') }}" class="block py-1 {{ request()->routeIs('portfolio') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Portofolio</a>
            <a href="{{ route('shop') }}" class="block py-1 {{ request()->routeIs('shop*') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Shop</a>
            @auth
                @php
                    $pendingPaymentsCount = 0;
                    if (\Illuminate\Support\Facades\Schema::hasTable('payment_confirmations')) {
                        $pendingPaymentsCount = \App\Models\PaymentConfirmation::where('status', 'pending')->count();
                    }
                @endphp
                <a href="{{ route('admin.dashboard') }}" class="block py-1 {{ request()->routeIs('admin.dashboard') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Dashboard</a>
                <a href="{{ route('admin.payments.index') }}" class="block py-1 {{ request()->routeIs('admin.payments.*') ? 'text-black dark:text-white font-semibold' : 'text-zinc-600 dark:text-zinc-400' }}">Payments @if($pendingPaymentsCount > 0)<span class="ml-1 rounded-full bg-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">{{ $pendingPaymentsCount }}</span>@endif</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="py-1 opacity-60 hover:opacity-100 transition">Logout</button>
                </form>
            @endauth
        </nav>
    </header>

    <main class="flex-grow flex flex-col justify-center px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 dark:border-zinc-900 py-8 px-4 sm:px-6 lg:px-8 text-xs tracking-widest uppercase opacity-40">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 max-w-7xl mx-auto">
            <span class="text-center sm:text-left">&copy; 2026 JEM-PRODUCTION. Music Audio Production</span>
            <a href="https://www.instagram.com/jem._.production/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 hover:opacity-80 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
                <span>@jem._.production</span>
            </a>
        </div>
    </footer>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleMobileBtn = document.getElementById('theme-toggle-mobile');
        const toggleCircle = document.getElementById('toggle-circle');
        const toggleCircleMobile = document.getElementById('toggle-circle-mobile');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        function updateToggleUI(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                toggleCircle.className = "inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 translate-x-6";
                if (toggleCircleMobile) {
                    toggleCircleMobile.className = "inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 translate-x-6";
                }
            } else {
                document.documentElement.classList.remove('dark');
                toggleCircle.className = "inline-block h-4 w-4 transform rounded-full bg-black transition-transform duration-300 translate-x-1";
                if (toggleCircleMobile) {
                    toggleCircleMobile.className = "inline-block h-4 w-4 transform rounded-full bg-black transition-transform duration-300 translate-x-1";
                }
            }
        }

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            updateToggleUI(true);
        } else {
            updateToggleUI(false);
        }

        themeToggleBtn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateToggleUI(!isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
        });

        if (themeToggleMobileBtn) {
            themeToggleMobileBtn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.contains('dark');
                updateToggleUI(!isDark);
                localStorage.setItem('theme', !isDark ? 'dark' : 'light');
            });
        }

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>