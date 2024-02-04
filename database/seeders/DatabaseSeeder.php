<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusUpdate;
use App\Models\User;
use App\Models\Patient;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a single instance of User First Admin
        User::create([
            'id' => 1,
            'Name' => 'John Doe',
            'Email' => 'john.doe@example.com',
            'PersonalID' => '123456789',
            'Password' => '123',
            'DateOfBirth' => '1990-01-01',
            'Type' => 'Admin',
            'inService' => 'Active',
        ]);

        //create randomly 5 instances from each Table
        User::factory(5)->create();
        Patient::factory(5)->create();
        StatusUpdate::factory(5)->create();
    }
}
