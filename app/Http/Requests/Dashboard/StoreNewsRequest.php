<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsRequest extends FormRequest
{
    use ImageValidationRules;

    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(News::class, 'slug'),
            ],
            'content' => [
                'required',
                'string',
            ],
            'published_at' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'image' => $this->imageRules(),
        ];
    }
}
