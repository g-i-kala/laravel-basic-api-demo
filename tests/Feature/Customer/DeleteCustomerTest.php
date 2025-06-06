<?php

use App\Models\User;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->userA = User::factory()->create();
    $this->customersUserA = Customer::factory()->for($this->userA)->create();
});

// Happy path

it('can delete a customer', function () {
    actingAs($this->userA, 'sanctum');

    $customerId = $this->customersUserA->first()->id;

    $response = delete('/api/v1/customers/' . $customerId);
    expect($response->status())->toBe(204);

    expect(DB::table('customers')
        ->where('id', $customerId)
        ->get()
        ->isEmpty())
        ->toBeTrue();

});

// Unhappy path

it('cant delete a customer when not authorized', function () {
    $userB = User::factory()->create();
    $customerUserB = Customer::factory()->for($userB)->create();

    actingAs($this->userA, 'sanctum');
    $response = delete('/api/v1/customers/' . $customerUserB->id);

    expect($response->status())->toBe(403);

});

it('cant delete a customer when no ide provided', function () {
    actingAs($this->userA, 'sanctum');
    $response = delete('/api/v1/customers/');

    expect($response->status())->toBe(405);
});

it('cant delete a nonexisting customer', function () {
    actingAs($this->userA, 'sanctum');

    $response = delete('/api/v1/customers/99999');
    expect($response->status())->toBe(404);
});
