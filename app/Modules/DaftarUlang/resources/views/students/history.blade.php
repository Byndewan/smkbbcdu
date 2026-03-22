@extends('layouts.siswa')

@section('title', 'Riwayat Transaksi')

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8 px-1">
            <a href="{{ route('student.dashboard') }}"
                class="w-10 h-10 rounded-xl bg-white border border-black/10 flex items-center justify-center text-slate-500 hover:bg-black hover:text-white transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-tight">Riwayat Transaksi</h1>
        </div>

        @if ($transactions->isEmpty())
            <div class="bg-white border border-black/10 rounded-3xl p-12 text-center shadow-sm">
                <div class="w-20 h-20 bg-gray-50 border border-black/5 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl text-gray-300">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h3 class="font-display font-bold text-lg text-slate-800 dark:text-slate-300">Belum ada riwayat</h3>
                <p class="text-sm text-slate-400 font-sans">Transaksi kamu akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($transactions as $trx)
                    <div class="bg-white border border-black/10 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all hover:border-black/30 hover:shadow-sm group">

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl border flex items-center justify-center text-lg shrink-0
                                {{ $trx->status == 'paid'
                                    ? 'bg-green-50 border-green-200 text-green-600'
                                    : (in_array($trx->status, ['doc_rejected', 'payment_rejected'])
                                        ? 'bg-red-50 border-red-200 text-red-600'
                                        : 'bg-yellow-50 border-yellow-200 text-yellow-600 animate-pulse') }}">

                                @if ($trx->status == 'paid')
                                    <i class="bi bi-check-lg"></i>
                                @elseif (in_array($trx->status, ['doc_rejected', 'payment_rejected']))
                                    <i class="bi bi-exclamation-lg"></i>
                                @else
                                    <i class="bi bi-hourglass-split"></i>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-display font-bold text-slate-900 dark:text-white text-lg leading-tight">
                                    {{ $trx->bill->title ?? 'Pembayaran' }}
                                </h4>
                                <div class="flex items-center gap-2 text-[11px] font-mono text-slate-400 mt-1.5 uppercase tracking-wide">
                                    <span>{{ $trx->updated_at->format('d M Y') }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span>#{{ $trx->trx_code }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-5 w-full md:w-auto pl-[4rem] md:pl-0">

                            <div class="text-right">
                                <span class="block font-display font-bold text-slate-900 dark:text-slate-200 text-lg">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-widest
                                    {{ $trx->status == 'paid' ? 'text-green-600' : (in_array($trx->status, ['doc_rejected', 'payment_rejected']) ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ str_replace('_', ' ', $trx->status) }}
                                </span>
                            </div>

                            @if ($trx->status == 'paid')
                                <a href="{{ route('student.bills.invoice', $trx->du_bill_id) }}"
                                    class="w-10 h-10 rounded-lg bg-white border border-black/10 flex items-center justify-center text-slate-600 hover:bg-black hover:text-white hover:border-black transition-all shadow-sm"
                                    title="Lihat Invoice">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                            @elseif(in_array($trx->status, ['doc_rejected', 'payment_rejected']))
                                <a href="{{ route('student.bills.show', $trx->du_bill_id) }}"
                                    class="w-10 h-10 rounded-lg bg-red-50 border border-red-200 flex items-center justify-center text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm animate-bounce"
                                    title="Perbaiki">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            @endif

                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
