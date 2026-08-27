<?php

namespace App\Http\Requests\Dashboard\Concerns;

use App\Models\Specification;
use App\Models\SpecificationValue;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

trait ValidatesProductSpecifications
{
    protected function specificationRules(): array
    {
        return [
            'specification_groups' => [
                'nullable',
                'array',
            ],
            'specification_groups.*' => [
                'integer',
                Rule::exists(Specification::class, 'id'),
            ],
            'specification_value_ids' => [
                'nullable',
                'array',
            ],
            'specification_value_ids.*' => [
                'integer',
                Rule::exists(SpecificationValue::class, 'id'),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $groupIds = collect($this->input('specification_groups', []))
                ->map(fn (mixed $id): int => (int) $id)
                ->filter()
                ->values();

            $valueIds = collect($this->input('specification_value_ids', []))
                ->map(fn (mixed $id): int => (int) $id)
                ->filter()
                ->values();

            if ($valueIds->isEmpty()) {
                return;
            }

            $invalidCount = SpecificationValue::query()
                ->whereIn('id', $valueIds)
                ->whereNotIn('specification_id', $groupIds)
                ->count();

            if ($invalidCount > 0) {
                $validator->errors()->add(
                    'specification_value_ids',
                    __('قيمة الخاصية المحددة لا تنتمي إلى المجموعة المختارة.'),
                );
            }
        });
    }
}
