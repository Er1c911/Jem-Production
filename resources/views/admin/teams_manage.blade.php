@extends('layouts.app')

@section('title', 'Kelola Teams - Admin')

@section('content')
<div class="max-w-7xl w-full mx-auto space-y-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-zinc-200 dark:border-zinc-800 pb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest opacity-50 font-display">Management</span>
            <h1 class="font-display text-4xl font-black uppercase tracking-tight mt-1">Kelola Teams</h1>
            <p class="text-sm text-zinc-500">Sinkronisasi data tim dengan halaman public</p>
        </div>
        <button onclick="document.getElementById('addTeamModal').showModal()" class="w-full md:w-auto bg-black text-white dark:bg-white dark:text-black px-6 py-3 font-bold uppercase tracking-widest text-sm rounded-lg hover:scale-105 transition transform">
            + Tambah Tim
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('team'))
        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
            {{ $errors->first('team') }}
        </div>
    @endif

    @if ($teams->isEmpty())
        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">Belum ada tim. Tambahkan tim baru untuk memulai.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($teams as $team)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800/80 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        @if ($team->photo)
                            @php
                                $photoSrc = str_starts_with($team->photo, 'data:')
                                    ? $team->photo
                                    : route('team.photo', ['path' => $team->photo], false);
                            @endphp
                            <div class="relative w-16 h-16">
                                <img src="{{ $photoSrc }}" alt="{{ $team->name }}" class="w-16 h-16 rounded-2xl object-cover" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden w-16 h-16 bg-zinc-900 dark:bg-zinc-100 rounded-2xl flex items-center justify-center text-white dark:text-black font-display text-2xl font-bold">
                                    {{ $team->initials }}
                                </div>
                            </div>
                        @else
                            <div class="w-16 h-16 bg-zinc-900 dark:bg-zinc-100 rounded-2xl flex items-center justify-center text-white dark:text-black font-display text-2xl font-bold">
                                {{ $team->initials }}
                            </div>
                        @endif
                        <div class="flex flex-wrap justify-end gap-2">
                            <button onclick="editTeam({{ $team->id }}, '{{ $team->name }}', '{{ $team->position }}', '{{ $team->description }}', '{{ $team->initials }}')" class="text-xs bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                                Edit
                            </button>
                            <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')" class="text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold uppercase tracking-wide mb-1">{{ $team->name }}</h3>
                    <p class="text-sm opacity-70 mb-3">{{ $team->position }}</p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $team->description }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Tambah/Edit Tim -->
<dialog id="addTeamModal" class="backdrop:bg-black/50 backdrop:backdrop-blur-sm rounded-2xl p-4 sm:p-6 max-w-md w-[calc(100%-2rem)] shadow-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-display text-2xl font-black uppercase tracking-tight" id="modalTitle">Tambah Tim</h2>
        <button type="button" onclick="document.getElementById('addTeamModal').close()" class="text-2xl opacity-50 hover:opacity-100">×</button>
    </div>

    <form id="teamForm" method="POST" action="{{ route('admin.teams.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="teamId" name="team_id">

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Foto Anggota Tim</label>
            <div class="mt-2 flex flex-col gap-3">
                <div id="photoPreview" class="w-full h-40 border-2 border-dashed border-zinc-300 dark:border-zinc-700 rounded-lg flex items-center justify-center bg-zinc-50 dark:bg-zinc-900/50">
                    <span class="text-sm opacity-50">Tidak ada foto</span>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" class="block w-full text-sm border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 focus:outline-none focus:border-black dark:focus:border-white transition">
                <small class="opacity-60">Format: JPEG, PNG, GIF | Max 2MB</small>
            </div>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Nama</label>
            <input type="text" name="name" id="teamName" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Posisi</label>
            <input type="text" name="position" id="teamPosition" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Inisial (2-3 huruf)</label>
            <input type="text" name="initials" id="teamInitials" maxlength="3" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition uppercase" required>
        </div>

        <div>
            <label class="text-sm font-bold uppercase tracking-widest opacity-60">Deskripsi</label>
            <textarea name="description" id="teamDescription" rows="4" class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-4 py-2 mt-2 bg-white dark:bg-zinc-900 focus:outline-none focus:border-black dark:focus:border-white transition resize-none" required></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="button" onclick="document.getElementById('addTeamModal').close()" class="flex-1 border border-zinc-200 dark:border-zinc-800 px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:bg-zinc-50 dark:hover:bg-zinc-900 transition">
                Batal
            </button>
            <button type="submit" class="flex-1 bg-black text-white dark:bg-white dark:text-black px-4 py-2 rounded-lg font-bold uppercase tracking-widest text-sm hover:scale-105 transition transform">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');

    // Preview foto ketika dipilih
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                photoPreview.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">`;
            };
            reader.readAsDataURL(file);
        }
    });

    function editTeam(id, name, position, description, initials) {
        document.getElementById('modalTitle').textContent = 'Edit Tim';
        document.getElementById('teamId').value = id;
        document.getElementById('teamName').value = name;
        document.getElementById('teamPosition').value = position;
        document.getElementById('teamDescription').value = description;
        document.getElementById('teamInitials').value = initials;
        
        // Reset photo input and preview
        photoInput.value = '';
        photoPreview.innerHTML = '<span class="text-sm opacity-50">Tidak ada foto baru</span>';
        
        const form = document.getElementById('teamForm');
        form.action = '{{ route("admin.teams.update", ":id") }}'.replace(':id', id);
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        
        // Remove existing method input if any
        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();
        form.appendChild(method);
        
        document.getElementById('addTeamModal').showModal();
    }

    document.getElementById('addTeamModal').addEventListener('close', function() {
        document.getElementById('modalTitle').textContent = 'Tambah Tim';
        document.getElementById('teamForm').reset();
        document.getElementById('teamForm').action = '{{ route("admin.teams.store") }}';
        photoPreview.innerHTML = '<span class="text-sm opacity-50">Tidak ada foto</span>';
        const method = document.getElementById('teamForm').querySelector('input[name="_method"]');
        if (method) method.remove();
    });
</script>
@endsection
