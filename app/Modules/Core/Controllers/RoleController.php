<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')
                ->where('name', '!=', 'SuperAdmin')
                ->latest()
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {
                    if ($row->permissions->isEmpty()) {
                        return '<span class="text-muted small">No Permissions</span>';
                    }

                    return $row->permissions->map(function ($perm) {
                        return '<span class="badge bg-primary me-1 mb-1">' . $perm->name . '</span>';
                    })->join(' ');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.core.roles.edit', $row->id);
                    $deleteUrl = route('admin.core.roles.destroy', $row->id);
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-1 btn-modal" data-url="' . $editUrl . '"><i class="bi bi-pencil"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-url="' . $deleteUrl . '" data-name="' . $row->name . '"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }

        return view('Core::roles.index');
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('Core::roles.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $request->id,
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::updateOrCreate(
                ['id' => $request->id],
                ['name' => $request->name, 'guard_name' => 'web']
            );
            $role->syncPermissions($request->permissions);
            DB::commit();
            return response()->json(['message' => 'Role berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('Core::roles.form', compact('role', 'permissions'));
    }

    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();

        return response()->json(['message' => 'Role berhasil dihapus!']);
    }
}
