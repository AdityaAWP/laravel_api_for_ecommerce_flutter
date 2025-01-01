<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('details.product')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        if (!$request->user()) {
            Log::channel('order')->warning('Unauthenticated request');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Validate the request
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total price from products
        $totalPrice = 0;
        foreach ($request->products as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }

        // Create order with calculated total price
        $order = Order::create([
            'user_id' => $request->user()->id,
            'total_price' => $totalPrice,
        ]);

        // Log the created order
        Log::channel('order')->info('Created Order:', $order->toArray());

        // Create order details
        foreach ($request->products as $product) {
            $detail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'product_price' => $product['price'],
                'product_quantity' => $product['quantity'],
            ]);

            // Log each order detail
            Log::channel('order')->info('Created Order Detail:', $detail->toArray());
        }

        return response()->json(['data' => $order->load('details.product')], 201);
    }
}
?>