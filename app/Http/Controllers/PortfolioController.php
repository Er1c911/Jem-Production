<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.portfolio_manage', compact('portfolios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? (Portfolio::max('sort_order') + 1);

        try {
            Portfolio::create($validated);
        } catch (\Throwable $e) {
            Log::error('Create portfolio failed', ['message' => $e->getMessage()]);
            return back()->withInput()->withErrors([
                'portfolio' => 'Gagal menyimpan data portofolio. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? $portfolio->sort_order;

        try {
            $portfolio->update($validated);
        } catch (\Throwable $e) {
            Log::error('Update portfolio failed', [
                'portfolio_id' => $portfolio->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'portfolio' => 'Gagal memperbarui data portofolio. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        try {
            $portfolio->delete();
        } catch (\Throwable $e) {
            Log::error('Delete portfolio failed', [
                'portfolio_id' => $portfolio->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'portfolio' => 'Gagal menghapus data portofolio. Silakan coba lagi.',
            ]);
        }

        return redirect()->route('admin.portfolios.index')->with('success', 'Portofolio berhasil dihapus.');
    }

    public function userPortfolio()
    {
        $portfolios = Portfolio::orderBy('sort_order')->orderBy('id')->get();
        return view('user.portfolio', compact('portfolios'));
    }
}
