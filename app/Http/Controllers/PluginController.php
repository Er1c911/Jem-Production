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
            'image' => 'nullable|url',
            'link' => 'nullable|url|max:2048',
        ]);

        // If no image URL provided, set to null
        if (empty($validated['image'])) {
            $validated['image'] = null;
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
            'image' => 'nullable|url',
            'link' => 'nullable|url|max:2048',
        ]);

        // If image changed from a stored path to a URL, try delete old stored file
        $oldImage = $plugin->image;
        $newImage = $validated['image'] ?? null;
        if ($oldImage && $newImage && !preg_match('/^https?:\/\//i', $oldImage)) {
            // oldImage looked like a stored path; delete if exists on configured disk
            $disk = config('filesystems.default') ?? env('FILESYSTEM_DISK', 'public');
            if ($disk === 'local') {
                $disk = 'public';
            }
            try {
                if (Storage::disk($disk)->exists($oldImage)) {
                    Storage::disk($disk)->delete($oldImage);
                }
            } catch (\Exception $e) {
                // ignore deletion errors
            }
        }

        if (empty($validated['image'])) {
            $validated['image'] = null;
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
