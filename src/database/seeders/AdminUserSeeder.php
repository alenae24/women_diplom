<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@women.ru'],
            [
                'name' => 'Admin',
                'phone' => '+79991234567',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        
        $clientUser = User::updateOrCreate(
            ['email' => 'client@test.ru'],
            [
                'name' => 'Тестовый Клиент',
                'phone' => '+79991112233',
                'password' => Hash::make('client123'),
                'role' => 'user',
            ]
        );       
    }
}
