<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'name', 'phone', 'email', 'gender', 'dob', 'phone_verified_at',
    ];

    protected $hidden = ['remember_token'];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'dob'               => 'date',
    ];

    // ✅ No password column — disable Laravel's default password handling
    public function getAuthPassword()
    {
        return null;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}