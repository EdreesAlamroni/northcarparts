<?php

namespace App\Http\Requests\Dashboard\Concerns;

use App\Models\Specification;
use Illuminate\Contracts\Validation\Validator;

trait ValidatesProductSpecifications
{
    protected function specificationRules(): array
    {
        return [
            'specifications' => [
                'nullable',
                'array',
            ],
            'specifications.*' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $specificationIds = collect($this->input('specifications', []))
                ->keys()
                ->map(fn (mixed $id): int => (int) $id)
                ->filter()
                ->values();

            if ($specificationIds->isEmpty()) {
                return;
            }

            $validCount = Specification::query()
                ->whereIn('id', $specificationIds)
                ->count();

            if ($validCount !== $specificationIds->count()) {
                $validator->errors()->add(
                    'specifications',
                    __('الخاصية المحددة غير صحيحة.'),
                );
            }
        });
    }
}
