<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    //Define initial values for each field
    public function definition() : array
    {
        return [
            'Name' => $this->faker->name,
            'Email' => $this->faker->unique()->safeEmail,
            'PersonalID' => $this->faker->unique()->numberBetween(0-200),
            'password' => $this->faker->password,
            'DateOfBirth' => $this->faker->date,
            'Type' => $this->faker->randomElement(['Paramedic', 'Admin', 'Hospital']),
            'inService' => $this->faker->randomElement(['Active', 'NotActive']),
        ];
    }
}
