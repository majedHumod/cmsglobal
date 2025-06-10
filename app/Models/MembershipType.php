<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MembershipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'features',
        'is_active',
        'is_protected',
        'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_protected' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // منع حذف العضويات المحمية
        static::deleting(function ($membershipType) {
            if ($membershipType->is_protected) {
                throw new \Exception('لا يمكن حذف هذا النوع من العضوية لأنه محمي من النظام.');
            }
        });

        // إنشاء slug تلقائياً
        static::creating(function ($membershipType) {
            if (empty($membershipType->slug)) {
                $membershipType->slug = Str::slug($membershipType->name);
                
                // التأكد من أن الـ slug فريد
                $originalSlug = $membershipType->slug;
                $counter = 1;
                while (static::where('slug', $membershipType->slug)->exists()) {
                    $membershipType->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    // العلاقات
    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function activeUserMemberships()
    {
        return $this->hasMany(UserMembership::class)->where('is_active', true)->where('expires_at', '>', now());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeNotProtected($query)
    {
        return $query->where('is_protected', false);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'مجاني';
        }
        return number_format($this->price, 2) . ' ريال';
    }

    public function getDurationTextAttribute()
    {
        if ($this->duration_days == 30) {
            return 'شهر واحد';
        } elseif ($this->duration_days == 365) {
            return 'سنة واحدة';
        } elseif ($this->duration_days == 7) {
            return 'أسبوع واحد';
        } else {
            return $this->duration_days . ' يوم';
        }
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_protected) {
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🔒 محمي</span>';
        }
        
        if ($this->is_active) {
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ نشط</span>';
        }
        
        return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">⏸️ غير نشط</span>';
    }

    // Methods
    public function canBeDeleted()
    {
        return !$this->is_protected && $this->userMemberships()->count() == 0;
    }

    public function canBeModified()
    {
        return !$this->is_protected;
    }

    public function getActiveSubscribersCount()
    {
        return $this->activeUserMemberships()->count();
    }
}