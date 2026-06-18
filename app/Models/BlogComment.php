<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id', 'user_id', 'parent_id', 'guest_name',
        'guest_email', 'body', 'is_approved', 'is_admin_reply',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_admin_reply' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('is_approved', true)->orderBy('created_at');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getAuthorNameAttribute(): string
    {
        if ($this->user) return $this->user->name;
        return $this->guest_name ?? 'Anonymous';
    }

    public function getAuthorAvatarAttribute(): string
    {
        if ($this->is_admin_reply) {
            return 'https://ui-avatars.com/api/?name=SNN+Admin&background=634524&color=fff&size=60';
        }
        if ($this->user) {
            return $this->user->avatar_url;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->guest_name ?? 'Guest') . '&background=c5a059&color=fff&size=60';
    }
}
