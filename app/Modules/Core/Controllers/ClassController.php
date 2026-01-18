<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ClassController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = SchoolClass::with('major')
                ->restricted()
                ->orderBy('code', 'asc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('major_abb', function($row){
                    return $row->major ? $row->major->abbreviation : '-';
                })
                ->addColumn('action', function($row){
                    $editBtn = '<button data-url="'.route('admin.core.classes.edit', $row->id).'" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="'.route('admin.core.classes.destroy', $row->id).'" data-name="'.$row->name.'" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $delBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Core::classes.index');
    }

    public function create()
    {
        $majors = Major::restricted()->orderBy('name')->get();
        return view('Core::classes.form', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:core_classes,code',
            'name' => 'required',
            'major_id' => 'required|exists:core_majors,id'
        ]);

        SchoolClass::create([
            'code' => $request->code,
            'name' => $request->name,
            'major_id' => $request->major_id,
        ]);

        return $this->success(null, 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = SchoolClass::findOrFail($id);
        $majors = Major::restricted()->orderBy('name')->get();

        return view('Core::classes.form', compact('data', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:core_classes,code,'.$id,
            'name' => 'required',
            'major_id' => 'required|exists:core_majors,id'
        ]);

        $class = SchoolClass::findOrFail($id);

        $class->update([
            'code' => $request->code,
            'name' => $request->name,
            'major_id' => $request->major_id,
        ]);

        return $this->success(null, 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);

        if($class->students()->exists()) {
            return $this->error('Gagal! Kelas masih ada siswanya.', 422);
        }

        $class->delete();

        return $this->success(null, 'Data berhasil dihapus!');
    }
}
