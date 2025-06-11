<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class FixPermissionRelationships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:permission-relationships {tenant_domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix permission relationships to use the correct class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantDomain = $this->argument('tenant_domain');
        
        try {
            // إذا تم تحديد مستأجر، قم بالتبديل إلى قاعدة بياناته
            if ($tenantDomain) {
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
            }
            
            $this->info("🔍 Checking for permission relationship issues...");
            
            // التحقق من وجود جدول user_permission_overrides
            if (Schema::hasTable('user_permission_overrides')) {
                $this->info("✅ Found user_permission_overrides table");
                
                // التحقق من وجود علاقة مع جدول permissions
                if (Schema::hasColumn('user_permission_overrides', 'permission_id')) {
                    $this->info("✅ Found permission_id column in user_permission_overrides table");
                    
                    // إصلاح العلاقة في جدول user_permission_overrides
                    $this->fixPermissionRelationship('user_permission_overrides', 'permission_id');
                }
            } else {
                $this->warn("⚠️ user_permission_overrides table does not exist");
            }
            
            // التحقق من وجود جدول permission_categories
            if (Schema::hasTable('permission_categories')) {
                $this->info("✅ Found permission_categories table");
                
                // إصلاح العلاقة في جدول permission_categories
                $this->fixPermissionRelationship('permissions', 'permission_category_id');
            } else {
                $this->warn("⚠️ permission_categories table does not exist");
            }
            
            // التحقق من وجود جدول permission_dependencies
            if (Schema::hasTable('permission_dependencies')) {
                $this->info("✅ Found permission_dependencies table");
                
                // إصلاح العلاقة في جدول permission_dependencies
                $this->fixPermissionRelationship('permission_dependencies', 'permission_id');
                $this->fixPermissionRelationship('permission_dependencies', 'depends_on_permission_id');
            } else {
                $this->warn("⚠️ permission_dependencies table does not exist");
            }
            
            // إنشاء الصلاحيات المتقدمة
            $this->info("🔄 Creating advanced permissions...");
            try {
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\Tenants\\PermissionsSeeder',
                    '--force' => true
                ]);
                $this->info("✅ Advanced permissions created successfully");
            } catch (\Exception $e) {
                $this->error("❌ Error creating advanced permissions: " . $e->getMessage());
            }
            
            $this->info("🎉 Permission relationships fixed successfully!");
            
            // العودة إلى قاعدة البيانات الرئيسية إذا تم التبديل
            if ($tenantDomain) {
                TenantService::switchToDefault();
                $this->info("🔄 Switched back to default database");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            
            // التأكد من العودة إلى قاعدة البيانات الرئيسية في حالة الخطأ
            if ($tenantDomain) {
                try {
                    TenantService::switchToDefault();
                    $this->info("🔄 Switched back to default database");
                } catch (\Exception $switchError) {
                    $this->error("❌ Failed to switch back to default database: " . $switchError->getMessage());
                }
            }
            
            return 1;
        }
        
        return 0;
    }
    
    /**
     * إصلاح علاقة الصلاحيات في جدول معين
     */
    private function fixPermissionRelationship($table, $column)
    {
        try {
            // التحقق من وجود الجدول والعمود
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                $this->warn("⚠️ Table {$table} or column {$column} does not exist");
                return;
            }
            
            // إنشاء نموذج للصلاحية إذا لم يكن موجوداً
            $this->createPermissionModel();
            
            $this->info("🔧 Fixing permission relationship in {$table}.{$column}");
            
            // تحديث العلاقات في الموديل
            $this->updateModelRelationships();
            
            $this->info("✅ Permission relationship in {$table}.{$column} fixed successfully");
        } catch (\Exception $e) {
            $this->error("❌ Error fixing permission relationship in {$table}.{$column}: " . $e->getMessage());
        }
    }
    
    /**
     * إنشاء نموذج للصلاحية إذا لم يكن موجوداً
     */
    private function createPermissionModel()
    {
        $modelPath = app_path('Models/Permission.php');
        
        if (!file_exists($modelPath)) {
            $this->info("📝 Creating Permission model as a proxy to Spatie's Permission model");
            
            $content = <<<'EOT'
<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // This is a proxy class to Spatie's Permission model
    // It exists to fix relationship issues in the application
}
EOT;
            
            file_put_contents($modelPath, $content);
            $this->info("✅ Permission model created successfully");
        } else {
            $this->info("✅ Permission model already exists");
        }
    }
    
    /**
     * تحديث العلاقات في الموديل
     */
    private function updateModelRelationships()
    {
        // تحديث العلاقات في موديل UserPermissionOverride
        $modelPath = app_path('Models/UserPermissionOverride.php');
        
        if (file_exists($modelPath)) {
            $content = file_get_contents($modelPath);
            
            // تحديث استيراد الكلاس
            $content = str_replace(
                'use Spatie\Permission\Models\Permission;',
                'use App\Models\Permission;',
                $content
            );
            
            // تحديث العلاقة
            $content = preg_replace(
                '/public function permission\(\)\s*\{.*?return \$this->belongsTo\(.*?Permission::class.*?\);.*?\}/s',
                'public function permission()
    {
        return $this->belongsTo(Permission::class);
    }',
                $content
            );
            
            file_put_contents($modelPath, $content);
            $this->info("✅ Updated relationships in UserPermissionOverride model");
        }
        
        // تحديث العلاقات في موديل PermissionCategory
        $modelPath = app_path('Models/PermissionCategory.php');
        
        if (file_exists($modelPath)) {
            $content = file_get_contents($modelPath);
            
            // تحديث استيراد الكلاس
            $content = str_replace(
                'use Spatie\Permission\Models\Permission;',
                'use App\Models\Permission;',
                $content
            );
            
            // تحديث العلاقة
            $content = preg_replace(
                '/public function permissions\(\)\s*\{.*?return \$this->hasMany\(.*?Permission::class.*?\);.*?\}/s',
                'public function permissions()
    {
        return $this->hasMany(Permission::class, \'permission_category_id\');
    }',
                $content
            );
            
            file_put_contents($modelPath, $content);
            $this->info("✅ Updated relationships in PermissionCategory model");
        }
    }
}