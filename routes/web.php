<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LanguageController;
use App\Http\Controllers\Client\MenuController;
use App\Http\Controllers\Client\PageController;
use Illuminate\Support\Facades\Route;

// Redirect root to default locale
Route::get('/', function () {
    $localeCookie = request()->cookie('locale');
    $locale = $localeCookie ?? 'en'; // Default to English
    
    return redirect('/' . $locale);
});

Route::get('language/{locale}', [LanguageController::class, 'change'])->name('language.change');

// Đảm bảo middleware setLocale xử lý đúng khi locale là null
Route::group(['prefix' => '{locale?}', 'middleware' => 'setLocale', 'where' => ['locale' => '[a-zA-Z]{2}|']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    
    Route::get('/about-us', [PageController::class, 'about'])->name('about');
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
});

Route::middleware('setLocale')->group(function () {
    Route::get('/about-us', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/about-us");
    });
    
    Route::get('/menu', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/menu");
    });
    
    Route::get('/cart', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/cart");
    });
    
    Route::get('/page/{slug}', function($slug) {
        $locale = app()->getLocale();
        return redirect("/{$locale}/page/{$slug}");
    });
});