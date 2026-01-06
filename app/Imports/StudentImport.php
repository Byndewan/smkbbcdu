<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class StudentImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public function model(array $row)
    {
        if(!isset($row['kode_kelas_baru']) || !isset($row['nipd'])) {
             return null;
        }

        $newClass = DB::table('core_classes')->where('code', $row['kode_kelas_baru'])->first();
        $prevClass = DB::table('core_classes')->where('code', $row['kode_kelas_sebelumnya'])->first();
        $major = DB::table('core_majors')->where('code', $row['kode_jurusan'])->first();
        $year = DB::table('core_school_years')->where('code', $row['kode_tahun_ajaran'])->first();
        if (!$newClass) throw new Exception("Kelas Baru '{$row['kode_kelas_baru']}' tidak ditemukan!");
        if (!$major) throw new Exception("Jurusan '{$row['kode_jurusan']}' tidak ditemukan!");
        if (!$year) throw new Exception("Tahun Ajaran '{$row['kode_tahun_ajaran']}' tidak ditemukan!");
        return new Student([
            'nipd'             => $row['nipd'],
            'name'             => $row['nama_siswa'],
            'email'            => $row['nipd'].'@siswa.bbc',
            'gender'           => $row['jenis_kelamin'] == 'L' ? 'L' : 'P',
            'pob'              => $row['tempat_lahir'] ?? null,
            'dob'              => $row['tanggal_lahir'],
            'current_class_id' => $newClass->id,
            'prev_class_id'    => $prevClass ? $prevClass->id : null,
            'major_id'         => $major->id,
            'school_year_id'   => $year->id,
            'password'         => $row['nipd'],
            'is_active'        => true,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
