<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class MajorController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = DB::table('core_majors')->select('*');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-url="' . route('core.majors.edit', $row->id) . '" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="' . route('core.majors.destroy', $row->id) . '" data-name="' . $row->name . '" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $delBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Core::majors.index');
    }

    public function create()
    {
        return view('Core::majors.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:core_majors,code',
            'name' => 'required',
            'abbreviation' => 'required'
        ]);

        DB::table('core_majors')->insert([
            'code' => $request->code,
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'description' => $request->description,
            'created_at' => now()
        ]);

        return $this->success(null, 'Jurusan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = DB::table('core_majors')->where('id', $id)->first();

        if (!$data) {
            return '<div class="p-3 text-danger">Data tidak ditemukan</div>';
        }

        return view('Core::majors.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:core_majors,code,' . $id,
            'name' => 'required',
            'abbreviation' => 'required'
        ]);
        DB::table('core_majors')->where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'description' => $request->description,
            'updated_at' => now()
        ]);

        return $this->success(null, 'Data jurusan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $isUsed = DB::table('core_classes')->where('major_id', $id)->exists();
        if ($isUsed) {
            return $this->error('Gagal hapus! Jurusan ini sedang digunakan oleh Kelas.', 422);
        }
        DB::table('core_majors')->where('id', $id)->delete();

        return $this->success(null, 'Data jurusan berhasil dihapus!');
    }
}
