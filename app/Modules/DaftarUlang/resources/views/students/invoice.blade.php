<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kwitansi - {{ $transaction->trx_code }}</title>
    @vite(['resources/css/student.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .card {
                box-shadow: none;
                border: 2px solid #000;
            }

        }

        .pattern-bg {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center py-10 px-4 pattern-bg">

    <div class="max-w-2xl w-full">

        <div class="flex justify-between items-center mb-6 no-print">
            <a href="{{ route('student.history') }}"
                class="text-gray-500 hover:text-gray-800 flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <button onclick="window.print()"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold shadow hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-lg border-t-8 border-blue-600 relative overflow-hidden card">

            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <span
                    class="text-9xl font-black text-green-600 border-8 border-green-600 p-4 rounded-xl -rotate-12 block">LUNAS</span>
            </div>

            <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">KWITANSI PEMBAYARAN</h1>
                    <p class="text-sm text-gray-500 mt-1">SMK Budi Bakti Ciwidey</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">No. Referensi</p>
                    <p class="font-mono font-bold text-gray-800 text-lg">{{ $transaction->trx_code }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Diterima Dari</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $student->name }}</p>
                    <p class="text-sm text-gray-600">{{ $student->nisn ?? 'NIPD: ' . $student->nipd }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Tanggal Pembayaran</p>
                    <p class="font-bold text-gray-800">{{ date('d F Y', strtotime($transaction->payment_date)) }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $transaction->payment_method == 'manual' ? 'Transfer Bank' : 'Virtual Account' }}</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Untuk Pembayaran</p>
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-800 text-lg">{{ $bill->title }}</span>
                    <span class="font-bold text-gray-800 text-xl">Rp
                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex justify-between items-end mt-12 pt-6 border-t border-gray-200">
                <div class="text-xs text-gray-400">
                    <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
                    <p>Dokumen ini sah dan diterbitkan secara elektronik.</p>
                </div>
                <div class="text-center">
                    <div class="h-16 flex items-end justify-center mb-2">
                        <span class="font-serif italic text-gray-400 text-2xl">Verified System</span>
                    </div>
                    <p class="text-sm font-bold text-gray-700">Bagian Keuangan</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
