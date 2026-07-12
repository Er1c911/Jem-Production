<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\Request;

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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('plugins', 'public');
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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('plugins', 'public');
        }

        $plugin->update($validated);

        return redirect()->route('admin.plugins.index')->with('success', 'Plugin berhasil diperbarui.');
    }

    public function destroy(Plugin $plugin)
    {
        $plugin->delete();

        return redirect()->route('admin.plugins.index')->with('success', 'Plugin berhasil dihapus.');
    }
}
