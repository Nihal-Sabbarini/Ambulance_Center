<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StatusUpdate;

class StatusUpdateFactory extends Factory
{
    //Define initial values for each field
    public function definition() : array
    {
        //BP possible values
        $systolic = $this->faker->numberBetween(45, 160);
        $diastolic = $this->faker->numberBetween(30, 100);
        $bloodPressure = "{$systolic}/{$diastolic}";

        return [
            'patient_id' => \App\Models\Patient::factory()->create()->id,
            'EmergMedCareGiven' => $this->faker->sentence(),
            'PatientResponseToEmergMedCare' => $this->faker->sentence(),
            'Time' => $this->faker->time(),
            'B_P' => $bloodPressure,
            'PULSE' => $this->faker->randomFloat(2, 0, 100),
            'RESP' => $this->faker->randomFloat(2, 0, 100),
            'TEMP' => $this->faker->randomFloat(2, 0, 100),
            'PulseO2' => $this->faker->randomFloat(2, 0, 100),
            'ECG' => $this->faker->randomFloat(2, 0, 100),
            'Death' => $this->faker->randomElement(['DOA','Refused CPR','During intervention','During Transport']),
            'TimeOfDeath' => $this->faker->time(),
            'AuthorityNotified' => $this->faker->randomElement(['yes','no']),
             ];
    }
}





