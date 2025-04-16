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
        
        // Lấy danh mục món ăn
        $categories = MenuCategory::getActive();
        
        // Lấy món ăn nổi bật (có is_featured = true)
        $featuredItems = MenuItem::getFeatured()->take(8);
        
        // Lấy các addon (món ăn kèm)
        $addons = AddonItem::getActive();
        
        return view('client.pages.home', compact(
            'seoSettings', 
            'contactSettings', 
            'socialSettings',
            'categories',
            'featuredItems',
            'addons'
        ));
    }
}