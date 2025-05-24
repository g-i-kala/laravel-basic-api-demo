<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use App\Http\Requests\V1\ApiRequest;

class StoreCustomerRequest extends ApiRequest
{
    /**
       * Define fields that are to be prepared for validation.
       */
    protected array $normalize = ['postalCode', 'name', 'type', 'email', 'address', 'city', 'state'];

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
            'name' => ['required'],
            'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'postal_code' => ['required']
        ];
    }

}
