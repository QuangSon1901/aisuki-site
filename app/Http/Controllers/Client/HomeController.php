<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        // Get menu categories
        $categories = MenuCategory::getActive();
        
        // First get featured items, then eager load addons
        $featuredItems = MenuItem::getFeatured()->take(8);
        
        // Eager load the addons relationship if needed
        if($featuredItems->isNotEmpty()) {
            $itemIds = $featuredItems->pluck('id')->toArray();
            $featuredItems = MenuItem::with('addons')->whereIn('id', $itemIds)->get();
        }
        
        return view('client.pages.home', compact(
            'seoSettings', 
            'contactSettings', 
            'socialSettings',
            'categories',
            'featuredItems'
        ));
    }
}