<?php

namespace Database\Seeders\Event;

use App\Models\Event\Library\EventMode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $mode = [
            ['mode_name' => 'Face to Face'],
            ['mode_name' => 'Online'],
       
        
          
         
        ];

        foreach ($mode as $modes) {
            EventMode::create($modes);
        }
    
    }
}
