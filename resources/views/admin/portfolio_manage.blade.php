@extends('layouts.app')

@section('title', 'Kelola Portofolio - Admin')

@section('content')
<div class="max-w-7xl w-full mx-auto space-y-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-zinc-200 dark:border-zinc-800 pb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest opacity-50 font-display">Management</span>
            <h1 class="font-display text-4xl font-black uppercase tracking-tight mt-1">Kelola Portofolio</h1>
            <p class="text-sm text-zinc-500">Sinkronisasi data portofolio dengan halaman publik</p>
        </div>
        <button onclick="document.getElementById('portfolioModal').showModal()" class="w-full md:w-auto bg-black text-white dark:bg-white dark:text-black px-6 py-3 font-bold uppercase tracking-widest text-sm rounded-lg hover:scale-105 transition transform">
            + Tambah Portofolio
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('portfolio'))
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            {{ $errors->first('portfolio') }}
        </div>
    @endif

    @if ($portfolios->isEmpty())
        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Belum ada data portofolio. Tambahkan item pertama.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($portfolios as $item)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="mb-5 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
                        @if (!empty($item->image))
                            <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-44 object-cover">
                        @else
                            <div class="h-44 bg-gradient-to-br from-zinc-900 to-zinc-700 dark:from-zinc-100 dark:to-zinc-300 flex items-center justify-center">
                                <div class="text-white dark:text-black text-sm uppercase tracking-widest opacity-80">Tanpa Gambar</div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <div class="text-xs uppercase tracking-widest opacity-50">Urutan: {{ $item->sort_order }}</div>
                            <h3 class="text-lg font-bold uppercase tracking-wide mt-1">{{ $item->title }}</h3>
                            <p class="text-sm opacity-70">{{ $item->label }}</p>
                        </div>
                        <div class="flex flex-wrap justify-end gap-2">
                            <button
                                onclick="editPortfolio({{ $item->id }}, @js($item->title), @js($item->label), @js($item->description), @js($item->external_link), {{ $item->sort_order }})"
                                class="text-xs bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition"
                            >
                                Edit
                            </button>
                            <form action="{{ route('admin.portfolios.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus item portofolio ini?')" class="text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">{{ $item->description }}</p>

                    @if (!empty($item->external_link))
                        <a href="{{ $item->external_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold underline underline-offset-4 mb-4">
                            Buka Link Portofolio
                        </a>
                    @endif

                </div>
            @endforeach
        </div>
    @endif
</div>

<dialog id="portfolioModal" class="backdrop:bg-black/50 backdrop:backdrop-blur-sm rounded-2xl p-4 sm:p-6 max-w-lg w-[calc(100%-2rem)] shadow-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-display text-2xl font-black uppercase tracking-tight" id="portfolioModalTitle">Tambah Portofolio</h2>
        <button type="button" onclick="document.getElementById('portfolioModal').close()" class="text-2xl opacity-50 hover:opacity-100">×</button>
    </div>

    <form id="portfolioForm" method="POST" action="{{ route('admin.portfolios.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Judul</label>
            <input type="text" name="title" id="portfolioTitle" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Label</label>
            <input type="text" name="label" id="portfolioLabel" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Deskripsi</label>
            <textarea name="description" id="portfolioDescription" rows="4" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition resize-none" required></textarea>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Gambar Portofolio</label>
            <input type="file" name="image" id="portfolioImage" accept="image/*" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition">
            <small class="opacity-60">Format: JPG, PNG, GIF, WEBP | Maks: 2MB</small>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Link Portofolio</label>
            <input type="url" name="external_link" id="portfolioExternalLink" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" placeholder="https://contoh.com/project">
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Urutan Tampil</label>
            <input type="number" min="1" name="sort_order" id="portfolioSortOrder" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" value="1">
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="button" onclick="document.getElementById('portfolioModal').close()" class="flex-1 border border-zinc-200 dark:border-zinc-800 px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:bg-zinc-50 dark:hover:bg-zinc-900 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 bg-black text-white dark:bg-white dark:text-black px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:scale-105 transition transform">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<script>
    function editPortfolio(id, title, label, description, externalLink, sortOrder) {
        document.getElementById('portfolioModalTitle').textContent = 'Edit Portofolio';
        document.getElementById('portfolioTitle').value = title;
        document.getElementById('portfolioLabel').value = label;
        document.getElementById('portfolioDescription').value = description;
        document.getElementById('portfolioExternalLink').value = externalLink ?? '';
        document.getElementById('portfolioSortOrder').value = sortOrder ?? 1;
        document.getElementById('portfolioImage').value = '';

        const form = document.getElementById('portfolioForm');
        form.action = '{{ route("admin.portfolios.update", ":id") }}'.replace(':id', id);

        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        form.appendChild(method);

        document.getElementById('portfolioModal').showModal();
    }

    document.getElementById('portfolioModal').addEventListener('close', function() {
        document.getElementById('portfolioModalTitle').textContent = 'Tambah Portofolio';
        const form = document.getElementById('portfolioForm');
        form.reset();
        form.action = '{{ route("admin.portfolios.store") }}';

        const method = form.querySelector('input[name="_method"]');
        if (method) method.remove();

        document.getElementById('portfolioSortOrder').value = 1;
    });
</script>
@endsection
