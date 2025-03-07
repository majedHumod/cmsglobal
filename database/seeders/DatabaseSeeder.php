<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run2(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }


    public function run()
{
    // إنشاء الصلاحيات
    $createPost = Permission::create(['name' => 'create post']);
    $editPost = Permission::create(['name' => 'edit post']);
    $deletePost = Permission::create(['name' => 'delete post']);

    // إنشاء الأدوار وربطها بالصلاحيات
    $adminRole = Role::create(['name' => 'admin']);
    $editorRole = Role::create(['name' => 'editor']);
    $userRole = Role::create(['name' => 'user']);

    $adminRole->givePermissionTo([$createPost, $editPost, $deletePost]);
    $editorRole->givePermissionTo([$createPost, $editPost]);
}

}
