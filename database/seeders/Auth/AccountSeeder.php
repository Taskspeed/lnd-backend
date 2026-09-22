<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gumawa muna ng roles/permissions kung wala pa (idempotent gamit firstOrCreate)
        $hr = Role::firstOrCreate([
            'name'       => 'hr_admin',
            'guard_name' => 'sanctum',
        ]);

        $office = Role::create([
            'name'       => 'office_admin',
            'guard_name' => 'sanctum',
        ]);
 

        $create_events = Permission::firstOrCreate([
            'name'       => 'create_event',
            'guard_name' => 'sanctum',
        ]);

        $user_management = Permission::firstOrCreate([
            'name'       => 'create_user',
            'guard_name' => 'sanctum',
        ]);

        $user = User::firstOrCreate(
            ['username' => 'admin'], // unique key para hindi mag-duplicate paulit-ulit
            [
                'name'       => 'Cliford Millan',
                'control_no' => '022485',
                'password'   => Hash::make('admin'),
                'office'     => 'OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER',
            ]
        );

        $user->assignRole($hr);
        $user->givePermissionTo([$create_events, $user_management]);
    }
}