<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $grandTotal = DB::table('du_transactions')
            ->where('status', 'paid')
            ->sum('total_amount');
        $totalPaidStudents = DB::table('du_transactions')
            ->where('status', 'paid')
            ->count();
        $breakdowns = DB::table('du_transactions')
            ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
            ->join('core_majors', 'core_students.major_id', '=', 'core_majors.id')
            ->join('core_classes', 'core_students.current_class_id', '=', 'core_classes.id')
            ->select(
                'core_majors.name as major_name',
                'core_classes.name as current_grade',
                DB::raw('SUM(CASE WHEN du_transactions.status = "paid" THEN du_transactions.total_amount ELSE 0 END) as realized_income'),
                DB::raw('SUM(du_transactions.total_amount) as potential_income'),
                DB::raw('COUNT(CASE WHEN du_transactions.status = "paid" THEN 1 END) as paid_count'),
                DB::raw('COUNT(du_transactions.id) as total_count')
            )
            ->groupBy('core_majors.name', 'core_classes.name')
            ->orderBy('core_majors.name')
            ->orderBy('core_classes.name')
            ->get();

        return view('DaftarUlang::dashboard.index', compact('grandTotal', 'totalPaidStudents', 'breakdowns'));
    }
}
