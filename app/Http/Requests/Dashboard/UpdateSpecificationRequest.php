<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Specification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpecificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Specification::class, 'name')->ignore($this->route('specification')),
            ],
        ];
    }
}
