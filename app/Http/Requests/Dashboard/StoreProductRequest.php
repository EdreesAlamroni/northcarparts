<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Http\Requests\Dashboard\Concerns\ValidatesProductSpecifications;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    use ImageValidationRules, ValidatesProductSpecifications;

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
            'cross_references' => [
                Rule::array(Brand::query()->pluck('id')),
            ],
            'cross_references.*' => [
                'nullable',
                'string',
                'max:255',
                'regex:/[A-Z0-9]/i',
            ],
            ...$this->specificationRules(),
            'images' => $this->imagesRules(),
            'images.*' => $this->imageItemRules(),
        ];
    }

    public function getAttributes(): array
    {
        return Arr::except($this->validated(), [
            'images',
            'specifications',
            'cross_references',
        ]);
    }
}
