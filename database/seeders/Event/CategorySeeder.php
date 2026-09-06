<?php

namespace Database\Seeders\Event;

use App\Models\Event\Library\EventCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $category = [
            ['category_name' => 'Foundation'],
            ['category_name' => 'Managerial'],
            ['category_name' => 'Supervisory'],
       
        
          
         
        ];

        foreach ($category as $categories) {
            EventCategory::create($categories);
        }
    }
}
