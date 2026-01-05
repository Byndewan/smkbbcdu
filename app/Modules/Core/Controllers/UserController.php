<?php

namespace App\Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['roles', 'major'])
                ->select('users.*')
                ->where('users.id', '!=', 1)
                ->latest()
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->roles->first() ? '<span class="badge bg-info">'.$row->roles->first()->name.'</span>' : '-';
                })
                ->addColumn('major', function ($row) {
                    return $row->major ? $row->major->name : '<span class="badge bg-success">Semua Jurusan</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('core.users.edit', $row->id);
                    $deleteUrl = route('core.users.destroy', $row->id);
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
        $user = new User;

        return view('Core::users.form', compact('roles', 'majors', 'user'));
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

        DB::beginTransaction();
        try {
            $isOperator = $request->has('is_operator') ? true : false;

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'is_operator' => $isOperator,
                'major_id' => $isOperator ? $request->major_id : null,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user = User::updateOrCreate(['id' => $request->id], $data);
            $user->syncRoles([$request->role]);

            activity()
                ->performedOn($user)
                ->causedBy(Auth::user())
                ->withProperties(['is_operator' => $isOperator, 'major_id' => $data['major_id']])
                ->log($request->id ? 'User diupdate' : 'User baru dibuat');

            DB::commit();

            return response()->json(['message' => 'User berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        $majors = Major::all();

        return view('Core::users.form', compact('user', 'roles', 'majors'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus!']);
    }
}
