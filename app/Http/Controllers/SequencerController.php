<?php

namespace App\Http\Controllers;

use App\Models\Sequencer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SequencerController extends Controller
{
    public function index()
    {
        $sequencers = Sequencer::orderByDesc('id')->get();
        return view('admin.sequencer_manage', compact('sequencers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:102400',
        ]);

        if ($request->hasFile('video')) {
            $validated['video_path'] = $request->file('video')->store('sequencers/videos', 'public');
        }

        unset($validated['video']);

        try {
            Sequencer::create($validated);
        } catch (\Throwable $e) {
            Log::error('Create sequencer failed', ['message' => $e->getMessage()]);
            return back()->withInput()->withErrors([
                'sequencer' => 'Gagal menyimpan data sequencer. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.sequencer.index')->with('success', 'Data sequencer berhasil ditambahkan.');
    }

    public function update(Request $request, Sequencer $sequencer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:102400',
        ]);

        if ($request->hasFile('video')) {
            if (!empty($sequencer->video_path) && Storage::disk('public')->exists($sequencer->video_path)) {
                Storage::disk('public')->delete($sequencer->video_path);
            }

            $validated['video_path'] = $request->file('video')->store('sequencers/videos', 'public');
        }

        unset($validated['video']);

        try {
            $sequencer->update($validated);
        } catch (\Throwable $e) {
            Log::error('Update sequencer failed', [
                'sequencer_id' => $sequencer->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'sequencer' => 'Gagal memperbarui data sequencer. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.sequencer.index')->with('success', 'Data sequencer berhasil diperbarui.');
    }

    public function destroy(Sequencer $sequencer)
    {
        if (!empty($sequencer->video_path) && Storage::disk('public')->exists($sequencer->video_path)) {
            Storage::disk('public')->delete($sequencer->video_path);
        }

        try {
            $sequencer->delete();
        } catch (\Throwable $e) {
            Log::error('Delete sequencer failed', [
                'sequencer_id' => $sequencer->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'sequencer' => 'Gagal menghapus data sequencer. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.sequencer.index')->with('success', 'Data sequencer berhasil dihapus.');
    }

    public function userSequencer()
    {
        $sequencers = Sequencer::orderByDesc('id')->get();
        return view('user.shop-sequencer', compact('sequencers'));
    }
}
