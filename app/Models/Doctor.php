<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'hospital_id', 'department_id', 'name', 'qualification',
        'specialization', 'experience_years', 'photo', 'phone',
        'email', 'consultation_fee', 'bio', 'is_active',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'consultation_fee'   => 'decimal:2',
        'experience_years'   => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function slots()
    {
        return $this->hasMany(DoctorSlot::class);
    }

    public function availableSlots()
    {
        return $this->hasMany(DoctorSlot::class)
                    ->where('is_booked', false)
                    ->where('is_blocked', false)
                    ->whereDate('slot_date', '>=', now()->toDateString());
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForHospital($query, $hospitalId)
    {
        return $query->where('hospital_id', $hospitalId);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/doctor-placeholder.png');
    }
}
