<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        // Lấy phí vận chuyển từ cài đặt
        $deliveryFee = (float) setting('delivery_fee', 2.50);
        
        // Lấy các addon hiện có
        $addons = AddonItem::getActive();
        
        return view('client.pages.cart', compact(
            'seoSettings',
            'contactSettings',
            'socialSettings',
            'deliveryFee',
            'addons'
        ));
    }
}