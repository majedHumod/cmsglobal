<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class QuickAdminGrant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick:admin {user_id=5} {tenant_domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick command to grant admin role to user ID 5 (or specified user)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $tenantDomain = $this->argument('tenant_domain');
        
        try {
            // إذا لم يتم تحديد المستأجر، اعرض القائمة
            if (!$tenantDomain) {
                $this->info("🏢 Available tenants:");
                $tenants = Tenant::on('system')->get(['domain', 'name']);
                
                if ($tenants->isEmpty()) {
                    $this->error("❌ No tenants found!");
                    return 1;
                }
                
                foreach ($tenants as $tenant) {
                    $this->line("   - {$tenant->domain} ({$tenant->name})");
                }
                
                $tenantDomain = $this->ask('Enter tenant domain');
                
                if (!$tenantDomain) {
                    $this->error("❌ Tenant domain is required!");
                    return 1;
                }
            }
            
            // البحث عن المستأجر
            $tenant = Tenant::on('system')->where('domain', $tenantDomain)->first();
            
            if (!$tenant) {
                $this->error("❌ Tenant with domain '{$tenantDomain}' not found!");
                return 1;
            }
            
            $this->info("🏢 Processing tenant: {$tenant->name} ({$tenant->domain})");
            
            // التبديل إلى قاعدة بيانات المستأجر
            TenantService::switchToTenant($tenant);
            
            // البحث عن المستخدم
            $user = User::find($userId);
            
            if (!$user) {
                $this->error("❌ User with ID '{$userId}' not found!");
                
                // عرض المستخدمين المتاحين
                $this->info("💡 Available users:");
                $users = User::take(10)->get(['id', 'name', 'email']);
                foreach ($users as $u) {
                    $this->line("   - ID: {$u->id}, Name: {$u->name}, Email: {$u->email}");
                }
                
                TenantService::switchToDefault();
                return 1;
            }
            
            $this->info("👤 Found user: {$user->name} ({$user->email})");
            
            // إنشاء/العثور على دور الأدمن
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);
            
            // منح دور الأدمن
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->info("✅ Successfully granted admin role to {$user->name}!");
            } else {
                $this->info("ℹ️  User {$user->name} already has admin role");
            }
            
            // عرض الأدوار الحالية
            $this->info("\n📋 Current user roles:");
            foreach ($user->roles as $role) {
                $this->line("   • {$role->name}");
            }
            
            $this->newLine();
            $this->info("🎉 Operation completed successfully!");
            $this->info("🌐 User can now access admin features at: /dashboard");
            
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