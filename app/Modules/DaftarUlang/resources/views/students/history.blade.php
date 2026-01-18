@extends('layouts.siswa')

@section('title', 'Riwayat Transaksi')

@section('content')

    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('student.dashboard') }}"
                class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-500 hover:text-[var(--bbc-hex)] transition">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Riwayat Transaksi</h1>
        </div>

        @if ($transactions->isEmpty())
            <div class="clay-card p-12 text-center">
                <div
                    class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl text-slate-400">
                    <i class="bi bi-folder-open"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-600 dark:text-slate-300">Belum ada riwayat</h3>
                <p class="text-sm text-slate-400">Transaksi kamu akan muncul di sini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($transactions as $trx)
                    <div
                        class="clay-card !p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:!shadow-clay-hover transition-all">

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0
                            {{ $trx->status == 'paid'
                                ? 'bg-green-100 text-green-600 dark:bg-green-900/30'
                                : (in_array($trx->status, ['doc_rejected', 'payment_rejected'])
                                    ? 'bg-red-100 text-red-600 dark:bg-red-900/30'
                                    : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30') }}">

                                @if ($trx->status == 'paid')
                                    <i class="bi bi-check"></i>
                                @elseif (in_array($trx->status, ['doc_rejected', 'payment_rejected']))
                                    <i class="bi bi-exclamation-circle"></i>
                                @else
                                    <i class="bi bi-hourglass-split"></i>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg">
                                    {{ $trx->bill->title ?? 'Pembayaran' }}</h4>
                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    <span><i class="bi bi-calendar mr-1"></i>
                                        {{ $trx->updated_at->format('d M Y, H:i') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $trx->trx_code }}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between md:justify-end gap-4 w-full md:w-auto pl-[4rem] md:pl-0">
                            <div class="text-right">
                                <span class="block font-bold text-slate-800 dark:text-slate-200">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </span>
                                <span
                                    class="text-[10px] uppercase font-bold tracking-wider
                                {{ $trx->status == 'paid' ? 'text-green-500' : (in_array($trx->status, ['doc_rejected', 'payment_rejected']) ? 'text-red-500' : 'text-yellow-500') }}">
                                    {{ str_replace('_', ' ', $trx->status) }}
                                </span>
                            </div>

                            @if ($trx->status == 'paid')
                                <a href="{{ route('student.bills.invoice', $trx->du_bill_id) }}"
                                    class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-[var(--bbc-hex)] hover:text-white transition shadow-sm"
                                    title="Lihat Invoice">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </a>
                            @elseif(in_array($trx->status, ['doc_rejected', 'payment_rejected']))
                                <a href="{{ route('student.bills.show', $trx->du_bill_id) }}"
                                    class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500 hover:scale-110 transition shadow-sm"
                                    title="Perbaiki">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
