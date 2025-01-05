<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ShippingDataSeeder extends Seeder
{
    public function run()
    {
        try {
            // Truncate the cities and provinces tables
            City::truncate();
            Province::truncate();
            Log::info('Cities and provinces tables truncated.');

            // Real Indonesian provinces data
            $provinces = [
                ['province_id' => '1', 'province' => 'Bali'],
                ['province_id' => '2', 'province' => 'Bangka Belitung'],
                ['province_id' => '3', 'province' => 'Banten'],
                ['province_id' => '4', 'province' => 'Bengkulu'],
                ['province_id' => '5', 'province' => 'DI Yogyakarta'],
                ['province_id' => '6', 'province' => 'DKI Jakarta'],
                ['province_id' => '7', 'province' => 'Gorontalo'],
                ['province_id' => '8', 'province' => 'Jambi'],
                ['province_id' => '9', 'province' => 'Jawa Barat'],
                ['province_id' => '10', 'province' => 'Jawa Tengah'],
                ['province_id' => '11', 'province' => 'Jawa Timur'],
            ];

            foreach ($provinces as $provinceData) {
                Province::create($provinceData);
            }
            Log::info('Provinces seeded successfully.');

            // Extended cities data
            $cities = [
                // Existing cities from before
                ['city_id' => '1', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Badung', 'zip_code' => '80351'],
                ['city_id' => '2', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Bangli', 'zip_code' => '80619'],
                ['city_id' => '3', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Buleleng', 'zip_code' => '81111'],
                ['city_id' => '4', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'zip_code' => '11220'],
                ['city_id' => '5', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'zip_code' => '10540'],
                ['city_id' => '6', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'zip_code' => '12230'],
                ['city_id' => '7', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bandung', 'zip_code' => '40111'],
                ['city_id' => '8', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bandung', 'zip_code' => '40311'],
                ['city_id' => '9', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bekasi', 'zip_code' => '17121'],
                ['city_id' => '10', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Banyumas', 'zip_code' => '53114'],
                ['city_id' => '11', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Batang', 'zip_code' => '51211'],
                ['city_id' => '12', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Surabaya', 'zip_code' => '60119'],
                ['city_id' => '13', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Malang', 'zip_code' => '65112'],
                ['city_id' => '14', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'zip_code' => '55111'],
                ['city_id' => '15', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bogor', 'zip_code' => '16111'],
                ['city_id' => '16', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Cimahi', 'zip_code' => '40512'],
                ['city_id' => '17', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Depok', 'zip_code' => '16411'],
                ['city_id' => '18', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Semarang', 'zip_code' => '50111'],
                ['city_id' => '19', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Solo', 'zip_code' => '57111'],
                ['city_id' => '20', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Tegal', 'zip_code' => '52111'],
                ['city_id' => '21', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Kediri', 'zip_code' => '64125'],
                ['city_id' => '22', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Madiun', 'zip_code' => '63122'],
                ['city_id' => '23', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Mojokerto', 'zip_code' => '61316'],
                ['city_id' => '24', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Serang', 'zip_code' => '42111'],
                ['city_id' => '25', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Tangerang', 'zip_code' => '15111'],
                ['city_id' => '26', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Cilegon', 'zip_code' => '42411'],
                ['city_id' => '27', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'zip_code' => '13330'],
                ['city_id' => '28', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Utara', 'zip_code' => '14140'],
                ['city_id' => '29', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Bantul', 'zip_code' => '55711'],
                ['city_id' => '30', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Sleman', 'zip_code' => '55511'],
                ['city_id' => '31', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kota', 'city_name' => 'Denpasar', 'zip_code' => '80227'],
                ['city_id' => '32', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Gianyar', 'zip_code' => '80511'],
                ['city_id' => '33', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Tabanan', 'zip_code' => '82111'],
                ['city_id' => '34', 'province_id' => '2', 'province' => 'Bangka Belitung', 'type' => 'Kota', 'city_name' => 'Pangkal Pinang', 'zip_code' => '33115'],
                ['city_id' => '35', 'province_id' => '4', 'province' => 'Bengkulu', 'type' => 'Kota', 'city_name' => 'Bengkulu', 'zip_code' => '38229'],
            ];

            foreach ($cities as $cityData) {
                City::create($cityData);
            }
            Log::info('Cities seeded successfully.');

            $this->command->info('Shipping data seeded successfully!');
        } catch (\Exception $e) {
            Log::error('Error seeding shipping data: ' . $e->getMessage());
            $this->command->error('Failed to seed shipping data: ' . $e->getMessage());
        }
    }
}
