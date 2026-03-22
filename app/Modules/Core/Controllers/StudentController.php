<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDeleted;
use App\Models\SchoolClass;
use App\Models\Major;
use App\Models\SchoolYear;
use App\Imports\StudentImport;
use App\Models\DuTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ApiResponse;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = Student::with(['schoolYear'])
                ->leftJoin('core_classes as current', 'core_students.current_class_id', '=', 'current.id')
                ->leftJoin('core_classes as prev', 'core_students.prev_class_id', '=', 'prev.id')
                ->leftJoin('core_majors', 'core_students.major_id', '=', 'core_majors.id')
                ->select(
                    'core_students.*',
                    'current.name as current_class_name',
                    'prev.name as prev_class_name',
                    'core_majors.name as major_full_name',
                    'core_majors.abbreviation as major_abbr'
                )
                ->restricted()
                ->latest('core_students.created_at');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('identitas', function ($row) {
                    return '<div class="d-flex flex-column">
                            <span class="fw-bold text-dark">' . $row->name . '</span>
                            <span class="small text-muted font-monospace"><i class="bi bi-card-heading me-1"></i>' . $row->nipd . '</span>
                        </div>';
                })
                ->addColumn('jk', function ($row) {
                    return $row->gender == 'L'
                        ? '<span class="badge bg-primary bg-opacity-10 text-primary">L</span>'
                        : '<span class="badge bg-danger bg-opacity-10 text-danger">P</span>';
                })
                ->addColumn('kelas_info', function ($row) {
                    $prev = $row->prev_class_name ?? '-';
                    $curr = $row->current_class_name ?? '-';
                    return '<div class="d-flex flex-column gap-1" style="font-size: 0.85rem;">
                            <div><span class="text-muted" style="width:60px; display:inline-block;">Lama</span>: <span class="fw-bold">' . $prev . '</span></div>
                            <div><span class="text-primary" style="width:60px; display:inline-block;">Baru</span>: <span class="fw-bold">' . $curr . '</span></div>
                        </div>';
                })
                ->addColumn('jurusan', function ($row) {
                    return $row->major_abbr
                        ? '<span class="badge bg-secondary bg-opacity-10 text-secondary">' . $row->major_abbr . '</span>'
                        : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Non-Aktif</span>';
                })
                ->addColumn('action', function ($row) {
                    $urlDetail = route('admin.core.students.show', $row->id);
                    $urlEdit = route('admin.core.students.edit', $row->id);
                    $urlDelete = route('admin.core.students.delete_confirm', $row->id);
                    $popupBig = "const w=1500;const h=900;const left=(screen.width-w)/2;const top=(screen.height-h)/2;window.open(this.dataset.url, '_blank', `width=\${w},height=\${h},left=\${left},top=\${top},resizable=yes,scrollbars=yes`)";
                    $popupSmall = "const w=600;const h=600;const left=(screen.width-w)/2;const top=(screen.height-h)/2;window.open(this.dataset.url, '_blank', `width=\${w},height=\${h},left=\${left},top=\${top},resizable=yes,scrollbars=yes`)";
                    $btn  = '<button onclick="' . $popupBig . '" data-url="' . $urlDetail . '" class="btn btn-sm btn-info text-white me-1" title="Detail"><i class="bi bi-eye"></i></button>';
                    if ($row->phone) {
                        $phone = preg_replace('/^0/', '62', $row->phone);
                        $btn .= '<a href="https://wa.me/' . $phone . '" target="_blank" class="btn btn-sm btn-success me-1" title="Hubungi"><i class="bi bi-whatsapp"></i></a>';
                    } else {
                        $btn .= '<button class="btn btn-sm btn-secondary me-1" disabled><i class="bi bi-whatsapp"></i></button>';
                    }
                    $btn .= '<button onclick="' . $popupBig . '" data-url="' . $urlEdit . '" class="btn btn-sm btn-warning text-white me-1" title="Edit"><i class="bi bi-pencil"></i></button>';
                    $btn .= '<button onclick="' . $popupSmall . '" data-url="' . $urlDelete . '" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['identitas', 'jk', 'kelas_info', 'jurusan', 'status', 'action'])
                ->make(true);
        }

        return view('Core::students.index');
    }

    public function create()
    {
        $majors = Major::restricted()->get();
        $classes = SchoolClass::restricted()->get();
        $years = SchoolYear::orderBy('name', 'desc')->get();

        return view('Core::students.form', compact('majors', 'classes', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nipd' => 'required|unique:core_students,nipd',
            'name' => 'required',
            'email' => 'required|email|unique:core_students,email',
            'major_id' => 'required',
            'gender' => 'required|in:L,P',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::create([
                'nipd' => $request->nipd,
                'nisn' => $request->nisn,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'photo_path' => 'defaults/' . strtolower(trim($request->gender)) . '.svg',
                'gender' => $request->gender,
                'pob' => $request->pob,
                'dob' => $request->dob,
                'major_id' => $request->major_id,
                'current_class_id' => $request->class_id,
                'prev_class_id' => $request->prev_class_id,
                'school_year_id' => $request->school_year_id,
                'password' => Hash::make($request->nipd,),
                'is_active' => $request->is_active ?? 1,
            ]);
            $student->assignRole('Siswa');

            DB::commit();
            return $this->success(null, 'Siswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(null, 'Gagal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $student = Student::with(['class', 'major', 'schoolYear'])->findOrFail($id);
        return view('Core::students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $majors = Major::restricted()->get();
        $classes = SchoolClass::restricted()->get();
        $years = SchoolYear::orderBy('name', 'desc')->get();

        return view('Core::students.form', compact('student', 'majors', 'classes', 'years'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nipd' => 'required|unique:core_students,nipd,' . $id,
            'name' => 'required',
            'email' => 'required|email|unique:core_students,email,' . $id,
            'gender' => 'required|in:L,P',
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'nipd' => $request->nipd,
            'nisn' => $request->nisn,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'pob' => $request->pob,
            'dob' => $request->dob,
            'major_id' => $request->major_id,
            'current_class_id' => $request->class_id,
            'prev_class_id' => $request->prev_class_id,
            'is_active' => $request->is_active ?? $student->is_active,
        ]);

        return $this->success(null, 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
            'reason' => 'required|string|min:5'
        ]);

        $student = Student::findOrFail($id);
        $hasTransaction = DuTransaction::where('student_id', $id)->exists();

        if ($hasTransaction) {
            return $this->error('Siswa ini TIDAK BISA DIHAPUS karena memiliki riwayat transaksi keuangan. Silakan hubungi bagian keuangan / bendahara.', 422);
        }

        $admin = Auth::user();

        if (!Hash::check($request->password, $admin->password)) {
            return $this->error('Password salah!', 403);
        }

        DB::beginTransaction();
        try {
            StudentDeleted::create([
                'original_student_id' => $student->id,
                'nipd' => $student->nipd,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'gender' => $student->gender,
                'pob' => $student->pob,
                'address' => $student->address,
                'dob' => $student->dob,
                'photo_path' => $student->photo_path,
                'current_class_id' => $student->current_class_id,
                'prev_class_id' => $student->prev_class_id,
                'major_id' => $student->major_id,
                'school_year_id' => $student->school_year_id,
                'deleted_by' => $admin->id,
                'deleter_name' => $admin->name,
                'deletion_reason' => $request->reason,
                'deleted_at' => now(),
            ]);

            $student->delete();

            DB::commit();
            return $this->success(null, 'Siswa berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal menghapus: ' . $e->getMessage(), 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StudentImport, $request->file('file'));
            $seeder = new \Database\Seeders\FixStudentRoleSeeder;
            $seeder->run();
            return $this->success(null, 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return $this->error('Gagal Import: ' . $e->getMessage(), 500);
        }
    }

    public function deleteConfirm($id)
    {
        $student = Student::findOrFail($id);
        $hasTransaction = DuTransaction::where('student_id', $id)->exists();
        $isActive = $student->is_active;

        return view('Core::students.delete_confirm', compact('student', 'hasTransaction', 'isActive'));
    }
}
