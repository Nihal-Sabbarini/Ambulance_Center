<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class StatusUpdate extends Model implements JWTSubject
{
    use Notifiable,HasFactory;

    //specify which model attributes can filled
    protected $fillable = [
        'patient_id',
        'EmergMedCareGiven',
        'PatientResponseToEmergMedCare',
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

    //relationship between StatusUpdate and Patient -> means: Update form belong to one patient forms
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
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
