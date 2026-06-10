<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GebruikersgegevensController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WatchlistItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [GebruikersgegevensController::class, 'login'])->name('login.submit');

Route::get('/aanmelden', function () {
    return view('aanmelden');
})->name('register');

Route::post('/register', [GebruikersgegevensController::class, 'register'])->name('register.submit');
Route::post('/logout', [GebruikersgegevensController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/watchlists', [WatchlistController::class, 'store'])->name('watchlists.store');
    Route::get('/watchlist', [WatchlistItemController::class, 'index'])->name('watchlist.index');
    Route::get('/watchlist/{watchlistItem}/edit', [WatchlistItemController::class, 'edit'])->name('watchlist.edit');
    Route::put('/watchlist/{watchlistItem}', [WatchlistItemController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{watchlistItem}', [WatchlistItemController::class, 'destroy'])->name('watchlist.destroy');

    Route::resource('gebruikersgegevens', GebruikersgegevensController::class)->except(['create', 'store']);
    Route::get('/watchlist/create', [WatchlistItemController::class, 'create'])->name('watchlist.create');
    Route::post('/watchlist', [WatchlistItemController::class, 'store'])->name('watchlist.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/{user}', [AdminController::class, 'show'])->name('admin.show');
});
