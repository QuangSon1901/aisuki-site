<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::orderBy('created_at', 'desc');
        
        if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
   
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }
   
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
       
        $order->update([
            'status' => $request->status,
        ]);
       
        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}