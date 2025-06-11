<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class FixUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-permissions {user_id} {tenant_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user permissions and ensure admin role is properly assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $tenantDomain = $this->argument('tenant_domain');
        
        try {
            // البحث عن المستأجر
            $tenant = Tenant::on('system')->where('domain', $tenantDomain)->first();
            
            if (!$tenant) {
                $this->error("❌ Tenant with domain '{$tenantDomain}' not found!");
                return 1;
            }
            
            $this->info("🏢 Found tenant: {$tenant->name} (Domain: {$tenant->domain})");
            
            // التبديل إلى قاعدة بيانات المستأجر
            TenantService::switchToTenant($tenant);
            
            // البحث عن المستخدم
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("❌ User with ID '{$userId}' not found!");
                return 1;
            }
            
            $this->info("👤 Found user: {$user->name} ({$user->email})");
            
            // تشغيل seeder للصلاحيات
            $this->info("📝 Creating permissions and roles...");
            try {
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\Tenants\\PermissionsSeeder',
                    '--force' => true
                ]);
            } catch (\Exception $e) {
                $this->warn("⚠️ Could not run seeder: " . $e->getMessage());
                $this->info("Creating permissions manually...");
            }
            
            // التأكد من وجود دور الأدمن
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);
            
            // إنشاء الصلاحيات المتقدمة إذا لم تكن موجودة
            $advancedPermissions = [
                'view advanced permissions',
                'manage advanced permissions',
                'grant permission overrides',
                'revoke permission overrides',
                'view permission reports',
                'manage permission groups',
            ];
            
            foreach ($advancedPermissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
            
            // منح جميع الصلاحيات للأدمن
            try {
                $adminRole->syncPermissions(Permission::all());
            } catch (\Exception $e) {
                $this->warn("⚠️ Could not sync all permissions: " . $e->getMessage());
                
                // منح الصلاحيات المتقدمة فقط
                foreach ($advancedPermissions as $permissionName) {
                    try {
                        $permission = Permission::where('name', $permissionName)->first();
                        if ($permission) {
                            $adminRole->givePermissionTo($permission);
                        }
                    } catch (\Exception $ex) {
                        $this->warn("⚠️ Could not assign permission {$permissionName}: " . $ex->getMessage());
                    }
                }
            }
            
            // منح المستخدم دور الأدمن
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->info("✅ Assigned admin role to user");
            } else {
                $this->info("ℹ️  User already has admin role");
            }
            
            // التحقق من الصلاحيات
            $this->info("\n📋 Current user roles:");
            foreach ($user->roles as $role) {
                $this->line("   • {$role->name}");
            }
            
            try {
                $this->info("\n🔑 User permissions count: " . $user->getAllPermissions()->count());
            } catch (\Exception $e) {
                $this->warn("⚠️ Could not count permissions: " . $e->getMessage());
            }
            
            // التحقق من الصلاحيات المتقدمة
            $this->info("\n🔍 Advanced permissions check:");
            foreach ($advancedPermissions as $permission) {
                try {
                    $hasPermission = $user->hasPermissionTo($permission);
                    $status = $hasPermission ? '✅' : '❌';
                    $this->line("   {$status} {$permission}");
                } catch (\Exception $e) {
                    $this->line("   ❓ {$permission} (could not check)");
                }
            }
            
            $this->newLine();
            $this->info("🎉 User permissions fixed successfully!");
            $this->info("🌐 User can now access:");
            $this->line("   • /admin/permissions");
            $this->line("   • /admin/permissions/users/{$userId}/manage");
            $this->line("   • /admin/permissions/groups");
            $this->line("   • /admin/permissions/report");
            
            // العودة إلى قاعدة البيانات الرئيسية
            TenantService::switchToDefault();
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            try {
                TenantService::switchToDefault();
            } catch (\Exception $switchError) {
                $this->error("❌ Failed to switch back to default database: " . $switchError->getMessage());
            }
            
            return 1;
        }
        
        return 0;
    }
}