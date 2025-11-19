<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create([
            'role_id' => Role::first()->id,
            'name' => 'Admin',
            'email' => 'admin@log.in',
            'password' => Hash::make('12345678'),
        ]);

        $admin->email_verified_at = now();
        $admin->save();
    }
}
