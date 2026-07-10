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
                <a href="{{ route('home') }}" class="hover:underline underline-offset-4">Home</a>
                <a href="{{ route('teams') }}" class="hover:underline underline-offset-4">Teams</a>
                <a href="{{ route('portfolio') }}" class="hover:underline underline-offset-4">Portofolio</a>
                <a href="{{ route('shop') }}" class="hover:underline underline-offset-4">Shop</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline underline-offset-4">Dashboard</a>
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
            <a href="{{ route('home') }}" class="block py-1">Home</a>
            <a href="{{ route('teams') }}" class="block py-1">Teams</a>
            <a href="{{ route('portfolio') }}" class="block py-1">Portofolio</a>
            <a href="{{ route('shop') }}" class="block py-1">Shop</a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="block py-1">Dashboard</a>
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

    <footer class="border-t border-zinc-200 dark:border-zinc-900 py-8 text-center text-xs tracking-widest uppercase opacity-40">
        &copy; 2026 JEM-PRODUCTION. Music Audio Production
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
</body>
</html>