<?php

use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userA = User::factory()->create();
    $this->customerA = Customer::factory()->for($this->userA)->create();
    $this->invoicesCustomerA = Invoice::factory()->for($this->customerA)->count(5)->create();
});

// Happy path

it('can get invoices for authentificated user', function () {

    actingAs($this->userA, 'sanctum');

    $response = get("/api/v1/invoices/");
    $response->assertOk();

    expect($response->status())->toBe(200);

    collect($response->json('data'))
        ->each(fn ($invoice) => expect($invoice)
        ->toHaveKeys(['id', 'customerId', 'amount', 'status', 'billedDate', 'paidDate']));

    $customerId = $this->customerA->id;

    collect($response->json('data'))
        ->pluck('customerId')
        ->each(fn ($customer) => expect($customer)->toBe($customerId));

});

it('gets invoices only for the authentificated user', function () {
    $this->userB = User::factory()->create();
    $this->customerB = Customer::factory()->for($this->userB)->create();
    $this->invoicesCustomerB = Invoice::factory()->for($this->customerB)->count(3)->create();

    actingAs($this->userA, 'sanctum');

    $response = get("/api/v1/invoices/");
    expect($response->json('data'))->toHaveCount(5);
});

it('shows invoices with filtering', function () {
    actingAs($this->userA, 'sanctum');
    $filterQuery = '?amount[gt]=5000';

    $response = get("/api/v1/invoices/" . $filterQuery);
    expect($response->status())->toBe(200);

    collect($response->json('data'))->each(fn ($invoice) =>
    expect($invoice['amount'])->toBeGreaterThan(5000));

    collect($response->json('data'))
        ->pluck('amount')
        ->each(fn ($amount) => expect($amount)->toBeGreaterThan(5000));

});

// Unhappy path

it('cant get invoices for an unauthentificated user', function () {
    $response = get("/api/v1/invoices/");
    expect($response->status())->toBe(302);
    $response->assertRedirect('/login');

});
