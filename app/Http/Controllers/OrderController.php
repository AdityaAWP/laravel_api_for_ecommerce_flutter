<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\RajaOngkirService;
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
    
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_cost' => 'required|numeric',
            'shipping_destination' => 'required|string',
            'courier_service' => 'required|string',
            'estimated_days' => 'required|string',
            'order_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            // Calculate total price from products
            $totalPrice = collect($request->products)->sum(function($product) {
                return $product['price'] * $product['quantity'];
            });
        
            // Create order with shipping information
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $totalPrice + $request->shipping_cost,
                'shipping_price' => $request->shipping_cost,
                'shipping_destination' => $request->shipping_destination,
                'courier_service' => $request->courier_service,
                'estimated_days' => $request->estimated_days,
            ]);

            if ($request->hasFile('order_proof')) {
                $proofPath = $request->file('order_proof')->store('order_proofs', 'public');
                $order->order_proof = $proofPath;
                $order->save();
            }
        
            // Create order details
            foreach ($request->products as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'product_price' => $product['price'],
                    'product_quantity' => $product['quantity'],
                ]);
            }
        
            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order->load('details.product')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'order_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $order = Order::findOrFail($orderId);

        if ($request->hasFile('order_proof')) {
            $proofPath = $request->file('order_proof')->store('order_proofs', 'public');
            $order->order_proof = $proofPath;
            $order->save();
        }

        return response()->json([
            'message' => 'Proof of payment uploaded successfully',
            'data' => $order
        ], 200);
    }

    public function orderHistory(Request $request)
    {
        $orders = Order::with('details.product')->where('user_id', $request->user()->id)->get();
        return response()->json($orders);
    }
}
