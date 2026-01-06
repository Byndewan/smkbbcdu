<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class SchoolYearController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = SchoolYear::query()->orderBy('code', 'desc');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Tidak Aktif</span>';
                })
                ->addColumn('action', function($row){
                    $editBtn = '<button data-url="'.route('core.school-years.edit', $row->id).'" class="btn btn-sm btn-warning btn-modal text-white me-1" title="Edit"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="'.route('core.school-years.destroy', $row->id).'" data-name="'.$row->name.'" class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>';

                    $activeBtn = '';
                    if(!$row->is_active){
                        $activeBtn = '<form action="'.route('core.school-years.activate', $row->id).'" method="POST" class="d-inline form-ajax">
                            '.csrf_field().' <button type="submit" class="btn btn-sm btn-success text-white me-1" title="Set Aktif"><i class="bi bi-check-lg"></i></button>
                        </form>';
                    }

                    return $activeBtn . $editBtn . $delBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('Core::school_years.index');
    }

    public function create()
    {
        return view('Core::school_years.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:core_school_years,code',
            'name' => 'required',
        ]);

        SchoolYear::create([
            'code' => $request->code,
            'name' => $request->name,
            'is_active' => false,
        ]);

        return $this->success(null, 'Tahun ajaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = SchoolYear::findOrFail($id);
        return view('Core::school_years.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:core_school_years,code,'.$id,
            'name' => 'required',
        ]);

        $year = SchoolYear::findOrFail($id);
        $year->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return $this->success(null, 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $year = SchoolYear::findOrFail($id);
        $isUsed = DB::table('core_students')
            ->where('school_year_id', $id)
            ->whereNull('deleted_at')
            ->exists();

        if($isUsed) {
            return $this->error('Gagal! Data sedang digunakan oleh siswa.', 422);
        }

        $year->delete();

        return $this->success(null, 'Data berhasil dihapus (Soft Delete)!');
    }

    public function activate($id)
    {
        try {
            DB::beginTransaction();
            SchoolYear::query()->update(['is_active' => false]);
            $year = SchoolYear::findOrFail($id);
            $year->update(['is_active' => true]);

            activity()
                ->performedOn($year)
                ->causedBy(auth('web')->user())
                ->log('Mengaktifkan Tahun Ajaran: ' . $year->name);

            DB::commit();
            return $this->success(null, 'Tahun ajaran aktif berhasil diubah!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(null, 'Gagal mengaktifkan: ' . $e->getMessage());
        }
    }
}
