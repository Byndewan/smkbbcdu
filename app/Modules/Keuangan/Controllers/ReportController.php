<?php

namespace App\Modules\Keuangan\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Keuangan\Exports\TransactionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('du_transactions')
                ->join('core_students', 'du_transactions.student_id', '=', 'core_students.id')
                ->join('du_bills', 'du_transactions.du_bill_id', '=', 'du_bills.id')
                ->leftJoin('core_majors', 'core_students.major_id', '=', 'core_majors.id')
                ->select(
                    'du_transactions.*',
                    'core_students.name as student_name',
                    'core_students.nipd',
                    'core_majors.name as major_name',
                    'du_bills.title as bill_title'
                )
                ->where('du_transactions.status', 'paid');
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('du_transactions.updated_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            if ($request->filled('major_id')) {
                $query->where('core_students.major_id', $request->major_id);
            }

            $grandTotal = $query->clone()->sum('total_amount');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('trx_code', function($row){
                    return '<span class="text-muted small">#'.$row->trx_code.'</span>';
                })
                ->editColumn('updated_at', function($row){
                    return date('d/m/Y H:i', strtotime($row->updated_at));
                })
                ->editColumn('student_name', function($row){
                    return '<div class="fw-bold">'.$row->student_name.'</div><small class="text-muted">'.$row->nipd.'</small>';
                })
                ->editColumn('major_name', function($row){
                    return '<span class="badge bg-secondary">'.$row->major_name.'</span>';
                })
                ->editColumn('total_amount', function($row){
                    return '<span class="fw-bold text-end d-block">Rp '.number_format($row->total_amount, 0, ',', '.').'</span>';
                })
                ->rawColumns(['trx_code', 'student_name', 'major_name', 'total_amount'])
                ->with('grandTotal', number_format($grandTotal, 0, ',', '.'))
                ->make(true);
        }

        $majors = DB::table('core_majors')->get();
        return view('Keuangan::report.index', compact('majors'));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $majorId = $request->major_id;
        $fileName = 'Laporan_Keuangan_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new TransactionExport($startDate, $endDate, $majorId), $fileName);
    }
}
