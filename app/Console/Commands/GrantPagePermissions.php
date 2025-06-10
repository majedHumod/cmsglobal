<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GrantPagePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:grant-page-permissions {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant page management permissions to a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        try {
            // البحث عن المستخدم
            $user = User::findOrFail($userId);
            
            $this->info("🔍 Found user: {$user->name} (ID: {$user->id})");
            $this->info("📧 Email: {$user->email}");
            
            // إنشاء الصلاحيات إذا لم تكن موجودة
            $permissions = [
                'create pages',
                'edit pages', 
                'delete pages',
                'view pages',
                'publish pages'
            ];
            
            $this->info("📝 Creating/checking permissions...");
            
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                
                $this->line("   ✓ {$permissionName}");
            }
            
            // إنشاء دور "page_manager" إذا لم يكن موجوداً
            $pageManagerRole = Role::firstOrCreate([
                'name' => 'page_manager',
                'guard_name' => 'web'
            ]);
            
            $this->info("👤 Created/found 'page_manager' role");
            
            // ربط الصلاحيات بالدور
            $pageManagerRole->syncPermissions($permissions);
            $this->info("🔗 Synced permissions with page_manager role");
            
            // منح المستخدم دور page_manager
            if (!$user->hasRole('page_manager')) {
                $user->assignRole('page_manager');
                $this->info("✅ Assigned 'page_manager' role to user");
            } else {
                $this->info("ℹ️  User already has 'page_manager' role");
            }
            
            // التحقق من الصلاحيات الحالية للمستخدم
            $this->info("\n📋 Current user roles:");
            foreach ($user->roles as $role) {
                $this->line("   • {$role->name}");
            }
            
            $this->info("\n🔑 Current user permissions:");
            foreach ($user->getAllPermissions() as $permission) {
                $this->line("   • {$permission->name}");
            }
            
            $this->newLine();
            $this->info("🎉 Successfully granted page management permissions to user {$user->name}!");
            $this->info("🌐 The user can now access page management at: /pages");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}