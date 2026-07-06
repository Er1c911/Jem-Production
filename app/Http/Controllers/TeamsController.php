<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
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
            'description' => 'required|string',
            'initials' => 'required|string|max:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teams', 'public');
        }

        Team::create($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Tim berhasil ditambahkan');
    }

    // Update team
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'initials' => 'required|string|max:3',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($team->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($team->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($team->photo);
            }
            $validated['photo'] = $request->file('photo')->store('teams', 'public');
        }

        $team->update($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Tim berhasil diperbarui');
    }

    // Delete team
    public function destroy(Team $team)
    {
        // Delete photo if exists
        if ($team->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($team->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($team->photo);
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
