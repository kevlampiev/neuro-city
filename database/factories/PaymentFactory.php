<?php

namespace Database\Factories;

use App\Models\Agreement;
use App\Models\BankAccount;
use App\Models\CFSItem;
use App\Models\Company;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $summ = rand (-1000000, 1000000);
        // $bankAccount = BankAccount::inRandomOrder()->first();
        // $agreement = Agreement::inRandomOrder()->first();
        // $project = Project::inRandomOrder()->first();
        // $cfsItem = CFSItem::inRandomOrder()->first();
        // return [
        //     'description' => $this->faker->text(50), // Исправлено описание
        //     'date_open' => $this->faker->date(),
        //     'bank_account_id' => $bankAccount->id,
        //     'agreement_id' => $agreement->id,
        //     'amount' => $summ,
        //     'VAT' => $summ*[0, 0.1, 0.2][rand(0,2)],
        //     'project_id' => $project->id,
        //     'cfs_item_id' => $cfsItem->id,
        //     'created_at' => now(),
        // ];


        return [
            'description' => $this->faker->text(50), // Исправлено описание
            'date_open' => $this->faker->date(),
            'bank_account_id' => BankAccount::factory(),
            'agreement_id' => Agreement::factory(),
            'amount' => $summ,
            'VAT' => $summ*[0, 0.1, 0.2][rand(0,2)],
            'project_id' => Project::factory(),
            'cfs_item_id' => CFSItem::factory(),
            'created_at' => now(),
        ];
    }
}
