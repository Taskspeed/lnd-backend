<?php

namespace Database\Seeders\Event;

use App\Models\Event\Library\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $type = [
            ['type_name' => 'Technical'],
            ['type_name' => 'Seminar'],
            ['type_name' => 'WorkShop'],
       
        
          
         
        ];

        foreach ($type as $types) {
            EventType::create($types);
        }
    
    }
}
