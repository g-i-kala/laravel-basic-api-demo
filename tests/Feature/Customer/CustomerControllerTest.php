<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);



it('can get customers for authentificated user', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->for($user)->create();

    actingAs($user, 'sanctum');

    $response = get("/api/v1/customers/");
    $response->assertOk();

});


it('shows only the users customers', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->count(2)->create();
    $customersUserB = Customer::factory()->for($userB)->count(4)->create();

    actingAs($userA, 'sanctum');

    $response = get("/api/v1/customers/");
    $response->assertJsonCount(2, 'data');

});

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

it('shows the customer via id belonging to the logged in user', function () {
    $userA = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->count(2)->create();

    actingAs($userA, 'sanctum');

    $customerId = $customersUserA->first()->id;

    $response = get('/api/v1/customers/' . $customerId);
    $response->assertJsonPath('data.id', $customerId);

});

it('can update the customer data with PUT request', function () {
    $userA = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->count(2)->create();

    Sanctum::actingAs($userA, ['update']);

    $customerId = $customersUserA->first()->id;

    $newCustomerData = [
        "name" => "Testing Tess.",
        "type" => "I",
        "email" => "testoria@test.com",
        "address" => "167 Testerion",
        "city" => "Testicle",
        "state" => "Testax",
        "postalCode" => "666",
    ];

    $response = put('/api/v1/customers/' . $customerId, $newCustomerData);
    $response->assertJson([
        'data' => [
            "name" => "Testing Tess.",
            "type" => "I",
            "email" => "testoria@test.com",
            "address" => "167 Testerion",
            "city" => "Testicle",
            "state" => "Testax",
            "postalCode" => "666",
            ]
        ]);

    $this->assertDatabaseHas('customers', [
        'id' => $userA->id,
        'name' => 'Testing Tess.',
        "email" => "testoria@test.com",
    ]);

});

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

it('can delete a customer', function () {
    $userA = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->create();

    actingAs($userA, 'sanctum');

    $customerId = $customersUserA->first()->id;

    $response = delete('/api/v1/customers/' . $customerId);
    expect($response->status())->toBe(204);

    $this->assertDatabaseMissing('customers', [
        'id' => $customerId
    ]);

});
