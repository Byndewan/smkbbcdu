<?php

namespace App\Modules\Siswa\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        return view('Siswa::profile.index', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'email' => 'required|email|unique:core_students,email,' . $student->id,
            'phone' => 'required|numeric',
        ]);

        $student->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $student = Auth::guard('student')->user();

        if (! Hash::check($request->current_password, $student->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $student->update([
            'password' => $request->password,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updatePhoto(Request $request, FileService $fileService)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student = Auth::guard('student')->user();

        if ($request->hasFile('photo')) {
            if ($student->photo_path) {
                $fileService->delete($student->photo_path);
            }
            $path = $fileService->uploadStudentFile(
                $request->file('photo'),
                $student,
                'profile'
            );
            $student->update(['photo_path' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
