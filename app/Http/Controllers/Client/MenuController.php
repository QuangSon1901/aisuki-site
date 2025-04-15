<?php
// app/Http/Controllers/Client/MenuController.php
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
        
        // Lấy tất cả danh mục hiện có với ngôn ngữ hiện tại
        $categories = MenuCategory::getActive();
        
        // Lấy món ăn theo danh mục
        $menuItems = [];
        foreach ($categories as $category) {
            $menuItems[$category->slug] = MenuItem::getByCategory($category->id);
        }
        
        // Lấy các add-on với ngôn ngữ hiện tại
        $addons = AddonItem::getActive();
        
        return view('client.pages.menu', compact(
            'seoSettings', 
            'contactSettings', 
            'socialSettings', 
            'categories',
            'menuItems',
            'addons'
        ));
    }
}