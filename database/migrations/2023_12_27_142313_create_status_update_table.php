<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //create a Table called status_updates With its field names and type
    public function up()
    {
        Schema::create('status_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('EmergMedCareGiven')->nullable();
            $table->string('PatientResponseToEmergMedCare')->nullable();
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
        Schema::dropIfExists('status_updates');
    }
};
