<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Hospital extends Model
{
     use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'slug', 'address', 'city', 'state',
        'pincode', 'phone', 'email', 'logo', 'description', 'is_active',
    ];
     protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function booted(): void
    {
        static::creating(function (Hospital $hospital) {
            if (empty($hospital->slug)) {
                $hospital->slug = Str::slug($hospital->name) . '-' . Str::random(4);
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function adminUsers()
    {
        return $this->hasMany(AdminUser::class);
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
