@extends('layouts.app')
@section('title', 'Pendaftaran Berhasil — ' . $festivalName)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/50 to-teal-100/50
            flex items-center justify-center p-4">

    {{-- Decorative blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-emerald-300/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-teal-300/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md animate-slide-up">

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            {{-- Top banner --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute inset-0" style="background-image:radial-gradient(circle,rgba(255,255,255,0.07) 1px,transparent 1px);background-size:20px 20px"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 border border-white/30 rounded-3xl
                                flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black text-white mb-1">Pendaftaran Berhasil!</h1>
                    <p class="text-emerald-100 text-sm">{{ $festivalName }} {{ $festivalYear }}</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">

                {{-- NISN --}}
                @if($nisn)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-6 text-center">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Nomor NISN Anda</p>
                    <p class="text-3xl font-black text-gray-900 tracking-[0.2em] font-mono">{{ $nisn }}</p>
                    <p class="text-xs text-gray-400 mt-2">Simpan sebagai bukti pendaftaran</p>
                </div>
                @endif

                {{-- Steps --}}
                <div class="space-y-3 mb-7">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Langkah Selanjutnya</p>
                    @foreach([
                        ['Data pendaftaran Anda sudah tersimpan di sistem kami.', 'emerald'],
                        ['Panitia akan memverifikasi bukti pembayaran Anda segera.', 'blue'],
                        ['Pantau pengumuman melalui pihak sekolah Anda.', 'violet'],
                    ] as $i => [$text, $c])
                    <div class="flex items-start gap-3 p-3.5 bg-{{ $c }}-50 border border-{{ $c }}-100 rounded-xl">
                        <div class="w-6 h-6 bg-{{ $c }}-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-{{ $c }}-700 text-xs font-bold">{{ $i+1 }}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $text }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="space-y-2.5">
                    <a href="{{ route('home') }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                              text-white font-bold py-3.5 rounded-2xl text-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('daftar.form') }}"
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200
                              text-gray-700 font-semibold py-3.5 rounded-2xl text-sm transition-all active:scale-95">
                        Daftarkan Peserta Lain
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">{{ $festivalName }} {{ $festivalYear }}</p>
    </div>
</div>
@endsection
