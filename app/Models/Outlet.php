<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'region_id', 'name', 'address', 'phone', 'whatsapp',
        'gmaps_url', 'latitude', 'longitude', 'photo', 'operational_hours',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'operational_hours' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function promoClaims()
    {
        return $this->hasMany(PromoClaim::class, 'used_at_outlet_id');
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-outlet.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getTodayHoursAttribute(): ?string
    {
        if (!$this->operational_hours) return null;
        $day = strtolower(now()->format('D'));
        return $this->operational_hours[$day] ?? $this->operational_hours['default'] ?? null;
    }
}
