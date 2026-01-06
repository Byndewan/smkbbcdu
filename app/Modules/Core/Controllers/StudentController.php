<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;     // Model Siswa
use App\Models\SchoolClass; // Model Kelas
use App\Models\Major;       // Model Jurusan
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ApiResponse;

class StudentController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = Student::with(['class', 'major'])
                ->restricted()
                ->latest();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('class_name', function($row){
                    return $row->class ? $row->class->name : '-';
                })
                ->addColumn('major_name', function($row){
                    return $row->major ? $row->major->abbreviation : '-';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-url="' . route('core.students.edit', $row->id) . '" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="' . route('core.students.destroy', $row->id) . '" data-name="' . $row->name . '" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $delBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Core::students.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // PENTING: Logic import sebaiknya pakai Model::create di dalam file StudentImport.php
            // biar Activity Log & Roles jalan otomatis.
            Excel::import(new StudentImport, $request->file('file'));

            // Opsional: Jalankan seeder fix role kalau di Import belum di-handle
            // $seeder = new \Database\Seeders\FixStudentRoleSeeder;
            // $seeder->run();

            return $this->success(null, 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return $this->error('Gagal Import: ' . $e->getMessage(), 500);
        }
    }

    public function create()
    {
        $majors = Major::restricted()->get();
        $classes = SchoolClass::restricted()->get();

        return view('Core::students.form', compact('majors', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nipd' => 'required|unique:core_students,nipd',
            'name' => 'required',
            'email' => 'required|email|unique:core_students,email',
            'major_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::create([
                'nipd' => $request->nipd,
                'nisn' => $request->nisn,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'major_id' => $request->major_id,
                'current_class_id' => $request->class_id,
                'school_year_id' => $request->school_year_id,
                'password' => '12345678',
                'is_active' => true,
            ]);

            $student->assignRole('Siswa');

            DB::commit();
            return $this->success(null, 'Siswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(null,'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $majors = Major::restricted()->get();
        $classes = SchoolClass::restricted()->get();

        return view('Core::students.form', compact('student', 'majors', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nipd' => 'required|unique:core_students,nipd,'.$id,
            'name' => 'required',
            'email' => 'required|email|unique:core_students,email,'.$id,
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'nipd' => $request->nipd,
            'nisn' => $request->nisn,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'major_id' => $request->major_id,
            'current_class_id' => $request->class_id,
        ]);

        return $this->success(null, 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Cek Tagihan sebelum hapus
        // Kita asumsikan relasi bills() ada di model Student
        // if($student->bills()->exists()) { return error... }

        $student->delete();

        return $this->success(null, 'Siswa berhasil dihapus!');
    }
}
