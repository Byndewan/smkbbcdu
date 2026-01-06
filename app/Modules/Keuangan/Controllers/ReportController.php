<?php

namespace App\Modules\Keuangan\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DuTransaction;
use App\Models\Major;
use App\Modules\Keuangan\Exports\TransactionExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = DuTransaction::with(['student.major', 'bill'])
                ->restricted()
                ->where('status', 'paid');
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('updated_at', [
                    $request->start_date.' 00:00:00',
                    $request->end_date.' 23:59:59',
                ]);
            }

            if ($request->filled('major_id')) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('major_id', $request->major_id);
                });
            }
            $grandTotal = $query->clone()->sum('total_amount');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('trx_code', function ($row) {
                    return '<span class="text-muted small">#'.$row->trx_code.'</span>';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d/m/Y H:i');
                })
                ->addColumn('student_name', function ($row) {
                    return '<div class="fw-bold">'.$row->student->name.'</div><small class="text-muted">'.$row->student->nipd.'</small>';
                })
                ->addColumn('major_name', function ($row) {
                    return $row->student && $row->student->major
                        ? '<span class="badge bg-secondary">'.$row->student->major->name.'</span>'
                        : '-';
                })
                ->editColumn('total_amount', function ($row) {
                    return '<span class="fw-bold text-end d-block">Rp '.number_format($row->total_amount, 0, ',', '.').'</span>';
                })
                ->addColumn('bill_title', function ($row) {
                    return $row->bill->title ?? '-';
                })
                ->rawColumns(['trx_code', 'student_name', 'major_name', 'total_amount'])
                ->with('grandTotal', number_format($grandTotal, 0, ',', '.'))
                ->make(true);
        }

        $majors = Major::restricted()->get();

        return view('Keuangan::report.index', compact('majors'));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $majorId = $request->major_id;
        $fileName = 'Laporan_Keuangan_'.date('Y-m-d_H-i').'.xlsx';
        return Excel::download(new TransactionExport($startDate, $endDate, $majorId), $fileName);
    }
}
