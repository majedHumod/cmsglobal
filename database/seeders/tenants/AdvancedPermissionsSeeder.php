<?php

namespace Database\Seeders\Tenants;

use Illuminate\Database\Seeder;
use App\Models\PermissionGroup;
use App\Models\PermissionCategory;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdvancedPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مجموعات الصلاحيات
        $groups = [
            [
                'name' => 'إدارة المحتوى',
                'slug' => 'content-management',
                'description' => 'صلاحيات إدارة المحتوى والصفحات والمقالات',
                'icon' => 'document-text',
                'color' => '#3b82f6',
                'sort_order' => 1
            ],
            [
                'name' => 'إدارة المستخدمين',
                'slug' => 'user-management',
                'description' => 'صلاحيات إدارة المستخدمين والأدوار والصلاحيات',
                'icon' => 'users',
                'color' => '#10b981',
                'sort_order' => 2
            ],
            [
                'name' => 'إدارة النظام',
                'slug' => 'system-management',
                'description' => 'صلاحيات إدارة النظام والإعدادات العامة',
                'icon' => 'cog',
                'color' => '#f59e0b',
                'sort_order' => 3
            ],
            [
                'name' => 'التقارير والتحليلات',
                'slug' => 'reports-analytics',
                'description' => 'صلاحيات عرض التقارير والإحصائيات',
                'icon' => 'chart-bar',
                'color' => '#8b5cf6',
                'sort_order' => 4
            ]
        ];

        foreach ($groups as $groupData) {
            $group = PermissionGroup::create($groupData);

            // إنشاء تصنيفات لكل مجموعة
            $this->createCategoriesForGroup($group);
        }

        // تحديث الصلاحيات الموجودة
        $this->updateExistingPermissions();

        // إنشاء صلاحيات جديدة
        $this->createNewPermissions();

        // تحديث الأدوار
        $this->updateRoles();
    }

    private function createCategoriesForGroup(PermissionGroup $group)
    {
        $categories = [];

        switch ($group->slug) {
            case 'content-management':
                $categories = [
                    ['name' => 'الصفحات', 'slug' => 'pages'],
                    ['name' => 'المقالات', 'slug' => 'articles'],
                    ['name' => 'الملاحظات', 'slug' => 'notes'],
                    ['name' => 'الجداول الغذائية', 'slug' => 'meal-plans'],
                    ['name' => 'الوسائط', 'slug' => 'media']
                ];
                break;

            case 'user-management':
                $categories = [
                    ['name' => 'المستخدمين', 'slug' => 'users'],
                    ['name' => 'الأدوار', 'slug' => 'roles'],
                    ['name' => 'الصلاحيات', 'slug' => 'permissions'],
                    ['name' => 'العضويات', 'slug' => 'memberships']
                ];
                break;

            case 'system-management':
                $categories = [
                    ['name' => 'الإعدادات العامة', 'slug' => 'settings'],
                    ['name' => 'النسخ الاحتياطي', 'slug' => 'backups'],
                    ['name' => 'السجلات', 'slug' => 'logs'],
                    ['name' => 'الصيانة', 'slug' => 'maintenance']
                ];
                break;

            case 'reports-analytics':
                $categories = [
                    ['name' => 'تقارير المستخدمين', 'slug' => 'user-reports'],
                    ['name' => 'تقارير المحتوى', 'slug' => 'content-reports'],
                    ['name' => 'الإحصائيات', 'slug' => 'statistics'],
                    ['name' => 'التحليلات', 'slug' => 'analytics']
                ];
                break;
        }

        foreach ($categories as $index => $categoryData) {
            PermissionCategory::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'description' => "تصنيف {$categoryData['name']} في مجموعة {$group->name}",
                'permission_group_id' => $group->id,
                'sort_order' => $index + 1,
                'is_active' => true
            ]);
        }
    }

    private function updateExistingPermissions()
    {
        // تحديث الصلاحيات الموجودة بالتصنيفات
        $permissionMappings = [
            // صلاحيات الصفحات
            'create pages' => 'pages',
            'edit pages' => 'pages',
            'delete pages' => 'pages',
            'view pages' => 'pages',
            'publish pages' => 'pages',

            // صلاحيات المقالات
            'create posts' => 'articles',
            'edit posts' => 'articles',
            'delete posts' => 'articles',

            // صلاحيات المستخدمين
            'manage users' => 'users',
            'manage roles' => 'roles',
            'manage permissions' => 'permissions',

            // صلاحيات العضويات
            'manage membership-types' => 'memberships',
            'create membership-types' => 'memberships',
            'edit membership-types' => 'memberships',
            'delete membership-types' => 'memberships',
            'view membership-types' => 'memberships'
        ];

        foreach ($permissionMappings as $permissionName => $categorySlug) {
            $permission = Permission::where('name', $permissionName)->first();
            $category = PermissionCategory::where('slug', $categorySlug)->first();

            if ($permission && $category) {
                $permission->update([
                    'permission_category_id' => $category->id,
                    'description' => "صلاحية {$permissionName}",
                    'level' => $this->getPermissionLevel($permissionName),
                    'is_active' => true,
                    'sort_order' => $this->getPermissionSortOrder($permissionName)
                ]);
            }
        }
    }

    private function createNewPermissions()
    {
        $newPermissions = [
            // صلاحيات الملاحظات
            ['name' => 'create notes', 'category' => 'notes', 'level' => 'basic'],
            ['name' => 'edit notes', 'category' => 'notes', 'level' => 'basic'],
            ['name' => 'delete notes', 'category' => 'notes', 'level' => 'intermediate'],
            ['name' => 'view notes', 'category' => 'notes', 'level' => 'basic'],

            // صلاحيات الجداول الغذائية
            ['name' => 'create meal-plans', 'category' => 'meal-plans', 'level' => 'basic'],
            ['name' => 'edit meal-plans', 'category' => 'meal-plans', 'level' => 'basic'],
            ['name' => 'delete meal-plans', 'category' => 'meal-plans', 'level' => 'intermediate'],
            ['name' => 'view meal-plans', 'category' => 'meal-plans', 'level' => 'basic'],
            ['name' => 'publish meal-plans', 'category' => 'meal-plans', 'level' => 'intermediate'],

            // صلاحيات الوسائط
            ['name' => 'upload media', 'category' => 'media', 'level' => 'basic'],
            ['name' => 'delete media', 'category' => 'media', 'level' => 'intermediate'],
            ['name' => 'manage media library', 'category' => 'media', 'level' => 'advanced'],

            // صلاحيات النظام
            ['name' => 'view system settings', 'category' => 'settings', 'level' => 'advanced'],
            ['name' => 'edit system settings', 'category' => 'settings', 'level' => 'critical'],
            ['name' => 'view logs', 'category' => 'logs', 'level' => 'advanced'],
            ['name' => 'clear logs', 'category' => 'logs', 'level' => 'critical'],
            ['name' => 'create backups', 'category' => 'backups', 'level' => 'advanced'],
            ['name' => 'restore backups', 'category' => 'backups', 'level' => 'critical'],

            // صلاحيات التقارير
            ['name' => 'view user reports', 'category' => 'user-reports', 'level' => 'intermediate'],
            ['name' => 'view content reports', 'category' => 'content-reports', 'level' => 'intermediate'],
            ['name' => 'view statistics', 'category' => 'statistics', 'level' => 'basic'],
            ['name' => 'view analytics', 'category' => 'analytics', 'level' => 'intermediate'],
            ['name' => 'export reports', 'category' => 'analytics', 'level' => 'advanced']
        ];

        foreach ($newPermissions as $permData) {
            $category = PermissionCategory::where('slug', $permData['category'])->first();
            
            if ($category) {
                Permission::firstOrCreate(
                    ['name' => $permData['name']],
                    [
                        'guard_name' => 'web',
                        'permission_category_id' => $category->id,
                        'description' => "صلاحية {$permData['name']}",
                        'level' => $permData['level'],
                        'is_active' => true,
                        'sort_order' => 0
                    ]
                );
            }
        }
    }

    private function updateRoles()
    {
        // تحديث دور الأدمن
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->update([
                'description' => 'مدير النظام مع صلاحيات كاملة',
                'level' => 'critical',
                'color' => '#dc2626',
                'icon' => 'shield-check',
                'is_system' => true,
                'is_assignable' => true,
                'sort_order' => 1,
                'is_active' => true
            ]);

            // منح جميع الصلاحيات للأدمن
            $adminRole->syncPermissions(Permission::all());
        }

        // تحديث دور المستخدم
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->update([
                'description' => 'مستخدم عادي مع صلاحيات أساسية',
                'level' => 'basic',
                'color' => '#3b82f6',
                'icon' => 'user',
                'is_system' => true,
                'is_assignable' => true,
                'sort_order' => 3,
                'is_active' => true
            ]);

            // منح صلاحيات أساسية للمستخدم
            $basicPermissions = Permission::whereIn('name', [
                'view notes', 'create notes', 'edit notes',
                'view meal-plans', 'create meal-plans', 'edit meal-plans',
                'view pages', 'view statistics'
            ])->get();
            
            $userRole->syncPermissions($basicPermissions);
        }

        // إنشاء دور مدير الصفحات
        $pageManagerRole = Role::firstOrCreate(
            ['name' => 'page_manager'],
            [
                'guard_name' => 'web',
                'description' => 'مدير الصفحات والمحتوى',
                'level' => 'intermediate',
                'color' => '#10b981',
                'icon' => 'document-text',
                'is_system' => false,
                'is_assignable' => true,
                'sort_order' => 2,
                'is_active' => true
            ]
        );

        // منح صلاحيات إدارة المحتوى لمدير الصفحات
        $contentPermissions = Permission::whereHas('category', function ($query) {
            $query->whereHas('group', function ($subQuery) {
                $subQuery->where('slug', 'content-management');
            });
        })->where('level', '!=', 'critical')->get();
        
        $pageManagerRole->syncPermissions($contentPermissions);
    }

    private function getPermissionLevel(string $permissionName): string
    {
        $criticalPermissions = ['delete', 'manage users', 'manage roles', 'manage permissions'];
        $advancedPermissions = ['publish', 'manage'];
        $intermediatePermissions = ['edit', 'update'];

        foreach ($criticalPermissions as $critical) {
            if (str_contains($permissionName, $critical)) {
                return 'critical';
            }
        }

        foreach ($advancedPermissions as $advanced) {
            if (str_contains($permissionName, $advanced)) {
                return 'advanced';
            }
        }

        foreach ($intermediatePermissions as $intermediate) {
            if (str_contains($permissionName, $intermediate)) {
                return 'intermediate';
            }
        }

        return 'basic';
    }

    private function getPermissionSortOrder(string $permissionName): int
    {
        $order = ['view' => 1, 'create' => 2, 'edit' => 3, 'delete' => 4, 'manage' => 5, 'publish' => 6];

        foreach ($order as $action => $sortOrder) {
            if (str_contains($permissionName, $action)) {
                return $sortOrder;
            }
        }

        return 0;
    }
}