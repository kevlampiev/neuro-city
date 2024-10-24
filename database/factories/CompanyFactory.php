<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $someInt = rand(1,4);
        return [
            'name' => fake()->name(),
            'fullname' => fake()->name(),
            'inn' => fake()->numberBetween(11111111111,99911111111),
            'ogrn' => fake()->numberBetween(11111111111,99911111111),
            'post_adress' => fake()->address(),
            'our_company' => ($someInt === 4),
            'company_type' => ['bank','other'][rand(0,1)],
            'phone' =>fake()->phoneNumber(),
            'created_at'=>now(),
        ];
    }
}
