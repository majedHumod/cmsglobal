<?php

namespace Database\Seeders\Tenants;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // seed data here
        DB::table('users')->insert([
            'name' => 'Tenant Admin',
            'email' => 'admin@tenant.com',
            'password' => bcrypt('password'),
        ]);
    }
}


