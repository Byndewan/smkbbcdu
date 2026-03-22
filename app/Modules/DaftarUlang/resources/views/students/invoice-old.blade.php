@extends('layouts.siswa')

@section('title', ' Kwitansi - '.$transaction->trx_code)

@push('styles')
    <style>
        .font-mono {
            font-family: 'Space Mono', monospace;
        }

        .ticket::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 20px;
            background: linear-gradient(45deg, transparent 50%, #F2F4F8 50%),
                linear-gradient(-45deg, transparent 50%, #F2F4F8 50%);
            background-size: 20px 20px;
            background-repeat: repeat-x;
            transition: background 0.3s;
        }

        .dark .ticket::after {
            background: linear-gradient(45deg, transparent 50%, #1a1c23 50%),
                linear-gradient(-45deg, transparent 50%, #1a1c23 50%);
        }

        @media print {
            body {
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .ticket {
                box-shadow: none;
                border: 2px solid #000;
                margin: 0;
                width: 100%;
                max-width: none;
            }

            .ticket::after {
                display: none;
            }

            .dark .ticket {
                background: white;
                color: black;
            }

            nav {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full">
        <div class="w-full max-w-2xl mx-auto flex justify-between items-center mb-8 no-print animate-fade-in-down">

            <a href="{{ route('student.history') }}"
                class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-[#24262d] text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-sm border border-slate-200 dark:border-slate-700">
                <i class="bi bi-arrow-left"></i> <span class="hidden sm:inline">Kembali</span>
            </a>

            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-full bg-[var(--bbc-hex)] text-white font-bold text-sm shadow-lg shadow-red-200 dark:shadow-none hover:brightness-110 transition hover:-translate-y-1">
                    <i class="bi bi-printer-fill"></i> <span class="hidden sm:inline">Cetak</span>
                </button>
            </div>
        </div>

        <div
            class="ticket relative w-full max-w-2xl mx-auto bg-white dark:bg-[#24262d] rounded-3xl shadow-2xl overflow-hidden mb-10 border border-slate-100 dark:border-slate-700 transition-colors duration-300">

            <div class="bg-[var(--bbc-hex)] p-8 text-center relative overflow-hidden">
                <div
                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                    <span
                        class="text-9xl font-black text-white border-8 border-white p-4 rounded-xl -rotate-12 block uppercase tracking-widest">LUNAS</span>
                </div>

                <div class="relative z-10 text-white">
                    <div
                        class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner border border-white/30">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <h1 class="text-2xl font-[900] tracking-wide uppercase mb-1">Kwitansi Pembayaran</h1>
                    <p class="text-white/90 text-sm font-medium">SMK Budi Bakti Ciwidey</p>
                </div>

                <div class="absolute inset-0 opacity-20"
                    style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 10px 10px;">
                </div>
            </div>

            <div class="p-8 sm:p-10">

                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-dashed border-slate-200 dark:border-slate-700 pb-6 mb-6 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Diterima Dari</p>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $student->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">
                            {{ $student->nisn ?? 'NIPD: ' . $student->nipd }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">No. Referensi</p>
                        <span
                            class="font-mono font-bold text-slate-700 dark:text-slate-200 text-lg bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg">
                            {{ $transaction->trx_code }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Tanggal Bayar</p>
                        <p class="font-bold text-slate-700 dark:text-slate-300">
                            {{ date('d F Y', strtotime($transaction->payment_date)) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">
                            Pukul {{ date('H:i', strtotime($transaction->created_at)) }} WIB
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Metode Bayar</p>
                        <p class="font-bold text-slate-700 dark:text-slate-300 uppercase">
                            {{ $transaction->payment_method == 'manual' ? 'Transfer Bank' : 'Virtual Account' }}
                        </p>
                        @if ($transaction->payment_method == 'manual')
                            <p class="text-xs text-slate-500 dark:text-slate-500 mt-0.5">{{ $transaction->bank_sender }}</p>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1">Pembayaran Untuk</p>
                        <p class="font-bold text-slate-800 dark:text-white text-lg">{{ $bill->title }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-2xl font-[900] text-[var(--bbc-hex)]">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row justify-between items-end mt-10 pt-6 border-t border-slate-100 dark:border-slate-700 gap-6">
                    <div class="text-xs text-slate-400 text-center sm:text-left">
                        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
                        <p class="mt-1">Dokumen ini sah dan diterbitkan secara elektronik oleh sistem.</p>
                    </div>
                    <div class="text-center">
                        <div
                            class="h-16 w-32 border-2 border-slate-300 dark:border-slate-600 rounded-lg flex items-center justify-center mb-2 mx-auto sm:mx-0 opacity-50 rotate-[-5deg]">
                            <span class="font-mono font-bold text-slate-400 text-xs tracking-widest">VERIFIED</span>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Bagian
                            Keuangan</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
