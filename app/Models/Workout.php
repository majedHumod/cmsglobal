<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'difficulty',
        'video_url',
        'status',
        'user_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'duration' => 'integer',
    ];

    /**
     * العلاقة مع المستخدم (المدرب)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع جداول التمارين
     */
    public function schedules()
    {
        return $this->hasMany(WorkoutSchedule::class);
    }

    /**
     * Scope للتمارين النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope للتمارين حسب المستوى
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope للتمارين الخاصة بالمستخدم
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * الحصول على اسم مستوى الصعوبة بالعربية
     */
    public function getDifficultyNameAttribute()
    {
        $difficulties = [
            'easy' => 'سهل',
            'medium' => 'متوسط',
            'hard' => 'صعب'
        ];

        return $difficulties[$this->difficulty] ?? $this->difficulty;
    }

    /**
     * الحصول على حالة التمرين بالعربية
     */
    public function getStatusNameAttribute()
    {
        return $this->status ? 'نشط' : 'غير نشط';
    }

    /**
     * الحصول على لون مستوى الصعوبة
     */
    public function getDifficultyColorAttribute()
    {
        $colors = [
            'easy' => 'green',
            'medium' => 'yellow',
            'hard' => 'red'
        ];

        return $colors[$this->difficulty] ?? 'gray';
    }

    /**
     * التحقق من إمكانية التعديل
     */
    public function canEdit($user)
    {
        return $user->hasRole('admin') || $this->user_id === $user->id;
    }

    /**
     * التحقق من إمكانية الحذف
     */
    public function canDelete($user)
    {
        return $user->hasRole('admin') || $this->user_id === $user->id;
    }
}