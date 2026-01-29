<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        Shift::create([
            'name' => 'Shift 1',
            'start_time' => '07:00',
            'end_time' => '15:00',
            'planned_operation_time' => 480,
            'status' => 'active'
        ]);

        Shift::create([
            'name' => 'Shift 2',
            'start_time' => '15:00',
            'end_time' => '23:00',
            'planned_operation_time' => 480,
            'status' => 'active'
        ]);
    }
}