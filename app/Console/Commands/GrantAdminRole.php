<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GrantAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:grant-admin {user_id} {tenant_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin role to a specific user in a specific tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $tenantDomain = $this->argument('tenant_domain');
        
        try {
            // البحث عن المستأجر في قاعدة البيانات الرئيسية
            $tenant = Tenant::on('system')->where('domain', $tenantDomain)->first();
            
            if (!$tenant) {
                $this->error("❌ Tenant with domain '{$tenantDomain}' not found!");
                $this->info("💡 Available tenants:");
                $tenants = Tenant::on('system')->get(['domain', 'name']);
                foreach ($tenants as $t) {
                    $this->line("   - {$t->domain} ({$t->name})");
                }
                return 1;
            }
            
            $this->info("🏢 Found tenant: {$tenant->name} (Domain: {$tenant->domain})");
            
            // التبديل إلى قاعدة بيانات المستأجر
            TenantService::switchToTenant($tenant);
            
            $this->info("🔄 Switched to tenant database: {$tenant->db_name}");
            
            // البحث عن المستخدم في قاعدة بيانات المستأجر
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("❌ User with ID '{$userId}' not found in tenant '{$tenantDomain}'!");
                return 1;
            }
            
            $this->info("🔍 Found user: {$user->name} (ID: {$user->id})");
            $this->info("📧 Email: {$user->email}");
            
            // إنشاء دور الأدمن إذا لم يكن موجوداً
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);
            
            $this->info("👑 Created/found 'admin' role");
            
            // إنشاء الصلاحيات الأساسية للأدمن
            $permissions = [
                'create posts',
                'edit posts', 
                'delete posts',
                'view posts',
                'manage users',
                'manage roles',
                'manage permissions',
                'manage pages',
                'create pages',
                'edit pages',
                'delete pages',
                'view pages',
                'publish pages',
                'manage membership-types',
                'create membership-types',
                'edit membership-types',
                'delete membership-types',
                'view membership-types'
            ];
            
            $this->info("📝 Creating/checking admin permissions...");
            
            $createdPermissions = [];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                $createdPermissions[] = $permission;
                $this->line("   ✓ {$permissionName}");
            }
            
            // ربط الصلاحيات بدور الأدمن
            $adminRole->syncPermissions($createdPermissions);
            $this->info("🔗 Synced permissions with admin role");
            
            // منح المستخدم دور الأدمن
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->info("✅ Assigned 'admin' role to user");
            } else {
                $this->info("ℹ️  User already has 'admin' role");
            }
            
            // التحقق من الصلاحيات الحالية للمستخدم
            $this->info("\n📋 Current user roles:");
            foreach ($user->roles as $role) {
                $this->line("   • {$role->name}");
            }
            
            $this->info("\n🔑 Current user permissions:");
            $userPermissions = $user->getAllPermissions();
            if ($userPermissions->count() > 10) {
                $this->line("   • Total permissions: " . $userPermissions->count());
                $this->line("   • Including: " . $userPermissions->take(5)->pluck('name')->join(', ') . '...');
            } else {
                foreach ($userPermissions as $permission) {
                    $this->line("   • {$permission->name}");
                }
            }
            
            $this->newLine();
            $this->info("🎉 Successfully granted admin role to user {$user->name}!");
            $this->info("👑 The user now has full administrative access to:");
            $this->line("   • User management");
            $this->line("   • Content management (Pages, Articles, Notes, Meal Plans)");
            $this->line("   • Membership types management");
            $this->line("   • System settings");
            
            // العودة إلى قاعدة البيانات الرئيسية
            TenantService::switchToDefault();
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // التأكد من العودة إلى قاعدة البيانات الرئيسية في حالة الخطأ
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