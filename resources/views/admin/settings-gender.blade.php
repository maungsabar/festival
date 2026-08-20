@extends('layouts.admin')
@section('title', 'Pengaturan Kategori ' . $gender)
@section('admin_content')

@php
  $isPutra   = $gender === 'Putra';
  $accent    = $isPutra ? 'blue' : 'pink';
  $accentHex = $isPutra ? '#1d4ed8' : '#db2777';
@endphp

<div class="mb-6">
  <h1 class="text-xl sm:text-2xl font-black text-gray-900 flex items-center gap-2 flex-wrap">
    Pengaturan Kategori
    <span class="inline-flex items-center gap-1 text-sm font-bold px-2.5 py-1 rounded-full
                 {{ $isPutra ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
      {{ $isPutra ? '♂' : '♀' }} {{ $gender }}
    </span>
  </h1>
  <p class="text-gray-400 text-sm mt-0.5">Status pendaftaran, hero, logo tahunan, countdown, dan kontak khusus kategori {{ $gender }}</p>
</div>

@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-5 text-sm text-emerald-800">
  <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
  @csrf

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ── Preview Panel ── --}}
    <div class="space-y-4">
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
          <span class="text-sm font-semibold text-gray-700">Preview Live — {{ $gender }}</span>
        </div>

        {{-- Hero Preview --}}
        <div class="p-4" id="heroPreviewWrap" style="background:{{ $settings['hero_bg_color'] ?: $accentHex }}; position:relative; min-height:130px;">
          @if($settings['hero_bg_image'])
          <img src="{{ asset('storage/hero_bg/'.$settings['hero_bg_image']) }}" id="heroBgPreviewImg"
               class="absolute inset-0 w-full h-full object-cover" style="opacity:{{ (100 - ($settings['hero_bg_overlay_opacity'] ?: 70)) / 100 }}">
          @else
          <img src="" id="heroBgPreviewImg" class="absolute inset-0 w-full h-full object-cover hidden">
          @endif
          <div class="relative z-10 flex flex-col items-center justify-center text-center p-2">
            <p class="text-[10px] text-white/70 uppercase tracking-wider mb-1.5">Preview Hero /lomba/{{ strtolower($gender) }}</p>
            <div id="p-logo-tahunan" class="w-9 h-9 bg-white/80 rounded-xl overflow-hidden flex items-center justify-center mx-auto mb-1.5">
              @if($settings['logo_tahunan'])
                <img src="{{ asset('storage/logos/'.$settings['logo_tahunan']) }}" class="w-full h-full object-contain p-0.5" alt="">
              @else
                <span class="text-xs">{{ $isPutra ? '♂' : '♀' }}</span>
              @endif
            </div>
            <p id="p-tagline-tahunan" class="text-white font-black text-xs">{{ $settings['tagline_tahunan'] ?: 'Tagline Tahunan '.$gender }}</p>
          </div>
        </div>

        <div class="p-4">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Logo Tahunan (Saat Ini)</p>
          <div id="logo-tahunan-current" class="w-16 h-16 rounded-xl border-2 border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
            @if($settings['logo_tahunan'])
              <img src="{{ asset('storage/logos/'.$settings['logo_tahunan']) }}" class="w-full h-full object-contain p-2" alt="">
            @else
              <span class="text-gray-300 text-xl">{{ $isPutra ? '♂' : '♀' }}</span>
            @endif
          </div>
        </div>
      </div>

      <div class="bg-{{ $accent }}-50 border border-{{ $accent }}-200 rounded-2xl p-4">
        <p class="text-xs font-semibold text-{{ $accent }}-700 mb-1">💡 Tips</p>
        <p class="text-xs text-{{ $accent }}-600">
          Semua pengaturan di halaman ini HANYA berlaku untuk <code>/lomba/{{ strtolower($gender) }}</code> — tidak memengaruhi kategori
          {{ $isPutra ? 'Putri' : 'Putra' }} atau halaman utama.
        </p>
      </div>
    </div>

    {{-- ── Form Columns ── --}}
    <div class="xl:col-span-2 space-y-5">

      {{-- Status Pendaftaran (radio card, aksen warna mengikuti gender) --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">🚦</span>
          Status Pendaftaran {{ $gender }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
          @php
            $selectedStatus = old('status_pendaftaran', $settings['status_pendaftaran']);
            $statusOptions = [
              ['belum',   'Belum Dibuka', 'bg-gray-400'],
              ['dibuka',  'Dibuka',       'bg-emerald-500'],
              ['ditutup', 'Ditutup',      'bg-red-500'],
            ];
          @endphp
          @foreach($statusOptions as [$val, $lbl, $dot])
          @php
            // "Dibuka" selalu tetap aksen hijau (indikator sukses); status lain
            // (Belum/Ditutup) memakai aksen warna gender yang sedang login —
            // biru untuk Putra, pink untuk Putri — sesuai permintaan.
            //
            // PENTING: pakai varian CSS `has-[:checked]:` (bukan class yang dihitung
            // di PHP dari nilai lama), supaya begitu radio diklik, browser LANGSUNG
            // mengubah tampilan lewat native CSS :checked — tidak perlu submit/reload
            // dan tidak perlu JS tambahan. Sebelumnya class "aktif" cuma dihitung
            // sekali saat halaman dimuat, jadi klik radio kelihatan tidak berefek.
            $activeCls = $val === 'dibuka'
              ? 'has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-400 has-[:checked]:text-emerald-800 has-[:checked]:ring-2 has-[:checked]:ring-emerald-300'
              : "has-[:checked]:bg-{$accent}-50 has-[:checked]:border-{$accent}-400 has-[:checked]:text-{$accent}-800 has-[:checked]:ring-2 has-[:checked]:ring-{$accent}-300";
          @endphp
          <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 cursor-pointer transition-all
                        hover:border-gray-300 {{ $activeCls }}">
            <input type="radio" name="status_pendaftaran" value="{{ $val }}" {{ $selectedStatus === $val ? 'checked' : '' }} class="sr-only peer">
            <span class="w-3 h-3 rounded-full {{ $dot }} shrink-0"></span>
            <span class="text-sm font-semibold">{{ $lbl }}</span>
          </label>
          @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          @foreach([['teks_belum','Teks "Belum"','Pendaftaran Belum Dibuka'],['teks_dibuka','Teks "Dibuka"','Pendaftaran Resmi Dibuka'],['teks_ditutup','Teks "Ditutup"','Pendaftaran Resmi Ditutup']] as [$key,$label,$ph])
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
            <input type="text" name="{{ $key }}" maxlength="200"
                   value="{{ old($key, $settings[$key]) }}"
                   placeholder="{{ $ph }}"
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-3 py-2.5 text-xs bg-white
                          focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
          </div>
          @endforeach
        </div>

        <p class="text-xs text-gray-400 mt-3">Mengatur tombol "Daftar Sekarang" &amp; kartu lomba khusus halaman kategori {{ $gender }}.</p>
      </div>

      {{-- Hero & Identitas Tahunan Kategori --}}
      <div class="bg-white border border-{{ $accent }}-100 bg-{{ $accent }}-50/30 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-1.5 flex items-center gap-2">
          <span class="w-6 h-6 bg-{{ $accent }}-100 text-{{ $accent }}-600 rounded-lg flex items-center justify-center text-xs">
            {{ $isPutra ? '♂' : '♀' }}
          </span>
          Hero &amp; Identitas Tahunan
        </h2>
        <p class="text-[11px] text-gray-400 mb-4">Tampil di header halaman <code>/lomba/{{ strtolower($gender) }}</code>. Jika gambar kosong, sistem memakai gradasi warna bawaan.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warna Latar Hero</label>
            <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-3 py-2.5 bg-white">
              <input type="color" name="hero_bg_color" id="heroBgColor"
                     value="{{ $settings['hero_bg_color'] ?: $accentHex }}"
                     class="w-8 h-8 rounded-lg cursor-pointer border-0 p-0 bg-transparent">
              <span id="heroBgColorText" class="text-xs text-gray-600 font-mono">{{ $settings['hero_bg_color'] ?: $accentHex }}</span>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Opacity Overlay (<span id="opacityVal">{{ $settings['hero_bg_overlay_opacity'] ?: 70 }}</span>%)</label>
            <input type="range" name="hero_bg_overlay_opacity" id="heroBgOpacity"
                   min="0" max="100" value="{{ $settings['hero_bg_overlay_opacity'] ?: 70 }}"
                   class="w-full accent-{{ $accent }}-500">
            <p class="text-[10px] text-gray-400 mt-1">Tinggi = gambar lebih gelap</p>
          </div>
        </div>

        <div>
          <p class="text-xs font-semibold text-gray-600 mb-2">Gambar Hero</p>
          <label id="heroBgLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-{{ $accent }}-200
                 hover:border-{{ $accent }}-400 hover:bg-{{ $accent }}-50 rounded-2xl p-5 cursor-pointer transition-all bg-white">
            <input type="file" name="hero_bg_image" id="heroBgInput" accept=".jpg,.jpeg,.png,.webp" class="sr-only">
            <div id="heroBgIdle" class="text-center">
              <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <p class="text-xs text-gray-500 font-semibold">{{ $settings['hero_bg_image'] ? 'Ganti Gambar Hero' : 'Klik upload gambar hero' }}</p>
              <p class="text-[10px] text-gray-400 mt-0.5">JPG, PNG, WebP • Max 5MB</p>
            </div>
            <div id="heroBgDone" class="hidden text-center">
              <img id="heroBgPreviewNew" src="" class="w-14 h-14 object-cover rounded-xl border border-gray-200 mx-auto mb-1" alt="">
              <p id="heroBgFileName" class="text-xs font-semibold text-gray-700 truncate max-w-[200px] mx-auto"></p>
              <p class="text-[10px] text-{{ $accent }}-600 mt-0.5">✓ File dipilih</p>
            </div>
          </label>
          @error('hero_bg_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          @if($settings['hero_bg_image'])
          <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
            <input type="checkbox" name="remove_hero_bg_image" value="1" class="w-3.5 h-3.5 accent-red-500">
            <span class="text-xs text-red-500 font-medium">Hapus gambar hero</span>
          </label>
          @endif
        </div>

        <div class="border-t border-{{ $accent }}-100 mt-4 pt-4 space-y-4">
          {{-- Logo Tahunan --}}
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Logo Tahunan / Event</label>
            <div class="flex items-center gap-2">
              <div class="w-12 h-12 rounded-lg border border-{{ $accent }}-200 bg-white flex items-center justify-center overflow-hidden shrink-0">
                @if($settings['logo_tahunan'])
                  <img src="{{ asset('storage/logos/'.$settings['logo_tahunan']) }}" id="logoTahunanPreview" class="w-full h-full object-contain p-1" alt="">
                @else
                  <span id="logoTahunanPreview" class="text-gray-300 text-sm">{{ $isPutra ? '♂' : '♀' }}</span>
                @endif
              </div>
              <label class="flex-1 flex items-center justify-center gap-1.5 border-2 border-dashed border-{{ $accent }}-200
                     hover:border-{{ $accent }}-400 hover:bg-{{ $accent }}-50 rounded-lg px-3 py-2.5 cursor-pointer transition-all bg-white">
                <input type="file" name="logo_tahunan" id="logoTahunanInput" accept=".png,.jpg,.jpeg,.svg,.webp" class="sr-only">
                <span id="logoTahunanFileName" class="text-xs text-{{ $accent }}-700 font-semibold truncate">
                  {{ $settings['logo_tahunan'] ? 'Ganti Logo' : 'Upload Logo' }}
                </span>
              </label>
            </div>
            @error('logo_tahunan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @if($settings['logo_tahunan'])
            <label class="flex items-center gap-1.5 mt-1.5 cursor-pointer">
              <input type="checkbox" name="remove_logo_tahunan" value="1" class="w-3.5 h-3.5 accent-red-500">
              <span class="text-xs text-red-500 font-medium">Hapus logo tahunan</span>
            </label>
            @endif
          </div>

          {{-- Judul Hero --}}
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Judul Hero</label>
            <input type="text" name="judul_hero" maxlength="150"
                   value="{{ old('judul_hero', $settings['judul_hero']) }}"
                   placeholder="cth: Lomba Kategori {{ $gender }}"
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-3 text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
            <p class="text-xs text-gray-400 mt-1.5">Kosongkan untuk memakai judul bawaan "Lomba Kategori {{ $gender }}".</p>
            @error('judul_hero')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Tagline Tahunan --}}
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tagline Tahunan / Event</label>
            <input type="text" name="tagline_tahunan" maxlength="200"
                   value="{{ old('tagline_tahunan', $settings['tagline_tahunan']) }}"
                   placeholder="cth: Semangat Juara, Torehkan Prestasi!"
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-3 text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
            @error('tagline_tahunan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Countdown --}}
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Batas Waktu Pendaftaran (Countdown)</label>
            <input type="datetime-local" name="countdown"
                   value="{{ $settings['countdown'] ? \Illuminate\Support\Carbon::parse($settings['countdown'])->format('Y-m-d\TH:i') : old('countdown') }}"
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-3 text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
            <p class="text-xs text-gray-400 mt-1.5">Kosongkan untuk menyembunyikan countdown di halaman ini.</p>
            @error('countdown')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- Kontak WhatsApp --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
          <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">📞</span>
          Kontak WhatsApp {{ $gender }}
        </h2>
        <div class="bg-{{ $accent }}-50 border border-{{ $accent }}-100 rounded-xl p-4">
          <div class="space-y-3">
            @foreach([['contact_whatsapp_1','contact_whatsapp_1_nama','WhatsApp 1'],['contact_whatsapp_2','contact_whatsapp_2_nama','WhatsApp 2']] as [$waKey,$namaKey,$label])
            <div class="flex gap-2">
              <div class="flex-1">
                <label class="block text-[10px] font-semibold text-{{ $accent }}-600 mb-0.5">{{ $label }}</label>
                <input type="text" name="{{ $waKey }}" value="{{ old($waKey, $settings[$waKey]) }}"
                       class="w-full border border-{{ $accent }}-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2 text-xs bg-white
                              focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all"
                       placeholder="08xxxxxxxxxx">
              </div>
              <div class="w-28">
                <label class="block text-[10px] font-semibold text-{{ $accent }}-600 mb-0.5">Nama</label>
                <input type="text" name="{{ $namaKey }}" value="{{ old($namaKey, $settings[$namaKey]) }}"
                       class="w-full border border-{{ $accent }}-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2 text-xs bg-white
                              focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all"
                       placeholder="HUMAS">
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Media Sosial --}}
      <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
        <h2 class="font-bold text-gray-900 text-sm mb-1.5 flex items-center gap-2">
          <span class="w-6 h-6 bg-{{ $accent }}-100 text-{{ $accent }}-600 rounded-lg flex items-center justify-center text-xs">📱</span>
          Media Sosial {{ $gender }}
        </h2>
        <p class="text-[11px] text-gray-400 mb-4">Tampil di footer halaman <code>/lomba/{{ strtolower($gender) }}</code> — terpisah dari media sosial umum di landing page.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach([
            ['social_instagram', 'Instagram', 'https://instagram.com/namamu'],
            ['social_tiktok',    'TikTok',    'https://tiktok.com/@namamu'],
            ['social_youtube',   'YouTube',   'https://youtube.com/@namamu'],
            ['social_facebook',  'Facebook',  'https://facebook.com/namamu'],
          ] as [$socKey, $label, $ph])
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ $label }}</label>
            <input type="url" name="{{ $socKey }}" maxlength="300"
                   value="{{ old($socKey, $settings[$socKey]) }}"
                   placeholder="{{ $ph }}"
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-2.5 text-sm bg-white
                          focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
            @error($socKey)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>
          @endforeach
        </div>
      </div>

      {{-- Submit --}}
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3
                  bg-white border border-gray-100 rounded-2xl shadow-sm px-5 py-4">
        <p class="text-xs text-gray-400">
          <span class="font-semibold text-gray-600">Catatan:</span> Perubahan hanya berlaku untuk kategori {{ $gender }}.
        </p>
        <button type="submit" id="saveBtn"
                class="inline-flex items-center justify-center gap-2 bg-{{ $accent }}-600 hover:bg-{{ $accent }}-700
                       text-white font-bold text-sm px-6 py-3 rounded-xl transition-all active:scale-95 shrink-0">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Pengaturan {{ $gender }}
        </button>
      </div>
    </div>
  </div>
</form>

@endsection

@push('scripts')
<script>
// Hero Background Color
const heroBgColorEl   = document.getElementById('heroBgColor');
const heroBgColorText = document.getElementById('heroBgColorText');
const heroPreviewWrap = document.getElementById('heroPreviewWrap');
heroBgColorEl.addEventListener('input', function () {
  heroPreviewWrap.style.background = this.value;
  heroBgColorText.textContent = this.value;
});

// Hero Overlay Opacity
const heroBgOpacityEl = document.getElementById('heroBgOpacity');
const opacityValEl    = document.getElementById('opacityVal');
heroBgOpacityEl.addEventListener('input', function () {
  const v = parseInt(this.value);
  opacityValEl.textContent = v;
  const bgImg = document.getElementById('heroBgPreviewImg');
  if (bgImg && bgImg.src) bgImg.style.opacity = (100 - v) / 100;
});

// Hero Background image upload preview
document.getElementById('heroBgInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  if (file.size > 5 * 1024 * 1024) { alert('Ukuran gambar maksimal 5MB!'); this.value = ''; return; }
  const r = new FileReader();
  r.onload = e => {
    const src = e.target.result;
    document.getElementById('heroBgIdle').classList.add('hidden');
    document.getElementById('heroBgDone').classList.remove('hidden');
    document.getElementById('heroBgPreviewNew').src = src;
    document.getElementById('heroBgFileName').textContent = file.name;
    const bgImg = document.getElementById('heroBgPreviewImg');
    if (bgImg) { bgImg.src = src; bgImg.classList.remove('hidden'); bgImg.style.opacity = (100 - parseInt(heroBgOpacityEl.value)) / 100; }
  };
  r.readAsDataURL(file);
});

// Logo Tahunan upload preview
document.getElementById('logoTahunanInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  if (file.size > 2 * 1024 * 1024) { alert('Ukuran logo maksimal 2MB!'); this.value = ''; return; }
  const r = new FileReader();
  r.onload = e => {
    const src = e.target.result;
    document.getElementById('logoTahunanFileName').textContent = file.name;
    const preview = document.getElementById('logoTahunanPreview');
    if (preview) preview.outerHTML = `<img src="${src}" id="logoTahunanPreview" class="w-full h-full object-contain p-1" alt="">`;
    const current = document.getElementById('logo-tahunan-current');
    if (current) current.innerHTML = `<img src="${src}" class="w-full h-full object-contain p-2" alt="">`;
  };
  r.readAsDataURL(file);
});

// Submit loading
document.getElementById('settingsForm').addEventListener('submit', function () {
  const btn = document.getElementById('saveBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menyimpan...';
});
</script>
@endpush
