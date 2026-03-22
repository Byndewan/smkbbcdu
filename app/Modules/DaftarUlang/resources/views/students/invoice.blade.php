@extends('layouts.siswa')

@section('title', 'Kwitansi - ' . $transaction->trx_code)

@section('content')
    <div class="max-w-2xl mx-auto font-sans pb-10">
        <div class="flex justify-between items-center mb-8 px-1 no-print">
            <a href="{{ route('student.history') }}"
                class="w-12 h-12 rounded-xl bg-white border border-black/10 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>

            <button onclick="window.print()"
                class="flex items-center gap-2 px-6 py-3 bg-black text-white rounded-xl font-bold text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,0.2)] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,0.2)] transition-all active:translate-y-[0px] active:shadow-none border border-black">
                <i class="bi bi-printer-fill"></i>
                <span class="hidden sm:inline">Cetak Bukti</span>
            </button>
        </div>

        <div id="receipt-container"
            class="bg-white border-2 border-black rounded-3xl p-8 md:p-10 relative overflow-hidden shadow-[8px_8px_0px_0px_#000] transition-all">

            <div class="absolute inset-0 receipt-pattern opacity-30 pointer-events-none"></div>

            <div
                class="relative z-10 border-b-2 border-dashed border-black/20 pb-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-black text-white flex items-center justify-center rounded-xl text-2xl">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h2 class="font-display font-bold text-2xl text-black leading-none">BBC PAY</h2>
                        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mt-1">Official Receipt</p>
                    </div>
                </div>

                <div class="text-left md:text-right">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kode
                        Transaksi</span>
                    <span
                        class="font-mono font-bold text-lg bg-gray-100 px-3 py-1 rounded border border-black/10 block w-fit md:ml-auto">
                        #{{ $transaction->trx_code }}
                    </span>
                </div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-4 mb-8">

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Diterima Dari</p>
                    <h3 class="font-display font-bold text-xl text-black">{{ $student->name }}</h3>
                    <p class="text-sm font-mono text-slate-500 mt-1">{{ $student->nisn ?? 'NIPD: ' . $student->nipd }}</p>
                </div>

                <div class="text-left md:text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal & Waktu</p>
                    <p class="font-bold text-black text-lg">
                        {{ date('d F Y', strtotime($transaction->payment_date)) }}
                    </p>
                    <p class="text-sm font-mono text-slate-500 mt-1">
                        {{ date('H:i', strtotime($transaction->created_at)) }} WIB
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Untuk Pembayaran</p>
                    <div class="p-4 bg-gray-50 border border-black/5 rounded-xl flex items-center justify-between">
                        <span class="font-bold text-slate-700">{{ $transaction->bill->title ?? 'Tagihan Sekolah' }}</span>
                        <span class="font-mono text-sm text-slate-500">
                            {{ $transaction->payment_method == 'manual' ? 'Transfer Bank' : 'Virtual Account' }}
                        </span>
                    </div>
                </div>

            </div>

            <div
                class="relative z-10 border-t-2 border-black pt-8 flex flex-col md:flex-row justify-between items-center gap-8">

                <div class="order-2 md:order-1">
                    <div class="border-4 border-green-600 text-green-600 px-4 py-2 rounded-lg font-black text-2xl uppercase tracking-widest -rotate-12 opacity-80 mix-blend-multiply"
                        style="font-family: 'Courier New', Courier, monospace;">
                        LUNAS / PAID
                    </div>
                </div>

                <div class="text-center md:text-right order-1 md:order-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Jumlah</p>
                    <h1 class="font-display font-black text-4xl md:text-5xl text-black tracking-tight">
                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </h1>
                </div>

            </div>

            <div class="mt-12 flex justify-between items-end relative z-10 opacity-60">
                <div class="text-[10px] font-mono text-slate-400 w-1/2">
                    <p>Dicetak otomatis oleh sistem.</p>
                    <p>Sah tanpa tanda tangan basah.</p>
                </div>
                <div class="text-center">
                    <div class="h-10 w-24 border-b border-black mb-1"></div>
                    <p class="text-[10px] font-bold uppercase tracking-widest">Admin Keuangan</p>
                </div>
            </div>

        </div>

    </div>
@endsection
