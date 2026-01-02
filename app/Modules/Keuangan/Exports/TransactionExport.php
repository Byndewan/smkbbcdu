<?php

namespace App\Modules\Keuangan\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $majorId;
    public function __construct($startDate, $endDate, $majorId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->majorId = $majorId;
    }

    public function collection()
    {
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

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('du_transactions.updated_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);
        }

        if ($this->majorId) {
            $query->where('core_students.major_id', $this->majorId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Referensi',
            'Tanggal Bayar',
            'NIPD',
            'Nama Siswa',
            'Jurusan',
            'Tagihan',
            'Metode Bayar',
            'Nominal',
            'Status'
        ];
    }

    public function map($row): array
    {
        return [
            $row->trx_code,
            date('d-m-Y H:i', strtotime($row->updated_at)),
            $row->nipd,
            $row->student_name,
            $row->major_name,
            $row->bill_title,
            strtoupper($row->payment_method),
            $row->total_amount,
            strtoupper($row->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
