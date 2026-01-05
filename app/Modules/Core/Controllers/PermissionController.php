<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('permissions')
                ->select(['id', 'name', 'guard_name'])
                ->orderBy('id', 'desc')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('core.permissions.edit', $row->id);
                    $deleteUrl = route('core.permissions.destroy', $row->id);

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
        $perm = new Permission;

        return view('Core::permissions.form', compact('permission'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$request->id,
        ]);

        DB::beginTransaction();
        try {
            $perm = Permission::updateOrCreate(
                ['id' => $request->id],
                ['name' => $request->name, 'guard_name' => 'web']
            );

            activity()
                ->performedOn($perm)
                ->causedBy(Auth::user())
                ->withProperties(['name' => $perm->name])
                ->log($request->id ? 'Permission diupdate' : 'Permission baru dibuat');

            DB::commit();

            return response()->json(['message' => 'Permission berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();

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

        activity()
            ->performedOn($perm)
            ->causedBy(Auth::user())
            ->withProperties(['name' => $perm->name])
            ->log('Permission dihapus');

        $perm->delete();

        return response()->json(['message' => 'Permission berhasil dihapus!']);
    }
}
