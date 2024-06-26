<?php

namespace Database\Factories;

use Carbon\Carbon as CarbonCarbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\AgreementType;
use App\Models\Company;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AgreementFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $dateOpen = fake()->date();
        $dateClose = Carbon::parse($dateOpen)->addDays(30);
        $agreementType = AgreementType::query()->inRandomOrder()->first();
        $seller = Company::inRandomOrder()->first();
        $buyer = Company::where('id','<>', $seller->id)->inRandomOrder()->first();
        $creator = User::inRandomOrder()->first();

        return [
            'name' => fake()->name(),
            'agr_number' => fake()->uuid(),
            'date_open' => $dateOpen,
            'date_close' => $dateClose, 
            'agreement_type_id' => $agreementType->id, 
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'created_by' => $creator->id,
        ];
    }


}
