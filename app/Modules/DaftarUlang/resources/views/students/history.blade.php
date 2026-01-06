<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembayaran</title>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans pb-20">

    <nav class="bg-white shadow-sm px-4 py-3 sticky top-0 z-50">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Riwayat Pembayaran</h1>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-6 px-4">

        @if ($transactions->isEmpty())
            <div class="text-center py-10">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-gray-800 font-bold mb-1">Belum ada riwayat</h3>
                <p class="text-gray-500 text-sm">Kamu belum melakukan transaksi apapun.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($transactions as $trx)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">

                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $trx->bill_title }}</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ date('d M Y H:i', strtotime($trx->updated_at)) }}</p>
                            </div>

                            @if ($trx->status == 'paid')
                                <span
                                    class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">LUNAS</span>
                            @elseif(in_array($trx->status, ['pending_docs', 'payment_review']))
                                <span
                                    class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">DIPROSES</span>
                            @elseif(in_array($trx->status, ['doc_rejected', 'payment_rejected']))
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">PERLU
                                    REVISI</span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">DRAFT</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Nominal</p>
                            <p class="text-lg font-bold text-gray-800">Rp
                                {{ number_format($trx->total_amount > 0 ? $trx->total_amount : $trx->bill_amount, 0, ',', '.') }}
                            </p>
                        </div>

                        @if ($trx->status == 'doc_rejected')
                            <div class="bg-red-50 p-3 rounded-lg mb-3">
                                <p class="text-xs text-red-600 font-semibold mb-1">Dokumen Ditolak:</p>
                                <p class="text-xs text-red-700 line-clamp-2">{{ $trx->admin_note }}</p>
                            </div>
                            <a href="{{ route('student.bills.show', $trx->du_bill_id) }}"
                                class="block w-full text-center bg-red-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition">
                                Perbaiki Dokumen
                            </a>
                        @elseif ($trx->status == 'payment_rejected')
                            <div class="bg-red-50 p-3 rounded-lg mb-3">
                                <p class="text-xs text-red-600 font-semibold mb-1">Pembayaran Ditolak:</p>
                                <p class="text-xs text-red-700 line-clamp-2">{{ $trx->admin_note }}</p>
                            </div>
                            <a href="{{ route('student.bills.payment', $trx->du_bill_id) }}"
                                class="block w-full text-center bg-red-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition">
                                Perbaiki Data Pembayaran
                            </a>
                        @elseif($trx->status == 'paid')
                            <a href="{{ route('student.bills.invoice', $trx->du_bill_id) }}"
                                class="block w-full text-center bg-green-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm">
                                <i class="bi bi-receipt me-1"></i> Lihat Kwitansi
                            </a>
                        @elseif($trx->status == 'draft')
                            <a href="{{ route('student.bills.show', $trx->du_bill_id) }}"
                                class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                                Lanjut Bayar
                            </a>
                        @endif

                    </div>
                @endforeach
            </div>

        @endif

    </div>

</body>

</html>
