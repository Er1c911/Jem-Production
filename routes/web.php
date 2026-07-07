<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TeamsController;

// Halaman Utama (User) - Publik
Route::get('/', function () {
    return view('user.index');
})->name('home');

// Halaman Teams (Publik - Sinkron dengan Admin)
Route::get('/teams', [TeamsController::class, 'userTeams'])->name('teams');

// Halaman Portfolio
Route::get('/portfolio', function () {
    return view('user.portfolio');
})->name('portfolio');

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
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Teams Management
    Route::resource('teams', TeamsController::class, [
        'names' => [
            'index' => 'admin.teams.index',
            'store' => 'admin.teams.store',
            'update' => 'admin.teams.update',
            'destroy' => 'admin.teams.destroy',
        ]
    ]);
});