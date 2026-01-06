<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class FixStudentRoleSeeder extends Seeder
{
    public function run()
    {
        $roleSiswa = Role::firstOrCreate(
            ['name' => 'Siswa', 'guard_name' => 'student']
        );

        Student::doesntHave('roles')->chunk(1000, function ($students) use ($roleSiswa) {
            $dataToInsert = [];

            foreach ($students as $student) {
                $dataToInsert[] = [
                    'role_id' => $roleSiswa->id,
                    'model_type' => Student::class,
                    'model_id' => $student->id,
                ];
            }

            if (! empty($dataToInsert)) {
                DB::table('model_has_roles')->insertOrIgnore($dataToInsert);
            }
        });
    }
}
