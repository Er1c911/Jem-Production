@extends('layouts.app')

@section('title', 'JEM Production | Portfolio')

@section('content')
<div class="max-w-6xl mx-auto w-full">
    <div class="space-y-12">
        <div class="space-y-4">
            <h1 class="font-display text-5xl md:text-7xl font-black tracking-tight leading-[0.95] uppercase">
                Portfolio
            </h1>
            <p class="text-lg md:text-xl font-light text-zinc-600 dark:text-zinc-400 max-w-2xl leading-relaxed">
                Karya-karya produksi yang telah kami wujudkan dengan standar kualitas tertinggi dan fokus pada efisiensi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Portfolio Item 1 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">01</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Analytics Dashboard</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Analytics Dashboard</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Platform analitik real-time dengan visualisasi data yang intuitif dan berbasis monokrom. Dirancang untuk mempercepat pengambilan keputusan tim produksi.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">React</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Node.js</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">PostgreSQL</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Item 2 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">02</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Team Collaboration</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Team Collaboration Suite</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Sistem kolaborasi tim terintegrasi dengan fitur komunikasi, manajemen tugas, dan tracking progress. Meminimalkan noise visual untuk fokus maksimal.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Laravel</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">WebSocket</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Redis</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Item 3 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">03</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Automation Engine</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Automation Engine</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Engine otomasi produksi yang menangani workflow kompleks dengan efisiensi tinggi. Mengurangi manual work dan meningkatkan output berkualitas.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Python</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Celery</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Docker</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Item 4 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">04</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Resource Management</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Resource Management</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Platform manajemen resource dengan alokasi dinamis dan optimalisasi beban kerja. Memaksimalkan utilitas tim dan peralatan produksi.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Vue.js</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">FastAPI</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">MongoDB</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Item 5 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">05</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Quality Control</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Quality Control System</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Sistem kontrol kualitas otomatis dengan machine learning untuk deteksi anomali. Memastikan konsistensi output dan standar produksi tertinggi.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">TensorFlow</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">OpenCV</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">AWS</span>
                    </div>
                </div>
            </div>

            <!-- Portfolio Item 6 -->
            <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-black dark:hover:border-white transition-all duration-300">
                <div class="aspect-video bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                    <div class="text-white dark:text-black text-center">
                        <div class="text-4xl font-display font-black mb-2">06</div>
                        <div class="text-sm uppercase tracking-widest opacity-80">Reporting System</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold uppercase tracking-wide mb-2">Advanced Reporting</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 leading-relaxed">
                        Sistem pelaporan komprehensif dengan custom reports dan scheduled delivery. Memberikan insight mendalam tentang performa produksi.
                    </p>
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Next.js</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">GraphQL</span>
                        <span class="text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 px-3 py-1 rounded-full">Stripe</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
