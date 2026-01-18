<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['roles', 'major'])
                ->where('id', '!=', 1)
                ->latest()
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->roles->first()
                        ? '<span class="badge bg-info">'.$row->roles->first()->name.'</span>'
                        : '-';
                })
                ->addColumn('major', function ($row) {
                    return $row->major
                        ? $row->major->name
                        : '<span class="badge bg-success">Semua Jurusan</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.core.users.edit', $row->id);
                    $deleteUrl = route('admin.core.users.destroy', $row->id);
                    $btn = '<button type="button" class="btn btn-sm btn-warning me-1 btn-modal" data-url="'.$editUrl.'"><i class="bi bi-pencil"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-delete" data-url="'.$deleteUrl.'" data-name="'.$row->name.'"><i class="bi bi-trash"></i></button>';

                    return $btn;
                })
                ->rawColumns(['role', 'major', 'action'])
                ->make(true);
        }

        return view('Core::users.index');
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        $majors = Major::all();

        return view('Core::users.form', compact('roles', 'majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required',
            'major_id' => 'nullable|required_if:is_operator,1|exists:core_majors,id',
        ]);

        try {
            $isOperator = $request->has('is_operator') ? true : false;
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'is_operator' => $isOperator,
                'major_id' => $isOperator ? $request->major_id : null,
            ];

            if ($request->password) {
                $data['password'] = $request->password;
            }

            $user = User::updateOrCreate(['id' => $request->id], $data);
            $user->syncRoles([$request->role]);

            return $this->success(null, 'User berhasil disimpan!');

        } catch (\Exception $e) {
            return $this->error('Gagal: '.$e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        $majors = Major::all();

        return view('Core::users.form', compact('user', 'roles', 'majors'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == 1) {
            return $this->error(null, 'SuperAdmin tidak boleh dihapus!');
        }

        $user->delete();

        return $this->success(null, 'User berhasil dihapus!');
    }
}
