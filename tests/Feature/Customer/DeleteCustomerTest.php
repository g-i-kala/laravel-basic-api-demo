<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);

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
