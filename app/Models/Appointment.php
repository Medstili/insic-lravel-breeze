<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    
    public function coach(){
        return $this->belongsTo(User::class,'coach_id');
    }


    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function speciality(){
        return $this->belongsTo(Speciality::class);
}
}
