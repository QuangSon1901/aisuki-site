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

    public function checkout()
    {
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        // Lấy cài đặt về giao hàng và nhận tại cửa hàng
        $enableDelivery = (bool) setting('enable_delivery', 1);
        $enablePickup = (bool) setting('enable_pickup', 1);
        $deliveryFee = (float) setting('delivery_fee', 2.50);
        $minOrderAmount = (float) setting('min_order_amount', 10.00);
        
        return view('client.pages.checkout', compact(
            'seoSettings',
            'contactSettings',
            'socialSettings',
            'enableDelivery',
            'enablePickup',
            'deliveryFee',
            'minOrderAmount'
        ));
    }

    public function submitOrder(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:delivery,pickup',
            'payment_method' => 'required|in:cash',
            'notes' => 'nullable|string',
        ]);
        
        // Kiểm tra thêm đối với thông tin địa chỉ giao hàng nếu chọn phương thức giao hàng
        if ($request->delivery_method === 'delivery') {
            $request->validate([
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:20',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);
        }
        
        // Ở đây thông thường sẽ lưu đơn hàng vào database
        // và gửi thông báo cho quản trị viên/khách hàng
        // Phần này sẽ được phát triển sau
        
        // Chuyển hướng đến trang chủ với thông báo thành công
        return redirect()->route('home', ['locale' => app()->getLocale()])->with('success', 
            trans_db('sections', 'order_success', false) ?: 'Your order has been submitted!'
        );
    }
}