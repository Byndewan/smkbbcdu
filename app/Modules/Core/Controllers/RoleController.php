<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('roles')
                ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->select('roles.id', 'roles.name', DB::raw('GROUP_CONCAT(permissions.name) as permission_names'))
                ->where('roles.name', '!=', 'SuperAdmin')
                ->groupBy('roles.id', 'roles.name')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {
                    if (! $row->permission_names) {
                        return '<span class="text-muted small">No Permissions</span>';
                    }
                    $badges = '';
                    $perms = explode(',', $row->permission_names);

                    foreach ($perms as $perm) {
                        $badges .= '<span class="badge bg-primary me-1 mb-1">'.$perm.'</span>';
                    }

                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('core.roles.edit', $row->id);
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-1 btn-modal" data-url="'.$editUrl.'"><i class="bi bi-pencil"></i></button>';
                    $deleteUrl = route('core.roles.destroy', $row->id);
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-url="'.$deleteUrl.'" data-name="'.$row->name.'"><i class="bi bi-trash"></i></button>';
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
        $role = new Role;

        return view('Core::roles.form', compact('permissions', 'role'));
    }

    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();

        return view('Core::roles.form', compact('role', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$request->id,
            'permissions' => 'array',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::updateOrCreate(
                ['id' => $request->id],
                ['name' => $request->name, 'guard_name' => 'web']
            );

            $role->syncPermissions($request->permissions);

            activity()
                ->performedOn($role)
                ->causedBy(Auth::user())
                ->withProperties(['name' => $role->name])
                ->log($request->id ? 'Role diupdate' : 'Role baru dibuat');

            DB::commit();

            return response()->json(['message' => 'Role berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::findById($id);

        activity()
            ->performedOn($role)
            ->causedBy(Auth::user())
            ->withProperties(['name' => $role->name])
            ->log('Role dihapus oleh '.Auth::user()->name);

        $role->delete();

        return response()->json([
            'message' => 'Role berhasil dihapus!',
        ]);
    }
}
