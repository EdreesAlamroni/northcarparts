<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ImageValidationRules;
use App\Models\News;
use App\ModelStates\News\NewsState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\ModelStates\Validation\ValidStateRule;

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
            'state' => [
                new ValidStateRule(NewsState::class),
            ],
            'image' => $this->imageRules(),
        ];
    }
}
