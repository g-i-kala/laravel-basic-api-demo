<?php

namespace App\Http\Requests\V1;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends ApiRequest
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
            'name' => ['required'],
            'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'postal_code' => ['required']
        ];
        } else {
            return [
            'name' => ['sometimes', 'required'],
            'type' => ['sometimes', 'required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => ['sometimes', 'required', 'email'],
            'address' => ['sometimes', 'required'],
            'city' => ['sometimes', 'required'],
            'state' => ['sometimes', 'required'],
            'postal_code' => ['sometimes', 'required']
        ];
        }

    }
}
