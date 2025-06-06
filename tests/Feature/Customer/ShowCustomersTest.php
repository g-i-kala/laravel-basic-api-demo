<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->userA = User::factory()->create();
    $this->customersUserA = Customer::factory()->for($this->userA)->count(2)->create();
});

it('can get customers for authentificated user', function () {
    actingAs($this->userA, 'sanctum');

    $response = get("/api/v1/customers/");
    $response->assertOk();

});

it('shows only the users customers', function () {
    $userB = User::factory()->create();
    $customersUserB = Customer::factory()->for($userB)->count(4)->create();

    actingAs($this->userA, 'sanctum');

    $response = get("/api/v1/customers/");
    $response->assertJsonCount(2, 'data');

});

it('can show customers including their invoices', function () {
    actingAs($this->userA, 'sanctum');
    $query = 'includeInvoices';
    $response = get('/api/v1/customers?' . $query . '=true');

    expect($response->status())->toBe(200);
});
