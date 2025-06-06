<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, getJson, post, put, patch, delete};

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->userA = User::factory()->create();
    $this->customersUserA = Customer::factory()->for($this->userA)->count(2)->create();
});


it('shows a single customer via id belonging to the logged in user', function () {
    actingAs($this->userA, 'sanctum');

    $customerId = $this->customersUserA->first()->id;

    $response = getJson('/api/v1/customers/' . $customerId);

    expect($response->status())->toBe(200);
    expect($response->json())->toHaveKey('data.id', $customerId);

});


it('can show a single user customer vis logged in user id with invoices', function () {
    actingAs($this->userA, 'sanctum');

    $customerId = $this->customersUserA->first()->id;
    $query = 'includeInvoices';

    $response = getJson('/api/v1/customers/' . $customerId . '?' . $query . '=true');
    $response->assertJsonPath('data.id', $customerId);

    expect($response->status())->toBe(200);
    expect($response->json())->toHaveKey('data.invoices');

});
