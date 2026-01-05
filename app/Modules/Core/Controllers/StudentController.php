<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = DB::table('core_students')
                ->join('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
                ->join('core_majors', 'core_students.major_id', '=', 'core_majors.id')
                ->select(
                    'core_students.*',
                    'core_classes.name as class_name',
                    'core_majors.abbreviation as major_name'
                )
                ->restrictMajor('core_students.major_id')
                ->orderBy('core_students.created_at', 'desc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></button>';
                })
                ->make(true);
        }

        return view('Core::students.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            Excel::import(new StudentImport, $request->file('file'));
            $seeder = new \Database\Seeders\FixStudentRoleSeeder;
            $seeder->run();
            DB::commit();

            return $this->success(null, 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return $this->error('Gagal : '.$e->getMessage(), 500);
        }
    }
}
