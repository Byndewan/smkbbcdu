<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleSuperAdmin = Role::create(['name' => 'SuperAdmin']);
        $rolePetugas = Role::create(['name' => 'Petugas']);
        $roleBendahara = Role::create(['name' => 'Bendahara']);
        $roleSiswa = Role::create(['name' => 'Siswa']);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);
        $user->assignRole($roleSuperAdmin);

        $operator = User::create([
            'name' => 'Operator',
            'email' => 'operator@operator.com',
            'password' => Hash::make('operator'),
        ]);
        $operator->assignRole($rolePetugas);

        $bend = User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@bendahara.com',
            'password' => Hash::make('bendahara'),
        ]);
        $bend->assignRole($roleBendahara);

        $siswa = User::create([
            'name' => 'Badhie',
            'email' => '123456789@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        $siswa->assignRole($roleSiswa);
    }
}
