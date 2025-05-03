<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/menu-items/{id}/addons', 'App\Http\Controllers\Api\MenuItemController@getAddons');
Route::get('/announcements', 'App\Http\Controllers\Api\AnnouncementController@getActiveAnnouncements');
