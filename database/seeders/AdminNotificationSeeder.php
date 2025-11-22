<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminNotification::create([
            'title' => 'Welcome to the system',
            'message' => 'Welcome to the system',
            'type' => 'info',
            'url' => 'https://www.google.com',
            'read' => false,
        ]);
    }
}
