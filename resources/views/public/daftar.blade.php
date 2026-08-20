@extends('layouts.app')
@section('title', 'Formulir Pendaftaran — ' . $festivalName)

@section('content')
@php
  $isPutraForm = $genderLabel === 'Putra';
  $formAccent  = $isPutraForm ? 'blue' : 'pink';
  // Dihitung terpisah (bukan langsung di dalam @json() nanti) karena direktif @json()
  // Blade salah mem-parsing ekspresi ternary yang berisi array literal ber-koma.
  $lockedLombaJs = $lockedLomba ? [
      'id' => $lockedLomba->id, 'tipe' => $lockedLomba->tipe,
      'min_anggota' => $lockedLomba->min_anggota, 'max_anggota' => $lockedLomba->max_anggota,
  ] : null;
@endphp
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">

  {{-- Navbar --}}
  <nav class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-white/60 shadow-sm">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
      <a href="{{ route('home') }}"
         class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>Kembali
      </a>
      <div class="flex items-center gap-2 text-sm font-bold text-gray-800">
        <div class="w-6 h-6 bg-white/80 rounded-md flex items-center justify-center overflow-hidden shrink-0">
          @if($festivalLogo)
            <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain" alt="">
          @else <span class="text-white text-xs">🏆</span> @endif
        </div>
        {{ $festivalName }}
      </div>
    </div>
  </nav>

  <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    {{-- Header --}}
    <div class="text-center mb-7">
      @if($festivalLogo)
      <div class="w-14 h-14 bg-white/80 rounded-2xl mx-auto mb-3 overflow-hidden p-1.5 shadow-lg shadow-blue-200">
        <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain" alt="">
      </div>
      @endif
      <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Formulir Pendaftaran</h1>
      <p class="text-gray-500 text-sm mt-1">{{ $festivalName }} {{ $festivalYear }}</p>
      <div class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold px-3 py-1.5 rounded-full
                 {{ $isPutraForm ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
        {{ $isPutraForm ? '♂' : '♀' }} Kategori {{ $genderLabel }}
      </div>
    </div>

    {{-- Error alert --}}
    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
      <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <p class="font-semibold text-red-700 text-sm mb-1">{{ $errors->count() }} kesalahan ditemukan:</p>
        <ul class="space-y-0.5">
          @foreach($errors->all() as $e)<li class="text-red-600 text-sm">• {{ $e }}</li>@endforeach
        </ul>
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('daftar.store') }}" enctype="multipart/form-data" id="daftarForm">
      @csrf

      {{-- ── STEP 1: Data Diri Peserta ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-4">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Data Diri Peserta / Kapten</h2>
            <p class="text-xs text-gray-400">Untuk lomba beregu, ini adalah data kapten tim</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {{-- NISN --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">NISN <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="text" name="nisn" id="nisn" maxlength="10" value="{{ old('nisn') }}" inputmode="numeric"
                     class="w-full border {{ $errors->has('nisn')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                            rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                     placeholder="10 digit NISN">
              <span id="nisn-count" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 tabular-nums">0/10</span>
            </div>
            @error('nisn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Nama --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama" value="{{ old('nama') }}"
                   class="w-full border {{ $errors->has('nama')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                          rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="Nama lengkap" required>
            @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- HP Peserta --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP Peserta/Kapten <span class="text-red-500">*</span></label>
            <input type="tel" name="hp_peserta" value="{{ old('hp_peserta') }}"
                   class="w-full border {{ $errors->has('hp_peserta')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                          rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="08xxxxxxxxxx" required>
            @error('hp_peserta')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Gender — terkunci sesuai halaman kategori asal, tidak bisa diubah manual --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
            <input type="hidden" name="gender" value="{{ $genderLabel }}">
            <div class="flex items-center gap-2.5 border-2 {{ $isPutraForm ? 'border-blue-200 bg-blue-50/60' : 'border-pink-200 bg-pink-50/60' }} rounded-xl px-4 py-3">
              <div class="w-6 h-6 rounded-lg {{ $isPutraForm ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} font-black text-sm flex items-center justify-center shrink-0">
                {{ $isPutraForm ? '♂' : '♀' }}
              </div>
              <span class="font-semibold text-sm text-gray-800">{{ $genderLabel }}</span>
              <span class="text-xs text-gray-400 ml-auto">Terkunci sesuai kategori</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
          {{-- Nama Sekolah --}}
          <div class="sm:col-span-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah') }}"
                   class="w-full border {{ $errors->has('nama_sekolah')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                          rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="Contoh: MA MILBoS Bogor" required>
            @error('nama_sekolah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Alamat --}}
          <div class="sm:col-span-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Sekolah <span class="text-red-500">*</span></label>
            <textarea name="alamat_sekolah" rows="2"
                      class="w-full border {{ $errors->has('alamat_sekolah')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                             rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                      placeholder="Alamat lengkap sekolah" required>{{ old('alamat_sekolah') }}</textarea>
            @error('alamat_sekolah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- ── STEP 2: Kontak Pendamping ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-4">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-8 h-8 bg-emerald-600 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Kontak Pendamping</h2>
            <p class="text-xs text-gray-400">Guru atau pembimbing yang mendampingi peserta</p>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pendamping <span class="text-red-500">*</span></label>
            <input type="text" name="nama_pendamping" value="{{ old('nama_pendamping') }}"
                   class="w-full border {{ $errors->has('nama_pendamping')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                          rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="Nama guru/pendamping" required>
            @error('nama_pendamping')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP Pendamping <span class="text-red-500">*</span></label>
            <input type="tel" name="hp_pendamping" value="{{ old('hp_pendamping') }}"
                   class="w-full border {{ $errors->has('hp_pendamping')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                          rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="08xxxxxxxxxx" required>
            @error('hp_pendamping')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- ── STEP 3: Pilih Lomba ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-4">
        <div class="flex items-start justify-between gap-3 mb-5 flex-wrap">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-violet-600 rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
            </div>
            <div>
              <h2 class="font-bold text-gray-900 text-sm">Pilih Perlombaan</h2>
              <p class="text-xs text-gray-400">
                @if($lockedLomba) Lomba sudah dipilih dari halaman kategori @else Pilih jenis kelamin untuk melihat daftar lomba @endif
              </p>
            </div>
          </div>

          {{-- Filter Jenjang Lomba — cuma relevan kalau lomba belum terkunci --}}
          @unless($lockedLomba)
          <div class="shrink-0">
            <div class="flex items-center justify-between mb-1">
              <span class="text-[10px] font-semibold text-gray-500">Filter Jenjang</span>
              <span class="text-[10px] text-blue-600 font-bold ml-2" id="jenjang-label-badge">SEMUA</span>
            </div>
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl border border-gray-200">
              <button type="button" onclick="setJenjangFilter('SEMUA')" id="filter-btn-SEMUA"
                      class="filter-jenjang-btn flex-1 text-center py-2 px-2.5 text-xs font-bold rounded-lg transition-all bg-white text-blue-600 shadow-sm">
                Semua
              </button>
              <button type="button" onclick="setJenjangFilter('SMP')" id="filter-btn-SMP"
                      class="filter-jenjang-btn flex-1 text-center py-2 px-2.5 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900">
                SMP
              </button>
              <button type="button" onclick="setJenjangFilter('SMA')" id="filter-btn-SMA"
                      class="filter-jenjang-btn flex-1 text-center py-2 px-2.5 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900">
                SMA
              </button>
              <button type="button" onclick="setJenjangFilter('UMUM')" id="filter-btn-UMUM"
                      class="filter-jenjang-btn flex-1 text-center py-2 px-2.5 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900">
                UMUM
              </button>
            </div>
          </div>
          @endunless
        </div>

        @if($lockedLomba)
        {{-- Lomba terkunci — dipilih langsung dari tombol "Daftar" di card lomba,
             tidak perlu memilih ulang dari daftar. --}}
        <div class="border-2 {{ $isPutraForm ? 'border-blue-300 bg-blue-50/50' : 'border-pink-300 bg-pink-50/50' }} rounded-2xl px-4 py-3.5">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-{{ $formAccent }}-100 rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-{{ $formAccent }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-sm text-gray-800">{{ $lockedLomba->nama_lomba }}</p>
              <div class="flex items-center gap-1.5 mt-1">
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full
                  @if($lockedLomba->jenjang==='SMP') bg-teal-100 text-teal-700
                  @elseif($lockedLomba->jenjang==='SMA') bg-indigo-100 text-indigo-700
                  @else bg-amber-100 text-amber-700 @endif">{{ $lockedLomba->jenjang }}</span>
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full {{ $lockedLomba->tipe==='team'?'bg-violet-100 text-violet-700':'bg-gray-100 text-gray-600' }}">
                  {{ $lockedLomba->tipe==='team' ? '👥 Beregu' : '👤 Perorangan' }}
                </span>
              </div>
            </div>
          </div>
          <a href="{{ route('lomba.kategori', strtolower($genderLabel)) }}#lomba"
             class="inline-block text-xs font-semibold text-{{ $formAccent }}-600 hover:underline mt-3">
            Ganti pilihan lomba
          </a>
        </div>
        <input type="hidden" name="id_lomba" id="id_lomba" value="{{ $lockedLomba->id }}">
        @else
        <div id="lomba-placeholder"
             class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center">
          <div class="w-10 h-10 bg-gray-100 rounded-xl mx-auto mb-2 flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
            </svg>
          </div>
          <p class="text-sm text-gray-400">Pilih jenis kelamin terlebih dahulu</p>
        </div>
        <div id="lomba-loading" class="hidden border-2 border-dashed border-blue-200 rounded-2xl p-8 text-center">
          <div class="w-7 h-7 border-[3px] border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
          <p class="text-sm text-blue-500">Memuat daftar lomba...</p>
        </div>
        <div id="lomba-list" class="hidden space-y-2.5"></div>
        <input type="hidden" name="id_lomba" id="id_lomba" value="{{ old('id_lomba') }}">
        @endif
        @error('id_lomba')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
      </div>

      {{-- ── STEP 4: Nama Tim + Anggota (team only) ── --}}
      <div id="team-section" class="hidden bg-white rounded-3xl shadow-sm border border-violet-100 p-5 sm:p-6 mb-4">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-8 h-8 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Data Tim</h2>
            <p id="team-desc" class="text-xs text-gray-400">Isi data semua anggota tim</p>
          </div>
        </div>

        {{-- Nama Tim --}}
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Tim <span class="text-red-500">*</span></label>
          <input type="text" name="nama_tim" value="{{ old('nama_tim') }}"
                 class="w-full border {{ $errors->has('nama_tim')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                        rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Nama tim Anda">
          @error('nama_tim')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Anggota note --}}
        <div class="bg-violet-50 border border-violet-200 rounded-xl px-4 py-3 mb-4">
          <p class="text-xs text-violet-700">
            <strong>Catatan:</strong> Data peserta/kapten (anggota ke-1) sudah diisi di bagian atas.
            Isi data anggota lainnya di bawah ini.
          </p>
        </div>

        {{-- Anggota dinamis --}}
        <div id="anggota-container" class="space-y-3"></div>
      </div>

      {{-- ── STEP 5: Twibbon ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-4">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 bg-pink-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Link Twibbon</h2>
            <p class="text-xs text-gray-400">Bukti peserta sudah membagikan twibbon</p>
          </div>
        </div>
        <input type="url" name="link_twibbon" value="{{ old('link_twibbon') }}"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="https://www.instagram.com/p/xxxx atau link lainnya">
        @error('link_twibbon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- ── Rekening Pembayaran (menyesuaikan gender yang dipilih di atas) ── --}}
      <div id="rekening-box" class="hidden bg-emerald-50 border border-emerald-200 rounded-3xl p-5 sm:p-6 mb-6">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M6 15h2m3 0h5M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Rekening Pembayaran</h2>
            <p class="text-xs text-gray-400">Transfer biaya pendaftaran ke rekening berikut, lalu unggah buktinya di bawah</p>
          </div>
        </div>
        <div id="rekening-detail" class="bg-white border border-emerald-200 rounded-2xl p-4"></div>
      </div>

      {{-- ── STEP 6: Upload Dokumen ── --}}
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm">Upload Dokumen</h2>
            <p class="text-xs text-gray-400">JPG, PNG, PDF — Maks. 2MB per file</p>
          </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          @foreach([
            ['kartuInput','kartuIdle','kartuDone','kartuName','kartuLabel','kartu_siswa','Kartu Siswa','Kartu identitas/siswa aktif','blue'],
            ['bayarInput','bayarIdle','bayarDone','bayarName','bayarLabel','bukti_pembayaran','Bukti Pembayaran','Bukti transfer/pembayaran','emerald'],
          ] as [$inputId,$idleId,$doneId,$nameId,$labelId,$fieldName,$title,$sub,$c])
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $title }} <span class="text-red-500">*</span></label>
            <label id="{{ $labelId }}"
                   class="flex flex-col items-center justify-center gap-2 border-2 border-dashed
                          {{ $errors->has($fieldName)?'border-red-300 bg-red-50':'border-gray-200 hover:border-'.$c.'-400 hover:bg-'.$c.'-50/30' }}
                          rounded-2xl p-5 cursor-pointer transition-all min-h-[120px]">
              <input type="file" name="{{ $fieldName }}" id="{{ $inputId }}"
                     accept=".jpg,.jpeg,.png,.gif,.pdf" class="sr-only">
              <div id="{{ $idleId }}" class="text-center">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-1.5">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                </div>
                <p class="text-sm text-gray-500 font-medium">{{ $title }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $sub }}</p>
              </div>
              <div id="{{ $doneId }}" class="hidden text-center">
                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-1.5">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
                <p id="{{ $nameId }}" class="text-xs text-gray-700 font-medium truncate max-w-[160px]"></p>
                <p class="text-xs text-green-500 mt-0.5">✓ File dipilih</p>
              </div>
            </label>
            @error($fieldName)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          @endforeach
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit" id="submitBtn"
              class="w-full font-bold py-4 rounded-2xl text-base transition-all shadow-lg flex items-center justify-center gap-3
                     bg-{{ $formAccent }}-600 hover:bg-{{ $formAccent }}-700 active:scale-[0.99] text-white shadow-{{ $formAccent }}-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Kirim Pendaftaran
      </button>
      <p class="text-center text-xs text-gray-400 mt-3">
        Dengan mendaftar, Anda menyetujui syarat dan ketentuan {{ $festivalName }} {{ $festivalYear }}.
      </p>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const API_URL     = "{{ route('api.lomba') }}";
const LOCKED_GENDER = "{{ $genderLabel }}"; // gender terkunci dari halaman kategori asal, tidak lagi dipilih manual
const LOCKED_LOMBA = @json($lockedLombaJs);
const OLD_LOMBA   = "{{ old('id_lomba') }}";
const REKENING_DATA = @json($rekenings);
let currentLomba  = null;
let currentGender = LOCKED_GENDER;
let rawLombaData  = [];
let selectedJenjang = 'SEMUA';

// ── Filter Jenjang ───────────────────────────────────────────
function setJenjangFilter(jenjang) {
  selectedJenjang = jenjang;
  const badge = document.getElementById('jenjang-label-badge');
  if (badge) badge.textContent = jenjang;

  document.querySelectorAll('.filter-jenjang-btn').forEach(btn => {
    btn.classList.remove('bg-white', 'text-blue-600', 'shadow-sm', 'font-bold');
    btn.classList.add('text-gray-600', 'font-semibold');
  });
  const activeBtn = document.getElementById('filter-btn-' + jenjang);
  if (activeBtn) {
    activeBtn.classList.remove('text-gray-600', 'font-semibold');
    activeBtn.classList.add('bg-white', 'text-blue-600', 'shadow-sm', 'font-bold');
  }

  if (rawLombaData.length > 0) {
    renderLombaList();
  }
}

// ── NISN counter ─────────────────────────────────────────────
const nisnEl = document.getElementById('nisn');
nisnEl.addEventListener('input', function() {
  this.value = this.value.replace(/\D/g,'').slice(0,10);
  document.getElementById('nisn-count').textContent = this.value.length+'/10';
});

// ── Load lomba ────────────────────────────────────────────────
function loadLomba(gender) {
  currentGender = gender;
  document.getElementById('lomba-placeholder').classList.add('hidden');
  document.getElementById('lomba-loading').classList.remove('hidden');
  document.getElementById('lomba-list').classList.add('hidden');
  document.getElementById('id_lomba').value = '';
  currentLomba = null;
  hideTeamSection();

  fetch(API_URL + '?gender=' + gender)
    .then(r => r.json())
    .then(data => {
      document.getElementById('lomba-loading').classList.add('hidden');
      rawLombaData = data;
      if (!data.length) {
        document.getElementById('lomba-placeholder').classList.remove('hidden');
        document.getElementById('lomba-placeholder').querySelector('p').textContent = 'Tidak ada lomba tersedia.';
        return;
      }
      renderLombaList();
    })
    .catch(() => {
      document.getElementById('lomba-loading').classList.add('hidden');
      document.getElementById('lomba-placeholder').classList.remove('hidden');
    });
}

function renderLombaList() {
  const list  = document.getElementById('lomba-list');
  const color = currentGender === 'Putra' ? 'blue' : 'pink';
  list.innerHTML = '';

  const filtered = rawLombaData.filter(l => {
    if (selectedJenjang === 'SEMUA') return true;
    return l.jenjang === selectedJenjang || l.jenjang === 'UMUM' || l.jenjang === 'SEMUA';
  });

  if (!filtered.length) {
    list.innerHTML = `
      <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center">
        <p class="text-sm font-semibold text-gray-500">Tidak ada lomba untuk jenjang <span class="text-blue-600 font-bold">${selectedJenjang}</span> (${currentGender}).</p>
        <p class="text-xs text-gray-400 mt-1">Coba ganti filter jenjang ke <button type="button" onclick="setJenjangFilter('SEMUA')" class="text-blue-600 font-bold underline">Semua</button>.</p>
      </div>
    `;
    list.classList.remove('hidden');
    return;
  }

  filtered.forEach(l => {
    const isSel = String(l.id) === String(OLD_LOMBA);
    const isFull = l.is_full;
    const div = document.createElement('div');
    div.id = 'lomba-card-' + l.id;

    let kuotaHtml = '';
    if (l.kuota) {
      const used  = l.kuota - (l.sisa_kuota ?? 0);
      const pct   = Math.round(used / l.kuota * 100);
      kuotaHtml = `<div class="mt-2">
        <div class="flex items-center justify-between text-[10px] mb-1">
          <span class="text-gray-400">Kuota: ${used}/${l.kuota}</span>
          <span class="${isFull?'text-red-500 font-semibold':'text-gray-400'}">${isFull?'Penuh!':l.sisa_kuota+' sisa'}</span>
        </div>
        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
          <div class="${isFull?'bg-red-400':'bg-emerald-400'} h-full rounded-full" style="width:${pct}%"></div>
        </div>
      </div>`;
    }

    let jenjangColor = 'bg-indigo-100 text-indigo-700';
    if (l.jenjang === 'SMP') jenjangColor = 'bg-teal-100 text-teal-700';
    else if (l.jenjang === 'UMUM') jenjangColor = 'bg-amber-100 text-amber-700';
    const jenjangBadge = `<span class="text-[10px] font-semibold ${jenjangColor} px-1.5 py-0.5 rounded-full">${l.jenjang}</span>`;

    const tipeBadge = l.tipe === 'team'
      ? `<span class="text-[10px] font-semibold bg-violet-100 text-violet-700 px-1.5 py-0.5 rounded-full">👥 Beregu ${l.min_anggota}–${l.max_anggota} org</span>`
      : `<span class="text-[10px] font-semibold bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">👤 Perorangan</span>`;

    div.className = `flex flex-col border-2 rounded-xl px-4 py-3 transition-all
      ${isFull ? 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed' : `cursor-pointer ${isSel?`border-${color}-500 bg-${color}-50`:'border-gray-200 hover:border-gray-300 hover:bg-gray-50'}`}`;

    div.innerHTML = `
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-${color}-100 rounded-lg flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-${color}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-sm text-gray-800">${l.nama_lomba}</p>
          <div class="flex items-center gap-1.5 mt-0.5">${jenjangBadge}${tipeBadge}${isFull?'<span class="text-[10px] text-red-500 font-semibold">Kuota Penuh</span>':''}</div>
        </div>
        <div id="radio-${l.id}" class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all shrink-0
             ${isSel?`border-${color}-500 bg-${color}-500`:'border-gray-300'}">
          ${isSel?'<div class="w-2 h-2 bg-white rounded-full"></div>':''}
        </div>
      </div>
      ${kuotaHtml}`;

    if (!isFull) {
      div.onclick = () => selectLomba(l, color);
      if (isSel) { currentLomba = l; document.getElementById('id_lomba').value = l.id; if(l.tipe==='team') showTeamSection(l); }
    }
    list.appendChild(div);
  });
  list.classList.remove('hidden');
}

function selectLomba(l, color) {
  document.getElementById('id_lomba').value = l.id;
  currentLomba = l;
  document.querySelectorAll('[id^="lomba-card-"]').forEach(el => {
    el.classList.remove(`border-${color}-500`,`bg-${color}-50`);
    el.classList.add('border-gray-200');
  });
  document.querySelectorAll('[id^="radio-"]').forEach(el => {
    el.classList.remove(`border-${color}-500`,`bg-${color}-500`);
    el.classList.add('border-gray-300');
    el.innerHTML='';
  });
  const card  = document.getElementById('lomba-card-' + l.id);
  const radio = document.getElementById('radio-' + l.id);
  if(card)  { card.classList.remove('border-gray-200'); card.classList.add(`border-${color}-500`,`bg-${color}-50`); }
  if(radio) { radio.classList.remove('border-gray-300'); radio.classList.add(`border-${color}-500`,`bg-${color}-500`); radio.innerHTML='<div class="w-2 h-2 bg-white rounded-full"></div>'; }

  if (l.tipe === 'team') showTeamSection(l);
  else hideTeamSection();
}

function showTeamSection(l) {
  const sec = document.getElementById('team-section');
  sec.classList.remove('hidden');
  document.getElementById('team-desc').textContent = `${l.min_anggota}–${l.max_anggota} anggota (kapten sudah diisi di atas)`;
  buildAnggotaFields(l.max_anggota);
}
function hideTeamSection() { document.getElementById('team-section').classList.add('hidden'); }

function buildAnggotaFields(max) {
  const container = document.getElementById('anggota-container');
  container.innerHTML = '';
  for (let i = 2; i <= max; i++) {
    const div = document.createElement('div');
    div.className = 'bg-gray-50 border border-gray-200 rounded-2xl p-4';
    div.innerHTML = `
      <p class="font-semibold text-sm text-gray-700 mb-3">
        Anggota ke-${i} ${i === 2 ? '(Wajib)' : '(Opsional)'}
      </p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1">NISN</label>
          <input type="text" name="anggota[${i}][nisn]" maxlength="10" inputmode="numeric"
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="10 digit NISN">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap ${i===2?'<span class="text-red-500">*</span>':''}</label>
          <input type="text" name="anggota[${i}][nama]" ${i===2?'required':''}
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Nama anggota">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1">Kelas</label>
          <input type="text" name="anggota[${i}][kelas]"
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Contoh: XII IPA 1">
        </div>
      </div>`;
    container.appendChild(div);
  }
}

// ── Rekening pembayaran (toggle sesuai gender terpilih) ─────────
// REKENING_DATA[gender] berisi ARRAY (satu kategori bisa punya lebih dari satu rekening/bank).
function showRekening(gender) {
  const box  = document.getElementById('rekening-box');
  const list = REKENING_DATA[gender] || [];
  const detail = document.getElementById('rekening-detail');
  if (!list.length) { box.classList.add('hidden'); detail.innerHTML = ''; return; }
  detail.innerHTML = list.map((r, i) => `
    <div class="flex items-center justify-between gap-3 flex-wrap w-full ${i > 0 ? 'pt-3 mt-3 border-t border-emerald-100' : ''}">
      <div>
        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">${r.nama_bank}</p>
        <p class="text-lg font-black text-gray-900 tracking-wide">${r.nomor_rekening}</p>
        <p class="text-xs text-gray-500">a.n. ${r.atas_nama}</p>
      </div>
      <button type="button"
              onclick="navigator.clipboard.writeText('${r.nomor_rekening}').then(() => { this.textContent='Disalin!'; setTimeout(() => this.textContent='Salin Nomor', 1500); })"
              class="text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-2 rounded-xl transition-all shrink-0 border border-emerald-200">
        Salin Nomor
      </button>
    </div>`).join('');
  box.classList.remove('hidden');
}

// Gender sudah terkunci (hidden input) — langsung muat rekening kategori ini,
// tidak perlu lagi menunggu radio dipilih.
showRekening(LOCKED_GENDER);

if (LOCKED_LOMBA) {
  // Lomba juga sudah terkunci dari card "Daftar" — tidak perlu fetch daftar lomba
  // sama sekali, id_lomba sudah terisi via hidden input server-side. Cukup munculkan
  // section Data Tim kalau lombanya tipe beregu.
  if (LOCKED_LOMBA.tipe === 'team') showTeamSection(LOCKED_LOMBA);
} else {
  loadLomba(LOCKED_GENDER);
}

// ── File upload ──────────────────────────────────────────────
function setupUpload(inputId, idleId, doneId, nameId, labelId) {
  document.getElementById(inputId).addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2*1024*1024) { alert('Ukuran file maksimal 2MB!'); this.value=''; return; }
    document.getElementById(idleId).classList.add('hidden');
    document.getElementById(doneId).classList.remove('hidden');
    document.getElementById(nameId).textContent = file.name;
    const lbl = document.getElementById(labelId);
    lbl.classList.remove('border-gray-200');
    lbl.classList.add('border-green-400','bg-green-50/30');
  });
}
setupUpload('kartuInput','kartuIdle','kartuDone','kartuName','kartuLabel');
setupUpload('bayarInput','bayarIdle','bayarDone','bayarName','bayarLabel');

// ── Submit loading ────────────────────────────────────────────
document.getElementById('daftarForm').addEventListener('submit', function() {
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = `<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
  </svg> Mengirim...`;
});
</script>
@endpush
