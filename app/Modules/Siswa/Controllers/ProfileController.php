<?php

namespace App\Modules\Siswa\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. Halaman Edit Profil
    public function index()
    {
        $student = Auth::guard('student')->user();

        return view('Siswa::profile.index', compact('student'));
    }

    public function update(Request $request)
    {
        $id = Auth::guard('student')->id();

        $request->validate([
            'email' => 'required|email|unique:core_students,email,'.$id,
            'phone' => 'required|numeric',
        ]);

        $data = [
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at' => now(),
        ];

        DB::table('core_students')->where('id', $id)->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = Auth::guard('student')->user();
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }
        DB::table('core_students')->where('id', $user->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('student')->user();

        if ($request->hasFile('photo')) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $path = $request->file('photo')->store('uploads/students/photos', 'public');
            DB::table('core_students')->where('id', $user->id)->update([
                'photo_path' => $path,
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
