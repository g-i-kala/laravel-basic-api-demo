<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);

it('can update choosen customer fields with PATCH request', function () {
    $userA = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->count(2)->create();

    Sanctum::actingAs($userA, ['update']);

    $customerId = $customersUserA->first()->id;

    $newCustomerData = [
        "name" => "Testing Tess.",
        "email" => "testoria@test.com",
        "city" => "Testicle",
        "postalCode" => "666",
    ];

    $response = patch('/api/v1/customers/' . $customerId, $newCustomerData);
    $response->assertJson([
        'data' => [
            "name" => "Testing Tess.",
            "email" => "testoria@test.com",
            "city" => "Testicle",
            "postalCode" => "666",
            ]
        ]);

    $this->assertDatabaseHas('customers', [
        'id' => $userA->id,
        'name' => 'Testing Tess.',
        "email" => "testoria@test.com",
    ]);

});
