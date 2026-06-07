<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterService;
use App\Models\Master;
use App\Models\Service;

class MasterServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MasterService::truncate();

        $masters = Master::all();
        $services = Service::all();
        $priceMap = [
            'top' => ['Маникюр' => 2000, 'Педикюр' => 2500, 'Гель-лак' => 1600, 'Комплекс' => 4000],
            'middle' => ['Маникюр' => 1500, 'Педикюр' => 2000, 'Гель-лак' => 1200, 'Комплекс' => 3200],
            'junior' => ['Маникюр' => 1200, 'Педикюр' => 1700, 'Гель-лак' => 1000, 'Комплекс' => 2700],
        ];
        foreach ($masters as $master) {
            $prices = $priceMap[$master->level];
            foreach ($services as $service) {
                if (isset($prices[$service->name])) {
                    MasterService::create([
                        'master_id' => $master->id,
                        'service_id' => $service->id,
                        'price' => $prices[$service->name],
                    ]);
                }
            }
        }
    }
}
