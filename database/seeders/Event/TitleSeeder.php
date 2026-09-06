<?php

namespace Database\Seeders\Event;

use App\Models\Event\Library\EventTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
       $title = [
            ['title_name' => 'EPSON BUSINESS SOLUTIONS: AN END-USER TECH SHOWCASE'],
            ['title_name' => 'VIRTUAL ASSISTANT ORIENTATION PROGRAM'],
            ['title_name' => 'MOBILE DEVELOPMENT TRAINING SERIES ( TRAINING 1: INTRODUCTION TO MOBILE APP DEVELOPMENT USING FLUTTER)'],
            ['title_name' => 'MOBILE DEVELOPMENT TRAINING SERIES ( TRAINING 2: BUILDING DYNAMIC UIS:HARNESSING THE POWER OF THE STATEFUL WIDGETS IN FLUTTER)'],
            ['title_name' => 'THE FUTURE OF ICT - EMERGING TECHNOLOGIES AND PREDICTIONS'],
            ['title_name' => 'TRAINING-WORKSHOP FOR DESIGNING INTERACTIVE MAPS USING VUE LEAFLET'],
            ['title_name' => 'DIGITAL MOTION GRAPHICS DESIGN TRAINING/SEMINAR'],

        
          
         
        ];

        foreach ($title as $titles) {
            EventTitle::create($titles);
        }
    
    }
}
