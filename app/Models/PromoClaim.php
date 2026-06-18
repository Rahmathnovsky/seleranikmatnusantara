<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PromoClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'promo_id', 'user_id', 'claim_code', 'claimed_at',
        'used_at', 'used_at_outlet_id', 'status',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->claim_code)) {
                $model->claim_code = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
            }
            $model->claimed_at = now();
        });
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'used_at_outlet_id');
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') return true;
        return $this->promo && $this->promo->end_date && $this->promo->end_date->isPast();
    }

    public function getQrDataAttribute(): string
    {
        return json_encode([
            'code' => $this->claim_code,
            'promo' => $this->promo?->title,
            'user' => $this->user?->name,
            'claimed_at' => $this->claimed_at?->toISOString(),
        ]);
    }
}
