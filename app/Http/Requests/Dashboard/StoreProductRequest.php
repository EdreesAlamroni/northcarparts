<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Enums\FilterType;
use App\Http\Requests\Dashboard\Concerns\ValidatesProductSpecifications;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use App\ModelStates\Product\ProductState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\ModelStates\Validation\ValidStateRule;

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
                Rule::unique(Product::class, 'code'),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'slug'),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'filter_type' => [
                'required',
                Rule::enum(FilterType::class),
            ],
            'oem_number' => [
                'required',
                'string',
                'max:255',
            ],
            'qr_code_redirect_url' => [
                'required',
                'url',
                'regex:/^https?:\/\//i',
                'max:255',
                Rule::unique(Product::class, 'qr_code_redirect_url'),
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
            'state' => [
                new ValidStateRule(ProductState::class),
            ],
            ...$this->specificationRules(),
            'image' => $this->imageRules(),
        ];
    }
}
