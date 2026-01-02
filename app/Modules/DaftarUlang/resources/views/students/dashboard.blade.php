@extends('layouts.siswa')

@section('title', 'Dashboard - Siswa')

@section('content')

    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Menu Pembayaran</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @if ($activeBill)

                @if ($isPaid)
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
                            <p class="text-gray-500 text-sm mb-4">Terima kasih sudah melakukan pembayaran.</p>

                            <a href="{{ route('student.bills.invoice', $activeBill->id) }}"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-green-700 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Lihat Rincian
                            </a>
                        </div>

                        <div class="p-6 opacity-30 pointer-events-none filter blur-[1px]">
                            <div
                                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $activeBill->title }}</h3>
                            <p class="text-gray-500 text-sm mt-2 line-clamp-2">Tagihan ini sudah diselesaikan.</p>
                        </div>
                    </div>
                @else
                    <a href="{{ route('student.bills.show', $activeBill->id) }}" class="block group h-full">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition hover:-translate-y-1 hover:border-blue-200 cursor-pointer h-full relative overflow-hidden">
                            <div class="absolute top-4 right-4">
                                <span class="flex h-3 w-3">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                </span>
                            </div>

                            <div
                                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">
                                {{ $activeBill->title }}</h3>
                            <p class="text-gray-500 text-sm mt-2 line-clamp-2">
                                {{ $activeBill->description ?? 'Silakan lakukan daftar ulang sebelum tanggal berakhir.' }}
                            </p>

                            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                                <span class="text-xs font-semibold bg-blue-50 text-blue-600 px-2 py-1 rounded">Segera
                                    Bayar</span>
                                <span class="text-xs text-gray-400">Jatuh Tempo:
                                    {{ \Carbon\Carbon::parse($activeBill->end_date)->format('d M Y') }}</span>
                            </div>
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
