<?php

use App\Models\User;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\{actingAs, get, post, postJson, put, patch, delete};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->newCustomer = [
        "name" => "Testing Tess.",
        "type" => "I",
        "email" => "testoria@test.com",
        "address" => "167 Testerion",
        "city" => "Testicle",
        "state" => "Testax",
        "postalCode" => "666",
    ];
});


// Happy path tests

it('creates a customer in the database', function () {

    Sanctum::actingAs($this->user, ['store']);

    $response = post("/api/v1/customers/", $this->newCustomer);

    expect($response->status())->toBe(201);

    expect(DB::table('customers')
        ->where('email', 'testoria@test.com')
        ->first())
        ->toMatchArray([
            'email' => 'testoria@test.com',
            'user_id' => $this->user->id,
        ]);

});

// Unhappy path tests

it('cant create a user is not authorized', function () {
    Sanctum::actingAs($this->user);

    $response = post('/api/v1/customers', $this->newCustomer);

    expect($response->status())->toBe(403);

});

it('cant create a user with partial fields', function () {
    Sanctum::actingAs($this->user, ['store']);

    $response = postJson('/api/v1/customers/', array_slice($this->newCustomer, 1));

    expect($response->status())->toBe(422);
    expect($response->json())->toHaveKey('errors');
    expect($response->json('errors.name'))->toContain('The name field is required.');

});
