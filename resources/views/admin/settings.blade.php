@extends('layouts.admin')
@section('title','Pengaturan Festival')
@section('admin_content')

<div class="mb-6">
  <h1 class="text-xl sm:text-2xl font-black text-gray-900">Pengaturan Festival</h1>
  <p class="text-gray-400 text-sm mt-0.5">Kustomisasi identitas, media, status pendaftaran, sosial media, dan kontak</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
  @csrf

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ── Preview Panel ── --}}
    <div class="space-y-4">
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
          <span class="text-sm font-semibold text-gray-700">Preview Live</span>
        </div>
        <div class="p-4 bg-gray-50 border-b border-gray-100">
          <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Navbar</p>
          <div class="bg-white border border-gray-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
            <div id="p-logo-nav" class="w-6 h-6 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs shrink-0 overflow-hidden">
              @if($settings['festival_logo'])<img src="{{ asset('storage/logos/'.$settings['festival_logo']) }}" class="w-full h-full object-contain" alt="">@else 🏆 @endif
            </div>
            <p id="p-name" class="font-black text-gray-900 text-xs truncate">{{ $settings['festival_name'] }}</p>
            <p id="p-year" class="text-gray-400 text-xs ml-auto shrink-0">{{ $settings['festival_year'] }}</p>
          </div>
        </div>
        <div class="p-4 bg-blue-950">
          <p class="text-[10px] text-blue-400 uppercase tracking-wider mb-2">Sidebar Admin</p>
          <div class="flex items-center gap-2 bg-white/10 rounded-xl px-3 py-2.5">
            <div id="p-logo-sidebar" class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center text-white text-xs shrink-0 overflow-hidden">
              @if($settings['festival_logo'])<img src="{{ asset('storage/logos/'.$settings['festival_logo']) }}" class="w-full h-full object-contain p-0.5" alt="">@else 🏆 @endif
            </div>
            <div class="min-w-0">
              <p id="p-name-2" class="text-white font-bold text-xs truncate">{{ $settings['festival_name'] }}</p>
              <p id="p-tagline" class="text-white/40 text-[10px] truncate">{{ $settings['festival_tagline']?:'Tagline' }}</p>
            </div>
          </div>
        </div>
        {{-- Hero Preview --}}
        <div class="p-4" id="heroPreviewWrap" style="background:{{ $settings['hero_bg_color'] ?: '#0a1628' }}; position:relative; min-height:90px;">
          @if($settings['hero_bg_image'])
          <img src="{{ asset('storage/hero_bg/'.$settings['hero_bg_image']) }}" id="heroBgPreviewImg"
               class="absolute inset-0 w-full h-full object-cover" style="opacity:{{ (100 - ($settings['hero_bg_overlay_opacity'] ?: 70)) / 100 }}">
          @else
          <img src="" id="heroBgPreviewImg" class="absolute inset-0 w-full h-full object-cover hidden">
          @endif
          <div class="relative z-10 flex flex-col items-center justify-center text-center p-2">
            <p class="text-[10px] text-blue-300 uppercase tracking-wider mb-1.5">Preview Hero</p>
            <div id="p-logo-hero" class="w-8 h-8 bg-white/80 rounded-xl overflow-hidden flex items-center justify-center mx-auto mb-1">
              @if($settings['festival_logo_hero'])
                <img src="{{ asset('storage/logos/'.$settings['festival_logo_hero']) }}" class="w-full h-full object-contain p-0.5" alt="">
              @elseif($settings['festival_logo'])
                <img src="{{ asset('storage/logos/'.$settings['festival_logo']) }}" class="w-full h-full object-contain p-0.5" alt="">
              @else
                <span class="text-xs">🏆</span>
              @endif
            </div>
            <p id="p-name-hero" class="text-white font-black text-xs">{{ $settings['festival_name'] }}</p>
          </div>
        </div>
        <div class="p-4">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Logo Header (Saat Ini)</p>
          <div id="logo-current" class="w-16 h-16 rounded-xl border-2 border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden mb-2">
            @if($settings['festival_logo'])
              <img src="{{ asset('storage/logos/'.$settings['festival_logo']) }}" class="w-full h-full object-contain p-2" alt="">
            @else
              <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            @endif
          </div>
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Logo Hero (Saat Ini)</p>
          <div id="logo-hero-current" class="w-16 h-16 rounded-xl border-2 border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
            @if($settings['festival_logo_hero'])
              <img src="{{ asset('storage/logos/'.$settings['festival_logo_hero']) }}" class="w-full h-full object-contain p-2" alt="">
            @else
              <span class="text-gray-300 text-xl">🏆</span>
            @endif
          </div>
        </div>
      </div>
      <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
        <p class="text-xs font-semibold text-blue-700 mb-1">💡 Tips</p>
        <p class="text-xs text-blue-600">Gunakan logo berlatar <strong>transparan</strong> (PNG/SVG). Logo Header dan Hero bisa berbeda. Rasio 1:1, minimal 64×64px.</p>
      </div>
    </div>

    {{-- ── Form Columns ── --}}
    <div class="xl:col-span-2 space-y-5">

      {{-- Status Pendaftaran --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">🚦</span>
          Status Pendaftaran
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
          @foreach([['belum','Belum Dibuka','bg-gray-100 border-gray-200 text-gray-700','bg-gray-400'],['dibuka','Dibuka','bg-emerald-50 border-emerald-300 text-emerald-800','bg-emerald-500'],['ditutup','Ditutup','bg-red-50 border-red-300 text-red-800','bg-red-500']] as [$val,$lbl,$cls,$dot])
          <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                        {{ $settings['pendaftaran_status'] === $val ? $cls . ' ring-2 ring-offset-1 ring-current' : 'border-gray-200 hover:border-gray-300' }}">
            <input type="radio" name="pendaftaran_status" value="{{ $val }}"
                   {{ $settings['pendaftaran_status'] === $val ? 'checked' : '' }}
                   class="sr-only" id="status_{{ $val }}">
            <span class="w-3 h-3 rounded-full {{ $dot }} shrink-0"></span>
            <span class="text-sm font-semibold">{{ $lbl }}</span>
          </label>
          @endforeach
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          @foreach([['pendaftaran_belum_teks','Teks "Belum"','Pendaftaran Belum Dibuka'],['pendaftaran_dibuka_teks','Teks "Dibuka"','Pendaftaran Resmi Dibuka'],['pendaftaran_ditutup_teks','Teks "Ditutup"','Pendaftaran Resmi Ditutup']] as [$key,$label,$ph])
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
            <input type="text" name="{{ $key }}" maxlength="200"
                   value="{{ old($key, $settings[$key]) }}"
                   placeholder="{{ $ph }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
          </div>
          @endforeach
        </div>
      </div>

      {{-- Identitas --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs">✏️</span>
          Identitas Festival
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Festival <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="text" name="festival_name" id="inputName" maxlength="100"
                     value="{{ old('festival_name',$settings['festival_name']) }}"
                     class="w-full border {{ $errors->has('festival_name')?'border-red-400':'border-gray-200 focus:border-blue-500' }}
                            rounded-xl px-4 py-3 pr-16 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
              <span id="nameCount" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 tabular-nums">
                {{ strlen($settings['festival_name']) }}/100
              </span>
            </div>
            @error('festival_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
            <input type="number" name="festival_year" id="inputYear" min="2000" max="2099"
                   value="{{ old('festival_year',$settings['festival_year']) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
            @error('festival_year')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tagline</label>
            <input type="text" name="festival_tagline" id="inputTagline" maxlength="200"
                   value="{{ old('festival_tagline',$settings['festival_tagline']) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="Kompetisi Antar Pelajar">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teks Hero</label>
            <textarea name="festival_hero_text" rows="2" maxlength="500"
                      class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                      placeholder="Deskripsi singkat yang tampil di halaman beranda">{{ old('festival_hero_text',$settings['festival_hero_text']) }}</textarea>
          </div>
        </div>
      </div>

      {{-- Logo Upload --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-violet-100 text-violet-600 rounded-lg flex items-center justify-center text-xs">🖼️</span>
          Logo Festival
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {{-- Logo Header --}}
          <div>
            <p class="text-xs font-semibold text-gray-600 mb-2">Logo Header & Sidebar</p>
            <label id="logoLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200
                   hover:border-violet-400 hover:bg-violet-50/30 rounded-2xl p-5 cursor-pointer transition-all">
              <input type="file" name="festival_logo" id="logoInput" accept=".png,.jpg,.jpeg,.svg,.webp" class="sr-only">
              <div id="logoIdle" class="text-center">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                </div>
                <p class="text-xs text-gray-500 font-semibold">Klik upload logo header</p>
                <p class="text-[10px] text-gray-400 mt-0.5">PNG, JPG, SVG, WebP • Max 2MB</p>
              </div>
              <div id="logoDone" class="hidden text-center">
                <img id="logoPreviewNew" src="" class="w-14 h-14 object-contain rounded-xl border border-gray-200 mx-auto mb-1" alt="">
                <p id="logoFileName" class="text-xs font-semibold text-gray-700 truncate max-w-[160px] mx-auto"></p>
                <p class="text-[10px] text-violet-600 mt-0.5">✓ File dipilih</p>
              </div>
            </label>
            @error('festival_logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @if($settings['festival_logo'])
            <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
              <input type="checkbox" name="remove_logo" value="1" id="removeLogo" class="w-3.5 h-3.5 accent-red-500">
              <span class="text-xs text-red-500 font-medium">Hapus logo header</span>
            </label>
            @endif
          </div>

          {{-- Logo Hero --}}
          <div>
            <p class="text-xs font-semibold text-gray-600 mb-2">Logo Hero (Beranda)</p>
            <label id="logoHeroLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200
                   hover:border-indigo-400 hover:bg-indigo-50/30 rounded-2xl p-5 cursor-pointer transition-all">
              <input type="file" name="festival_logo_hero" id="logoHeroInput" accept=".png,.jpg,.jpeg,.svg,.webp" class="sr-only">
              <div id="logoHeroIdle" class="text-center">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                </div>
                <p class="text-xs text-gray-500 font-semibold">Klik upload logo hero</p>
                <p class="text-[10px] text-gray-400 mt-0.5">PNG, JPG, SVG, WebP • Max 2MB</p>
              </div>
              <div id="logoHeroDone" class="hidden text-center">
                <img id="logoHeroPreviewNew" src="" class="w-14 h-14 object-contain rounded-xl border border-gray-200 mx-auto mb-1" alt="">
                <p id="logoHeroFileName" class="text-xs font-semibold text-gray-700 truncate max-w-[160px] mx-auto"></p>
                <p class="text-[10px] text-indigo-600 mt-0.5">✓ File dipilih</p>
              </div>
            </label>
            @error('festival_logo_hero')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @if($settings['festival_logo_hero'])
            <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
              <input type="checkbox" name="remove_logo_hero" value="1" id="removeLogoHero" class="w-3.5 h-3.5 accent-red-500">
              <span class="text-xs text-red-500 font-medium">Hapus logo hero</span>
            </label>
            @endif
          </div>
        </div>
      </div>

      {{-- Hero Background --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xs">🌄</span>
          Hero Background (Multiple Slide / Carousel)
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Background</label>
            <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-3 py-2.5">
              <input type="color" name="hero_bg_color" id="heroBgColor"
                     value="{{ $settings['hero_bg_color'] ?: '#0a1628' }}"
                     class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0 bg-transparent">
              <span id="heroBgColorText" class="text-xs text-gray-600 font-mono">{{ $settings['hero_bg_color'] ?: '#0a1628' }}</span>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Opacity Overlay (<span id="opacityVal">{{ $settings['hero_bg_overlay_opacity'] ?: 70 }}</span>%)</label>
            <input type="range" name="hero_bg_overlay_opacity" id="heroBgOpacity"
                   min="0" max="100" value="{{ $settings['hero_bg_overlay_opacity'] ?: 70 }}"
                   class="w-full accent-indigo-500">
            <p class="text-[10px] text-gray-400 mt-1">Tinggi = gambar lebih gelap</p>
          </div>
        </div>

        {{-- Galeri Gambar Background Saat Ini --}}
        @if(!empty($settings['hero_bg_images_list']))
        <div class="mb-5 bg-gray-50 border border-gray-100 rounded-2xl p-4">
          <p class="text-xs font-bold text-gray-700 mb-2.5 flex items-center justify-between">
            <span>Gambar Background Saat Ini ({{ count($settings['hero_bg_images_list']) }} Slide):</span>
            <label class="inline-flex items-center gap-1.5 cursor-pointer text-red-500 hover:text-red-600 font-normal">
              <input type="checkbox" name="remove_hero_bg" value="1" id="removeHeroBg" class="w-3.5 h-3.5 accent-red-500">
              <span class="text-xs">Hapus Semua Background</span>
            </label>
          </p>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($settings['hero_bg_images_list'] as $idx => $img)
            <div class="relative group border border-gray-200 rounded-xl overflow-hidden bg-white p-1.5 flex flex-col justify-between shadow-sm">
              <div class="w-full h-24 rounded-lg overflow-hidden relative bg-gray-100">
                <img src="{{ asset('storage/hero_bg/' . $img) }}" class="w-full h-full object-cover">
                <span class="absolute top-1 left-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded backdrop-blur">
                  Slide {{ $idx + 1 }}
                </span>
              </div>
              <label class="flex items-center gap-1.5 mt-2 cursor-pointer text-red-600 hover:text-red-700">
                <input type="checkbox" name="delete_hero_bg_images[]" value="{{ $img }}" class="w-3.5 h-3.5 accent-red-500 rounded">
                <span class="text-[11px] font-semibold">Hapus gambar ini</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Upload Gambar Baru (Multiple) --}}
        <label id="heroBgLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200
               hover:border-indigo-400 hover:bg-indigo-50/30 rounded-2xl p-5 cursor-pointer transition-all">
          <input type="file" name="hero_bg_images[]" id="heroBgInput" accept=".jpg,.jpeg,.png,.webp" multiple class="sr-only">
          <div id="heroBgIdle" class="text-center">
            <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="text-xs text-gray-500 font-semibold">Tambah / Upload Gambar Background Baru</p>
            <p class="text-[10px] text-gray-400 mt-0.5">Bisa pilih 2 gambar atau lebih sekaligus (JPG, PNG, WebP • Max 5MB per file)</p>
          </div>
          <div id="heroBgDone" class="hidden w-full text-center">
            <div id="heroBgNewList" class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-2"></div>
            <p id="heroBgFileName" class="text-xs font-semibold text-indigo-600 truncate max-w-xs mx-auto"></p>
            <p class="text-[10px] text-indigo-600 mt-0.5">✓ File dipilih dan siap disimpan</p>
          </div>
        </label>
        @error('hero_bg_images')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        @error('hero_bg_images.*')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
      </div>

      {{-- Countdown --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xs">⏱️</span>
          Countdown Timer
        </h2>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Penutupan Pendaftaran</label>
        <input type="datetime-local" name="countdown_date"
               value="{{ old('countdown_date',$settings['countdown_date']) }}"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <p class="text-xs text-gray-400 mt-1.5">Kosongkan untuk menyembunyikan countdown di halaman publik.</p>
      </div>

      {{-- Social Media --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-pink-100 text-pink-600 rounded-lg flex items-center justify-center text-xs">📱</span>
          Social Media
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach([
            ['social_instagram','Instagram','https://instagram.com/...','text-pink-500'],
            ['social_tiktok','TikTok','https://tiktok.com/@...','text-gray-700'],
            ['social_youtube','YouTube','https://youtube.com/...','text-red-500'],
            ['social_facebook','Facebook','https://facebook.com/...','text-blue-600'],
          ] as [$key,$label,$ph,$tc])
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center gap-1.5">
              <span class="{{ $tc }} text-xs">●</span> {{ $label }}
            </label>
            <input type="url" name="{{ $key }}" value="{{ old($key,$settings[$key]) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="{{ $ph }}">
            @error($key)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          @endforeach
        </div>
      </div>

      {{-- Kontak Panitia --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">📞</span>
          Kontak Panitia
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. Telepon</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone',$settings['contact_phone']) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="08xxxxxxxxxx">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">WhatsApp Umum</label>
            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp',$settings['contact_whatsapp']) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="08xxxxxxxxxx">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email',$settings['contact_email']) }}"
                   class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                   placeholder="panitia@email.com">
            @error('contact_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- WhatsApp per divisi --}}
        <div class="border-t border-gray-100 pt-4">
          <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">WhatsApp per Divisi (untuk Form Pendaftaran)</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Putra --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
              <p class="text-xs font-bold text-blue-700 mb-3 flex items-center gap-1.5">♂ Divisi Putra</p>
              <div class="space-y-2">
                @foreach([['contact_whatsapp_putra_1','contact_whatsapp_putra_1_nama','WA Putra 1'],['contact_whatsapp_putra_2','contact_whatsapp_putra_2_nama','WA Putra 2']] as [$waKey,$namaKey,$label])
                <div class="flex gap-2">
                  <div class="flex-1">
                    <label class="block text-[10px] font-semibold text-blue-600 mb-0.5">{{ $label }}</label>
                    <input type="text" name="{{ $waKey }}" value="{{ old($waKey,$settings[$waKey]) }}"
                           class="w-full border border-blue-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                           placeholder="08xxxxxxxxxx">
                  </div>
                  <div class="w-28">
                    <label class="block text-[10px] font-semibold text-blue-600 mb-0.5">Nama</label>
                    <input type="text" name="{{ $namaKey }}" value="{{ old($namaKey,$settings[$namaKey]) }}"
                           class="w-full border border-blue-200 focus:border-blue-500 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                           placeholder="HUMAS">
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            {{-- Putri --}}
            <div class="bg-pink-50 border border-pink-100 rounded-xl p-4">
              <p class="text-xs font-bold text-pink-700 mb-3 flex items-center gap-1.5">♀ Divisi Putri</p>
              <div class="space-y-2">
                @foreach([['contact_whatsapp_putri_1','contact_whatsapp_putri_1_nama','WA Putri 1'],['contact_whatsapp_putri_2','contact_whatsapp_putri_2_nama','WA Putri 2']] as [$waKey,$namaKey,$label])
                <div class="flex gap-2">
                  <div class="flex-1">
                    <label class="block text-[10px] font-semibold text-pink-600 mb-0.5">{{ $label }}</label>
                    <input type="text" name="{{ $waKey }}" value="{{ old($waKey,$settings[$waKey]) }}"
                           class="w-full border border-pink-200 focus:border-pink-500 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-pink-500/20 transition-all"
                           placeholder="08xxxxxxxxxx">
                  </div>
                  <div class="w-28">
                    <label class="block text-[10px] font-semibold text-pink-600 mb-0.5">Nama</label>
                    <input type="text" name="{{ $namaKey }}" value="{{ old($namaKey,$settings[$namaKey]) }}"
                           class="w-full border border-pink-200 focus:border-pink-500 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-pink-500/20 transition-all"
                           placeholder="HUMAS">
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Submit --}}
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3
                  bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400">
          <span class="font-semibold text-gray-600">Catatan:</span> Perubahan langsung berlaku di seluruh halaman.
        </p>
        <button type="submit" id="saveBtn"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                       text-white font-bold text-sm px-6 py-3 rounded-xl transition-all active:scale-95 shrink-0">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Pengaturan
        </button>
      </div>
    </div>
  </div>
</form>

@endsection

@push('scripts')
<script>
// Live preview: nama, tahun, tagline
const iName=document.getElementById('inputName'), iYear=document.getElementById('inputYear'),
      iTag=document.getElementById('inputTagline'), nCount=document.getElementById('nameCount');
function sync() {
  const n=iName.value||'Nama Festival', y=iYear.value||new Date().getFullYear(), t=iTag.value||'Tagline';
  document.getElementById('p-name').textContent=n;
  document.getElementById('p-name-2').textContent=n;
  document.getElementById('p-name-hero').textContent=n;
  document.getElementById('p-year').textContent=y;
  document.getElementById('p-tagline').textContent=t;
  nCount.textContent=iName.value.length+'/100';
}
iName.addEventListener('input',sync); iYear.addEventListener('input',sync); iTag.addEventListener('input',sync);

// Logo Header upload preview
document.getElementById('logoInput').addEventListener('change',function(){
  const file=this.files[0]; if(!file) return;
  if(file.size>2*1024*1024){alert('Ukuran logo maksimal 2MB!');this.value='';return;}
  const r=new FileReader();
  r.onload=e=>{
    const src=e.target.result;
    document.getElementById('logoIdle').classList.add('hidden');
    document.getElementById('logoDone').classList.remove('hidden');
    document.getElementById('logoPreviewNew').src=src;
    document.getElementById('logoFileName').textContent=file.name;
    ['p-logo-nav','p-logo-sidebar','logo-current'].forEach(id=>{
      const el=document.getElementById(id);
      if(el) el.innerHTML=`<img src="${src}" class="w-full h-full object-contain p-0.5" alt="">`;
    });
  };
  r.readAsDataURL(file);
});

// Logo Hero upload preview
document.getElementById('logoHeroInput').addEventListener('change',function(){
  const file=this.files[0]; if(!file) return;
  if(file.size>2*1024*1024){alert('Ukuran logo maksimal 2MB!');this.value='';return;}
  const r=new FileReader();
  r.onload=e=>{
    const src=e.target.result;
    document.getElementById('logoHeroIdle').classList.add('hidden');
    document.getElementById('logoHeroDone').classList.remove('hidden');
    document.getElementById('logoHeroPreviewNew').src=src;
    document.getElementById('logoHeroFileName').textContent=file.name;
    const heroEl=document.getElementById('p-logo-hero');
    const heroCurrentEl=document.getElementById('logo-hero-current');
    if(heroEl) heroEl.innerHTML=`<img src="${src}" class="w-full h-full object-contain p-0.5" alt="">`;
    if(heroCurrentEl) heroCurrentEl.innerHTML=`<img src="${src}" class="w-full h-full object-contain p-2" alt="">`;
  };
  r.readAsDataURL(file);
});

// Hero Background Multiple Images preview
const heroBgInput = document.getElementById('heroBgInput');
if (heroBgInput) {
  heroBgInput.addEventListener('change', function() {
    const files = Array.from(this.files);
    if (!files.length) return;

    for (let f of files) {
      if (f.size > 5 * 1024 * 1024) {
        alert(`Ukuran file "${f.name}" melebihi 5MB!`);
        this.value = '';
        return;
      }
    }

    document.getElementById('heroBgIdle').classList.add('hidden');
    const doneEl = document.getElementById('heroBgDone');
    doneEl.classList.remove('hidden');

    const newList = document.getElementById('heroBgNewList');
    if (newList) newList.innerHTML = '';

    files.forEach((file) => {
      const r = new FileReader();
      r.onload = e => {
        const div = document.createElement('div');
        div.className = 'relative h-20 rounded-lg overflow-hidden border border-indigo-200 bg-gray-50';
        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">
          <span class="absolute bottom-1 right-1 bg-indigo-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">+Baru</span>`;
        if (newList) newList.appendChild(div);
      };
      r.readAsDataURL(file);
    });

    document.getElementById('heroBgFileName').textContent = `${files.length} gambar baru dipilih`;
  });
}

// Hero Background Color
const heroBgColorEl=document.getElementById('heroBgColor');
const heroBgColorText=document.getElementById('heroBgColorText');
const heroPreviewWrap=document.getElementById('heroPreviewWrap');
heroBgColorEl.addEventListener('input',function(){
  heroPreviewWrap.style.background=this.value;
  heroBgColorText.textContent=this.value;
});

// Hero Overlay Opacity
const heroBgOpacityEl=document.getElementById('heroBgOpacity');
const opacityValEl=document.getElementById('opacityVal');
function updateHeroBgOpacity(){
  const v=parseInt(heroBgOpacityEl.value);
  opacityValEl.textContent=v;
  const bgImg=document.getElementById('heroBgPreviewImg');
  if(bgImg && bgImg.src) bgImg.style.opacity=(100-v)/100;
}
heroBgOpacityEl.addEventListener('input',updateHeroBgOpacity);

// Remove logo header
const rm=document.getElementById('removeLogo');
if(rm) rm.addEventListener('change',function(){
  ['p-logo-nav','p-logo-sidebar','logo-current'].forEach(id=>{
    const el=document.getElementById(id);
    if(el) el.innerHTML=this.checked?'<span style="font-size:10px;color:#f87171">Hapus</span>':'🏆';
  });
});

// Remove logo hero
const rmHero=document.getElementById('removeLogoHero');
if(rmHero) rmHero.addEventListener('change',function(){
  const el=document.getElementById('p-logo-hero');
  const el2=document.getElementById('logo-hero-current');
  const msg=this.checked?'<span style="font-size:10px;color:#f87171">Hapus</span>':'<span>🏆</span>';
  if(el) el.innerHTML=msg;
  if(el2) el2.innerHTML=msg;
});

// Status pendaftaran radio styling
document.querySelectorAll('input[name="pendaftaran_status"]').forEach(radio=>{
  radio.addEventListener('change',function(){
    document.querySelectorAll('label[for^="status_"], .status-radio-label').forEach(l=>l.classList.remove('ring-2'));
    this.closest('label')?.classList.add('ring-2');
  });
});

// Submit loading
document.getElementById('settingsForm').addEventListener('submit',function(){
  const btn=document.getElementById('saveBtn');
  btn.disabled=true;
  btn.innerHTML='<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menyimpan...';
});
</script>
@endpush
