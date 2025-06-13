<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'header_image',
        'header_text_color',
        'show_join_button',
        'join_button_text',
        'join_button_url',
        'join_button_color',
        'content',
        'meta_title',
        'meta_description',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'show_join_button' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active landing page
     *
     * @return LandingPage|null
     */
    public static function getActive()
    {
        return Cache::remember('active_landing_page', 3600, function () {
            return self::where('is_active', true)->latest()->first();
        });
    }

    /**
     * Clear the landing page cache
     */
    public static function clearCache()
    {
        Cache::forget('active_landing_page');
    }

    /**
     * Get the user that created the landing page
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}