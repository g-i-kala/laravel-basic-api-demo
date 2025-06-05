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
