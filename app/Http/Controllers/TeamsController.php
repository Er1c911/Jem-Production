<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    private function encodePhotoToDataUrl(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        try {
            $file = $request->file('photo');
            $raw = file_get_contents($file->getRealPath());

            if ($raw === false) {
                return null;
            }

            $mime = $file->getMimeType() ?: 'image/jpeg';
            return 'data:'.$mime.';base64,'.base64_encode($raw);
        } catch (\Throwable $e) {
            Log::error('Team photo upload failed', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    // Show teams management page (Admin)
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams_manage', compact('teams'));
    }

    // Store new team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'initials' => 'required|string|max:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoDataUrl = $this->encodePhotoToDataUrl($request);
        if ($request->hasFile('photo') && $photoDataUrl === null) {
            return back()->withInput()->withErrors([
                'team' => 'Foto gagal diproses. Coba gunakan gambar lain.',
            ]);
        }

        if ($photoDataUrl !== null) {
            $validated['photo'] = $photoDataUrl;
        }

        try {
            Team::create($validated);
        } catch (\Throwable $e) {
            Log::error('Create team failed', [
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'team' => 'Gagal menyimpan data tim. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.teams.index')->with('success', 'Tim berhasil ditambahkan');
    }

    // Update team
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'initials' => 'required|string|max:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($team->photo && !str_starts_with($team->photo, 'data:') && Storage::disk('public')->exists($team->photo)) {
                Storage::disk('public')->delete($team->photo);
            }

            $photoDataUrl = $this->encodePhotoToDataUrl($request);
            if ($photoDataUrl === null) {
                return back()->withInput()->withErrors([
                    'team' => 'Foto gagal diproses. Coba gunakan gambar lain.',
                ]);
            }

            if ($photoDataUrl !== null) {
                $validated['photo'] = $photoDataUrl;
            }
        }

        try {
            $team->update($validated);
        } catch (\Throwable $e) {
            Log::error('Update team failed', [
                'team_id' => $team->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'team' => 'Gagal memperbarui data tim. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.teams.index')->with('success', 'Tim berhasil diperbarui');
    }

    // Delete team
    public function destroy(Team $team)
    {
        // Delete photo if exists
        if ($team->photo && !str_starts_with($team->photo, 'data:') && Storage::disk('public')->exists($team->photo)) {
            Storage::disk('public')->delete($team->photo);
        }

        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Tim berhasil dihapus');
    }

    // Show public teams page
    public function userTeams()
    {
        $teams = Team::all();
        return view('user.teams', compact('teams'));
    }
}
