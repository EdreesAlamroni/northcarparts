<?php

namespace App\Http\Requests\Dashboard;

use App\Http\Requests\Dashboard\Concerns\ValidatesProductSpecifications;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    use ValidatesProductSpecifications;

    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        /** @var Product $product */
        $product = $this->route('product');

        return [
            'category_id' => [
                'required',
                Rule::exists(Category::class, 'id'),
            ],
            'oem_manufacturer_id' => [
                'required',
                Rule::exists(Manufacturer::class, 'id'),
            ],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'code')->ignore($product->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'slug')->ignore($product->id),
            ],
            'oem_number' => [
                'required',
                'string',
                'max:255',
            ],
            ...$this->specificationRules(),
        ];
    }
}
