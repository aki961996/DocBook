<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

      protected $fillable = [
        'hospital_id', 'name', 'icon', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    // ── Relationships ──────────────────────────────────────────

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
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
}
