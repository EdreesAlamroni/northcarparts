<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Category;
use App\ModelStates\Category\CategoryState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\ModelStates\Validation\ValidStateRule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        /** @var Category $category */
        $category = $this->route('category');

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
                Rule::unique(Category::class, 'slug')->ignore($category->id),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'technical_description' => [
                'nullable',
                'string',
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
            'state' => [
                new ValidStateRule(CategoryState::class),
            ],
        ];
    }
}
