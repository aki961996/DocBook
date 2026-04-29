<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DoctorSlot extends Model
{
    use HasUuids;

    protected $fillable = [
        'doctor_id', 'slot_date', 'start_time', 'end_time',
        'max_patients', 'is_booked', 'is_blocked',
    ];

   protected $casts = [
    'slot_date'  => 'date',      
    'is_booked'  => 'boolean',
    'is_blocked' => 'boolean',
];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'slot_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_booked', false)
                     ->where('is_blocked', false)
                     ->whereDate('slot_date', '>=', now()->toDateString());
    }
}

