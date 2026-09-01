<?php

namespace App\Http\Requests\Dashboard;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        /** @var News $news */
        $news = $this->route('news');

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
                Rule::unique(News::class, 'slug')->ignore($news->id),
            ],
            'content' => [
                'required',
                'string',
            ],
            'published_at' => [
                'nullable',
                'date_format:Y-m-d',
            ],
        ];
    }
}
