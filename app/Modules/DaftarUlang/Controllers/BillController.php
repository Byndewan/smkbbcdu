<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponse;

class BillController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = DB::table('du_bills')
                ->join('core_school_years', 'du_bills.target_school_year_id', '=', 'core_school_years.id')
                ->leftJoin('core_majors', 'du_bills.target_major_id', '=', 'core_majors.id')
                ->select('du_bills.*', 'core_school_years.name as year_name', 'core_majors.name as major_name')
                ->orderBy('du_bills.id', 'desc');

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('amount', function($row){
                    return 'Rp ' . number_format($row->amount, 0, ',', '.');
                })
                ->addColumn('target', function($row){
                    $jurusan = $row->major_name ?? 'Semua Jurusan';
                    return $jurusan . ' (' . $row->year_name . ')';
                })
                ->addColumn('status', function($row){
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Tutup</span>';
                })
                ->addColumn('action', function($row){
                    $editBtn = '<button data-url="'.route('du.bills.edit', $row->id).'" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="'.route('du.bills.destroy', $row->id).'" data-name="'.$row->title.'" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $delBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('DaftarUlang::bills.index');
    }

    public function create()
    {
        $majors = DB::table('core_majors')->get();
        $years = DB::table('core_school_years')->where('is_active', true)->get();

        return view('DaftarUlang::bills.form', compact('majors', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'target_school_year_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'req_names.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $billId = DB::table('du_bills')->insertGetId([
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'target_major_id' => $request->target_major_id,
                'target_school_year_id' => $request->target_school_year_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => true,
                'created_at' => now(),
            ]);

            if ($request->has('req_names')) {
                $requirements = [];
                foreach ($request->req_names as $index => $name) {
                    if(!empty($name)) {
                        $requirements[] = [
                            'du_bill_id' => $billId,
                            'document_name' => $name,
                            'is_mandatory' => true,
                            'file_type' => 'image',
                            'created_at' => now()
                        ];
                    }
                }

                if(count($requirements) > 0) {
                    DB::table('du_bill_requirements')->insert($requirements);
                }
            }

            DB::commit();
            return $this->success(null, 'Tagihan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal menyimpan: ' . $e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $bill = DB::table('du_bills')->where('id', $id)->first();
        $requirements = DB::table('du_bill_requirements')->where('du_bill_id', $id)->get();

        $majors = DB::table('core_majors')->get();
        $years = DB::table('core_school_years')->get();

        return view('DaftarUlang::bills.form', compact('bill', 'requirements', 'majors', 'years'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            DB::table('du_bills')->where('id', $id)->update([
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'target_major_id' => $request->target_major_id,
                'target_school_year_id' => $request->target_school_year_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated_at' => now(),
            ]);

            DB::table('du_bill_requirements')->where('du_bill_id', $id)->delete();

            if ($request->has('req_names')) {
                $requirements = [];
                foreach ($request->req_names as $name) {
                    if(!empty($name)) {
                        $requirements[] = [
                            'du_bill_id' => $id,
                            'document_name' => $name,
                            'is_mandatory' => true,
                            'file_type' => 'image',
                            'created_at' => now()
                        ];
                    }
                }
                if(count($requirements) > 0) {
                    DB::table('du_bill_requirements')->insert($requirements);
                }
            }

            DB::commit();
            return $this->success(null, 'Tagihan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Gagal update: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $hasTransaction = DB::table('du_transactions')->where('du_bill_id', $id)->exists();

        if ($hasTransaction) {
            return $this->error('Gagal! Sudah ada siswa yang membayar tagihan ini. Nonaktifkan saja.', 422);
        }

        DB::table('du_bill_requirements')->where('du_bill_id', $id)->delete();
        DB::table('du_bills')->where('id', $id)->delete();

        return $this->success(null, 'Tagihan berhasil dihapus!');
    }
}
