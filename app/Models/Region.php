<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'parent_id', 'sort_order'];

    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id')->orderBy('sort_order');
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function scopeProvinces($query)
    {
        return $query->where('type', 'province');
    }

    public function scopeCities($query)
    {
        return $query->where('type', 'city');
    }
}
