<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class ClassController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = DB::table('core_classes')
                ->join('core_majors', 'core_classes.major_id', '=', 'core_majors.id')
                ->select('core_classes.*', 'core_majors.abbreviation as major_abb')
                ->restrictMajor('core_majors.id')
                ->orderBy('core_classes.code', 'asc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '<button data-url="'.route('core.classes.edit', $row->id).'" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="'.route('core.classes.destroy', $row->id).'" data-name="'.$row->name.'" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $delBtn;
                })
                ->make(true);
        }

        return view('Core::classes.index');
    }

    public function create()
    {
        $majors = DB::table('core_majors')->orderBy('name')->get();
        return view('Core::classes.form', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:core_classes,code',
            'name' => 'required',
            'major_id' => 'required|exists:core_majors,id'
        ]);

        DB::table('core_classes')->insert([
            'code' => $request->code,
            'name' => $request->name,
            'major_id' => $request->major_id,
            'created_at' => now()
        ]);

        return $this->success(null, 'Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = DB::table('core_classes')->where('id', $id)->first();
        $majors = DB::table('core_majors')->orderBy('name')->get();
        return view('Core::classes.form', compact('data', 'majors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:core_classes,code,'.$id,
            'name' => 'required',
            'major_id' => 'required|exists:core_majors,id'
        ]);

        DB::table('core_classes')->where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'major_id' => $request->major_id,
            'updated_at' => now()
        ]);

        return $this->success(null, 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $isUsed = DB::table('core_students')->where('current_class_id', $id)->exists();
        if($isUsed) return $this->error('Gagal! Kelas masih ada siswanya.', 422);

        DB::table('core_classes')->where('id', $id)->delete();
        return $this->success(null, 'Data berhasil dihapus!');
    }
}
