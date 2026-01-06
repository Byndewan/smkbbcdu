<?php

namespace App\Modules\Siswa\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    public function showForm()
    {
        $student = Auth::guard('student')->user();

        return view('Siswa::profile.complete', compact('student'));
    }

    public function store(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'email' => 'required|email|unique:core_students,email,'.$student->id,
            'phone' => 'required|numeric|digits_between:10,13',
            'address' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        // Eloquent Update
        $student->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => $request->password,
            'is_profile_completed' => true,
        ]);

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Data berhasil diperbarui! Silakan login kembali dengan Password Baru Anda.');
    }
}
