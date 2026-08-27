<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Http\Requests\Dashboard\Concerns\ValidatesProductSpecifications;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    use ImageValidationRules;
    use ValidatesProductSpecifications;

    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'code'),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'slug'),
            ],
            'category_id' => [
                'required',
                Rule::exists(Category::class, 'id'),
            ],
            'oem_manufacturer_id' => [
                'required',
                Rule::exists(Manufacturer::class, 'id'),
            ],
            'oem_number' => [
                'required',
                'string',
                'max:255',
            ],
            ...$this->specificationRules(),
            'image' => $this->imageRules(),
        ];
    }
}
