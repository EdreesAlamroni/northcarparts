<?php

namespace App\Console\Commands;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\password;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

#[Signature('app:install')]
#[Description('Install the application')]
class InstallApplication extends Command
{
    public function handle(): int
    {
        if (User::query()->exists()) {
            $this->newLine();
            $this->components->warn('The application has already been installed.');
            $this->components->twoColumnDetail('Reason', 'At least one user already exists in the database.');
            $this->components->twoColumnDetail('Action', 'No changes were made.');
            $this->newLine();

            return Command::FAILURE;
        }

        if (! $this->input->isInteractive()) {
            $this->components->error('This command must be run interactively.');

            return Command::FAILURE;
        }

        intro('Application Installation');

        $credentials = $this->collectAdministratorCredentials();

        $user = spin(
            callback: function () use ($credentials): User {
                return $this->install($credentials);
            },
            message: 'Installing application...',
        );

        $this->newLine();
        $this->components->info('Application installed successfully.');
        $this->components->twoColumnDetail('Administrator', $user->name);
        $this->components->twoColumnDetail('Email', $user->email);
        $this->newLine();

        outro('You can now sign in to the application.');

        return Command::SUCCESS;
    }

    private function collectAdministratorCredentials(): array
    {
        $rules = (new class
        {
            use ProfileValidationRules;

            public function rules(): array
            {
                return [
                    ...$this->profileRules(),
                    'password' => ['required', 'string', 'max:255', Password::defaults()],
                ];
            }
        })->rules();

        $credentials = [];

        $credentials['name'] = text(
            label: 'Administrator name',
            required: true,
            validate: function (string $value) use ($rules): ?string {
                return $this->validationMessage(
                    $rules,
                    ['name' => $value],
                    'name',
                );
            },
        );

        $credentials['email'] = text(
            label: 'Administrator email',
            required: true,
            validate: function (string $value) use ($rules, $credentials): ?string {
                return $this->validationMessage(
                    $rules,
                    [
                        'name' => $credentials['name'],
                        'email' => $value,
                    ],
                    'email',
                );
            },
        );

        $credentials['password'] = password(
            label: 'Administrator password',
            required: true,
            validate: function (string $value) use ($rules): ?string {
                return $this->validationMessage(
                    ['password' => $rules['password']],
                    ['password' => $value],
                    'password',
                );
            },
        );

        password(
            label: 'Confirm password',
            required: true,
            validate: function (string $value) use ($credentials): ?string {
                if ($value !== $credentials['password']) {
                    return 'The password confirmation does not match.';
                }

                return null;
            },
        );

        return $credentials;
    }

    private function validationMessage(array $rules, array $data, string $field): ?string
    {
        $validator = Validator::make($data, $rules);

        if ($validator->errors()->has($field)) {
            return $validator->errors()->first($field);
        }

        return null;
    }

    private function install(array $credentials): User
    {
        return DB::transaction(function () use ($credentials): User {
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                'is_administrator' => true,
                'email_verified_at' => now(),
            ]);

            $this->call('seed:permissions');

            return $user;
        });
    }
}
