<?php

namespace Database\Seeders\landlord;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use Carbon\Carbon; // ← هذا السطر هو سبب الخطأ الحالي

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $tenant = [
            [
                'name' => 'أكاديمية نور',
                'slug' => 'academy-nour',
                'domain' => 'nour.cmsglobals.test',  // ← تم التعديل هنا
                'subdomain' => 'nour',
                'email' => 'admin@nour.com',
                'phone' => '0551234567',
                'logo' => null,
                'status' => 'active',
                'trial_ends_at' => Carbon::now()->addDays(14),
                'db_name' => 'tenant_nour',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مركز تميم للتدريب',
                'slug' => 'tamim-center',
                'domain' => 'tamim.cmsglobals.test',  // ← تم التعديل هنا
                'subdomain' => 'tamim',
                'email' => 'admin@tamim.com',
                'phone' => '0559876543',
                'logo' => null,
                'status' => 'active',
                'trial_ends_at' => Carbon::now()->addDays(7),
                'db_name' => 'tenant_tamim',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Tenant::insert($tenant);
    }
}
