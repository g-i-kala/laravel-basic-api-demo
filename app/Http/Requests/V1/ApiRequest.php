<?php

namespace App\Http\Requests\V1;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    protected array $normalize = [];

    protected function prepareForValidation()
    {
        $data = [];

        foreach ($this->normalize as $field) {
            if ($this->has($field)) {
                $data[Str::snake($field)] = trim($this->input($field));
            }
        }

        $this->merge($data);
    }
}
