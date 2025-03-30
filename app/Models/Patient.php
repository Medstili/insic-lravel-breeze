<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function appointments (){
        return $this->hasMany(Appointment::class);
    }
    public function speciality (){
        return $this->belongsTo(Speciality::class);
    }

    public function coaches()
    {
        return $this->belongsToMany(User::class, 'coach_patient')
                    ->withPivot('max_appointments','used_count','position')
                    ->orderBy('position')
                    ->withTimestamps();
    }
    public function suggestedAppointments()
    {
        return $this->hasMany(SuggestedAppointment::class);
    }
    

}
