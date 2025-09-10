<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'meal_type',
        'calories',
        'protein',
        'carbs',
        'fats',
        'ingredients',
        'instructions',
        'image',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'calories' => 'integer',
        'protein' => 'integer',
        'carbs' => 'integer',
        'fats' => 'integer',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMealTypeNameAttribute()
    {
        $types = [
            'breakfast' => 'إفطار',
            'lunch' => 'غداء',
            'dinner' => 'عشاء',
            'snack' => 'وجبة خفيفة'
        ];

        return $types[$this->meal_type] ?? $this->meal_type;
    }

    public function getDifficultyNameAttribute()
    {
        $difficulties = [
            'easy' => 'سهل',
            'medium' => 'متوسط',
            'hard' => 'صعب'
        ];

        return $difficulties[$this->difficulty] ?? $this->difficulty;
    }

    public function getTotalTimeAttribute()
    {
        return ($this->prep_time ?? 0) + ($this->cook_time ?? 0);
    }

    /**
     * الحصول على إجمالي المغذيات الكبرى
     */
    public function getTotalMacrosAttribute()
    {
        return ($this->protein ?? 0) + ($this->carbs ?? 0) + ($this->fats ?? 0);
    }

    /**
     * الحصول على توزيع المغذيات كنسب مئوية
     */
    public function getMacroPercentagesAttribute()
    {
        $total = $this->total_macros;
        
        if ($total == 0) {
            return ['protein' => 0, 'carbs' => 0, 'fats' => 0];
        }
        
        return [
            'protein' => round((($this->protein ?? 0) / $total) * 100, 1),
            'carbs' => round((($this->carbs ?? 0) / $total) * 100, 1),
            'fats' => round((($this->fats ?? 0) / $total) * 100, 1),
        ];
    }
}