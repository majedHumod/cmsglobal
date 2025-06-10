<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'featured_image',
        'is_published',
        'show_in_menu',
        'menu_order',
        'user_id',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope للصفحات المنشورة
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope للصفحات التي تظهر في القائمة
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->orderBy('menu_order');
    }

    // Accessor للحصول على URL الصفحة
    public function getUrlAttribute()
    {
        return route('pages.show', $this->slug);
    }

    // Accessor للحصول على عنوان SEO
    public function getSeoTitleAttribute()
    {
        return $this->meta_title ?: $this->title;
    }

    // Accessor للحصول على وصف SEO
    public function getSeoDescriptionAttribute()
    {
        return $this->meta_description ?: $this->excerpt;
    }
}