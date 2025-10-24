<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'delivery_address' => 'nullable|string',
            'payment_method' => 'required|string',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array',
            'total' => 'required|numeric',
            'facility_id' => 'required|integer',
            'order_date' => 'required|date',
        ]);

        // إنشاء الطلب
        $order = Order::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
        ]);
    }

}
