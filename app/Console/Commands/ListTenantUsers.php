<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;

class ListTenantUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:users {tenant_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users in a specific tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantDomain = $this->argument('tenant_domain');
        
        try {
            // البحث عن المستأجر
            $tenant = Tenant::on('system')->where('domain', $tenantDomain)->first();
            
            if (!$tenant) {
                $this->error("❌ Tenant with domain '{$tenantDomain}' not found!");
                return 1;
            }
            
            $this->info("🏢 Tenant: {$tenant->name} (Domain: {$tenant->domain})");
            
            // التبديل إلى قاعدة بيانات المستأجر
            TenantService::switchToTenant($tenant);
            
            // جلب المستخدمين
            $users = User::with('roles')->get();
            
            if ($users->isEmpty()) {
                $this->info("📭 No users found in this tenant.");
                TenantService::switchToDefault();
                return 0;
            }
            
            $this->info("\n👥 Users in {$tenant->name}:");
            $this->info("========================");
            
            foreach ($users as $user) {
                $this->line("🆔 ID: {$user->id}");
                $this->line("👤 Name: {$user->name}");
                $this->line("📧 Email: {$user->email}");
                $this->line("🏷️  Roles: " . ($user->roles->pluck('name')->join(', ') ?: 'No roles'));
                $this->line("📅 Created: {$user->created_at->format('Y-m-d H:i:s')}");
                $this->line("---");
            }
            
            $this->newLine();
            $this->info("💡 To grant page permissions to a user, use:");
            $this->line("php artisan user:grant-page-permissions {user_id} {$tenantDomain}");
            
            // العودة إلى قاعدة البيانات الرئيسية
            TenantService::switchToDefault();
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // التأكد من العودة إلى قاعدة البيانات الرئيسية
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