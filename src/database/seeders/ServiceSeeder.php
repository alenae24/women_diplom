<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $services = [
            ['name' => 'Маникюр', 'duration' => 60, 'description' => 'Классический маникюр с покрытием'],
            ['name' => 'Педикюр', 'duration' => 90, 'description' => 'Педикюр с покрытием'],
            ['name' => 'Гель-лак', 'duration' => 45, 'description' => 'Снятие + покрытие гель-лаком'],
            ['name' => 'Комплекс', 'duration' => 150, 'description' => 'Маникюр + педикюр'],
        ];
        
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
