@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Typography System */
        .font-display { font-family: 'Syne', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }

        /* Brutalist Card */
        .card-brutal {
            background-color: white;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 1.5rem; /* rounded-3xl */
            transition: all 0.3s ease;
        }

        /* Brutalist Action Card (Hover Effect) */
        .action-card {
            background-color: white;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 1.25rem; /* rounded-2xl */
            transition: all 0.2s ease;
        }
        .action-card:hover {
            border-color: #000;
            box-shadow: 4px 4px 0px 0px #000;
            transform: translate(-2px, -2px);
        }

        /* Brutalist Button */
        .btn-brutal {
            background-color: #0a0a0a;
            color: white;
            transition: all 0.2s ease;
            box-shadow: 2px 2px 0px 0px rgba(0,0,0,0.2);
        }
        .btn-brutal:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px 0px #0a0a0a;
        }
    </style>
@endpush

@section('content')

    <div class="mb-10 px-1 animate-fade-in-up">
        <h1 class="font-display text-4xl font-bold text-slate-900 mb-2">
            Halo, {{ explode(' ', $student->name)[0] }}! 👋
        </h1>
        <p class="font-body text-slate-500">Ada tagihan baru apa hari ini?</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            @if ($activeBill)
                <div class="card-brutal p-8 relative overflow-hidden group">

                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -z-0 opacity-50 translate-x-1/3 -translate-y-1/3"></div>

                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-4">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 bg-black text-white text-xs font-bold uppercase tracking-wider rounded-full mb-3">
                                    Tagihan Aktif
                                </span>
                                <h2 class="font-display text-3xl font-bold text-slate-900 leading-tight">
                                    {{ $activeBill->title }}
                                </h2>
                                <p class="font-body text-sm text-slate-500 mt-2 flex items-center gap-2">
                                    <i class="fa-regular fa-calendar"></i>
                                    Jatuh Tempo: {{ \Carbon\Carbon::parse($activeBill->end_date)->format('d M Y') }}
                                </p>
                            </div>

                            <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-900 shadow-sm group-hover:rotate-6 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="py-6 border-t border-dashed border-slate-200">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Tagihan</span>
                            <span class="font-display text-5xl font-bold text-slate-900 tracking-tight">
                                Rp {{ number_format($activeBill->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-6">
                            @if (in_array($trxStatus, ['paid']))
                                <div class="p-5 rounded-2xl bg-green-50 border border-green-200 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center text-lg shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-green-800">Lunas!</h4>
                                        <p class="text-xs text-green-600">Terima kasih, pembayaran terverifikasi.</p>
                                    </div>
                                    <a href="{{ route('student.bills.invoice', $activeBill->id) }}" class="ml-auto text-sm font-bold text-green-800 underline decoration-2 underline-offset-4 hover:text-green-900">
                                        Lihat Kwitansi
                                    </a>
                                </div>

                            @elseif(in_array($trxStatus, ['pending_docs', 'payment_review']))
                                <div class="p-5 rounded-2xl bg-yellow-50 border border-yellow-200 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-yellow-400 text-white flex items-center justify-center text-lg shrink-0 animate-pulse">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-yellow-800">Sedang Diproses</h4>
                                        <p class="text-xs text-yellow-700">Admin sedang memverifikasi data Anda.</p>
                                    </div>
                                </div>

                            @elseif(in_array($trxStatus, ['doc_rejected', 'payment_rejected']))
                                <div class="p-5 rounded-2xl bg-red-50 border border-red-200 flex flex-col gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center text-lg shrink-0">
                                            <i class="fa-solid fa-xmark"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-red-800">Perlu Revisi</h4>
                                            <p class="text-xs text-red-600 mt-1">{{ $trxNote }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('student.bills.show', $activeBill->id) }}" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-center font-bold text-sm shadow-sm transition-colors">
                                        Perbaiki Sekarang
                                    </a>
                                </div>

                            @else
                                <a href="{{ route('student.bills.show', $activeBill->id) }}" class="btn-brutal w-full py-4 rounded-xl flex items-center justify-center gap-3 font-bold text-lg group">
                                    Bayar Sekarang
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="card-brutal p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 mb-6">
                        <i class="fa-regular fa-face-smile text-4xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-xl text-slate-800 mb-2">Hore! Tidak ada tagihan.</h3>
                    <p class="text-slate-400 text-sm max-w-xs mx-auto">Kamu bisa fokus belajar dengan tenang. Cek lagi nanti ya!</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 grid grid-cols-2 lg:grid-cols-1 gap-4 h-min content-start">

            <a href="{{ route('student.profile.index') }}" class="action-card p-6 flex flex-col items-center justify-center text-center gap-4 group">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div>
                    <span class="block font-display font-bold text-slate-800 text-lg group-hover:text-black">Profil Saya</span>
                    <span class="text-xs text-slate-400">Update biodata</span>
                </div>
            </a>

            <a href="{{ route('student.history') }}" class="action-card p-6 flex flex-col items-center justify-center text-center gap-4 group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <span class="block font-display font-bold text-slate-800 text-lg group-hover:text-black">Riwayat</span>
                    <span class="text-xs text-slate-400">Arsip pembayaran</span>
                </div>
            </a>

            {{-- <div class="col-span-2 lg:col-span-1 action-card p-6 flex items-center justify-between group cursor-help bg-slate-900 border-slate-900">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center text-xl">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-display font-bold text-white">Butuh Bantuan?</h4>
                        <p class="text-xs text-slate-400">Chat Admin Keuangan</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-500 group-hover:text-white transition-colors"></i>
            </div> --}}

        </div>

    </div>

@endsection
