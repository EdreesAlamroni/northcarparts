<?php

namespace App\Http\Requests\Dashboard;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends FormRequest
{
    use ProfileValidationRules;

    public function authorize(): bool
    {
        return auth('web')->check();
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        return [
            ...$this->profileRules($user->id),
            'roles' => [
                'required',
                'array',
            ],
            'roles.*' => [
                Rule::exists(Role::class, 'name'),
            ],
        ];
    }
}
