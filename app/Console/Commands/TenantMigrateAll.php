<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantMigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate-all {--path=} {--rollback}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $rollback = $this->option('rollback');
        
        try {
            $tenants = Tenant::on('system')->get();
            
            if ($tenants->isEmpty()) {
                $this->info("📭 No tenants found.");
                return 0;
            }
            
            $this->info("🏢 Found " . $tenants->count() . " tenant(s)");
            
            foreach ($tenants as $tenant) {
                $this->info("\n" . str_repeat("=", 50));
                $this->info("🚀 Processing tenant: {$tenant->name} ({$tenant->domain})");
                $this->info("💾 Database: {$tenant->db_name}");
                
                // التبديل إلى قاعدة بيانات المستأجر
                TenantService::switchToTenant($tenant);
                
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
                $output = Artisan::output();
                if (trim($output)) {
                    $this->line($output);
                }
                
                $this->info("✅ Completed for {$tenant->name}");
            }
            
            $this->info("\n🎉 All tenant migrations completed successfully!");
            
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