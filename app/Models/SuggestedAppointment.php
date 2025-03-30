<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuggestedAppointment extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'coach_id',
        'patient_id',
        'startTime', 
        'endTime',
        'Date',
        'priority',
        'Status',
        'speciality_id',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function coach()
    {
        return $this->belongsTo(User::class);
    }
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
    
    
}
