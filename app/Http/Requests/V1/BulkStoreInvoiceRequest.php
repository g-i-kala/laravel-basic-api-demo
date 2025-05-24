<?php

namespace App\Http\Requests\V1;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Requests\V1\ApiRequest;

class BulkStoreInvoiceRequest extends ApiRequest
{
    /**
    * Define fields that are to be prepared for validation.
    */
    //    protected array $normalize = ['customerId', 'amount', 'status', 'billedDate', 'paidDate'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.customer_id' => ['required', 'integer'],
            '*.amount'     => ['required', 'numeric', 'min:0'],
            '*.status'     => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billed_date' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paid_date'   => ['nullable', 'date_format:Y-m-d H:i:s', 'after_or_equal:billed_date']
        ];
    }

    public function prepareForValidation()
    {
        $normalized = [];

        foreach ($this->toArray() as $i => $item) {
            $normalized[$i] = collect($item)
                ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => is_string($value) ? trim($value) : $value])
                ->toArray();
        }

        $this->replace($normalized);
    }

}
