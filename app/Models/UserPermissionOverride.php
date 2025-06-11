<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserPermissionOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_id',
        'type',
        'reason',
        'expires_at',
        'granted_by',
        'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }

    public function scopeGrants($query)
    {
        return $query->where('type', 'grant');
    }

    public function scopeDenies($query)
    {
        return $query->where('type', 'deny');
    }

    // Accessors
    public function getIsValidAttribute()
    {
        return $this->is_active && 
               (is_null($this->expires_at) || $this->expires_at > now());
    }

    public function getIsExpiredAttribute()
    {
        return !is_null($this->expires_at) && $this->expires_at <= now();
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return 'غير نشط';
        }
        
        if ($this->is_expired) {
            return 'منتهي الصلاحية';
        }
        
        return $this->type === 'grant' ? 'ممنوح' : 'مرفوض';
    }

    public function getStatusBadgeAttribute()
    {
        $class = match($this->status_text) {
            'ممنوح' => 'bg-green-100 text-green-800',
            'مرفوض' => 'bg-red-100 text-red-800',
            'منتهي الصلاحية' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
        
        return "<span class=\"inline-flex px-2 py-1 text-xs font-semibold rounded-full {$class}\">{$this->status_text}</span>";
    }
}