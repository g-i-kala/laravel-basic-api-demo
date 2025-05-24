<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use App\Http\Requests\V1\ApiRequest;

class UpdateInvoiceRequest extends ApiRequest
{
    /**
    * Define fields that are to be prepared for validation.
    */
    protected array $normalize = ['customerId', 'amount', 'status', 'billedDate', 'paidDate'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('put')) {
            return [
            'customer_id' => ['required'],
            'amount'     => ['required', 'numeric', 'min:0'],
            'status'     => ['required', Rule::in(['B', 'P', 'V'])],
            'billed_date' => ['required', 'date'],
            'paid_date'   => ['nullable', 'date', 'after_or_equal:billedDate'],
        ];
        } else {
            return [
            'customer_id' => ['sometimes', 'required'],
            'amount'     => ['sometimes', 'required', 'numeric', 'min:0'],
            'status'     => ['sometimes', 'required', Rule::in(['B', 'P', 'V'])],
            'billed_date' => ['sometimes', 'required', 'date'],
            'paid_date'   => ['sometimes', 'nullable', 'date', 'after_or_equal:billedDate'],
        ];
        }

    }
}
