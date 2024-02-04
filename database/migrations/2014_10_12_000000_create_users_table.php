<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //create a Table called users With its field names and type
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('Name')->require();
            $table->string('Email')->unique()->required();
            $table->integer('PersonalID')->nullable();
            $table->string('Password')->required();
            $table->date('DateOfBirth')->nullable();
            $table->enum('Type',['Admin' , 'Paramedic' , 'Hospital'])->require();
            $table->enum('inService' ,['Active' , 'NotActive'])->require();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
