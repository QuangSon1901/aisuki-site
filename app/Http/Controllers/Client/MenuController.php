<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        // Get active categories
        $categories = MenuCategory::getActive();
        
        // Initialize menuItems array
        $menuItems = [];
        
        // Get menu items by category with their associated addons
        foreach ($categories as $category) {
            // Get basic items first
            $items = MenuItem::getByCategory($category->id);
            
            if($items->isNotEmpty()) {
                // Then eager load addons for these items
                $itemIds = $items->pluck('id')->toArray();
                $itemsWithAddons = MenuItem::with('addons')
                    ->whereIn('id', $itemIds)
                    ->get();
                
                $menuItems[$category->slug] = $itemsWithAddons;
            } else {
                $menuItems[$category->slug] = collect([]);
            }
        }
        
        return view('client.pages.menu', compact(
            'seoSettings', 
            'contactSettings', 
            'socialSettings', 
            'categories',
            'menuItems'
        ));
    }
}