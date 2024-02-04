<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;


class Patient extends Model implements JWTSubject
{
    use Notifiable,HasFactory;

    //specify which model attributes can filled
    protected $fillable = [
        'user_id',
        'FullName',
        'PatientID',
        'Address',
        'Date',
        'AgeYearsOrMonths',
        'Age',
        'PhoneNumber',
        'Gender',
        'ChiefComplaint',
        'PertinentFindingsOnPhysicalExarn',
        'MedicalHistory',
        'Allergies',
        'Medication',
        'MedicationName',
        'EmergMedCareGiven',
        'PatientResponseToEmergMedCare',
        'Destination',
        'Reasons',
        'SiteOfInjury',
        'Time',
        'B_P',
        'PULSE',
        'RESP',
        'TEMP',
        'PulseO2',
        'ECG',
        'Death',
        'TimeOfDeath',
        'AuthorityNotified'
    ];

    //hide the following columns from the request
    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    //relationship between StatusUpdate and Patient -> means: patient form has many update forms
    public function statusUpdates()
    {
        return $this->hasMany(StatusUpdate::class, 'patient_id');
    }

    //relationship between user and Patient -> means: patient form belong to one user
    public function user()
    {
       return $this->belongsTo(User::class, 'user_id');
    }

    // JWT methods must exist:

    //return the unique identifier of the user here the id column
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    //additional information about the user that will be encoded into the token
    //ustom claims that I want to include in the JWT payload
    public function getJWTCustomClaims()
    {
        return [];
    }
}

