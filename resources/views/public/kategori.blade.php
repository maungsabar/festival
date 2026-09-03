@extends('layouts.app')
@section('title', $festivalName . ' ' . $festivalYear . ' — Lomba ' . $genderLabel)

@php
  $isPutra = $genderLabel === 'Putra';
  $theme = $isPutra
    ? ['icon'=>'♂','from'=>'from-blue-600','to'=>'to-indigo-700','badgeBg'=>'bg-blue-100','badgeText'=>'text-blue-700']
    : ['icon'=>'♀','from'=>'from-pink-500','to'=>'to-rose-600','badgeBg'=>'bg-pink-100','badgeText'=>'text-pink-700'];
  // Dipakai JS filter jenjang (SMP/SMA/UMUM) untuk aksen border/background saat aktif.
  $jenjangFilterActiveClasses = $isPutra
    ? ['border-blue-600', 'bg-blue-50', 'text-blue-700']
    : ['border-pink-600', 'bg-pink-50', 'text-pink-700'];
@endphp

@section('content')

{{-- ── Navbar ── --}}
<nav class="bg-white border-b border-gray-100 sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-xl overflow-hidden shrink-0 flex items-center justify-center bg-gray-900">
        @if($festivalLogo)
          <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain p-0.5" alt="">
        @else <span class="text-white text-sm">🏆</span> @endif
      </div>
      <div>
        <span class="font-black text-sm text-gray-900">{{ $festivalName }}</span>
        <span class="text-xs text-gray-400 ml-1.5">{{ $festivalYear }}</span>
      </div>
    </a>
    <div class="flex items-center gap-4">
      {{-- Menu "Merchandise" hanya muncul otomatis kalau kategori ini punya merchandise aktif --}}
      @if($hasMerchandiseGender)
      <a href="{{ route('merchandise.index', strtolower($genderLabel)) }}"
         class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">
        🛍️ <span class="hidden sm:inline">Merchandise</span>
      </a>
      @endif
      <a href="{{ route('home') }}"
         class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>
</nav>

{{-- ── Header Kategori (Hero Dinamis per Gender) ── --}}
<section class="relative py-16 overflow-hidden" style="background-color: {{ $heroBgColorGender }};">
  @if($heroBgImageGender)
    <img src="{{ asset('storage/hero_bg/' . $heroBgImageGender) }}"
         class="absolute inset-0 w-full h-full object-cover"
         style="opacity: {{ (100 - intval($heroBgOverlayOpacityGender)) / 100 }};" alt="">
  @else
    <div class="absolute inset-0 bg-gradient-to-br {{ $theme['from'] }} {{ $theme['to'] }}"></div>
  @endif

  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 py-4 text-center">

    {{-- Logo Tahunan / Event (fallback ke ikon gender jika belum diatur admin) --}}
    @if($logoTahunan)
    <div class="w-16 h-16 bg-white/80 border border-white/20 rounded-3xl flex items-center justify-center mx-auto mb-4 overflow-hidden p-2">
      <img src="{{ asset('storage/logos/'.$logoTahunan) }}" class="w-full h-full object-contain" alt="">
    </div>
    @else
    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-2xl text-white">
      {{ $theme['icon'] }}
    </div>
    @endif

    {{-- Badge Kategori + Status Pendaftaran — ditumpuk vertikal, rapi di tengah --}}
    <div class="flex flex-col items-center gap-2 mb-4">
      <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
        🏅 Kategori {{ $genderLabel }} {{ $festivalYear }}
      </div>

      {{-- Keterangan Status Pendaftaran (khusus gender ini, diatur admin_putra/admin_putri) --}}
      <div class="inline-flex items-center gap-2 border text-xs font-semibold px-4 py-1.5 rounded-full animate-slide-up
                  @if($pendaftaranStatusGender === 'dibuka') bg-emerald-500/20 border-emerald-400/30 text-emerald-100
                  @elseif($pendaftaranStatusGender === 'ditutup') bg-red-500/20 border-red-400/30 text-red-100
                  @else bg-gray-500/20 border-gray-400/30 text-gray-100 @endif">
        <span class="w-1.5 h-1.5 rounded-full animate-pulse
                     @if($pendaftaranStatusGender === 'dibuka') bg-emerald-400
                     @elseif($pendaftaranStatusGender === 'ditutup') bg-red-400
                     @else bg-gray-400 @endif"></span>
        {{ $teksStatusGender }}
      </div>
    </div>

    {{-- Judul Hero (dinamis, bisa diatur admin_putra/admin_putri — fallback ke teks statis) --}}
    <h1 class="text-3xl sm:text-4xl font-black text-white mb-2">{{ $judulHero ?: 'Lomba Kategori '.$genderLabel }}</h1>

    {{-- Tagline Tahunan / Event (fallback ke teks statis jika belum diatur admin) --}}
    @if($taglineTahunan)
    <p class="text-white/90 text-base font-semibold mb-1">{{ $taglineTahunan }}</p>
    @endif

    {{-- Countdown Timer (khusus gender ini) — diletakkan di bawah tagline --}}
    @if($countdownGender)
    <div class="mt-4 mb-8 animate-slide-up" style="animation-delay:.05s">
      <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-3">Pendaftaran Ditutup Dalam</p>
      <div id="countdown" class="flex items-center justify-center gap-3">
        @foreach([['days','Hari'],['hours','Jam'],['minutes','Menit'],['seconds','Detik']] as [$key,$lbl])
        <div class="bg-white/10 border border-white/20 backdrop-blur rounded-2xl px-3 sm:px-5 py-3 text-center min-w-[64px] sm:min-w-[80px]">
          <div id="cd-{{ $key }}" class="text-2xl sm:text-3xl font-black text-white tabular-nums">00</div>
          <div class="text-white/70 text-[10px] font-semibold uppercase tracking-wider mt-0.5">{{ $lbl }}</div>
        </div>
        @if(!$loop->last)<div class="text-white/40 text-2xl font-black">:</div>@endif
        @endforeach
      </div>
    </div>
    @endif


    {{-- Tombol Aksi (dipindahkan dari Halaman Utama) --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 animate-slide-up" style="animation-delay:.27s">
      @if($pendaftaranStatusGender === 'dibuka')
      <a href="{{ route('daftar.form', ['gender' => strtolower($genderLabel)]) }}"
         class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5
                bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-300 hover:to-orange-300
                text-gray-900 font-bold px-8 py-4 rounded-2xl text-base transition-all active:scale-95
                shadow-xl shadow-black/20 hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        Daftar Sekarang
      </a>
      @else
      <button disabled
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5
                     bg-black/20 border border-white/20 text-white/50 font-bold px-8 py-4 rounded-2xl text-base cursor-not-allowed"
              title="{{ $teksStatusGender }}">
        <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
        {{ $teksStatusGender }}
      </button>
      @endif
      <a href="#lomba"
         class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5
                bg-white/10 hover:bg-white/20 border border-white/20
                text-white font-semibold px-8 py-4 rounded-2xl text-base transition-all active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
        Info Lomba
      </a>
    </div>
  </div>
</section>

{{-- ── Grid Lomba ── --}}
<section id="lomba" class="bg-white py-16">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    @if($lombas->count() > 0)
    @php
      // Hanya jenjang yang benar-benar punya lomba aktif yang muncul sebagai tombol,
      // urutan tetap SMP -> SMA -> UMUM terlepas urutan data di database.
      $jenjangTersedia = collect(['SMP', 'SMA', 'UMUM'])
          ->filter(fn ($j) => $lombas->contains('jenjang', $j))
          ->values();
    @endphp

    @if($jenjangTersedia->count() > 0)
    <p class="text-center font-bold text-gray-900 text-base sm:text-lg mb-4">Silahkan Pilih Kategoti Perlombaan</p>
    <div class="flex flex-wrap items-center justify-center gap-2.5 mb-10" id="jenjang-filter">
      @foreach($jenjangTersedia as $j)
      <button type="button" data-jenjang="{{ $j }}"
              class="jenjang-btn inline-flex items-center gap-2 text-sm font-bold px-6 py-3 rounded-2xl border-2 transition-all active:scale-95
                     border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50">
        {{ $j }}
      </button>
      @endforeach
    </div>
    @endif

    {{-- Ditampilkan sebagai state awal, sebelum salah satu jenjang diklik --}}
    <div id="lomba-placeholder" class="text-center py-14 text-gray-400">
      <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
      <p class="text-sm">Pilih jenjang di atas untuk melihat perlombaan yang tersedia.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4 hidden" id="lomba-grid">
      @foreach($lombas as $l)
      @php $full = $l->isFull(); $sisa = $l->sisaKuota(); @endphp
      <div class="lomba-card bg-white border {{ $full?'border-gray-200 opacity-70':'border-gray-100 hover:border-blue-200 hover:shadow-md' }}
                  rounded-2xl p-5 transition-all duration-200 {{ $full?'':'hover:-translate-y-0.5' }}"
           data-jenjang="{{ $l->jenjang }}">

        {{-- Card Image / Placeholder --}}
        <div class="w-full aspect-[2/3] rounded-xl overflow-hidden mb-4 border border-gray-100 flex items-center justify-center relative bg-gradient-to-br {{ $isPutra?'from-blue-500/10 to-indigo-500/10':'from-pink-500/10 to-rose-500/10' }}">
          @if($l->gambar)
            <img src="{{ asset('storage/lomba_images/' . $l->gambar) }}" class="w-full h-full object-cover">
          @else
            <span class="text-3xl opacity-40">{{ $isPutra?'🏆':'🎨' }}</span>
          @endif
        </div>

        <div class="flex items-start gap-3 mb-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0 {{ $theme['badgeBg'] }}">
            {{ $theme['icon'] }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-gray-900 text-sm {{ $full?'line-through text-gray-400':'' }}">{{ $l->nama_lomba }}</p>
            <div class="flex flex-wrap gap-1.5 mt-1">
              <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full {{ $theme['badgeBg'] }} {{ $theme['badgeText'] }}">{{ $l->gender }}</span>
              <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full
                @if($l->jenjang==='SMP') bg-teal-100 text-teal-700
                @elseif($l->jenjang==='SMA') bg-indigo-100 text-indigo-700
                @else bg-amber-100 text-amber-700 @endif">{{ $l->jenjang }}</span>
              <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full {{ $l->tipe==='team'?'bg-violet-100 text-violet-700':'bg-gray-100 text-gray-600' }}">
                {{ $l->tipe==='team'?'👥 Beregu':'👤 Perorangan' }}
              </span>
            </div>
            <div class="mt-2.5 flex items-center justify-between bg-emerald-50/60 border border-emerald-100 rounded-xl px-2.5 py-1.5">
              <span class="text-[11px] font-semibold text-emerald-800 flex items-center gap-1">💳 Biaya Pendaftaran</span>
              <span class="text-xs font-black text-emerald-700">{{ $l->formatted_biaya }}</span>
            </div>
          </div>
          @if($full)
          <span class="text-[10px] font-semibold bg-red-100 text-red-600 px-2 py-0.5 rounded-full shrink-0">Penuh</span>
          @endif
        </div>
        @if($l->kuota)
        <div class="mb-3">
          <div class="flex justify-between text-[10px] text-gray-400 mb-1">
            <span>Kuota</span>
            <span>{{ $l->pendaftars_count }}/{{ $l->kuota }}</span>
          </div>
          <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="{{ $full?'bg-red-400':'bg-emerald-400' }} h-full rounded-full"
                 style="width:{{ min(100,round($l->pendaftars_count/$l->kuota*100)) }}%"></div>
          </div>
        </div>
        @endif

        <div class="mt-3 flex gap-2">
          @if($pendaftaranStatusGender !== 'dibuka')
            <button disabled
                    class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold
                           bg-gray-100 text-gray-400 py-2.5 rounded-xl cursor-not-allowed opacity-75">
              Nonaktif
            </button>
          @elseif($full)
            <button disabled
                    class="flex-1 flex items-center justify-center text-xs font-semibold
                           bg-red-50 text-red-500 py-2.5 rounded-xl cursor-not-allowed">
              Kuota Penuh
            </button>
          @else
            <a href="{{ route('daftar.form', ['gender' => strtolower($genderLabel), 'lomba' => $l->id]) }}"
               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold
                      bg-blue-50 hover:bg-blue-100 text-blue-700 py-2.5 rounded-xl transition-all">
              Daftar
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          @endif

          @if($l->file_guidebook)
            <a href="{{ asset('storage/guidebooks/' . $l->file_guidebook) }}" target="_blank"
               class="flex-1 flex items-center justify-center gap-1 text-xs font-semibold
                      bg-gray-50 hover:bg-gray-100 text-gray-600 py-2.5 rounded-xl transition-all border border-gray-200">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              Guidebook
            </a>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-10 text-gray-400">
      <p>Belum ada lomba kategori {{ $genderLabel }} yang tersedia.</p>
    </div>
    @endif

  </div>
</section>

{{-- ── Pemenang Kategori (jika ada) ── --}}
@if($pemenang->count() > 0)
<section class="bg-gradient-to-r from-amber-50 to-orange-50 border-y border-amber-200 py-14">
  <div class="max-w-5xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10">
      <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        🏆 Hasil Kompetisi
      </div>
      <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Pemenang Kategori {{ $genderLabel }}</h2>
      <p class="text-gray-500 text-sm mt-2">Selamat kepada para juara {{ $festivalName }} {{ $festivalYear }}</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($pemenang as $l)
      <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center text-lg shrink-0">🏆</div>
          <div class="min-w-0">
            <p class="font-bold text-gray-900 text-sm truncate">{{ $l->nama_lomba }}</p>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $theme['badgeBg'] }} {{ $theme['badgeText'] }}">
              {{ $theme['icon'] }} {{ $l->gender }}
            </span>
          </div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5">
          <p class="text-xs text-amber-600 font-semibold uppercase tracking-wider mb-0.5">Juara</p>
          <p class="font-bold text-gray-900 text-sm">{{ $l->pemenang }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── Cara Mendaftar ── --}}
<section class="bg-white py-14">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="bg-gray-50 rounded-3xl border border-gray-100 p-8">
      <h3 class="font-bold text-gray-900 text-center mb-8">Cara Mendaftar</h3>
      <div class="grid sm:grid-cols-3 gap-6">
        @foreach([['1','Isi Formulir','Lengkapi data diri, pilih lomba, dan isi data anggota tim jika beregu.','blue'],['2','Upload Dokumen','Siapkan kartu siswa, bukti pembayaran, dan link twibbon.','violet'],['3','Tunggu Verifikasi','Panitia memverifikasi berkas dan menghubungi via nomor yang didaftarkan.','emerald']] as [$n,$t,$d,$c])
        <div class="text-center">
          <div class="w-12 h-12 bg-{{ $c }}-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <span class="text-{{ $c }}-700 font-black text-lg">{{ $n }}</span>
          </div>
          <h4 class="font-bold text-gray-900 text-sm mb-2">{{ $t }}</h4>
          <p class="text-gray-400 text-xs leading-relaxed">{{ $d }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

{{-- ── Informasi Kontak Panitia (khusus kategori {{ $genderLabel }}) ── --}}
@if($contactPhone || $contactWhatsapp || $contactEmail || $contactWaGender1 || $contactWaGender2)
<section class="bg-white py-14">
  <div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-8">
      <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3">
        📞 Hubungi Panitia
      </div>
      <h3 class="text-xl sm:text-2xl font-black text-gray-900">Informasi Kontak</h3>
      <p class="text-gray-400 text-sm mt-1">Butuh bantuan? Tim panitia kategori {{ $genderLabel }} siap membantu kamu</p>
    </div>

    {{-- Kontak Umum (berlaku untuk semua kategori) --}}
    @if($contactPhone || $contactWhatsapp || $contactEmail)
    <div class="flex flex-wrap justify-center gap-3 mb-8">
      @if($contactPhone)
      <a href="tel:{{ $contactPhone }}"
         class="group flex items-center gap-3 bg-white border border-gray-100 hover:border-blue-200
                hover:shadow-md shadow-sm rounded-2xl px-5 py-3.5 transition-all duration-200">
        <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-500 rounded-xl flex items-center justify-center shrink-0 transition-colors">
          <svg class="w-4 h-4 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
          </svg>
        </div>
        <div>
          <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Telepon</p>
          <p class="text-sm font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $contactPhone }}</p>
        </div>
      </a>
      @endif

      @if($contactWhatsapp)
      <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($contactWhatsapp) }}" target="_blank"
         class="group flex items-center gap-3 bg-white border border-gray-100 hover:border-emerald-200
                hover:shadow-md shadow-sm rounded-2xl px-5 py-3.5 transition-all duration-200">
        <div class="w-9 h-9 bg-emerald-100 group-hover:bg-emerald-500 rounded-xl flex items-center justify-center shrink-0 transition-colors">
          <svg class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </div>
        <div>
          <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">WhatsApp Umum</p>
          <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWhatsapp }}</p>
        </div>
      </a>
      @endif

      @if($contactEmail)
      <a href="mailto:{{ $contactEmail }}"
         class="group flex items-center gap-3 bg-white border border-gray-100 hover:border-violet-200
                hover:shadow-md shadow-sm rounded-2xl px-5 py-3.5 transition-all duration-200">
        <div class="w-9 h-9 bg-violet-100 group-hover:bg-violet-500 rounded-xl flex items-center justify-center shrink-0 transition-colors">
          <svg class="w-4 h-4 text-violet-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <div>
          <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Email</p>
          <p class="text-sm font-bold text-gray-800 group-hover:text-violet-700 transition-colors">{{ $contactEmail }}</p>
        </div>
      </a>
      @endif
    </div>
    @endif

    {{-- Kontak WhatsApp khusus kategori {{ $genderLabel }} --}}
    @if($contactWaGender1 || $contactWaGender2)
    <div class="max-w-md mx-auto">
      <div class="bg-gradient-to-br {{ $isPutra ? 'from-blue-50 to-indigo-50 border-blue-100' : 'from-pink-50 to-rose-50 border-pink-100' }} border rounded-2xl p-5">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 {{ $isPutra ? 'bg-blue-600' : 'bg-pink-500' }} rounded-xl flex items-center justify-center shrink-0">
            <span class="text-white text-sm font-black">{{ $theme['icon'] }}</span>
          </div>
          <div>
            <p class="font-bold {{ $isPutra ? 'text-blue-900' : 'text-pink-900' }} text-sm">Divisi {{ $genderLabel }}</p>
            <p class="{{ $isPutra ? 'text-blue-400' : 'text-pink-400' }} text-[10px]">Hubungi via WhatsApp</p>
          </div>
        </div>
        <div class="space-y-2.5">
          @if($contactWaGender1)
          <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($contactWaGender1) }}" target="_blank"
             class="flex items-center gap-3 bg-white hover:bg-emerald-50 border {{ $isPutra ? 'border-blue-100' : 'border-pink-100' }} hover:border-emerald-300
                    rounded-xl px-3.5 py-3 transition-all duration-200 group">
            <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
              <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaGender1Nama }}</p>
              <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaGender1 }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
          @endif
          @if($contactWaGender2)
          <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($contactWaGender2) }}" target="_blank"
             class="flex items-center gap-3 bg-white hover:bg-emerald-50 border {{ $isPutra ? 'border-blue-100' : 'border-pink-100' }} hover:border-emerald-300
                    rounded-xl px-3.5 py-3 transition-all duration-200 group">
            <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
              <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaGender2Nama }}</p>
              <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaGender2 }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
          @endif
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endif

{{-- ── Sponsor Section ── --}}
@if($sponsors->count() > 0)
<section class="bg-white py-14 border-t border-gray-100">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <p class="text-center text-xs font-bold uppercase tracking-widest text-gray-400 mb-6">Disponsori Oleh</p>
    <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
      @foreach($sponsors as $s)
        @if($s->link)
          <a href="{{ $s->link }}" target="_blank" class="transition-all duration-300 hover:scale-105 filter grayscale opacity-60 hover:grayscale-0 hover:opacity-100 focus:outline-none" title="{{ $s->nama }}">
            <img src="{{ asset('storage/sponsors/' . $s->logo) }}" class="h-10 md:h-12 object-contain" alt="{{ $s->nama }}">
          </a>
        @else
          <div class="transition-all duration-300 hover:scale-105 filter grayscale opacity-60 hover:grayscale-0 hover:opacity-100" title="{{ $s->nama }}">
            <img src="{{ asset('storage/sponsors/' . $s->logo) }}" class="h-10 md:h-12 object-contain" alt="{{ $s->nama }}">
          </div>
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ── CTA ── --}}
<section class="bg-gradient-to-r {{ $isPutra ? 'from-blue-600 via-blue-700 to-indigo-700' : 'from-pink-600 via-pink-700 to-rose-700' }} py-14">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
    <h2 class="text-2xl sm:text-3xl font-black text-white mb-2">Siap Bergabung?</h2>
    <p class="{{ $isPutra ? 'text-blue-200' : 'text-pink-200' }} text-sm mb-7">Daftarkan dirimu di kategori {{ $genderLabel }} {{ $festivalName }} {{ $festivalYear }} sebelum kuota penuh!</p>
    @if($pendaftaranStatusGender === 'dibuka')
    <a href="{{ route('daftar.form', ['gender' => strtolower($genderLabel)]) }}"
       class="inline-flex items-center gap-2.5 bg-white hover:bg-{{ $isPutra ? 'blue' : 'pink' }}-50 text-{{ $isPutra ? 'blue' : 'pink' }}-700
              font-bold px-8 py-4 rounded-2xl text-base transition-all active:scale-95 shadow-xl hover:-translate-y-0.5">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
      </svg>Mulai Pendaftaran
    </a>
    @else
    <button disabled
            class="inline-flex items-center gap-2.5 bg-black/20 border border-white/20 text-white/60
                   font-bold px-8 py-4 rounded-2xl text-base cursor-not-allowed opacity-80 shadow-none"
            title="{{ $teksStatusGender }}">
      <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
      </svg>{{ $teksStatusGender }}
    </button>
    @endif
  </div>
</section>

{{-- ── Footer ── --}}
<footer class="bg-gray-900 text-gray-400">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
      {{-- Brand --}}
      <div>
        <div class="flex items-center gap-2 mb-3">
          <div class="w-8 h-8 bg-white/80 rounded-xl overflow-hidden flex items-center justify-center">
            @if($festivalLogo)
              <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain" alt="">
            @else <span class="text-white text-sm">🏆</span> @endif
          </div>
          <span class="font-bold text-gray-200">{{ $festivalName }}</span>
        </div>
        <p class="text-xs text-gray-500 leading-relaxed">{{ $festivalTagline ?? 'Kompetisi Antar Pelajar' }}</p>
      </div>

      {{-- Kontak Panitia --}}
      <div>
        <h4 class="font-bold text-gray-300 text-sm mb-3">Hubungi Panitia</h4>
        <div class="space-y-2">
          @if($contactPhone)
          <a href="tel:{{ $contactPhone }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-white transition-colors">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            {{ $contactPhone }}
          </a>
          @endif
          @if($contactWhatsapp)
          <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($contactWhatsapp) }}" target="_blank"
             class="flex items-center gap-2 text-xs text-gray-400 hover:text-green-400 transition-colors">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp: {{ $contactWhatsapp }}
          </a>
          @endif
          @if($contactEmail)
          <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-white transition-colors">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            {{ $contactEmail }}
          </a>
          @endif
          @if(!$contactPhone && !$contactWhatsapp && !$contactEmail)
          <p class="text-xs text-gray-600 italic">Belum diatur</p>
          @endif
        </div>
      </div>

      {{-- Social Media (khusus kategori {{ $genderLabel }}) --}}
      <div>
        <h4 class="font-bold text-gray-300 text-sm mb-3">Ikuti Kami</h4>
        <div class="flex flex-wrap gap-2.5">
          @if($socialInstagramGender)
          <a href="{{ $socialInstagramGender }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-pink-400 transition-colors
                    bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
            Instagram
          </a>
          @endif
          @if($socialTiktokGender)
          <a href="{{ $socialTiktokGender }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-white transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.21 8.21 0 004.79 1.52V6.73a4.85 4.85 0 01-1.02-.04z"/>
            </svg>
            TikTok
          </a>
          @endif
          @if($socialYoutubeGender)
          <a href="{{ $socialYoutubeGender }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-red-400 transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
            </svg>
            YouTube
          </a>
          @endif
          @if($socialFacebookGender)
          <a href="{{ $socialFacebookGender }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-blue-400 transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
          </a>
          @endif
        </div>
        @if(!$socialInstagramGender && !$socialTiktokGender && !$socialYoutubeGender && !$socialFacebookGender)
        <p class="text-xs text-gray-600 italic">Belum diatur</p>
        @endif
      </div>
    </div>

    <div class="border-t border-gray-800 pt-6 flex justify-center items-center">
      <p class="text-xs text-gray-500 text-center">&copy; {{ $festivalYear }} {{ $festivalName }}. All rights reserved.</p>
    </div>
  </div>
</footer>

{{-- ── Tombol Back to Top ── --}}
<button id="btn-back-top-kategori"
        onclick="window.scrollTo({top:0,behavior:'smooth'})"
        aria-label="Kembali ke atas"
        style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:999;
               width:48px;height:48px;border-radius:50%;border:none;cursor:pointer;
               background:linear-gradient(135deg,#2563eb,#4f46e5);
               box-shadow:0 8px 24px rgba(37,99,235,0.4);
               display:flex;align-items:center;justify-content:center;
               opacity:0;transform:translateY(12px) scale(0.85);
               transition:opacity .3s ease,transform .3s ease;
               pointer-events:none;">
  <svg width="20" height="20" fill="none" stroke="white" stroke-width="2.5"
       stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
    <path d="M5 15l7-7 7 7"/>
  </svg>
</button>

@endsection

@push('scripts')
<script>
// Tombol Back to Top
const btnTopKategori = document.getElementById('btn-back-top-kategori');
function toggleBackTopKategori() {
  const show = window.scrollY > 300;
  btnTopKategori.style.opacity       = show ? '1'                      : '0';
  btnTopKategori.style.transform     = show ? 'translateY(0) scale(1)' : 'translateY(12px) scale(0.85)';
  btnTopKategori.style.pointerEvents = show ? 'auto'                   : 'none';
}
window.addEventListener('scroll', toggleBackTopKategori, {passive: true});
toggleBackTopKategori();

@if($countdownGender)
// Countdown Timer (khusus halaman kategori {{ $genderLabel }})
const deadline = new Date("{{ $countdownGender }}").getTime();
function updateCountdown() {
  const now  = Date.now();
  const diff = deadline - now;
  if (diff <= 0) {
    ['days','hours','minutes','seconds'].forEach(k => {
      const el = document.getElementById('cd-'+k);
      if(el) el.textContent = '00';
    });
    return;
  }
  const days    = Math.floor(diff / 86400000);
  const hours   = Math.floor((diff % 86400000) / 3600000);
  const minutes = Math.floor((diff % 3600000) / 60000);
  const seconds = Math.floor((diff % 60000) / 1000);
  const pad = n => String(n).padStart(2,'0');
  document.getElementById('cd-days').textContent    = pad(days);
  document.getElementById('cd-hours').textContent   = pad(hours);
  document.getElementById('cd-minutes').textContent = pad(minutes);
  document.getElementById('cd-seconds').textContent = pad(seconds);
}
updateCountdown();
setInterval(updateCountdown, 1000);
@endif

// Filter Jenjang (SMP/SMA/UMUM) — default: tidak ada lomba tampil sampai
// salah satu tombol diklik. Klik tombol yang sedang aktif lagi -> reset.
(function () {
  const filterWrap = document.getElementById('jenjang-filter');
  if (!filterWrap) return;

  const activeClasses   = @json($jenjangFilterActiveClasses);
  const inactiveClasses = ['border-gray-200','text-gray-500'];
  const btns        = filterWrap.querySelectorAll('.jenjang-btn');
  const grid         = document.getElementById('lomba-grid');
  const placeholder  = document.getElementById('lomba-placeholder');
  const cards        = document.querySelectorAll('.lomba-card');
  let selected = null;

  function render() {
    if (!selected) {
      grid.classList.add('hidden');
      placeholder.classList.remove('hidden');
      return;
    }
    placeholder.classList.add('hidden');
    grid.classList.remove('hidden');
    cards.forEach(c => { c.style.display = c.dataset.jenjang === selected ? '' : 'none'; });
  }

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      selected = (selected === btn.dataset.jenjang) ? null : btn.dataset.jenjang;
      btns.forEach(b => {
        const isActive = b.dataset.jenjang === selected;
        b.classList.remove(...activeClasses, ...inactiveClasses);
        b.classList.add(...(isActive ? activeClasses : inactiveClasses));
      });
      render();
    });
  });
})();
</script>
@endpush
