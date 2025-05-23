<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Filters\InvoicesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new InvoicesFilter();
        $filterItems = $filter->transform($request); // ['column', 'operator', 'value']

        if (count($filterItems) == 0) {
            return new InvoiceCollection(Invoice::paginate());
        } else {
            $invoice = Invoice::where($filterItems)->paginate();
            return new InvoiceCollection($invoice->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        return new InvoiceResource(Invoice::create($request->validated()));
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        // $job = Auth::user()->employer->jobs()->create(Arr::except($attributes, 'tags'));

        Invoice::insert($request->validated());

        return response()->json(['message' => 'Invoices created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
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
