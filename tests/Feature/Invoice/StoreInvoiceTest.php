<?php

use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{actingAs, get, post, put, patch, delete, postJson};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userA = User::factory()->create();
    $this->customerA = Customer::factory()->for($this->userA)->create();
    $this->invoicesCustomerA = Invoice::factory()->for($this->customerA)->count(5)->create();

    $this->newInvoice = [
        "customerId" => $this->customerA->id,
        "amount" => 666,
        "status" => "P",
        "billedDate" => "2021-01-11 11:11:11",
        "paidDate" => "2022-01-22 22:22:22"
    ];
});

// Happy path

it('can store a single invoice for authentificated user', function () {

    Sanctum::actingAs($this->userA, ['store']);

    $response = post("/api/v1/invoices/", $this->newInvoice);

    expect($response->status())->toBe(201);
    collect($response->json())
        ->each(fn ($invoice) => expect($invoice)
        ->toHaveKeys(['id', 'customerId', 'amount', 'status', 'billedDate', 'paidDate']));

    $newInvoiceId = collect($response->json())->pluck('id');

    expect(DB::table('invoices')
       ->where('id', $newInvoiceId)
       ->first())
       ->toMatchArray([
           'customer_id' => $this->customerA->id,
           'amount' => 666,
       ]);
});

it('can bulk store invoices for authentificated user', function () {

    Sanctum::actingAs($this->userA, ['store']);

    $newInvoices = [
        $this->newInvoice,
        [
            "customerId" => $this->customerA->id,
            "amount" => 777,
            "status" => "B",
            "billedDate" => "2021-01-13 13:11:11",
            "paidDate" => "2022-01-23 23:23:23"
        ],
        [
             "customerId" => $this->customerA->id,
            "amount" => 888,
            "status" => "P",
            "billedDate" => "2021-01-14 14:14:14",
            "paidDate" => "2022-01-24 00:24:24"
        ]
    ];

    $response = post("/api/v1/invoices/bulk", $newInvoices);

    expect($response->status())->toBe(200);
    expect(collect($response->json()))
        ->toHaveKey('message')
        ->toMatchArray([
            'message' => 'Invoices created successfully'
        ]);

});

// Unhappy path

it('cant store invoices for not authorized user', function () {
    $response = post("/api/v1/invoices/", $this->newInvoice);

    expect($response->status())->toBe(302);
    $response->assertRedirect('/login');
});

it('cant store incomplete invoices for an authorized user', function () {
    Sanctum::actingAs($this->userA, ['store']);

    $response = postJson("/api/v1/invoices/", array_slice($this->newInvoice, 1));

    expect($response->status())->toBe(422);
    expect($response->json())->toHaveKey('errors');
    expect(($response->json('errors.customer_id')))->toContain('The customer id field is required.');

});
