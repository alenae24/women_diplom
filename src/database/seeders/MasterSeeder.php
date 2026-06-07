<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Master;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masters = [
            ['name' => 'Анна Иванова', 'level' => 'top', 'specialization' => 'Маникюр, педикюр', 'bio' => 'Топ-мастер, опыт 7 лет'],
            ['name' => 'Елена Смирнова', 'level' => 'middle', 'specialization' => 'Маникюр, дизайн', 'bio' => 'Опыт 4 года'],
            ['name' => 'Мария Петрова', 'level' => 'junior', 'specialization' => 'Педикюр', 'bio' => 'Опыт 2 года'],
        ];
        foreach ($masters as $master) {
            Master::create($master);
        }
    }
}
