<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    //create a Table called patients With its field names and type
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('FullName')->nullable();
            $table->integer('PatientID')->unique()->nullable();
            $table->string('Address')->nullable();
            $table->date('Date')->nullable();
            $table->enum('AgeYearsOrMonths',['Years','Months'])->nullable();
            $table->integer('Age')->nullable();
            $table->string('PhoneNumber')->nullable();
            $table->enum('Gender',['Male','Female'])->nullable();
            $table->string('ChiefComplaint')->nullable();
            $table->string('PertinentFindingsOnPhysicalExarn')->nullable();
            $table->set('MedicalHistory',['Diabetes','COPD','cardiac','Seizure','Hypertension','cancer','ot'])->nullable();
            $table->set('Allergies',['None','PCN','Sulfa','Codeine','Iodine','Unknown'])->default('unknown')->nullable();
            $table->enum('Medication',['Denies','Name(s)'])->nullable();
            $table->string('MedicationName')->nullable();
            $table->string('EmergMedCareGiven')->nullable();
            $table->string('PatientResponseToEmergMedCare')->nullable();
            $table->enum('Destination',['Heath center','Interfacility','Rendezvous','Residence','Other'])->nullable();
            $table->set('Reasons',['Protocol','Nearest Facility','Family case choice','Online instructions','Other'])->nullable();
            $table->string('SiteOfInjury')->nullable();
            $table->time('Time')->nullable();
            $table->string('B_P')->nullable();
            $table->float('PULSE')->nullable();
            $table->float('RESP')->nullable();
            $table->float('TEMP')->nullable();
            $table->float('PulseO2')->nullable();
            $table->float('ECG')->nullable();
            $table->enum('Death',['DOA','Refused CPR','During intervention','During Transport'])->nullable();
            $table->time('TimeOfDeath')->nullable();
            $table->enum('AuthorityNotified',['yes','no'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
