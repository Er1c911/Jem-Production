<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = Plugin::orderByDesc('id')->get();

        return view('admin.plugin_manage', compact('plugins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $disk = config('filesystems.default') ?? env('FILESYSTEM_DISK', 'public');
        if ($disk === 'local') {
            $disk = 'public';
        }

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = $request->file('image')->store('plugins', $disk);
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Gagal menyimpan gambar: ' . $e->getMessage()]);
            }
        }

        Plugin::create($validated);

        return redirect()->route('admin.plugins.index')->with('success', 'Plugin berhasil ditambahkan.');
    }

    public function userPlugins()
    {
        $plugins = Plugin::orderByDesc('id')->get();

        return view('user.shop-plugins-vst', compact('plugins'));
    }

    public function update(Request $request, Plugin $plugin)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $disk = config('filesystems.default') ?? env('FILESYSTEM_DISK', 'public');
        if ($disk === 'local') {
            $disk = 'public';
        }

        if ($request->hasFile('image')) {
            try {
                // delete old image if exists on the same disk
                if (!empty($plugin->image) && Storage::disk($disk)->exists($plugin->image)) {
                    Storage::disk($disk)->delete($plugin->image);
                }
                $validated['image'] = $request->file('image')->store('plugins', $disk);
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Gagal menyimpan gambar: ' . $e->getMessage()]);
            }
        }

        $plugin->update($validated);

        return redirect()->route('admin.plugins.index')->with('success', 'Plugin berhasil diperbarui.');
    }

    public function destroy(Plugin $plugin)
    {
        $disk = config('filesystems.default') ?? env('FILESYSTEM_DISK', 'public');
        if ($disk === 'local') {
            $disk = 'public';
        }
        if (!empty($plugin->image) && Storage::disk($disk)->exists($plugin->image)) {
            Storage::disk($disk)->delete($plugin->image);
        }

        $plugin->delete();

        return redirect()->route('admin.plugins.index')->with('success', 'Plugin berhasil dihapus.');
    }
}
