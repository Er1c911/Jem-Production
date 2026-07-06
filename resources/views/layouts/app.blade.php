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
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-50 text-black dark:bg-zinc-950 dark:text-white transition-colors duration-500 font-sans min-h-screen flex flex-col antialiased">

    <header class="sticky top-0 z-50 backdrop-blur-md bg-zinc-50/70 dark:bg-zinc-950/70 border-b border-zinc-200 dark:border-zinc-800 px-8 py-5 flex justify-between items-center transition-colors duration-500">
        <a href="{{ route('home') }}" class="font-display text-xl font-extrabold tracking-tight uppercase hover:opacity-80 transition">
            jem<span class="font-light opacity-50">.prod</span>
        </a>
        
        <div class="flex items-center gap-8">
            <nav class="flex gap-6 text-sm font-medium tracking-wide">
                <a href="{{ route('home') }}" class="hover:underline underline-offset-4">Home</a>
                <a href="{{ route('teams') }}" class="hover:underline underline-offset-4">Teams</a>
                <a href="{{ route('portfolio') }}" class="hover:underline underline-offset-4">Portfolio</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline underline-offset-4">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="opacity-60 hover:opacity-100 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline underline-offset-4 opacity-80 hover:opacity-100 transition">Admin Portal</a>
                @endauth
            </nav>

            <button id="theme-toggle" class="relative inline-flex h-6 w-11 items-center rounded-full border border-black dark:border-white transition-colors duration-300 focus:outline-none">
                <span id="toggle-circle" class="inline-block h-4 w-4 transform rounded-full bg-black dark:bg-white transition-transform duration-300 translate-x-1 dark:translate-x-6"></span>
            </button>
        </div>
    </header>

    <main class="flex-grow flex flex-col justify-center px-8 py-16">
        @yield('content')
    </main>

    <footer class="border-t border-zinc-200 dark:border-zinc-900 py-8 text-center text-xs tracking-widest uppercase opacity-40">
        &copy; {{ date('Y') }} JEM-PRODUCTION. Crafting Digital Purity.
    </footer>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        
        function updateToggleUI(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                toggleCircle.className = "inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 translate-x-6";
            } else {
                document.documentElement.classList.remove('dark');
                toggleCircle.className = "inline-block h-4 w-4 transform rounded-full bg-black transition-transform duration-300 translate-x-1";
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
    </script>
</body>
</html>