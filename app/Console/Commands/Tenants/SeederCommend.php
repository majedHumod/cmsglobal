<?php
//seeder على مستوى كل قاعدة بيانات
namespace App\Console\Commands\Tenants;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeederCommend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:seeder {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run tenant-specific seeders for all tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $class = $this->argument('class');
        $tenants = Tenant::get();
        $tenants->each(function ($tenant) use($class) {
            // تبديل الاتصال إلى قاعدة بيانات العميل
            TenantService::switchToTenant($tenant);

            $this->info('🚀 Starting seeder for: ' . $tenant->domain);
            $this->info('---------------------------------------------');

            // تنفيذ المايجريشن
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\tenants\\'.$class,
                '--database' => 'tenant',
                '--force' => true, // مهم في بعض السيرفرات
            ]);

            // طباعة نتائج الأمر
            $this->line(Artisan::output());
        });
    }
}
