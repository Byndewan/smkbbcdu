<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ArchivistSeeder extends Seeder
{
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Archivist', 'guard_name' => 'web']);
        $user = User::create([
            'name' => 'System Archivist',
            'email' => 'trash@trash.com',
            'password' => Hash::make('trash'),
        ]);

        $user->assignRole($role);
    }
}
