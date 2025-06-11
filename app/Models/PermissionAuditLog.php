<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'action',
        'permission_name',
        'old_values',
        'new_values',
        'user_id',
        'ip_address',
        'user_agent',
        'reason'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // العلاقات
    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('auditable_type', User::class)
                    ->where('auditable_id', $userId);
    }

    public function scopeForRole($query, $roleId)
    {
        return $query->where('auditable_type', Role::class)
                    ->where('auditable_id', $roleId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getActionTextAttribute()
    {
        return match($this->action) {
            'granted' => 'تم منح',
            'revoked' => 'تم سحب',
            'updated' => 'تم تحديث',
            'expired' => 'انتهت صلاحية',
            default => $this->action
        };
    }

    public function getAuditableTypeTextAttribute()
    {
        return match($this->auditable_type) {
            User::class => 'مستخدم',
            Role::class => 'دور',
            default => 'غير محدد'
        };
    }

    // Methods
    public static function logPermissionChange($auditable, $action, $permissionName, $oldValues = null, $newValues = null, $reason = null)
    {
        return static::create([
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id,
            'action' => $action,
            'permission_name' => $permissionName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => $reason
        ]);
    }
}