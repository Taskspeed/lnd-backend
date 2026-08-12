<?php

namespace Database\Seeders\Event;

use App\Models\Event\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forms = [
            ['form_name' => 'Learning Application Plan'],
            ['form_name' => 'Learning Implementation Report'],
            ['form_name' => 'Learning Application Monitoring Report'],
            ['form_name' => 'Leaner Progress Report'],
        
          
         
        ];

        foreach ($forms as $form) {
            Form::create($form);
        }
    }
}
