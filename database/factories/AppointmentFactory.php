<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'client_id' => $this->faker->numberBetween(1, 1000),  //Client::inRandomOrder()->first()->id ?? Client::factory(),
            'date' => $this->faker->dateTimeBetween('-1 year', '+3 year'),
            'requested_examinations' => $this->faker->numberBetween(1, 10),
            'performed_examinations' => $this->faker->numberBetween(0, 10),
        ];
    }
}
