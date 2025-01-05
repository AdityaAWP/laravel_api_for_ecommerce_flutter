<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';
        $this->apiKey = config('services.rajaongkir.key');
    }

    public function getCities()
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->get($this->baseUrl . '/destination/domestic-destination', [
                'limit' => 1000, // Adjust the limit as needed
                'offset' => 0,
                'search' => 'sinduharjo' 
            ]);

            Log::info('Raw RajaOngkir Cities Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $cities = $responseData['data'] ?? [];

                // Format the cities data
                $formattedCities = array_map(function($city) {
                    return [
                        'city_id' => $city['id'],
                        'subdistrict_name' => $city['subdistrict_name'],
                        'district_name' => $city['district_name'],
                        'city_name' => $city['city_name'],
                        'province_name' => $city['province_name'],
                        'zip_code' => $city['zip_code']
                    ];
                }, $cities);

                Log::info('Formatted Cities:', ['cities' => $formattedCities]);

                return [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Cities retrieved successfully'
                    ],
                    'data' => $formattedCities
                ];
            }

            Log::error('Failed to get cities', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'meta' => [
                    'code' => $response->status(),
                    'status' => 'error',
                    'message' => 'Failed to fetch cities'
                ],
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function calculateShipping($origin, $destination, $courier)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/shipping/domestic/rate', [
                'origin' => $origin,
                'destination' => $destination,
                'courier' => $courier,
                'weight' => 500 // Default 1kg
            ]);

            Log::info('Raw RajaOngkir Shipping Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to calculate shipping cost', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'meta' => [
                    'code' => $response->status(),
                    'status' => 'error',
                    'message' => 'Failed to calculate shipping cost'
                ],
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir API Error (Cost): ' . $e->getMessage());
            throw $e;
        }
    }

    public function calculateDomesticCost($origin, $destination, $weight, $courier, $price)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->post($this->baseUrl . '/calculate/domestic-cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
                'price' => $price
            ]);

            Log::info('Raw RajaOngkir Domestic Cost Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to calculate domestic cost', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'meta' => [
                    'code' => $response->status(),
                    'status' => 'error',
                    'message' => 'Failed to calculate domestic cost'
                ],
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir API Error (Domestic Cost): ' . $e->getMessage());
            throw $e;
        }
    }
}