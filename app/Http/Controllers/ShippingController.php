<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\City;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer'
        ]);

        $cities = City::where('province_id', $request->province_id)->get();
        return response()->json($cities);
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'origin' => 'required|string', 
            'destination' => 'required|string', 
            'courier' => 'required|string'
        ]);

        try {
            $response = $this->rajaOngkirService->calculateShipping(
                $request->origin,
                $request->destination,
                $request->courier
            );
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'message' => 'Failed to calculate shipping cost',
                    'code' => 500,
                    'status' => 'error'
                ]
            ], 500);
        }
    }

    public function calculateDomesticCost(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string|in:jne,pos,tiki',
            'price' => 'required|string'
        ]);

        try {
            $response = Http::withHeaders([
                'key' => 'UEx6Mwpe3684594231e85b96sveKHP7o',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
                'price' => $request->price
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch shipping costs');
            }

            $responseData = $response->json();

            // Return the exact format from the API
            return response()->json([
                'meta' => $responseData['meta'],
                'data' => $responseData['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating domestic shipping cost: ' . $e->getMessage());
            return response()->json([
                'meta' => [
                    'message' => 'Failed to calculate shipping cost',
                    'code' => 500,
                    'status' => 'error'
                ],
                'data' => []
            ], 500);
        }
    }
}