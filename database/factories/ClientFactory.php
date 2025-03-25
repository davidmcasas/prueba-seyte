<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ClientFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company_name = fake()->unique()->company();
        $contract_start_date = Carbon::now()->addDays((rand(0, 365)));
        $contract_end_date = $contract_start_date->copy()->addDays((rand(7, 365)));
        return [
            'company_name' => $company_name,
            'cif' => fake()->unique()->randomNumber(8, true),
            'address' => fake()->unique()->address(),
            'municipality' => fake()->city(),
            'province' => fake()->country(),
            'contract_start_date' => $contract_start_date,
            'contract_end_date' => $contract_end_date,
            'examinations_included' => rand(1,100) * 10,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
