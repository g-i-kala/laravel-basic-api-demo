<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);

it('shows a single customer via id belonging to the logged in user', function () {
    $userA = User::factory()->create();
    $customersUserA = Customer::factory()->for($userA)->count(2)->create();

    actingAs($userA, 'sanctum');

    $customerId = $customersUserA->first()->id;

    $response = get('/api/v1/customers/' . $customerId);
    $response->assertJsonPath('data.id', $customerId);

});
