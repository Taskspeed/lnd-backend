<?php

namespace Database\Seeders;

use App\Models\Employee\EmployeeAttendance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeAttendanceSeeder extends Seeder
{
   
 
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample employees — palitan mo ito ng galing sa iyong Employee table
        // kung meron kang existing employee list na gusto mong gamitin.
        $employees = [
            ['control_no' => '022485', 'name' => 'Cliford Millan'],
            // ['control_no' => '022486', 'name' => 'Maria Santos'],
            // ['control_no' => '022487', 'name' => 'Pedro Reyes'],
        ];
 
        // Mga petsang gusto mong i-seed — dagdagan/palitan mo ayon sa kailangan mo
        $dates = [
            '2026-09-10',
       
        ];
 
        // Fixed na oras araw-araw
        $morningIn    = '09:00:00';
        $morningOut   = '12:00:00';
        $afternoonIn  = '13:00:00';
        $afternoonOut = '15:00:00';
 
        foreach ($dates as $date) {
            foreach ($employees as $employee) {
                EmployeeAttendance::create([
                    'nominated_employee_id'    => 4,
                    'control_no'    => $employee['control_no'],
                    'name'          => $employee['name'],
                    'scan_date'     => Carbon::parse($date)->format('Y-m-d'),
                    'morning_in'    => $morningIn,
                    'morning_out'   => $morningOut,
                    'afternoon_in'  => $afternoonIn,
                    'afternoon_out' => $afternoonOut,
                ]);
            }
        }
    }
   
}
