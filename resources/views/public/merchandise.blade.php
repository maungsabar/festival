@extends('layouts.app')
@section('title', $festivalName . ' ' . $festivalYear . ' — Merchandise ' . $genderLabel)

@php
  $isPutra = $genderLabel === 'Putra';
  $accent  = $isPutra ? 'blue' : 'pink';
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
    <a href="{{ route('lomba.kategori', strtolower($genderLabel)) }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali ke Kategori {{ $genderLabel }}
    </a>
  </div>
</nav>

{{-- ── Header ── --}}
<section class="bg-gradient-to-br {{ $isPutra ? 'from-blue-600 to-indigo-700' : 'from-pink-500 to-rose-600' }} py-14">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-2xl">
      🛍️
    </div>
    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
      {{ $isPutra ? '♂' : '♀' }} Kategori {{ $genderLabel }}
    </div>
    <h1 class="text-3xl sm:text-4xl font-black text-white mb-2">Merchandise {{ $genderLabel }}</h1>
    <p class="text-white/80 text-sm">Dukung {{ $festivalName }} {{ $festivalYear }} dengan merchandise resmi kami.</p>
  </div>
</section>

{{-- ── Grid Merchandise ── --}}
<section class="bg-white py-16">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($merchandises as $m)
      @php $habis = $m->stok !== null && $m->stok <= 0; @endphp
      <div class="bg-white border {{ $habis ? 'border-gray-200 opacity-70' : 'border-gray-100 hover:border-'.$accent.'-200 hover:shadow-md' }}
                  rounded-2xl p-5 transition-all duration-200 {{ $habis ? '' : 'hover:-translate-y-0.5' }}">

        {{-- Foto --}}
        <div class="w-full aspect-square rounded-xl overflow-hidden mb-4 border border-gray-100 flex items-center justify-center relative bg-gradient-to-br {{ $isPutra ? 'from-blue-500/10 to-indigo-500/10' : 'from-pink-500/10 to-rose-500/10' }}">
          @if($m->gambar)
            <img src="{{ asset('storage/merchandise/' . $m->gambar) }}" class="w-full h-full object-cover">
          @else
            <span class="text-3xl opacity-40">🛍️</span>
          @endif
          @if($habis)
          <span class="absolute top-2 right-2 text-[10px] font-semibold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Stok Habis</span>
          @endif
        </div>

        <p class="font-bold text-gray-900 text-sm mb-1 {{ $habis ? 'text-gray-400' : '' }}">{{ $m->nama }}</p>
        <p class="text-{{ $accent }}-600 font-black text-base mb-2">Rp{{ number_format($m->harga, 0, ',', '.') }}</p>

        @if($m->deskripsi)
        <p class="text-gray-400 text-xs leading-relaxed mb-3 line-clamp-2">{{ $m->deskripsi }}</p>
        @endif

        @if($m->stok !== null && !$habis)
        <p class="text-[10px] text-gray-400 mb-3">Sisa stok: {{ $m->stok }}</p>
        @endif

        @if($habis)
          <button disabled
                  class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold
                         bg-gray-100 text-gray-400 py-2.5 rounded-xl cursor-not-allowed">
            Stok Habis
          </button>
        @elseif($contactWaGender1)
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', $contactWaGender1) }}?text={{ urlencode('Halo, saya mau pesan '.$m->nama.' ('.$genderLabel.').') }}"
             target="_blank"
             class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold
                    bg-{{ $accent }}-50 hover:bg-{{ $accent }}-100 text-{{ $accent }}-700 py-2.5 rounded-xl transition-all">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Pesan via WhatsApp
          </a>
        @else
          <button disabled
                  class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold
                         bg-gray-100 text-gray-400 py-2.5 rounded-xl cursor-not-allowed"
                  title="Kontak pemesanan belum diatur admin">
            Kontak Belum Tersedia
          </button>
        @endif
      </div>
      @empty
      <div class="sm:col-span-2 lg:col-span-3 text-center py-10 text-gray-400">
        <p>Belum ada merchandise kategori {{ $genderLabel }} yang tersedia.</p>
      </div>
      @endforelse
    </div>
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
<button id="btn-back-top-merchandise"
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
const btnTopMerch = document.getElementById('btn-back-top-merchandise');
function toggleBackTopMerch() {
  const show = window.scrollY > 300;
  btnTopMerch.style.opacity       = show ? '1'                      : '0';
  btnTopMerch.style.transform     = show ? 'translateY(0) scale(1)' : 'translateY(12px) scale(0.85)';
  btnTopMerch.style.pointerEvents = show ? 'auto'                   : 'none';
}
window.addEventListener('scroll', toggleBackTopMerch, {passive: true});
toggleBackTopMerch();
</script>
@endpush
