@extends('layouts.siswa')

@section('title', 'Dashboard - Siswa')

@section('content')

    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Menu Pembayaran</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @if ($activeBill)
                @if (in_array($trxStatus, ['doc_rejected', 'payment_rejected']))
                    <div
                        class="col-span-1 md:col-span-2 bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded shadow-sm animate-pulse">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm leading-5 font-bold text-red-800">
                                    {{ $trxStatus == 'doc_rejected' ? 'Dokumen Ditolak' : 'Pembayaran Ditolak' }}
                                </h3>
                                <p class="text-sm text-red-700 mt-1">Alasan: "{{ $trxNote }}"</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($trxStatus == 'paid')
                    <div class="relative h-full bg-white rounded-xl shadow-sm border-2 border-green-500 overflow-hidden">
                        <div
                            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-xs flex flex-col items-center justify-center text-center p-6">
                            <div
                                class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1">Tagihan Lunas!</h3>
                            <p class="text-gray-500">Terima Kasih Sudah Melakukan Pembayaran</p>
                            <a href="{{ route('student.bills.invoice', $activeBill->id) }}"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-green-700 transition mt-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Lihat Kwitansi
                            </a>
                        </div>
                        <div class="p-6 opacity-30 pointer-events-none filter blur-[1px]">
                            <h3 class="text-lg font-bold text-gray-800">{{ $activeBill->title }}</h3>
                        </div>
                    </div>
                @else
                    @php
                        if ($trxStatus == 'payment_rejected') {
                            $targetRoute = route('student.bills.payment', $activeBill->id);
                        } else {
                            $targetRoute = route('student.bills.show', $activeBill->id);
                        }

                        $cardClass =
                            'bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition hover:-translate-y-1 cursor-pointer h-full relative overflow-hidden group ';

                        if (in_array($trxStatus, ['doc_rejected', 'payment_rejected'])) {
                            $cardClass .= 'border-red-500 bg-red-50';
                        } elseif (in_array($trxStatus, ['pending_docs', 'payment_review'])) {
                            $cardClass .= 'border-yellow-400 bg-yellow-50';
                        } else {
                            $cardClass .= 'border-gray-100 hover:border-blue-200';
                        }
                    @endphp

                    <a href="{{ $targetRoute }}" class="block h-full">
                        <div class="{{ $cardClass }}">

                            <div class="absolute top-4 right-4">
                                @if (in_array($trxStatus, ['doc_rejected', 'payment_rejected']))
                                    <span
                                        class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded border border-red-200">PERLU
                                        REVISI</span>
                                @elseif(in_array($trxStatus, ['pending_docs', 'payment_review']))
                                    <span
                                        class="bg-yellow-100 text-yellow-600 text-xs font-bold px-2 py-1 rounded border border-yellow-200">DIPROSES</span>
                                @else
                                    <span class="flex h-3 w-3">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                    </span>
                                @endif
                            </div>

                            <div
                                class="w-12 h-12 rounded-full flex items-center justify-center mb-4 transition
                                {{ in_array($trxStatus, ['doc_rejected', 'payment_rejected']) ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' }}">

                                @if (in_array($trxStatus, ['doc_rejected', 'payment_rejected']))
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-gray-800">{{ $activeBill->title }}</h3>

                            <p
                                class="text-sm mt-2 {{ in_array($trxStatus, ['doc_rejected', 'payment_rejected']) ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                @if ($trxStatus == 'doc_rejected')
                                    Dokumen ditolak. Klik untuk perbaiki.
                                @elseif($trxStatus == 'payment_rejected')
                                    Pembayaran ditolak. Klik untuk perbaiki.
                                @elseif(in_array($trxStatus, ['pending_docs', 'payment_review']))
                                    Menunggu verifikasi admin.
                                @else
                                    {{ $activeBill->description ?? 'Segera lakukan pembayaran.' }}
                                @endif
                            </p>

                            @if (!in_array($trxStatus, ['paid', 'pending_docs', 'payment_review', 'doc_rejected', 'payment_rejected']))
                                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                                    <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-2 py-1 rounded">Segera
                                        Bayar</span>
                                    <span class="text-xs text-gray-400">Jatuh Tempo:
                                        {{ \Carbon\Carbon::parse($activeBill->end_date)->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endif
            @else
                <div
                    class="bg-gray-100 rounded-xl p-6 border border-dashed border-gray-300 flex flex-col items-center justify-center text-center opacity-75 h-full min-h-[200px]">
                    <div class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-md font-bold text-gray-500">Tidak Ada Tagihan Aktif</h3>
                    <p class="text-xs text-gray-400 mt-1">Saat ini belum ada periode daftar ulang.</p>
                </div>
            @endif

            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 opacity-60 grayscale cursor-not-allowed h-full relative">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Pembayaran Kelulusan</h3>
                <p class="text-gray-500 text-sm mt-2">Menu ini belum tersedia.</p>
                <span
                    class="absolute top-4 right-4 bg-gray-200 text-gray-500 text-[10px] font-bold px-2 py-1 rounded">SOON</span>
            </div>

        </div>
    </div>

@endsection
