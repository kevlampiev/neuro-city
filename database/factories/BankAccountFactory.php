<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    public function definition(): array
    {
        return [
            'account_number' => $this->faker->regexify('[0-9]{20}'),
            'description' => $this->faker->sentence(), // Исправлено описание
            'date_open' => $this->faker->date(),
            'owner_id' => Company::factory(),
            'bank_id' => Company::factory(),
            'created_by' => User::factory(), // Связываем проект с фабрикой User
            'created_at' => now(),

        ];
    }
}
