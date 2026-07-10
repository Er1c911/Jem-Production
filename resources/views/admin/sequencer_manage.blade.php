@extends('layouts.app')

@section('title', 'Kelola Sequencer - Admin')

@section('content')
<div class="max-w-7xl w-full mx-auto space-y-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-zinc-200 dark:border-zinc-800 pb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest opacity-50 font-display">Management</span>
            <h1 class="font-display text-4xl font-black uppercase tracking-tight mt-1">Kelola Sequencer</h1>
            <p class="text-sm text-zinc-500">Sinkronisasi data sequencer dengan halaman user</p>
        </div>
        <button onclick="document.getElementById('sequencerModal').showModal()" class="w-full md:w-auto bg-black text-white dark:bg-white dark:text-black px-6 py-3 font-bold uppercase tracking-widest text-sm rounded-lg hover:scale-105 transition transform">
            + Tambah Sequencer
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('sequencer'))
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            {{ $errors->first('sequencer') }}
        </div>
    @endif

    @if ($sequencers->isEmpty())
        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Belum ada data sequencer. Tambahkan item pertama.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($sequencers as $item)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                    @include('partials.video-player', ['videoUrl' => $item->video_url, 'videoPath' => $item->video_path])

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-lg font-bold uppercase tracking-wide">{{ $item->title }}</h3>
                            <p class="text-sm opacity-70">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex flex-wrap justify-end gap-2">
                            <button
                                onclick="editSequencer({{ $item->id }}, @js($item->title), @js((string) $item->price), @js($item->description), @js($item->video_url))"
                                class="text-xs bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition"
                            >
                                Edit
                            </button>
                            <form action="{{ route('admin.sequencer.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data sequencer ini?')" class="text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $item->description }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

<dialog id="sequencerModal" class="backdrop:bg-black/50 backdrop:backdrop-blur-sm rounded-2xl p-4 sm:p-6 max-w-lg w-[calc(100%-2rem)] shadow-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-display text-2xl font-black uppercase tracking-tight" id="sequencerModalTitle">Tambah Sequencer</h2>
        <button type="button" onclick="document.getElementById('sequencerModal').close()" class="text-2xl opacity-50 hover:opacity-100">×</button>
    </div>

    <form id="sequencerForm" method="POST" action="{{ route('admin.sequencer.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Judul</label>
            <input type="text" name="title" id="sequencerTitle" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Harga</label>
            <input type="number" name="price" id="sequencerPrice" min="0" step="0.01" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Deskripsi</label>
            <textarea name="description" id="sequencerDescription" rows="4" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition resize-none" required></textarea>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Video URL</label>
            <input type="url" name="video_url" id="sequencerVideoUrl" placeholder="https://..." class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition">
            <small class="opacity-60">Gunakan link direct video (mis. CDN, Cloudinary, S3/R2 object URL).</small>
        </div>

        @if (!env('VERCEL'))
        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Upload Video</label>
            <input type="file" name="video" id="sequencerVideo" accept="video/mp4,video/webm,video/quicktime" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition">
            <small class="opacity-60">Format: MP4, WEBM, MOV | Maks: 100MB</small>
        </div>
        @else
        <div class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300">
            Deployment Vercel: upload file video besar tidak didukung. Gunakan Video URL.
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="button" onclick="document.getElementById('sequencerModal').close()" class="flex-1 border border-zinc-200 dark:border-zinc-800 px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:bg-zinc-50 dark:hover:bg-zinc-900 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 bg-black text-white dark:bg-white dark:text-black px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:scale-105 transition transform">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<script>
    function editSequencer(id, title, price, description, videoUrl) {
        document.getElementById('sequencerModalTitle').textContent = 'Edit Sequencer';
        document.getElementById('sequencerTitle').value = title;
        document.getElementById('sequencerPrice').value = price;
        document.getElementById('sequencerDescription').value = description;
        document.getElementById('sequencerVideoUrl').value = videoUrl ?? '';
        const fileInput = document.getElementById('sequencerVideo');
        if (fileInput) {
            fileInput.value = '';
        }

        const form = document.getElementById('sequencerForm');
        form.action = '{{ route("admin.sequencer.update", ":id") }}'.replace(':id', id);

        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) {
            existingMethod.remove();
        }

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        form.appendChild(method);

        document.getElementById('sequencerModal').showModal();
    }

    document.getElementById('sequencerModal').addEventListener('close', function() {
        document.getElementById('sequencerModalTitle').textContent = 'Tambah Sequencer';
        const form = document.getElementById('sequencerForm');
        form.reset();
        form.action = '{{ route("admin.sequencer.store") }}';

        const method = form.querySelector('input[name="_method"]');
        if (method) {
            method.remove();
        }
    });
</script>
@endsection
