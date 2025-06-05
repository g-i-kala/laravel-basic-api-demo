<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);


it('creates a customer in the database', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['store']);
    $newCustomer = [
        "name" => "Testing Tess.",
        "type" => "I",
        "email" => "testoria@test.com",
        "address" => "167 Testerion",
        "city" => "Testicle",
        "state" => "Testax",
        "postalCode" => "666",
    ];

    $response = post("/api/v1/customers/", $newCustomer);
    $response->assertCreated();

    $this->assertDatabaseHas('customers', [
    'email' => 'testoria@test.com',
    'user_id' => $user->id,
    ]);

});
