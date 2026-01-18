<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.core.permissions.edit', $row->id);
                    $deleteUrl = route('admin.core.permissions.destroy', $row->id);
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-1 btn-modal" data-url="'.$editUrl.'"><i class="bi bi-pencil"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-url="'.$deleteUrl.'" data-name="'.$row->name.'"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Core::permissions.index');
    }

    public function create()
    {
        return view('Core::permissions.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$request->id,
        ]);

        try {
            Permission::updateOrCreate(
                ['id' => $request->id],
                ['name' => $request->name, 'guard_name' => 'web']
            );

            return response()->json(['message' => 'Permission berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $perm = Permission::findById($id);
        return view('Core::permissions.form', compact('perm'));
    }

    public function destroy($id)
    {
        $perm = Permission::findById($id);
        $perm->delete();

        return response()->json(['message' => 'Permission berhasil dihapus!']);
    }
}
