<?php

namespace Database\Seeders;

use App\Models\Agreement;
use App\Models\BankAccount;
use App\Models\CFSItem;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Faker;
use App\Models\Project;

class PaymentSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i=0;$i<30;$i++) {
            $account = BankAccount::query()->inRandomOrder()->first();
            $agreement = Agreement::query()->inRandomOrder()->first();
            $project = Project::query()->inRandomOrder()->first();
            $cfsItem = CFSItem::query()->inRandomOrder()->first();
            $summ = rand(-1000000, 1000000);
    
            $payment = new Payment();
            $payment->description = fake()->text(50); // Исправлено описание
            $payment->date_open = fake()->date();
            $payment->bank_account_id = $account->id;
            $payment->agreement_id = $agreement->id;
            $payment->amount = $summ;
            $payment->VAT = $summ*[0, 0.1, 0.2][rand(0,2)];
            $payment->project_id = $project->id;
            $payment->cfs_item_id = $cfsItem->id;
            $payment->benefeciary_id = $agreement->owner_id;
            $payment->created_at = now();
            $payment->save();
    
        }
    }
}
