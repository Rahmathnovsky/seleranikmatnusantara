<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'title', 'slug', 'description', 'terms', 'image', 'banner_image',
        'start_date', 'end_date', 'max_claims', 'status', 'promo_type',
        'discount_value', 'discount_label', 'requires_login',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'requires_login' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) $model->slug = Str::slug($model->title);
        });
    }

    public function claims()
    {
        return $this->hasMany(PromoClaim::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') return false;
        $now = Carbon::today();
        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date && $now->gt($this->end_date)) return false;
        if ($this->max_claims && $this->claims()->count() >= $this->max_claims) return false;
        return true;
    }

    public function claimsCount(): int
    {
        return $this->claims()->count();
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-promo.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(fn($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', now()))
            ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', now()));
    }
}
