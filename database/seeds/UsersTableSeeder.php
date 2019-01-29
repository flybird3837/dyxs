<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\Models\User::create([
            'name' => 'admin',
            'phone' => '15869181957',
            'password' => '123456'
        ]);

        $admin->assignRole('administrator');
    }
}
