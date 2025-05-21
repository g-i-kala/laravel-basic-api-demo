<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends ApiRequest
{

    protected arrray $normalize = ['customerId', 'amount', 'status', 'billedDate', 'paidDate']l
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerId' => ['required'],
            'amount'     => ['required', 'numeric', 'min:0'],
            'status'     => ['required', Rule::in(['B', 'P', 'V'])],
            'billedDate' => ['required', 'date'],
            'paidDate'   => ['nullable', 'date', 'after_or_equal:billedDate'],
        ];
    }

}
