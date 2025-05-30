<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Filters\InvoicesFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Invoice::class);

        $filter = new InvoicesFilter();
        $filterItems = $filter->transform($request); // ['column', 'operator', 'value']

        $custsomerIds = $request->user()->customers()->pluck('id');
        $invoices = Invoice::whereIn('customer_id', $custsomerIds);

        if (count($filterItems) == 0) {
            return new InvoiceCollection($invoices->paginate());
        } else {
            $filteredInvoices = $invoices->where($filterItems)->paginate();
            return new InvoiceCollection($filteredInvoices->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        // find the customer by the id from request
        $customer = Customer::findOrFail($request->input('customer_id'));
        $this->authorize('view', $customer);

        return new InvoiceResource(Invoice::create($request->validated()));
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        Invoice::insert($request->validated());

        return response()->json(['message' => 'Invoices created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        $invoice->update($request->validated());

        return response()->json(['message' => 'Invoices updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
