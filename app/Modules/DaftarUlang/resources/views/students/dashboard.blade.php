@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')

    <div class="mb-8 px-2 animate-fade-in-up">
        <h1 class="text-3xl font-[800] text-slate-800 dark:text-white mb-1">
            Halo, {{ explode(' ', $student->name)[0] }}! 👋
        </h1>
        <p class="text-slate-500 dark:text-slate-400">Ada tagihan baru apa hari ini?</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="space-y-6">
            @if ($activeBill)
                <div class="clay-card p-8 group relative">
                    <div
                        class="absolute -right-10 -top-10 w-40 h-40 bg-red-400/10 rounded-full blur-3xl group-hover:bg-red-400/20 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="badge-pill bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300">
                                    Tagihan Aktif
                                </span>
                                <h2 class="text-2xl font-bold mt-3 text-slate-800 dark:text-white leading-tight">
                                    {{ $activeBill->title }}
                                </h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                    Jatuh Tempo: {{ \Carbon\Carbon::parse($activeBill->end_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white shadow-lg rotate-3 group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="flex items-end gap-2 mb-8">
                            <span class="text-sm text-slate-500 mb-1">Total:</span>
                            <span class="text-4xl font-[800] text-[var(--bbc-hex)] tracking-tight">
                                Rp {{ number_format($activeBill->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        @if (in_array($trxStatus, ['paid']))
                            <div
                                class="p-4 rounded-2xl bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0">
                                    <i class="bi bi-check"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-green-700 dark:text-green-400">Lunas!</h4>
                                    <p class="text-xs text-green-600 dark:text-green-500">Terima kasih sudah membayar.</p>
                                </div>
                                <a href="{{ route('student.bills.invoice', $activeBill->id) }}"
                                    class="ml-auto text-sm font-bold text-green-700 hover:underline">
                                    Lihat Kwitansi Anda &rarr;
                                </a>
                            </div>
                        @elseif(in_array($trxStatus, ['pending_docs', 'payment_review']))
                            <div
                                class="p-4 rounded-2xl bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-yellow-500 text-white flex items-center justify-center shrink-0 animate-pulse">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-yellow-700 dark:text-yellow-400">Sedang Diproses</h4>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-500">Menunggu verifikasi admin.</p>
                                </div>
                            </div>
                        @elseif(in_array($trxStatus, ['doc_rejected', 'payment_rejected']))
                            <div
                                class="p-4 rounded-2xl bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 flex flex-col gap-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0">
                                        <i class="bi bi-xmark"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-red-700 dark:text-red-400">Perlu Revisi</h4>
                                        <p class="text-xs text-red-600 dark:text-red-500 line-clamp-1">{{ $trxNote }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('student.bills.show', $activeBill->id) }}"
                                    class="btn-clay w-full !py-2 !text-sm">
                                    Perbaiki Sekarang
                                </a>
                            </div>
                        @else
                            <a href="{{ route('student.bills.show', $activeBill->id) }}" class="btn-clay w-full group">
                                <span>Bayar Sekarang</span>
                                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="clay-card p-10 text-center flex flex-col items-center justify-center h-64">
                    <div
                        class="w-16 h-16 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400 mb-4">
                        <i class="bi bi-smile text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-600 dark:text-slate-300">Hore! Tidak ada tagihan.</h3>
                    <p class="text-sm text-slate-400">Kamu bisa santai sejenak.</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 h-min">

            <a href="{{ route('student.profile.index') }}"
                class="clay-card !p-6 flex flex-col items-center justify-center text-center gap-3 hover:-translate-y-1 hover:!shadow-clay-hover cursor-pointer group">
                <div
                    class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-900/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-person-fill-gear"></i>
                </div>
                <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Profil Saya</span>
            </a>

            <a href="{{ route('student.history') }}"
                class="clay-card !p-6 flex flex-col items-center justify-center text-center gap-3 hover:-translate-y-1 hover:!shadow-clay-hover cursor-pointer group">
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-clock-history"></i>
                </div>
                <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Riwayat</span>
            </a>

            <div class="col-span-2 clay-card !p-6 flex items-center justify-between group cursor-help">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 flex items-center justify-center text-xl">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-bold text-slate-800 dark:text-white">Butuh Bantuan?</h4>
                        <p class="text-xs text-slate-500">Hubungi Admin Keuangan</p>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-slate-300 group-hover:text-blue-500 transition-colors"></i>
            </div>

        </div>

    </div>

@endsection
