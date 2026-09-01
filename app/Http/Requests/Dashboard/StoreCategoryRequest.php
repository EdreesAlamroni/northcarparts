<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    use ImageValidationRules;

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
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Category::class, 'slug'),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'technical_description' => [
                'nullable',
                'string',
            ],
            'image' => $this->imageRules(),
        ];
    }
}
