<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // This is a proxy class to Spatie's Permission model
    // It exists to fix relationship issues in the application
    
    // Override the relationship to PermissionCategory if needed
    public function category()
    {
        return $this->belongsTo(PermissionCategory::class, 'permission_category_id');
    }
}