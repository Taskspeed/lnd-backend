<?php

namespace Database\Seeders\Event;

use App\Models\Event\Library\EventVenue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $venue = [
            ['venue_name' => 'TAGUM CITY HALL AVR 5'],
        
       
        
          
         
        ];

        foreach ($venue as $venues) {
            EventVenue::create($venues);
        }
    
    }
}
