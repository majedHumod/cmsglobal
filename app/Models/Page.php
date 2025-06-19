<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي.
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'featured_image',
        'access_level',
        'is_published',
        'is_premium',
        'show_in_menu',
        'menu_order',
        'published_at',
        'user_id',
        'required_membership_types',
    ];

    /**
     * تحويل الحقول إلى أنواع معينة تلقائيًا.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_premium' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * فك ترميز حقل required_membership_types تلقائيًا عند القراءة.
     */
    public function getRequiredMembershipTypesAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    /**
     * ترميز حقل required_membership_types إلى JSON عند الحفظ.
     */
    public function setRequiredMembershipTypesAttribute($value)
    {
        $this->attributes['required_membership_types'] = json_encode($value ?: []);
    }

    /**
     * العلاقة مع المستخدم (مالك الصفحة).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope لاسترجاع الصفحات المنشورة فقط.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope لاسترجاع الصفحات التي تظهر في القائمة.
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    /**
     * Scope للتحقق من إمكانية الوصول حسب المستخدم.
     */
    public function scopeAccessibleBy($query, $user = null)
    {
        if (!$user) {
            // المستخدم غير مسجل الدخول يمكنه رؤية الصفحات العامة فقط
            return $query->where('access_level', 'public');
        }

        if ($user->hasRole('admin')) {
            return $query; // كل الصفحات
        }

        // أمثلة على التحقق من الصلاحيات
        return $query->where(function ($q) use ($user) {
            $q->where('access_level', 'public')
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('access_level', 'authenticated')
                     ->whereNotNull($user->id);
              })
              ->orWhere(function ($q3) use ($user) {
                  $q3->where('access_level', 'membership')
                     ->whereJsonContains('required_membership_types', $user->membership_type_id);
              });
        });
    }

    /**
     * تحقق ما إذا كان المستخدم يمكنه الوصول إلى الصفحة.
     */
    public function canAccess($user = null)
    {
        if ($this->access_level === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if ($this->access_level === 'authenticated' && $user) {
            return true;
        }

        if ($this->access_level === 'membership') {
            return in_array($user->membership_type_id, $this->required_membership_types);
        }

        // أضف تحقق إضافي حسب احتياجاتك

        return false;
    }

    /**
     * الحصول على أيقونة مستوى الوصول للعرض في القائمة
     */
    public function getAccessLevelIconAttribute()
    {
        return match($this->access_level) {
            'public' => '🌍',
            'authenticated' => '🔐',
            'user' => '👤',
            'page_manager' => '📝',
            'admin' => '👑',
            'membership' => '💎',
            default => '📄'
        };
    }

    /**
     * الحصول على نص مستوى الوصول للعرض
     */
    public function getAccessLevelTextAttribute()
    {
        return match($this->access_level) {
            'public' => 'عام للجميع',
            'authenticated' => 'المستخدمين المسجلين',
            'user' => 'المستخدمين العاديين',
            'page_manager' => 'مديري الصفحات',
            'admin' => 'المديرين فقط',
            'membership' => 'أعضاء العضويات المدفوعة',
            default => 'غير محدد'
        };
    }
}
