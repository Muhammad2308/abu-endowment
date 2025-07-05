<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear the table to avoid duplicates
        DB::table('permissions')->truncate();

        $permissions = [
            ['permission_title' => 'manage_users'],
            ['permission_title' => 'view_dashboard_stats'],
            ['permission_title' => 'manage_projects'],
            ['permission_title' => 'manage_donors'],
            ['permission_title' => 'make_donations'],
            ['permission_title' => 'view_reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 