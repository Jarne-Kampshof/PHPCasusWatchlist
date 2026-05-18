<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchlistItemController;
use App\Http\Controllers\GebruikersgegevensController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', function () {
    return view('login'); })->name('login');
Route::post('/login', [GebruikersgegevensController::class, 'login'])->name('login.submit');
Route::get('/aanmelden', function () {
    return view('aanmelden'); })->name('register');
Route::post('/register', [GebruikersgegevensController::class, 'register'])->name('register.submit');
Route::post('/logout', [GebruikersgegevensController::class, 'logout'])->middleware('auth')->name('logout');

// Watchlist routes (beschermd met auth)
Route::middleware('auth')->group(function () {
    Route::get('/watchlist', [WatchlistItemController::class, 'index'])->name('watchlist.index');
    Route::get('/watchlist/{watchlistItem}/edit', [WatchlistItemController::class, 'edit'])->name('watchlist.edit');
    Route::put('/watchlist/{watchlistItem}', [WatchlistItemController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{watchlistItem}', [WatchlistItemController::class, 'destroy'])->name('watchlist.destroy');

    Route::resource('gebruikersgegevens', GebruikersgegevensController::class)->except(['create', 'store']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/watchlist/create', [WatchlistItemController::class, 'create'])->name('watchlist.create');
    Route::post('/watchlist', [WatchlistItemController::class, 'store'])->name('watchlist.store');
});

Route::middleware(['role:admin'])->group(function () {

    Route::get('/admin', function () {

        return view('admin');

    })->name('admin');

}); 