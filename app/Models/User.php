<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable,HasFactory;

    //specify which model attributes can filled
    protected $fillable = [
        'Name',
        'Email',
        'PersonalID',
        'Password',
        'DateOfBirth',
        'Type',
        'inService',
    ];

    //hide the following columns from the request
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    //Save the encrypted password in the database
    public function setPasswordAttribute($value)
    {
    $this->attributes['Password'] = encrypt($value);
    }

    //return the origin password form the encoded one
    public function getOriginalPassword()
    {
        return Crypt::decrypt($this->attributes['Password']);
    }

    //relationship between User and Patient -> means: One user can have many patient forms
    public function patients()
    {
        return $this->hasMany(Patient::class, 'user_id');
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
        return [
            'Name' => $this->Name,
            'Email' => $this->Email,
            'Type' => $this->Type,
            'inService' => $this->inService,
        ];
    }
}
