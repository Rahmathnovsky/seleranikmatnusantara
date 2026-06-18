<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo', 'cover_image', 'description', 'tagline',
        'cuisine_type', 'color_primary', 'website_url', 'instagram_url',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) $model->slug = Str::slug($model->name);
        });
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function careerJobs()
    {
        return $this->hasMany(CareerJob::class);
    }

    public function activeOutlets()
    {
        return $this->hasMany(Outlet::class)->where('is_active', true);
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-brand.png');
    }

    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : asset('images/default-cover.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
