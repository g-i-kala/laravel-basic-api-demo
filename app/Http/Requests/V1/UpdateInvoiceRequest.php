<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\V1\ApiRequest;

class UpdateInvoiceRequest extends ApiRequest
{
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
            'amount' => ['required'],
            'status' => ['required', 'integer'],
            'billedDate' => ['required'],
            'paidDate' => ['required']
        ];
    }
}
