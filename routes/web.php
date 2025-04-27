<?php

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LanguageController;
use App\Http\Controllers\Client\MenuController;
use App\Http\Controllers\Client\PageController;
use App\Models\Language;
use Illuminate\Support\Facades\Route;

// Redirect root to default locale
Route::get('/', function () {
    $localeCookie = request()->cookie('locale');
    $locale = $localeCookie ?? Language::getDefault()->code; // Default to English
    
    return redirect('/' . $locale);
});

Route::get('sitemap.xml', function() {
    return response()->file(public_path('sitemap.xml'));
});

Route::get('language/{locale}', [LanguageController::class, 'change'])->name('language.change');

// Đảm bảo middleware setLocale xử lý đúng khi locale là null
Route::group(['prefix' => '{locale?}', 'middleware' => 'setLocale', 'where' => ['locale' => '[a-zA-Z]{2}|']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    
    Route::get('/about-us', [PageController::class, 'about'])->name('about');

    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/reservation/submit', [PageController::class, 'submitReservation'])
        ->name('reservation.submit')
        ->middleware('throttle:reservation');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/submit', [CartController::class, 'submitOrder'])
        ->name('checkout.submit')
        ->middleware('throttle:checkout');

    Route::get('/order-success/{orderNumber}', [CartController::class, 'orderSuccess'])->name('order.success');
    
    Route::get('{slug}', [PageController::class, 'show'])->name('page.show');
});

Route::middleware('setLocale')->group(function () {
    Route::get('/about-us', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/about-us");
    });

    Route::get('/contact', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/contact");
    });
    
    Route::get('/menu', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/menu");
    });
    
    Route::get('/cart', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/cart");
    });

    Route::get('/checkout', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/checkout");
    });
});