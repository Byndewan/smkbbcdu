<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStudentProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('student')->user();

        if ($user) {
            $isDefaultEmail = str_ends_with($user->email, '@siswa.bbc');
            if (
                !$user->is_profile_completed ||
                $isDefaultEmail ||
                empty($user->phone) ||
                empty($user->address)
            ) {
                if (!$request->routeIs('student.profile.complete') &&
                    !$request->routeIs('student.profile.store') &&
                    !$request->routeIs('student.logout')
                ) {
                    return redirect()->route('student.profile.complete')
                        ->with('warning', 'Demi keamanan, mohon lengkapi data diri dan ganti password Anda terlebih dahulu.');
                }
            }
        }

        return $next($request);
    }
}
