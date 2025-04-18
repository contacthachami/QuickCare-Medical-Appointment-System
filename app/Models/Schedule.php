<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = ['start', 'end', 'status', 'day', 'doctor_id', 'specific_date'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function Appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
