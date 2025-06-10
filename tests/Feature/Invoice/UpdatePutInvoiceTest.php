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

    $this->updateInvoice = [
        "customerId" => $this->customerA->id,
        "amount" => 111,
        "status" => "P",
        "billedDate" => "2021-01-11 11:11:11",
        "paidDate" => "2023-01-24 22:33:33"
    ];
});

// Happy path

it('can update a single invoice for authentificated user', function () {

    Sanctum::actingAs($this->userA, ['update']);
    $invoiceId = $this->invoicesCustomerA->first()->id;

    $response = put("/api/v1/invoices/" . $invoiceId, $this->updateInvoice);

    expect($response->status())->toBe(200);

    expect(DB::table('invoices')
       ->where('id', $invoiceId)
       ->first())
       ->toMatchArray([
           'customer_id' => $this->customerA->id,
           'amount' => 111,
       ]);

    expect(collect($response->json()))->toHaveKey('message');
    expect(($response->json('message')))->toContain('Invoices updated successfully');

});

// Unhappy path

// it('cant update invoices for not authorized user', function () {
//     $response = post("/api/v1/invoices/", $this->newInvoice);

//     expect($response->status())->toBe(302);
//     $response->assertRedirect('/login');
// });

// it('cant update incomplete invoices for an authorized user', function () {
//     Sanctum::actingAs($this->userA, ['store']);

//     $response = postJson("/api/v1/invoices/", array_slice($this->newInvoice, 1));

//     expect($response->status())->toBe(422);
//     expect($response->json())->toHaveKey('errors');
//     expect(($response->json('errors.customer_id')))->toContain('The customer id field is required.');

// });
