<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SequencerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\PaymentConfirmationController;
use App\Models\PaymentConfirmation;

// Halaman Utama (User) - Publik
Route::get('/', function () {
    return view('user.index');
})->name('home');

// Halaman Teams (Publik - Sinkron dengan Admin)
Route::get('/teams', [TeamsController::class, 'userTeams'])->name('teams');

// Halaman Portfolio
Route::get('/portfolio', [PortfolioController::class, 'userPortfolio'])->name('portfolio');

// Halaman Shop
Route::get('/shop', function () {
    return view('user.shop');
})->name('shop');

Route::get('/shop/plugins-vst', [PluginController::class, 'userPlugins'])->name('shop.plugins-vst');

Route::get('/shop/sequencer', [SequencerController::class, 'userSequencer'])->name('shop.sequencer');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/buy', [PaymentConfirmationController::class, 'storeFromCart'])->name('cart.buy');

Route::get('/team-photo', function (Request $request) {
    $path = $request->query('path');

    if (! $path || ! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->name('team.photo');

// Halaman Login Admin
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('login');

// Proses Login Admin
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Validasi akun statis sesuai request
    if ($credentials['username'] === 'jemadmins' && $credentials['password'] === 'produktifterus') {
        // Ambil atau buat user statis di session agar dianggap 'logged in' oleh Laravel
        $user = \App\Models\User::firstOrCreate(
            ['username' => 'jemadmins'],
            [
                'name' => 'Super Admin Jem',
                'email' => 'admin@jem-production.com',
                'password' => bcrypt('produktifterus')
            ]
        );
        
        Auth::login($user);
        $request->session()->regenerate();
 
        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors([
        'username' => 'Kredensial yang dimasukkan salah.',
    ])->onlyInput('username');
});

// Proses Logout Admin
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

// Dashboard Admin (Diproteksi Middleware Auth)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $pendingPaymentCount = PaymentConfirmation::where('status', 'pending')->count();

        return view('admin.dashboard', compact('pendingPaymentCount'));
    })->name('admin.dashboard');

    Route::get('/payments', [PaymentConfirmationController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/accept', [PaymentConfirmationController::class, 'accept'])->name('admin.payments.accept');
    Route::post('/payments/{payment}/cancel', [PaymentConfirmationController::class, 'cancel'])->name('admin.payments.cancel');
    Route::delete('/payments/{payment}', [PaymentConfirmationController::class, 'destroy'])->name('admin.payments.destroy');

    Route::get('/sequencer', [SequencerController::class, 'index'])->name('admin.sequencer.index');
    Route::post('/sequencer', [SequencerController::class, 'store'])->name('admin.sequencer.store');
    Route::put('/sequencer/{sequencer}', [SequencerController::class, 'update'])->name('admin.sequencer.update');
    Route::delete('/sequencer/{sequencer}', [SequencerController::class, 'destroy'])->name('admin.sequencer.destroy');

    Route::get('/plugins', [PluginController::class, 'index'])->name('admin.plugins.index');
    Route::post('/plugins', [PluginController::class, 'store'])->name('admin.plugins.store');
    Route::put('/plugins/{plugin}', [PluginController::class, 'update'])->name('admin.plugins.update');
    Route::delete('/plugins/{plugin}', [PluginController::class, 'destroy'])->name('admin.plugins.destroy');

    // Teams Management
    Route::resource('teams', TeamsController::class, [
        'names' => [
            'index' => 'admin.teams.index',
            'store' => 'admin.teams.store',
            'update' => 'admin.teams.update',
            'destroy' => 'admin.teams.destroy',
        ]
    ]);

    // Portfolio Management
    Route::resource('portfolios', PortfolioController::class, [
        'names' => [
            'index' => 'admin.portfolios.index',
            'store' => 'admin.portfolios.store',
            'update' => 'admin.portfolios.update',
            'destroy' => 'admin.portfolios.destroy',
        ]
    ]);
});