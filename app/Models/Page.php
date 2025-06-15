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
        'access_level',
        'is_premium',
        'access_roles',
        'menu_order',
        'user_id',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'is_premium' => 'boolean',
        'published_at' => 'datetime',
        'access_roles' => 'array',
        'required_membership_types' => 'array',
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

    // Scope للصفحات التي يمكن للمستخدم الوصول إليها
    public function scopeAccessibleBy($query, $user = null)
    {
        if (!$user) {
            return $query->where('access_level', 'public');
        }

        return $query->where(function ($q) use ($user) {
            $q->where('access_level', 'public')
              ->orWhere(function ($subQ) use ($user) {
                  $subQ->where('access_level', 'authenticated');
              })
              ->orWhere(function ($subQ) use ($user) {
                  // التحقق من الأدوار المحددة
                  if ($user->hasRole('admin')) {
                      $subQ->where('access_level', 'admin');
                  }
                  if ($user->hasRole('user')) {
                      $subQ->orWhere('access_level', 'user');
                  }
                  if ($user->hasRole('page_manager')) {
                      $subQ->orWhere('access_level', 'page_manager');
                  }
              })
              ->orWhere(function ($subQ) use ($user) {
                  // التحقق من الأدوار المخصصة في access_roles
                  $subQ->whereNotNull('access_roles');
                  foreach ($user->roles as $role) {
                      $subQ->orWhereJsonContains('access_roles', $role->name);
                  }
              });
        });
    }

    // التحقق من إمكانية وصول المستخدم للصفحة
    public function canAccess($user = null)
    {
        // الصفحات العامة متاحة للجميع
        if ($this->access_level === 'public') {
            return true;
        }

        // إذا لم يكن هناك مستخدم مسجل دخول
        if (!$user) {
            return false;
        }

        // الصفحات للمستخدمين المسجلين
        if ($this->access_level === 'authenticated') {
            // التحقق من العضويات المطلوبة
            if ($this->required_membership_types && is_array($this->required_membership_types) && count($this->required_membership_types) > 0) {
                // هنا يمكن إضافة التحقق من عضوية المستخدم
                // لكن سنتجاهل هذا الآن حتى يتم تنفيذ نظام العضويات بالكامل
            }
            return true;
        }

       // التحقق من العضويات المطلوبة
       if ($this->access_level === 'membership' && $user) {
           if ($user->hasRole('admin')) {
               return true; // المدراء يمكنهم الوصول لجميع الصفحات
           }

           // التحقق من وجود عضويات مطلوبة
           if (!$this->required_membership_types || (is_array($this->required_membership_types) && empty($this->required_membership_types))) {
               return false;
           }

           // التحقق من امتلاك المستخدم لأي من العضويات المطلوبة
           try {
               $membershipTypeIds = is_array($this->required_membership_types) ? $this->required_membership_types : json_decode($this->required_membership_types, true);
               
               if (empty($membershipTypeIds)) {
                   return false;
               }
               
               $userMemberships = \App\Models\UserMembership::where('user_id', $user->id)
                   ->where('is_active', true)
                   ->where('expires_at', '>', now())
                    ->whereIn('membership_type_id', $membershipTypeIds)
                   ->exists();
               
               return $userMemberships;
           } catch (\Exception $e) {
               \Log::error('Error checking user memberships: ' . $e->getMessage());
               return false;
           }
       }

        // التحقق من الأدوار المحددة
        if ($this->access_level === 'admin' && $user->hasRole('admin')) {
            return true;
        }

        if ($this->access_level === 'user' && $user->hasRole('user')) {
            return true;
        }

        if ($this->access_level === 'page_manager' && $user->hasRole('page_manager')) {
            return true;
        }

        // التحقق من الأدوار المخصصة
        if ($this->access_roles && is_array($this->access_roles)) {
            foreach ($this->access_roles as $role) {
                if ($user->hasRole($role)) {
                    return true;
                }
            }
        }
        
        // التحقق من العضويات المطلوبة
        if ($this->required_membership_types && is_array($this->required_membership_types) && count($this->required_membership_types) > 0) {
            // هنا يمكن إضافة التحقق من عضوية المستخدم
            // لكن سنتجاهل هذا الآن حتى يتم تنفيذ نظام العضويات بالكامل
        }

        return false;
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

    // Accessor للحصول على نص مستوى الوصول
    public function getAccessLevelTextAttribute()
    {
        $levels = [
            'public' => 'عام للجميع',
            'authenticated' => 'المستخدمين المسجلين',
            'admin' => 'المديرين فقط',
            'user' => 'المستخدمين العاديين',
            'page_manager' => 'مديري الصفحات',
           'membership' => 'أعضاء العضويات المدفوعة',
        ];

        return $levels[$this->access_level] ?? $this->access_level;
    }

    // Accessor للحصول على أيقونة مستوى الوصول
    public function getAccessLevelIconAttribute()
    {
        $icons = [
            'public' => '🌍',
            'authenticated' => '🔐',
            'admin' => '👑',
            'user' => '👤',
            'page_manager' => '📝',
           'membership' => '💎',
        ];

        return $icons[$this->access_level] ?? '🔒';
    }
}