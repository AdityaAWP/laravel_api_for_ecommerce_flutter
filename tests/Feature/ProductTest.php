<?php

use Illuminate\Http\Client\Response;

test('example', function () {
    $response = $this->get('/api/products', [
        'Authorization' => 'Bearer 16|JSHICRyqMy8CiXzFqEjIOvOOkCMWETcbmYItmkPc08b0733d',
    ]);

    $expectedData = [
        [
            'id' => 1,
            'product_title' => 'Wish U Were Hereaa',
            'product_description' => "This is Pink Floyd's Wish U Were Here Album",
            'product_price' => "500000.00",
            'product_image' => 'product_images/CXQ8JA4sfxokTeKGj5gxJhfMBzTT2M6ASdrgHmU5.jpg',
            'user_id' => 1,
            'created_at' => '2025-01-05T23:07:49.000000Z',
            'updated_at' => '2025-01-05T23:07:49.000000Z',
        ],
    ];

    $response
        ->assertStatus(200)
        ->assertJsonFragment(['data' => $expectedData]);
});
