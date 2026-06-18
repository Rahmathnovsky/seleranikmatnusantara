<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CareerJob extends Model
{
    use HasFactory;

    protected $table = 'career_jobs';

    protected $fillable = [
        'job_category_id', 'brand_id', 'title', 'slug', 'description',
        'requirements', 'benefits', 'location', 'salary_range', 'type',
        'status', 'deadline', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) $model->slug = Str::slug($model->title);
        });
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where(fn($q) => $q->whereNull('deadline')->orWhere('deadline', '>=', now()));
    }

    public function isOpen(): bool
    {
        if ($this->status !== 'open') return false;
        return !$this->deadline || $this->deadline->isFuture() || $this->deadline->isToday();
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'fulltime' => 'primary',
            'parttime' => 'accent',
            'internship' => 'info',
            'contract' => 'warning',
            default => 'secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'fulltime' => 'Full Time',
            'parttime' => 'Part Time',
            'internship' => 'Internship',
            'contract' => 'Contract',
            default => $this->type,
        };
    }
}
