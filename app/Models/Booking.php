<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'doctor_id', 'slot_id', 'hospital_id',
        'booking_token', 'patient_name', 'patient_phone',
        'patient_age', 'patient_gender', 'reason',
        'status', 'admin_notes',
    ];

    // ✅ ഇത് add ചെയ്യുക
    protected $casts = [
        'booked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $b) {
            $b->booking_token = strtoupper(Str::random(8));
        });
    }

    public function user()     { return $this->belongsTo(User::class); }
    public function doctor()   { return $this->belongsTo(Doctor::class); }
    public function slot()     { return $this->belongsTo(DoctorSlot::class, 'slot_id'); }
    public function hospital() { return $this->belongsTo(Hospital::class); }

    public function scopeForHospital($q, $id) { return $q->where('hospital_id', $id); }
    public function scopeStatus($q, $status)  { return $q->where('status', $status); }
}