<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model  
{
    
    protected $fillable = [
        'name',
    ];

    public function coachs()
    {
        return $this->hasMany(User::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
    public function patients(){
        return $this->hasMany(Patient::class);
    }
}
