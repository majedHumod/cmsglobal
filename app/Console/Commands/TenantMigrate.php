<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {tenant_domain} {--path=} {--rollback}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for a specific tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantDomain = $this->argument('tenant_domain');
        $path = $this->option('path');
        $rollback = $this->option('rollback');
        
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
            $this->info("💾 Database: {$tenant->db_name}");
            
            // التبديل إلى قاعدة بيانات المستأجر
            TenantService::switchToTenant($tenant);
            
            $this->info("🔄 Switched to tenant database");
            
            // تحديد المسار
            $migrationPath = $path ?: 'database/migrations/tenants/';
            
            if ($rollback) {
                $this->info("🔄 Rolling back migrations...");
                Artisan::call('migrate:rollback', [
                    '--database' => 'tenant',
                    '--path' => $migrationPath,
                    '--force' => true,
                ]);
            } else {
                $this->info("🚀 Running migrations...");
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => $migrationPath,
                    '--force' => true,
                ]);
            }
            
            // طباعة نتائج الأمر
            $this->line(Artisan::output());
            
            $this->info("✅ Migration completed successfully!");
            
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