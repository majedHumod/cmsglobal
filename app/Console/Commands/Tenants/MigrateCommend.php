<?php

namespace App\Console\Commands\Tenants;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateCommend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tenant-specific migrations for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all();

        $tenants->each(function ($tenant) {
            // تبديل الاتصال إلى قاعدة بيانات العميل
            TenantService::switchToTenant($tenant);

            $this->info('🚀 Starting migration for: ' . $tenant->domain);
            $this->info('---------------------------------------------');

            // تنفيذ المايجريشن
           //Artisan::call('migrate:rollback', [ في حال الرغبة في الرول باك
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenants/',
                '--database' => 'tenant',
                '--force' => true, // مهم في بعض السيرفرات
            ]);

            // طباعة نتائج الأمر
            $this->line(Artisan::output());
        });
    }
}
