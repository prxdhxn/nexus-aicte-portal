<?php

use Illuminate\Support\Facades\Route;
use App\Models\Curriculum;
use App\Models\Adoption;
use App\Models\ActivityLog;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect()->route(match($role) {
            'admin' => 'admin.dashboard',
            'sme'   => 'sme.dashboard',
            default => 'institute.dashboard',
        });
    }
    return view('welcome');
});


// Static public pages (no auth required — linked from login/register)
Route::get('/help', function () {
    return view('help');
})->name('help');


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// Socialite Routes
use App\Http\Controllers\SocialAuthController;
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// Preferences (Publicly accessible for language switcher on welcome/login pages)
Route::post('/preferences/language', [\App\Http\Controllers\PreferenceController::class, 'switchLanguage'])->name('preferences.language');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // View Mode (Authenticated only)
    Route::post('/preferences/view-mode', [\App\Http\Controllers\PreferenceController::class, 'toggleViewMode'])->name('preferences.toggle');

    // Dashboard with stats
    Route::get('/admin/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/sme/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('sme.dashboard');
    Route::get('/institute/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('institute.dashboard');
    
    // Fallback dashboard redirect
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        $routeName = $role === 'admin' ? 'admin.dashboard' : ($role === 'sme' ? 'sme.dashboard' : 'institute.dashboard');
        return redirect()->route($routeName);
    })->name('dashboard');

    Route::get('/api/stats', [\App\Http\Controllers\DashboardController::class, 'stats'])->name('api.stats');

    // Notifications (JSON API)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');
        Route::resource('users', UserController::class);
    });

    // Institute Profiles
    Route::get('/institutes/{user}', [UserController::class, 'profile'])->name('institutes.profile')->where('user', '[a-f0-9]{24}');

    // Curricula
    Route::get('/curricula/{curriculum}/export-pdf', [CurriculumController::class, 'exportPdf'])->name('curricula.export-pdf')->where('curriculum', '[a-f0-9]{24}');
    Route::get('/curricula/{curriculum}/history', [CurriculumController::class, 'history'])->name('curricula.history')->where('curriculum', '[a-f0-9]{24}');
    Route::resource('curricula', CurriculumController::class)->parameters(['curricula' => 'curriculum'])->where(['curriculum' => '[a-f0-9]{24}']);

    // Comments
    Route::post('/curricula/{curriculum}/comments', [CommentController::class, 'store'])->name('comments.store')->where('curriculum', '[a-f0-9]{24}');

    // Adoptions
    Route::post('/curricula/{curriculum}/submit', [AdoptionController::class, 'store'])->name('adoptions.store')->where('curriculum', '[a-f0-9]{24}');
    Route::get('/adoptions/{adoption}', [AdoptionController::class, 'show'])->name('adoptions.show')->where('adoption', '[a-f0-9]{24}');
    Route::put('/adoptions/{adoption}', [AdoptionController::class, 'update'])->name('adoptions.update')->where('adoption', '[a-f0-9]{24}');
    Route::delete('/adoptions/{adoption}', [AdoptionController::class, 'destroy'])->name('adoptions.destroy')->where('adoption', '[a-f0-9]{24}');
});
