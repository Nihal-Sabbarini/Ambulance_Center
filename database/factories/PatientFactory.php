<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    //Define initial values for each field
    public function definition() : array
    {
        //CheckBox values
        $availableMedicalHistory = ['Diabetes','COPD','cardiac','Seizure','Hypertension','cancer','ot'];
        $availableAllergies = ['None','PCN','Sulfa','Codeine','Iodine','Unknown'];
        $availableReasons = ['Protocol','Nearest Facility','Family case choice','Online instructions','Other'];

        //BP possible values
        $systolic = $this->faker->numberBetween(45, 160);
        $diastolic = $this->faker->numberBetween(30, 100);
        $bloodPressure = "{$systolic}/{$diastolic}";

        return [
            'user_id' => \App\Models\User::factory()->create()->id,
            'FullName' => $this->faker->name,
            'PatientID' => $this->faker->unique()->numberBetween(0-200),
            'Address' => $this->faker->address(),
            'AgeYearsOrMonths' => $this->faker->randomElement(['Years', 'Months']),
            'Age' => $this->faker->numerify('##'),
            'PhoneNumber' => $this->faker->numerify('##########'),
            'Gender' => $this->faker->randomElement(['Male','Female']),
            'ChiefComplaint' => $this->faker->sentence(),
            'PertinentFindingsOnPhysicalExarn' => $this->faker->sentence(),
            'MedicalHistory' => implode(',', $this->faker->randomElements($availableMedicalHistory, $this->faker->numberBetween(1, count($availableMedicalHistory)))),
            'Allergies' => implode(',', $this->faker->randomElements($availableAllergies, $this->faker->numberBetween(1, count($availableAllergies)))),
            'Medication' => $this->faker->randomElement(['Denies','Name(s)']),
            'MedicationName' => $this->faker->sentence(),
            'EmergMedCareGiven' => $this->faker->sentence(),
            'PatientResponseToEmergMedCare' => $this->faker->sentence(),
            'Destination' => $this->faker->randomElement(['Heath center','Interfacility','Rendezvous','Residence','Other']),
            'Reasons' => implode(',', $this->faker->randomElements($availableReasons, $this->faker->numberBetween(1, count($availableReasons)))),
            'SiteOfInjury' => $this->faker->sentence(),
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

