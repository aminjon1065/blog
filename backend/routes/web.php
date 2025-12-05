<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    $news = \Illuminate\Support\Facades\DB::table("news")->get();
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'news' => $news,
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
