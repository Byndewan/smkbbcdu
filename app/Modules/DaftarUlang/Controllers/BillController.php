<?php

namespace App\Modules\DaftarUlang\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Major;
use App\Models\SchoolYear;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    use ApiResponse;

    public function index()
    {
        if (request()->ajax()) {
            $data = Bill::with(['major', 'schoolYear'])
                ->restricted()
                ->latest();

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('amount', function ($row) {
                    return 'Rp '.number_format($row->amount, 0, ',', '.');
                })
                ->addColumn('target', function ($row) {
                    $jurusan = $row->major ? $row->major->name : 'Semua Jurusan';
                    $tahun = $row->schoolYear ? $row->schoolYear->name : '-';

                    return $jurusan.' ('.$tahun.')';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Tutup</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-url="'.route('du.bills.edit', $row->id).'" class="btn btn-sm btn-warning btn-modal text-white me-1"><i class="bi bi-pencil"></i></button>';
                    $delBtn = '<button data-url="'.route('du.bills.destroy', $row->id).'" data-name="'.$row->title.'" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></button>';

                    return $editBtn.$delBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('DaftarUlang::bills.index');
    }

    public function create()
    {
        $majors = Major::restricted()->get();
        $years = SchoolYear::where('is_active', true)->get();

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
            'req_names.*' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $bill = Bill::create([
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'target_major_id' => $request->target_major_id,
                'target_school_year_id' => $request->target_school_year_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => true,
            ]);
            if ($request->has('req_names')) {
                foreach ($request->req_names as $name) {
                    if (! empty($name)) {
                        $bill->requirements()->create([
                            'document_name' => $name,
                            'is_mandatory' => true,
                            'file_type' => 'image',
                        ]);
                    }
                }
            }
            DB::commit();
            return $this->success(null, 'Tagihan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->error('Gagal menyimpan: '.$e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $bill = Bill::findOrFail($id);
        $requirements = $bill->requirements;
        $majors = Major::restricted()->get();
        $years = SchoolYear::all();

        return view('DaftarUlang::bills.form', compact('bill', 'requirements', 'majors', 'years'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'target_school_year_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $bill = Bill::findOrFail($id);
            $bill->update([
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'target_major_id' => $request->target_major_id,
                'target_school_year_id' => $request->target_school_year_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $bill->requirements()->delete();
            if ($request->has('req_names')) {
                foreach ($request->req_names as $name) {
                    if (! empty($name)) {
                        $bill->requirements()->create([
                            'document_name' => $name,
                            'is_mandatory' => true,
                            'file_type' => 'image',
                        ]);
                    }
                }
            }

            DB::commit();

            return $this->success(null, 'Tagihan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->error('Gagal update: '.$e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        if ($bill->transactions()->exists()) {
            return $this->error('Gagal! Sudah ada siswa yang membayar tagihan ini. Nonaktifkan saja.', 422);
        }

        $bill->requirements()->delete();
        $bill->delete();

        return $this->success(null, 'Tagihan berhasil dihapus!');
    }
}
