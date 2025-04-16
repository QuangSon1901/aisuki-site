<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\AddonItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationApiController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Admin Auth Routes (no admin prefix needed as it's already added in RouteServiceProvider)
Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard 
    Route::get('/', [DashboardController::class, 'index'])->name('admin');
    Route::get('admin', [DashboardController::class, 'index'])->name('admin.home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Resource Routes
    // Menu Categories with translation support
    Route::get('menu-categories/{id}/create-translation', [MenuCategoryController::class, 'createTranslation'])->name('admin.menu-categories.create-translation');
    Route::post('menu-categories/{id}/create-translation', [MenuCategoryController::class, 'storeTranslation'])->name('admin.menu-categories.store-translation');
    Route::resource('menu-categories', MenuCategoryController::class)->names('admin.menu-categories');
    
    Route::get('menu-items/get-categories', [MenuItemController::class, 'getCategoriesByLanguage'])->name('admin.menu-items.get-categories');
    Route::get('menu-items/{id}/create-translation', [MenuItemController::class, 'createTranslation'])->name('admin.menu-items.create-translation');
    Route::post('menu-items/{id}/create-translation', [MenuItemController::class, 'storeTranslation'])->name('admin.menu-items.store-translation');
    Route::get('menu-items/get-addons', [MenuItemController::class, 'getAddonsByLanguage'])->name('admin.menu-items.get-addons');
    Route::resource('menu-items', MenuItemController::class)->names('admin.menu-items');

    Route::get('addon-items/{id}/create-translation', [AddonItemController::class, 'createTranslation'])->name('admin.addon-items.create-translation');
    Route::post('addon-items/{id}/create-translation', [AddonItemController::class, 'storeTranslation'])->name('admin.addon-items.store-translation');
    Route::resource('addon-items', AddonItemController::class)->names('admin.addon-items');

    Route::post('pages/upload-image', [PageController::class, 'uploadEditorImage'])->name('admin.pages.upload-image');
    Route::resource('pages', PageController::class)->names('admin.pages');

    Route::resource('users', UserController::class)->names('admin.users');

    Route::post('languages/{id}/set-default', [LanguageController::class, 'setDefault'])->name('admin.languages.set-default');
    Route::post('languages/{id}/toggle-active', [LanguageController::class, 'toggleActive'])->name('admin.languages.toggle-active');
    Route::resource('languages', LanguageController::class)->names('admin.languages');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings/update', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('settings/test-email', [SettingController::class, 'testEmail'])->name('admin.settings.test-email');

    // Translations
    Route::get('translations', [TranslationController::class, 'index'])->name('admin.translations.index');
    Route::get('translations/group/{group}', [TranslationController::class, 'group'])->name('admin.translations.group');
    Route::post('translations/update', [TranslationController::class, 'update'])->name('admin.translations.update');
    Route::post('translations/import', [TranslationController::class, 'import'])->name('admin.translations.import');
    Route::post('translations/export', [TranslationController::class, 'export'])->name('admin.translations.export');
    Route::post('translations/add-key', [TranslationController::class, 'addKey'])->name('admin.translations.add-key');
    Route::post('translations/create-group', [TranslationController::class, 'createGroup'])->name('admin.translations.create-group');

    Route::get('profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.profile.change-password');
    Route::put('profile/change-password', [ProfileController::class, 'updatePassword'])->name('admin.profile.update-password');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    
    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::post('notifications/{notification}/processed', [NotificationController::class, 'markAsProcessed'])->name('admin.notifications.mark-processed');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');

    Route::get('api/notifications/check', [NotificationApiController::class, 'checkNewNotifications'])
        ->name('admin.api.notifications.check');
});