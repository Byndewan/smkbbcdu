<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DuTransaction;
use App\Models\Major;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $grandTotal = DuTransaction::restricted()
            ->where('status', 'paid')
            ->sum('total_amount');
        $totalPaidStudents = DuTransaction::restricted()
            ->where('status', 'paid')
            ->count();
        $major = null;
        if (Auth::user()->is_operator && Auth::user()->major_id) {
            $major = Major::find(Auth::user()->major_id);
        }
        return view('DaftarUlang::dashboard', compact('grandTotal', 'totalPaidStudents', 'major'));
    }

    public function getDetailData()
    {
        $breakdowns = DuTransaction::query()
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('core_majors', 'core_students.major_id', '=', 'core_majors.id')
            ->join('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
            ->select(
                'core_majors.name as major_name',
                'core_classes.name as current_grade',
                DB::raw('COALESCE(SUM(CASE WHEN du_transactions.status = "paid" THEN du_transactions.total_amount ELSE 0 END), 0) as realized_income'),
                DB::raw('COALESCE(SUM(du_transactions.total_amount), 0) as potential_income'),
                DB::raw('COUNT(DISTINCT CASE WHEN du_transactions.status = "paid" THEN du_transactions.student_id END) as paid_count'),
                DB::raw('COUNT(DISTINCT du_transactions.student_id) as total_count')
            )
            ->where(function ($q) {
                if (Auth::user()->is_operator && Auth::user()->major_id) {
                    $q->where('core_students.major_id', Auth::user()->major_id);
                }
            })
            ->whereNull('du_transactions.deleted_at')
            ->groupBy('core_majors.name', 'core_classes.name')
            ->orderBy('core_majors.name')
            ->orderBy('core_classes.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $breakdowns
        ]);
    }

    public function indexFinance()
    {
        $grandTotal = DuTransaction::restricted()
            ->where('status', 'paid')
            ->sum('total_amount');
        $totalPaidStudents = DuTransaction::restricted()
            ->where('status', 'paid')
            ->count();
        $major = null;
        if (Auth::user()->is_operator && Auth::user()->major_id) {
            $major = Major::find(Auth::user()->major_id);
        }
        return view('Keuangan::dashboard', compact('grandTotal', 'totalPaidStudents', 'major'));
    }

    public function getDetailDataFinance()
    {
        $breakdowns = DuTransaction::query()
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('core_majors', 'core_students.major_id', '=', 'core_majors.id')
            ->join('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
            ->select(
                'core_majors.name as major_name',
                'core_classes.name as current_grade',
                DB::raw('COALESCE(SUM(CASE WHEN du_transactions.status = "paid" THEN du_transactions.total_amount ELSE 0 END), 0) as realized_income'),
                DB::raw('COALESCE(SUM(du_transactions.total_amount), 0) as potential_income'),
                DB::raw('COUNT(DISTINCT CASE WHEN du_transactions.status = "paid" THEN du_transactions.student_id END) as paid_count'),
                DB::raw('COUNT(DISTINCT du_transactions.student_id) as total_count')
            )
            ->where(function ($q) {
                if (Auth::user()->is_operator && Auth::user()->major_id) {
                    $q->where('core_students.major_id', Auth::user()->major_id);
                }
            })
            ->whereNull('du_transactions.deleted_at')
            ->groupBy('core_majors.name', 'core_classes.name')
            ->orderBy('core_majors.name')
            ->orderBy('core_classes.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $breakdowns
        ]);
    }

    public function indexCore()
    {
        $grandTotal = DuTransaction::restricted()
            ->where('status', 'paid')
            ->sum('total_amount');
        $totalPaidStudents = DuTransaction::restricted()
            ->where('status', 'paid')
            ->count();
        $major = null;
        if (Auth::user()->is_operator && Auth::user()->major_id) {
            $major = Major::find(Auth::user()->major_id);
        }
        return view('Core::dashboard', compact('grandTotal', 'totalPaidStudents', 'major'));
    }

    public function getDetailDataCore()
    {
        $breakdowns = DuTransaction::query()
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('core_majors', 'core_students.major_id', '=', 'core_majors.id')
            ->join('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
            ->select(
                'core_majors.name as major_name',
                'core_classes.name as current_grade',
                DB::raw('COALESCE(SUM(CASE WHEN du_transactions.status = "paid" THEN du_transactions.total_amount ELSE 0 END), 0) as realized_income'),
                DB::raw('COALESCE(SUM(du_transactions.total_amount), 0) as potential_income'),
                DB::raw('COUNT(DISTINCT CASE WHEN du_transactions.status = "paid" THEN du_transactions.student_id END) as paid_count'),
                DB::raw('COUNT(DISTINCT du_transactions.student_id) as total_count')
            )
            ->where(function ($q) {
                if (Auth::user()->is_operator && Auth::user()->major_id) {
                    $q->where('core_students.major_id', Auth::user()->major_id);
                }
            })
            ->whereNull('du_transactions.deleted_at')
            ->groupBy('core_majors.name', 'core_classes.name')
            ->orderBy('core_majors.name')
            ->orderBy('core_classes.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $breakdowns
        ]);
    }
}
