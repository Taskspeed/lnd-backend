<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Auth\AccountSeeder;
use Database\Seeders\Event\CategorySeeder;
use Database\Seeders\Event\FormSeeder;
use Database\Seeders\Event\ModeSeeder;
use Database\Seeders\Event\TitleSeeder;
use Database\Seeders\Event\TypeSeeder;
use Database\Seeders\Event\VenueSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            FormSeeder::class,
            AccountSeeder::class,
            TitleSeeder::class,
            TypeSeeder::class,
            ModeSeeder::class,
            CategorySeeder::class,
            VenueSeeder::class
        ]);
    }
}
