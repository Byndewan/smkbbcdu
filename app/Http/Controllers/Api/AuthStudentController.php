<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class AuthStudentController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $student = Student::where('email', $request->email)->first();
        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah.'
            ], 401);
        }
        $token = $student->createToken('mobile-app-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'data' => [
                'student' => $student,
                'token' => $token
            ]
        ], 200);
    }
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
