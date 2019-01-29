<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        // 管理员组
        \Spatie\Permission\Models\Role::create(['name' => 'administrator']);
        // 维护员组
        \Spatie\Permission\Models\Role::create(['name' => 'project_manage']);
        // 管理员组
        \Spatie\Permission\Models\Role::create(['name' => 'project_maintenance']);

    }
}
