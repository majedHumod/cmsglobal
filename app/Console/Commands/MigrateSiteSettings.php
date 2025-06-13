<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class MigrateSiteSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site-settings:migrate {tenant_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate and seed site settings for a specific tenant';

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
            
            // التحقق من وجود جدول الإعدادات
            if (!Schema::hasTable('site_settings')) {
                $this->info("📝 Creating site_settings table...");
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/tenants/2025_06_15_create_site_settings_table.php',
                    '--force' => true,
                ]);
                $this->info(Artisan::output());
            } else {
                $this->info("✅ site_settings table already exists");
            }
            
            // تشغيل seeder للإعدادات
            $this->info("🌱 Seeding site settings...");
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\Tenants\\SiteSettingsSeeder',
                '--force' => true,
            ]);
            $this->info(Artisan::output());
            
            $this->info("✅ Site settings migrated and seeded successfully!");
            
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