<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_job_id', 'full_name', 'email', 'phone', 'address',
        'cv_file', 'cover_letter', 'portfolio_url', 'date_of_birth',
        'last_education', 'major', 'university', 'gpa',
        'work_experience_years', 'status', 'hr_notes', 'applied_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'applied_at' => 'datetime',
        'gpa' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->applied_at)) {
                $model->applied_at = now();
            }
            if (empty($model->status)) {
                $model->status = 'new';
            }
        });
    }

    public function job()
    {
        return $this->belongsTo(CareerJob::class, 'career_job_id');
    }

    public function getCvUrlAttribute(): string
    {
        return asset('storage/' . $this->cv_file);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'info',
            'reviewed' => 'warning',
            'shortlisted' => 'primary',
            'interview' => 'accent',
            'offered' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'new' => 'Baru',
            'reviewed' => 'Ditinjau',
            'shortlisted' => 'Shortlist',
            'interview' => 'Interview',
            'offered' => 'Ditawari',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }
}
