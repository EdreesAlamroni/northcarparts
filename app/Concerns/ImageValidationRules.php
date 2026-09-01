<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait ImageValidationRules
{
    /**
     * Maximum allowed image upload size in kilobytes (10 MB).
     */
    protected const IMAGE_MAX_KILOBYTES = 10240;

    /**
     * Get the validation rules used to validate image uploads.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function imageRules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            ...$this->imageItemRules(),
        ];
    }

    /**
     * Get the validation rules used to validate multiple image uploads.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function imagesRules(bool $required = false): array
    {
        $rules = [
            $required ? 'required' : 'nullable',
            'array',
        ];

        if ($required) {
            $rules[] = 'min:1';
        }

        return $rules;
    }

    /**
     * Get the validation rules used to validate each image in a multiple upload.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function imageItemRules(): array
    {
        return [
            'image',
            'mimes:jpeg,png,webp',
            'max:'.self::IMAGE_MAX_KILOBYTES,
        ];
    }
}
