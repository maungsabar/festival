@extends('layouts.app')
@section('title', $festivalName . ' ' . $festivalYear . ' — Beranda')

@section('content')

{{-- ── Sticky Navbar ── --}}
<nav id="navbar" class="fixed top-0 inset-x-0 z-40 transition-all duration-300" style="background:transparent">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
      <div class="w-8 h-8 rounded-xl overflow-hidden shrink-0 flex items-center justify-center bg-white shadow shadow-blue-500/30">
        @if($festivalLogo)
          <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain p-0.5" alt="">
        @else <span class="text-white text-sm">🏆</span> @endif
      </div>
      <div>
        <span id="nav-brand" class="font-black text-sm text-white transition-colors">{{ $festivalName }}</span>
        <span id="nav-year" class="text-xs text-white/60 ml-1.5 transition-colors">{{ $festivalYear }}</span>
      </div>
    </a>
    <div class="flex items-center gap-2">
      <!-- <a href="{{ route('login') }}" id="nav-login"
         class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-xl transition-all text-white/80 hover:text-white hover:bg-white/10">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>Login Admin
      </a> -->
      {{-- Status pendaftaran sekarang per-kategori (Putra/Putri), jadi tombol ini
           mengarahkan ke pemilihan kategori dulu — bukan lagi form langsung. --}}
      <a href="#lomba" id="nav-cta"
         class="inline-flex items-center gap-1.5 text-sm font-bold px-4 py-2.5 rounded-xl transition-all active:scale-95 bg-white text-blue-700 hover:bg-blue-50 shadow-sm">
        Daftar Sekarang
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
      </a>
    </div>
  </div>
</nav>

{{-- ── Hero ── --}}
<section class="relative min-h-screen flex flex-col items-center justify-center pt-16 overflow-hidden"
         style="background-color:{{ $heroBgColor ?: '#0a1628' }}">

  {{-- Hero Background Images (Single or Multi Slide / Carousel) --}}
  @php
    $bgList = !empty($heroBgImages) ? $heroBgImages : ($heroBgImage ? [$heroBgImage] : []);
    $calcOpacity = (100 - intval($heroBgOverlayOpacity)) / 100;
  @endphp

  @if(count($bgList) > 0)
  <div class="absolute inset-0 pointer-events-none overflow-hidden" id="hero-bg-carousel">
    @foreach($bgList as $idx => $bgImg)
    <img src="{{ asset('storage/hero_bg/' . $bgImg) }}"
         class="hero-bg-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out"
         style="opacity: {{ $idx === 0 ? $calcOpacity : 0 }};"
         data-index="{{ $idx }}">
    @endforeach
  </div>
  @endif

  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-48 -right-48 w-[500px] h-[500px] bg-blue-500/15 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-48 -left-48 w-[500px] h-[500px] bg-indigo-600/15 rounded-full blur-3xl"></div>
    <div class="absolute inset-0" style="background-image:radial-gradient(circle,rgba(255,255,255,0.04) 1px,transparent 1px);background-size:40px 40px"></div>
  </div>

  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center w-full">

    {{-- Logo Hero --}}
    @php
      $heroLogo = $festivalLogoHero ?: $festivalLogo;
    @endphp
    @if($heroLogo)
    <div class="w-20 h-20 bg-white/80 border border-white/20 rounded-3xl flex items-center justify-center mx-auto mb-5 overflow-hidden p-2 animate-slide-up" style="animation-delay:.1s">
      <img src="{{ asset('storage/logos/'.$heroLogo) }}" class="w-full h-full object-contain" alt="">
    </div>
    @endif

    <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white leading-[1.1] mb-4 animate-slide-up" style="animation-delay:.15s">
      {{ $festivalName }}
    </h1>
    @if($festivalTagline)
    <p class="text-blue-300 text-lg font-semibold mb-3 animate-slide-up" style="animation-delay:.18s">{{ $festivalTagline }}</p>
    @endif
    @if($festivalHeroText)
    <p class="text-blue-200/80 text-base sm:text-lg max-w-xl mx-auto mb-10 leading-relaxed animate-slide-up" style="animation-delay:.22s">
      {{ $festivalHeroText }}
    </p>
    @endif

    <div class="grid grid-cols-3 gap-3 sm:gap-4 max-w-sm sm:max-w-md mx-auto animate-slide-up" style="animation-delay:.32s">
      @foreach([[$lombas->count().'','Cabang Lomba','from-blue-500/20 to-blue-600/20 border-blue-500/20'],['2','Divisi','from-pink-500/20 to-pink-600/20 border-pink-500/20'],['🎖️','Hadiah','from-amber-500/20 to-orange-500/20 border-amber-500/20']] as [$v,$l,$g])
      <div class="bg-gradient-to-br {{ $g }} border backdrop-blur-sm rounded-2xl p-3 sm:p-4 text-center">
        <div class="text-xl sm:text-2xl font-black text-white">{{ $v }}</div>
        <div class="text-blue-200/70 text-[11px] sm:text-xs mt-0.5">{{ $l }}</div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce opacity-40">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
    </svg>
  </div>
</section>

{{-- ── Pemenang Section (if any) ── --}}
@if($pemenang->count() > 0)
<section class="bg-gradient-to-r from-amber-50 to-orange-50 border-y border-amber-200 py-14">
  <div class="max-w-5xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10">
      <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        🏆 Hasil Kompetisi
      </div>
      <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Pemenang Lomba</h2>
      <p class="text-gray-500 text-sm mt-2">Selamat kepada para juara {{ $festivalName }} {{ $festivalYear }}</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($pemenang as $l)
      <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center text-lg shrink-0">🏆</div>
          <div class="min-w-0">
            <p class="font-bold text-gray-900 text-sm truncate">{{ $l->nama_lomba }}</p>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $l->gender==='Putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700' }}">
              {{ $l->gender==='Putra'?'♂':'♀' }} {{ $l->gender }}
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

{{-- ── Lomba Section ── --}}
<section id="lomba" class="bg-white py-20">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10">
      <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        🏅 Kategori Lomba {{ $festivalYear }}
      </div>
      <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-3">Pilih Kategori Lomba</h2>
      <p class="text-gray-500 text-sm">Pilih kategori sesuai jenis kelamin peserta untuk melihat cabang lomba yang tersedia.</p>
    </div>

    <div class="grid sm:grid-cols-2 gap-6 mb-10 max-w-3xl mx-auto">
      {{-- Kategori Putra --}}
      <a href="{{ route('lomba.kategori', 'putra') }}"
         class="group relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-50
                p-8 text-center transition-all duration-200 hover:shadow-xl hover:-translate-y-1 hover:border-blue-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-600 flex items-center justify-center text-3xl text-white shadow-lg shadow-blue-500/30">
          ♂
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-1">Kategori Putra</h3>
        <p class="text-sm text-gray-500 mb-5">{{ $lombas->where('gender','Putra')->count() }} cabang lomba tersedia</p>
        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-700">
          Lihat Lomba
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </span>
      </a>

      {{-- Kategori Putri --}}
      <a href="{{ route('lomba.kategori', 'putri') }}"
         class="group relative overflow-hidden rounded-3xl border border-pink-100 bg-gradient-to-br from-pink-50 to-rose-50
                p-8 text-center transition-all duration-200 hover:shadow-xl hover:-translate-y-1 hover:border-pink-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-pink-500 flex items-center justify-center text-3xl text-white shadow-lg shadow-pink-500/30">
          ♀
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-1">Kategori Putri</h3>
        <p class="text-sm text-gray-500 mb-5">{{ $lombas->where('gender','Putri')->count() }} cabang lomba tersedia</p>
        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-pink-700">
          Lihat Lomba
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </span>
      </a>
    </div>

    @if($sponsors->count() > 0)
    {{-- Sponsor Section --}}
    <div class="mt-12 border-t border-gray-100 pt-8">
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
    @endif

    {{-- ── Informasi Kontak Panitia ── --}}
    @if($contactPhone || $contactWhatsapp || $contactEmail || $contactWaPutra1 || $contactWaPutri1)
    <div class="mt-12 border-t border-gray-100 pt-10">
      <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full mb-3">
          📞 Hubungi Panitia
        </div>
        <h3 class="text-xl sm:text-2xl font-black text-gray-900">Informasi Kontak</h3>
        <p class="text-gray-400 text-sm mt-1">Butuh bantuan? Tim panitia siap membantu kamu</p>
      </div>

      {{-- Kontak Umum --}}
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
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWhatsapp) }}" target="_blank"
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

      {{-- Kontak WhatsApp per Divisi --}}
      @if($contactWaPutra1 || $contactWaPutra2 || $contactWaPutri1 || $contactWaPutri2)
      <div class="grid sm:grid-cols-2 gap-4 max-w-2xl mx-auto">

        {{-- Divisi Putra --}}
        @if($contactWaPutra1 || $contactWaPutra2)
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
              <span class="text-white text-sm font-black">♂</span>
            </div>
            <div>
              <p class="font-bold text-blue-900 text-sm">Divisi Putra</p>
              <p class="text-blue-400 text-[10px]">Hubungi via WhatsApp</p>
            </div>
          </div>
          <div class="space-y-2.5">
            @if($contactWaPutra1)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWaPutra1) }}" target="_blank"
               class="flex items-center gap-3 bg-white hover:bg-emerald-50 border border-blue-100 hover:border-emerald-300
                      rounded-xl px-3.5 py-3 transition-all duration-200 group">
              <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaPutra1Nama }}</p>
                <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaPutra1 }}</p>
              </div>
              <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            @endif
            @if($contactWaPutra2)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWaPutra2) }}" target="_blank"
               class="flex items-center gap-3 bg-white hover:bg-emerald-50 border border-blue-100 hover:border-emerald-300
                      rounded-xl px-3.5 py-3 transition-all duration-200 group">
              <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaPutra2Nama }}</p>
                <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaPutra2 }}</p>
              </div>
              <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            @endif
          </div>
        </div>
        @endif

        {{-- Divisi Putri --}}
        @if($contactWaPutri1 || $contactWaPutri2)
        <div class="bg-gradient-to-br from-pink-50 to-rose-50 border border-pink-100 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-pink-500 rounded-xl flex items-center justify-center shrink-0">
              <span class="text-white text-sm font-black">♀</span>
            </div>
            <div>
              <p class="font-bold text-pink-900 text-sm">Divisi Putri</p>
              <p class="text-pink-400 text-[10px]">Hubungi via WhatsApp</p>
            </div>
          </div>
          <div class="space-y-2.5">
            @if($contactWaPutri1)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWaPutri1) }}" target="_blank"
               class="flex items-center gap-3 bg-white hover:bg-emerald-50 border border-pink-100 hover:border-emerald-300
                      rounded-xl px-3.5 py-3 transition-all duration-200 group">
              <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaPutri1Nama }}</p>
                <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaPutri1 }}</p>
              </div>
              <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            @endif
            @if($contactWaPutri2)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWaPutri2) }}" target="_blank"
               class="flex items-center gap-3 bg-white hover:bg-emerald-50 border border-pink-100 hover:border-emerald-300
                      rounded-xl px-3.5 py-3 transition-all duration-200 group">
              <div class="w-8 h-8 bg-emerald-100 group-hover:bg-emerald-500 rounded-lg flex items-center justify-center shrink-0 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $contactWaPutri2Nama }}</p>
                <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ $contactWaPutri2 }}</p>
              </div>
              <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-400 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
            @endif
          </div>
        </div>
        @endif

      </div>
      @endif
    </div>
    @endif
    {{-- ── End Kontak Panitia ── --}}

  </div>
</section>

{{-- ── CTA ── --}}
<section class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 py-14">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
    <h2 class="text-2xl sm:text-3xl font-black text-white mb-2">Siap Bergabung?</h2>
    <p class="text-blue-200 text-sm mb-7">Daftarkan dirimu di {{ $festivalName }} {{ $festivalYear }} sebelum kuota penuh!</p>
    {{-- Status pendaftaran sekarang per-kategori (Putra/Putri) — arahkan ke pemilihan kategori dulu. --}}
    <a href="#lomba"
       class="inline-flex items-center gap-2.5 bg-white hover:bg-blue-50 text-blue-700
              font-bold px-8 py-4 rounded-2xl text-base transition-all active:scale-95 shadow-xl hover:-translate-y-0.5">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
      </svg>Mulai Pendaftaran
    </a>
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
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$contactWhatsapp) }}" target="_blank"
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

      {{-- Social Media --}}
      <div>
        <h4 class="font-bold text-gray-300 text-sm mb-3">Ikuti Kami</h4>
        <div class="flex flex-wrap gap-2.5">
          @if($socialInstagram)
          <a href="{{ $socialInstagram }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-pink-400 transition-colors
                    bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
            Instagram
          </a>
          @endif
          @if($socialTiktok)
          <a href="{{ $socialTiktok }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-white transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.32 6.32 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.21 8.21 0 004.79 1.52V6.73a4.85 4.85 0 01-1.02-.04z"/>
            </svg>
            TikTok
          </a>
          @endif
          @if($socialYoutube)
          <a href="{{ $socialYoutube }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-red-400 transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
            </svg>
            YouTube
          </a>
          @endif
          @if($socialFacebook)
          <a href="{{ $socialFacebook }}" target="_blank"
             class="flex items-center gap-1.5 text-xs text-gray-400 hover:text-blue-400 transition-colors bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-xl">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
          </a>
          @endif
        </div>
        @if(!$socialInstagram && !$socialTiktok && !$socialYoutube && !$socialFacebook)
        <p class="text-xs text-gray-600 italic">Belum diatur</p>
        @endif
      </div>
    </div>

    <div class="border-t border-gray-800 pt-6 flex justify-center items-center">
      <p class="text-xs text-gray-500 text-center">&copy; {{ $festivalYear }} {{ $festivalName }}. All rights reserved.</p>
      <!-- <a href="{{ route('login') }}" class="text-xs text-gray-600 hover:text-gray-400 transition-colors flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>Login Admin
      </a> -->
    </div>
  </div>
</footer>

{{-- ── Tombol Back to Top ── --}}
<button id="btn-back-top"
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
// Navbar scroll effect
const navbar = document.getElementById('navbar');
const navBrand = document.getElementById('nav-brand');
const navYear = document.getElementById('nav-year');
const navLogin = document.getElementById('nav-login');
function onScroll() {
  const s = window.scrollY > 60;
  navbar.style.background = s ? 'rgba(255,255,255,0.95)' : 'transparent';
  navbar.style.backdropFilter = s ? 'blur(12px)' : 'none';
  navbar.style.borderBottom = s ? '1px solid rgba(0,0,0,0.06)' : 'none';
  navbar.style.boxShadow = s ? '0 1px 20px rgba(0,0,0,0.06)' : 'none';
  if(navBrand) navBrand.style.color = s ? '#111827' : 'white';
  if(navYear)  navYear.style.color  = s ? '#9ca3af' : 'rgba(255,255,255,.6)';
  if(navLogin) navLogin.style.color = s ? '#4b5563' : 'rgba(255,255,255,.8)';
}
window.addEventListener('scroll', onScroll, {passive:true});
onScroll();

// Back to Top button
const btnTop = document.getElementById('btn-back-top');
function toggleBackTop() {
  const show = window.scrollY > 300;
  btnTop.style.opacity        = show ? '1'                      : '0';
  btnTop.style.transform      = show ? 'translateY(0) scale(1)' : 'translateY(12px) scale(0.85)';
  btnTop.style.pointerEvents  = show ? 'auto'                   : 'none';
}
window.addEventListener('scroll', toggleBackTop, {passive: true});
toggleBackTop();

// Hero Background Carousel (Otomatis ganti slide tiap 5 detik)
const heroBgSlides = document.querySelectorAll('.hero-bg-slide');
if (heroBgSlides.length > 1) {
  let currentHeroBgIndex = 0;
  const targetOpacity = {{ (100 - intval($heroBgOverlayOpacity)) / 100 }};
  setInterval(() => {
    heroBgSlides[currentHeroBgIndex].style.opacity = '0';
    currentHeroBgIndex = (currentHeroBgIndex + 1) % heroBgSlides.length;
    heroBgSlides[currentHeroBgIndex].style.opacity = targetOpacity;
  }, 5000);
}
</script>
@endpush
