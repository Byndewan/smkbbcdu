<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDeleted;
use App\Models\DuTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TrashController extends Controller
{
    public function index()
    {
        $deletedStudents = StudentDeleted::count();
        $deletedTrx = DuTransaction::onlyTrashed()->count();

        return view('Admin::trash.index', compact('deletedStudents', 'deletedTrx'));
    }

    public function students()
    {
        if (request()->ajax()) {
            $data = StudentDeleted::leftJoin('core_classes', 'core_students_deleted.current_class_id', '=', 'core_classes.id')
                ->leftJoin('core_majors', 'core_students_deleted.major_id', '=', 'core_majors.id')
                ->select(
                    'core_students_deleted.*',
                    'core_classes.name as class_name',
                    'core_majors.abbreviation as major_name'
                )
                ->latest('deleted_at');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('deleter_info', function($row){
                    return '<div class="d-flex flex-column small">
                                <span class="fw-bold text-danger">'.$row->deleter_name.'</span>
                                <span class="text-muted fst-italic">"'.$row->deletion_reason.'"</span>
                                <span class="text-secondary" style="font-size:10px;">'.date('d M Y H:i', strtotime($row->deleted_at)).'</span>
                            </div>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex gap-1 justify-content-center">
                            <button onclick="restoreData(\''.route('admin.trash.students.restore', $row->id).'\')" class="btn btn-sm btn-success text-white" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <button onclick="forceDelete(\''.route('admin.trash.students.kill', $row->id).'\')" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-fire"></i></button>
                        </div>
                    ';
                })
                ->rawColumns(['deleter_info', 'action'])
                ->make(true);
        }
        return view('Admin::trash.students');
    }

    public function restoreStudent($id)
    {
        $archive = StudentDeleted::findOrFail($id);

        DB::beginTransaction();
        try {
            $exists = Student::where('nipd', $archive->nipd)->orWhere('email', $archive->email)->exists();
            if($exists) {
                return response()->json(['message' => 'Gagal! NIPD/Email siswa ini sudah digunakan oleh data aktif baru.'], 422);
            }

            $student = Student::create([
                'nipd'             => $archive->nipd,
                'name'             => $archive->name,
                'email'            => $archive->email,
                'phone'            => $archive->phone,
                'gender'           => $archive->gender,
                'pob'              => $archive->pob,
                'address'          => $archive->address,
                'dob'              => $archive->dob,
                'photo_path'       => $archive->photo_path,
                'current_class_id' => $archive->current_class_id,
                'prev_class_id'    => $archive->prev_class_id,
                'major_id'         => $archive->major_id,
                'school_year_id'   => $archive->school_year_id,
                'password'         => $archive->nipd,
                'is_active'        => 0,
            ]);

            $student->assignRole('Siswa');
            $archive->delete();

            DB::commit();
            return response()->json(['message' => 'Data Siswa berhasil dipulihkan (Status: Non-Aktif).']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function killStudent($id)
    {
        $archive = StudentDeleted::findOrFail($id);

        if($archive->photo_path && file_exists(storage_path('app/public/'.$archive->photo_path))) {
            unlink(storage_path('app/public/'.$archive->photo_path));
        }

        $archive->delete();

        return response()->json(['message' => 'Data Siswa berhasil dihapus.']);
    }
}
