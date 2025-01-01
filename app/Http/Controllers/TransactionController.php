<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('details.product')->get();
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            Log::channel('transaction')->warning('Unauthenticated request');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Validate the request
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Calculate total price from products
        $totalPrice = 0;
        foreach ($request->products as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }

        // Create transaction with calculated total price
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'transaction_code' => 'TRX' . time(),
            'total_price' => $totalPrice,
        ]);

        // Log the created transaction

        // Create transaction details
        foreach ($request->products as $product) {
            $detail = TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        // Log the final response
        $response = $transaction->load('details.product');

        return response()->json($response, 201);
    }
}
