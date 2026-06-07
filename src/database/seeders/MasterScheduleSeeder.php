<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterSchedule;
use App\Models\Master;
use Carbon\Carbon;

class MasterScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $masters = Master::all();
        $start = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $date = $start->copy()->addDays($i);
            foreach ($masters as $master) {
                // 70% вероятность, что мастер работает в этот день
                if (rand(1, 100) <= 70) {
                    $startHour = rand(9, 12);
                    $endHour = $startHour + rand(4, 8);
                    MasterSchedule::create([
                        'master_id' => $master->id,
                        'date' => $date,
                        'start_time' => sprintf('%02d:00', $startHour),
                        'end_time' => sprintf('%02d:00', $endHour),
                        'is_day_off' => false,
                    ]);
                } else {
                    MasterSchedule::create([
                        'master_id' => $master->id,
                        'date' => $date,
                        'start_time' => '00:00',
                        'end_time' => '00:00',
                        'is_day_off' => true,
                    ]);
                }
            }
        }
    }
}
