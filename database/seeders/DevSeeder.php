<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Examination;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Client::factory(1000)->create();
        Appointment::factory(2000)->create(); // TODO: poner 30000
    }
}
