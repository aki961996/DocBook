<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasUuids;

    protected $table = 'admin_users';

    protected $fillable = ['name', 'email', 'password', 'role', 'hospital_id'];

    protected $hidden = ['password', 'remember_token'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isHospitalAdmin(): bool
    {
        return $this->role === 'hospital_admin';
    }
}