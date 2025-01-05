<?php
namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction($order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'item_details' => $order->details->map(function ($detail) {
                return [
                    'id' => $detail->product_id,
                    'price' => $detail->product_price,
                    'quantity' => $detail->product_quantity,
                    'name' => $detail->product->product_title,
                ];
            })->toArray(),
        ];

        return Snap::createTransaction($params);
    }
}