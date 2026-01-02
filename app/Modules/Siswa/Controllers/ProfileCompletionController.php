<?php

namespace App\Modules\Siswa\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileCompletionController extends Controller
{
    public function showForm()
    {
        $student = Auth::guard('student')->user();
        return view('Siswa::profile.complete', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:core_students,email,' . Auth::guard('student')->id(),
            'phone' => 'required|numeric|digits_between:10,13',
            'address' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $studentId = Auth::guard('student')->id();

        DB::table('core_students')->where('id', $studentId)->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'is_profile_completed' => true,
            'updated_at' => now(),
        ]);

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Data berhasil diperbarui! Silakan login kembali dengan Password Baru Anda.');
    }
}
