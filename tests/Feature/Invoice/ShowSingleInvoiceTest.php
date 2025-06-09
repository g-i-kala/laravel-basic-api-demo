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

});

it('gets invoices only for the authentificated user', function () {
    $this->userB = User::factory()->create();
    $this->customerB = Customer::factory()->for($this->userB)->create();
    $this->invoicesCustomerB = Invoice::factory()->for($this->customerB)->count(3)->create();

    actingAs($this->userA, 'sanctum');

    $response = get("/api/v1/invoices/");

    expect($response->json('data'))->toHaveCount(5);
});

// Unhappy path

it('cant get invoice for an unauthentificated user', function () {
    $response = get("/api/v1/invoices/");
    expect($response->status())->toBe(302);
    $response->assertRedirect('/login');

});


it('cant get invoices for another user', function () {
    $this->userB = User::factory()->create();
    $this->customerB = Customer::factory()->for($this->userB)->create();
    $this->invoicesCustomerB = Invoice::factory()->for($this->customerB)->count(3)->create();

    actingAs($this->userB, 'sanctum');

    $response = get("/api/v1/invoices/");

});
