<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AddonItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddon;
use App\Models\Notification;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    // public function submitOrder(Request $request)
    // {
    //     // Kiểm tra dữ liệu đầu vào
    //     $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'phone' => 'required|string|max:20',
    //         'delivery_method' => 'required|in:delivery,pickup',
    //         'payment_method' => 'required|in:cash',
    //         'notes' => 'nullable|string',
    //     ]);
        
    //     // Kiểm tra thêm đối với thông tin địa chỉ giao hàng nếu chọn phương thức giao hàng
    //     if ($request->delivery_method === 'delivery') {
    //         $request->validate([
    //             'street' => 'required|string|max:255',
    //             'house_number' => 'required|string|max:20',
    //             'city' => 'required|string|max:255',
    //             'postal_code' => 'required|string|max:20',
    //         ]);
    //     }
        
    //     // Ở đây thông thường sẽ lưu đơn hàng vào database
    //     // và gửi thông báo cho quản trị viên/khách hàng
    //     // Phần này sẽ được phát triển sau
        
    //     // Chuyển hướng đến trang chủ với thông báo thành công
    //     return redirect()->route('home', ['locale' => app()->getLocale()])->with('success', 
    //         trans_db('sections', 'order_success', false) ?: 'Your order has been submitted!'
    //     );
    // }

    public function submitOrder(Request $request)
    {
        // Validate input data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'delivery_method' => 'required|in:delivery,pickup',
            'payment_method' => 'required|in:cash',
            'notes' => 'nullable|string',
        ]);
        
        // Additional validation for delivery information
        if ($request->delivery_method === 'delivery') {
            $request->validate([
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:20',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
            ]);
        }
        
        // Get cart items from request
        $cartItems = json_decode($request->input('cart_items'), true);
        
        // Validate cart is not empty
        if (empty($cartItems)) {
            return redirect()->back()->with('error', trans_db('sections', 'cart_empty', false) ?: 'Your cart is empty');
        }
        
        // Calculate order totals
        $subtotal = 0;
        $deliveryFee = $request->delivery_method === 'delivery' ? (float) setting('delivery_fee', 0) : 0;
        
        // Calculate subtotal from cart items
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            
            // Add addon prices
            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $subtotal += $addon['price'];
                }
            }
        }
        
        $total = $subtotal + $deliveryFee;
        
        // Generate unique order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Create order record
        $order = Order::create([
            'order_number' => $orderNumber,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'delivery_method' => $request->delivery_method,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'notes' => $request->notes,
            'street' => $request->street,
            'house_number' => $request->house_number,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'delivery_time' => $request->delivery_time,
            'pickup_time' => $request->pickup_time,
        ]);
        
        // Add order items
        foreach ($cartItems as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['id'] ?? null,
                'item_name' => $item['name'],
                'item_code' => $item['code'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'note' => $item['note'] ?? null,
            ]);
            
            // Add addons if any
            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    OrderAddon::create([
                        'order_item_id' => $orderItem->id,
                        'addon_item_id' => $addon['id'] ?? null,
                        'addon_name' => $addon['name'],
                        'price' => $addon['price'],
                    ]);
                }
            }
        }
        
        // Create notification for admin
        $notification = new Notification([
            'type' => 'order',
            'title' => 'New Order: ' . $orderNumber,
            'content' => 'A new order has been placed by ' . $request->full_name . ' for ' . 
                         setting('currency', '€') . number_format($total, 2) . '.',
        ]);
        
        $order->notifications()->save($notification);
        
        // Send email notifications
        $this->sendOrderConfirmationEmail($order);
        $this->sendOrderNotificationToAdmin($order);
        
        // Redirect to success page
        return redirect()->route('order.success', [
            'locale' => app()->getLocale(),
            'orderNumber' => $orderNumber
        ]);
    }

    /**
     * Display order success page
     */
    public function orderSuccess(Request $request, $locate, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        
        return view('client.pages.order-success', compact('order'));
    }

    /**
     * Send order confirmation email to customer
     */
    protected function sendOrderConfirmationEmail(Order $order)
    {
        try {
            // Prepare email data
            $emailData = [
                'order' => $order,
                'site_name' => setting('site_name', 'AISUKI Restaurant'),
            ];

            // Send email
            $mailService = app(MailService::class);
            $mailService->sendOrderConfirmation($order->email, $emailData);
            
            return true;
        } catch (\Exception $e) {
            // Log error but don't break flow
            Log::error('Failed to send order confirmation: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order notification email to admin
     */
    protected function sendOrderNotificationToAdmin(Order $order)
    {
        try {
            // Prepare email data
            $emailData = [
                'order' => $order,
                'site_name' => setting('site_name', 'AISUKI Restaurant'),
            ];

            // Get admin email from settings
            $mailService = app(MailService::class);
            $adminEmail = setting('mail_contact_to', '');
            
            if ($adminEmail) {
                $mailService->sendOrderNotificationToAdmin($adminEmail, $emailData);
            }
            
            return true;
        } catch (\Exception $e) {
            // Log error but don't break flow
            Log::error('Failed to send admin notification: ' . $e->getMessage());
            return false;
        }
    }
}