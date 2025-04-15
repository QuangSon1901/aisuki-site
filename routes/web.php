<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LanguageController;
use App\Http\Controllers\Client\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Check locale cookie to see if user has chosen a language
    $localeCookie = request()->cookie('locale');
    $locale = $localeCookie ?? 'en'; // Default to Vietnamese

    return redirect('/' . $locale);
});


Route::get('language/{locale}', [LanguageController::class, 'change'])->name('language.change');

Route::group(['prefix' => '{locale?}', 'middleware' => 'setLocale'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
});