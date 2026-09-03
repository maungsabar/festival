@extends('layouts.admin')
@section('title', $lomba ? 'Edit Lomba' : 'Tambah Lomba')

@section('admin_content')
<div class="max-w-2xl mx-auto">
  <div class="mb-6">
    <a href="{{ route('admin.lomba.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>Kembali
    </a>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">{{ $lomba ? 'Edit Lomba' : 'Tambah Lomba' }}</h1>
  </div>

  {{-- Alerts modern --}}
  @if(session('success'))
    <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-5">
      <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p class="text-sm text-emerald-800 font-semibold">{{ session('success') }}</p>
    </div>
  @elseif(session('error'))
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
      <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p>
    </div>
  @elseif(session('info'))
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-5">
      <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <p class="text-sm text-blue-700 font-semibold">{{ session('info') }}</p>
    </div>
  @endif

  @if($errors->any())
  <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <ul class="space-y-1">@foreach($errors->all() as $e)<li class="text-sm text-red-700">• {{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form method="POST" action="{{ $lomba ? route('admin.lomba.update',$lomba) : route('admin.lomba.store') }}" enctype="multipart/form-data">
    @csrf @if($lomba) @method('PUT') @endif


    {{-- Section 1: Identitas --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-black">1</span>
        Identitas Lomba
      </h2>

      {{-- Nama --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lomba <span class="text-red-500">*</span></label>
        <input type="text" name="nama_lomba" value="{{ old('nama_lomba',$lomba?->nama_lomba) }}"
               class="w-full border {{ $errors->has('nama_lomba')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                      rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Contoh: Futsal, Voli, Tari Tradisional" required>
      </div>

      {{-- Gender --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
        @if(count($allowed)===1)
          <input type="hidden" name="gender" value="{{ $allowed[0] }}">
          <div class="flex items-center gap-2 border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500">
            {{ $allowed[0]==='Putra'?'♂':'♀' }} {{ $allowed[0] }}
            <span class="text-xs text-gray-400">(terkunci)</span>
          </div>
        @else
          <div class="grid grid-cols-2 gap-3">
            @foreach(['Putra','Putri'] as $g)
            <label class="relative cursor-pointer">
              <input type="radio" name="gender" value="{{ $g }}" class="peer sr-only"
                     {{ old('gender',$lomba?->gender??'Putra')===$g?'checked':'' }}>
              <div class="border-2 rounded-xl p-3 text-center transition-all
                          peer-checked:{{ $g==='Putra'?'border-blue-500 bg-blue-50':'border-pink-500 bg-pink-50' }}
                          border-gray-200 hover:{{ $g==='Putra'?'border-blue-300':'border-pink-300' }}">
                <div class="text-xl font-bold {{ $g==='Putra'?'text-blue-600':'text-pink-600' }}">{{ $g==='Putra'?'♂':'♀' }}</div>
                <p class="font-bold text-xs text-gray-800 mt-0.5">{{ $g }}</p>
              </div>
            </label>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Jenjang --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenjang <span class="text-red-500">*</span></label>
        <div class="grid grid-cols-3 gap-3">
          @foreach(['SMP','SMA','UMUM'] as $j)
          <label class="relative cursor-pointer">
            <input type="radio" name="jenjang" value="{{ $j }}" class="peer sr-only"
                   {{ old('jenjang',$lomba?->jenjang??'SMA')===$j?'checked':'' }}>
            <div class="border-2 rounded-xl p-3 text-center transition-all cursor-pointer
                        peer-checked:border-blue-500 peer-checked:bg-blue-50
                        border-gray-200 hover:border-blue-300">
              <p class="font-bold text-xs text-gray-800">{{ $j }}</p>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Tipe --}}
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Lomba <span class="text-red-500">*</span></label>
        <div class="grid grid-cols-2 gap-3">
          @foreach([['single','👤 Perorangan','Satu peserta'],['team','👥 Beregu','Tim dengan beberapa anggota']] as [$val,$label,$desc])
          <label class="relative cursor-pointer">
            <input type="radio" name="tipe" value="{{ $val }}" id="tipe_{{ $val }}" class="peer sr-only"
                   {{ old('tipe',$lomba?->tipe??'single')===$val?'checked':'' }}>
            <div class="border-2 rounded-xl p-3 text-center transition-all cursor-pointer
                        peer-checked:border-violet-500 peer-checked:bg-violet-50
                        border-gray-200 hover:border-violet-300">
              <div class="text-xl mb-0.5">{{ explode(' ',$label)[0] }}</div>
              <p class="font-bold text-xs text-gray-800">{{ implode(' ',array_slice(explode(' ',$label),1)) }}</p>
              <p class="text-[10px] text-gray-400">{{ $desc }}</p>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Anggota (team only) --}}
      @php
        $selectedTipe = old('tipe', $lomba?->tipe ?? 'single');
        $isTeam = $selectedTipe === 'team';
      @endphp
      <div id="team-fields" class="{{ $isTeam ? '' : 'hidden' }} grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Anggota <span class="text-red-500">*</span></label>
          <input type="number"
                 name="min_anggota"
                 id="min_anggota"
                 value="{{ old('min_anggota',$lomba?->min_anggota??2) }}"
                 min="2" max="50"
                 {{ $isTeam ? 'required' : 'disabled' }}
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maks. Anggota <span class="text-red-500">*</span></label>
          <input type="number"
                 name="max_anggota"
                 id="max_anggota"
                 value="{{ old('max_anggota',$lomba?->max_anggota??5) }}"
                 min="2" max="50"
                 {{ $isTeam ? 'required' : 'disabled' }}
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
        </div>
      </div>

      {{-- Gambar Lomba --}}
      <div class="mb-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Gambar Lomba <span class="text-gray-400 text-xs font-normal">(Rekomendasi 2:3, JPG/PNG/WebP, maks 2MB)</span></label>
        <input type="file" name="gambar" accept="image/*"
               data-max-size-kb="2048" data-error-target="gambarSizeError"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <p id="gambarSizeError" class="hidden text-red-500 text-xs mt-1">Ukuran file terlalu besar! Maksimal ukuran file adalah 2 MB.</p>
        @if($lomba && $lomba->gambar)
          <div class="mt-3 flex items-center gap-3">
            <img src="{{ asset('storage/lomba_images/' . $lomba->gambar) }}" class="w-16 h-12 object-cover rounded-lg border border-gray-200">
            <div class="text-xs text-gray-500 flex-1 min-w-0">
              <p class="font-semibold text-gray-700">Gambar saat ini</p>
              <p class="truncate max-w-xs text-gray-400">{{ $lomba->gambar }}</p>
            </div>

            <button type="submit"
                    form="delete-gambar-{{ $lomba->id }}"
                    data-confirm='Hapus Gambar Lomba? Tindakan ini tidak dapat dibatalkan.'
                    class="text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg transition-all font-semibold inline-flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h2a1 1 0 00-1-1h-1m-8 0H7a1 1 0 00-1 1h2m5 0V5a1 1 0 011-1h0a1 1 0 011 1v2"/>
              </svg>
              Hapus
            </button>
          </div>
        @endif
      </div>
    </div>

    {{-- Section 2: Kuota & Status --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
        <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs font-black">2</span>
        Kuota & Status
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kuota Peserta</label>
          <input type="number" name="kuota" value="{{ old('kuota',$lomba?->kuota) }}" min="1"
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Kosongkan = tidak terbatas">
          <p class="text-xs text-gray-400 mt-1.5">Saat penuh, lomba otomatis dinonaktifkan.</p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Biaya Pendaftaran (Rp)</label>
          <input type="number" name="biaya" value="{{ old('biaya',$lomba?->biaya ?? 0) }}" min="0" step="1000"
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Contoh: 50000 (Isi 0 jika Gratis)">
          <p class="text-xs text-gray-400 mt-1.5">Isi 0 jika pendaftaran gratis tanpa biaya.</p>
        </div>
      </div>

      <div class="mb-4">
        <label class="flex items-center gap-3 cursor-pointer select-none p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors w-full">
          <div class="relative shrink-0">
            <input type="checkbox" name="aktif" value="1" id="aktifToggle"
                   {{ old('aktif',$lomba?($lomba->aktif?'1':''):'1')?'checked':'' }} class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </div>
          <div>
            <p class="font-semibold text-gray-700 text-sm">Lomba Aktif</p>
            <p class="text-xs text-gray-400">Tampil di form publik</p>
          </div>
        </label>
      </div>
    </div>

    {{-- Section 3: Guidebook --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
        <span class="w-6 h-6 bg-violet-100 text-violet-600 rounded-lg flex items-center justify-center text-xs font-black">3</span>
        Guidebook Lomba
        <span class="text-[10px] text-gray-400 font-normal">(opsional)</span>
      </h2>
      <div class="mb-2">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Guidebook <span class="text-gray-400 text-xs font-normal">(Hanya PDF, maks 10MB)</span></label>
        <input type="file" name="file_guidebook" accept="application/pdf"
               data-max-size-kb="10240" data-error-target="guidebookSizeError"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <p id="guidebookSizeError" class="hidden text-red-500 text-xs mt-1">Ukuran file terlalu besar! Maksimal ukuran file adalah 10 MB.</p>
        @if($lomba && $lomba->file_guidebook)
          <div class="mt-3 flex items-center gap-2">
            <span class="text-red-500 text-lg">📄</span>
            <div class="text-xs text-gray-500 flex-1 min-w-0">
              <p class="font-semibold text-gray-700">Guidebook saat ini</p>
              <a href="{{ asset('storage/guidebooks/' . $lomba->file_guidebook) }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-xs block font-medium">
                {{ $lomba->file_guidebook }} (Lihat Guidebook)
              </a>
            </div>

            <button type="submit"
                    form="delete-guidebook-{{ $lomba->id }}"
                    data-confirm='Hapus Guidebook Lomba? Tindakan ini tidak dapat dibatalkan.'
                    class="text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg transition-all font-semibold inline-flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h2a1 1 0 00-1-1h-1m-8 0H7a1 1 0 00-1 1h2m5 0V5a1 1 0 011-1h0a1 1 0 011 1v2"/>
              </svg>
              Hapus
            </button>
          </div>
        @endif
      </div>
    </div>

    {{-- Section 4: Pemenang --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
        <span class="w-6 h-6 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xs font-black">4</span>
        Informasi Pemenang
        <span class="text-[10px] text-gray-400 font-normal">(opsional)</span>
      </h2>
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pemenang / Juara</label>
        <input type="text" name="pemenang" value="{{ old('pemenang',$lomba?->pemenang) }}"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Contoh: SMA Negeri 1 Jakarta — Juara 1">
      </div>
      <label class="flex items-center gap-3 cursor-pointer select-none p-4 bg-amber-50 hover:bg-amber-100 rounded-xl transition-colors">
        <div class="relative shrink-0">
          <input type="checkbox" name="tampil_pemenang" value="1"
                 {{ old('tampil_pemenang',$lomba?->tampil_pemenang)?'checked':'' }} class="sr-only peer">
          <div class="w-11 h-6 bg-gray-300 peer-checked:bg-amber-500 rounded-full transition-colors"></div>
          <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <div>
          <p class="font-semibold text-gray-700 text-sm">Tampilkan di Halaman Publik</p>
          <p class="text-xs text-gray-500">Info pemenang akan muncul di halaman beranda</p>
        </div>
      </label>
    </div>

    <div class="flex gap-3">
      <button type="submit"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition-all active:scale-95">
        {{ $lomba ? 'Simpan Perubahan' : 'Tambah Lomba' }}
      </button>
      <a href="{{ route('admin.lomba.index') }}"
         class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200
                text-gray-700 font-semibold px-5 py-3 rounded-xl text-sm transition-all active:scale-95">Batal</a>
    </div>
  </form>

  @if($lomba)
    @if($lomba->gambar)
      <form id="delete-gambar-{{ $lomba->id }}"
            method="POST"
            action="{{ route('admin.lomba.delete.gambar',$lomba) }}">
        @csrf
        @method('DELETE')
      </form>
    @endif

    @if($lomba->file_guidebook)
      <form id="delete-guidebook-{{ $lomba->id }}"
            method="POST"
            action="{{ route('admin.lomba.delete.guidebook',$lomba) }}">
        @csrf
        @method('DELETE')
      </form>
    @endif
  @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="tipe"]').forEach(r => {
  r.addEventListener('change', () => {
    const isTeam = r.value === 'team';
    const teamFields = document.getElementById('team-fields');
    teamFields.classList.toggle('hidden', !isTeam);

    // PENTING: field yang disembunyikan HARUS di-disable, bukan cuma dilepas 'required'-nya.
    // Alasan: input min/max_anggota juga punya batas min="2" — begitu tipe balik ke 'single',
    // nilainya otomatis 1 (di bawah batas min=2), jadi browser tetap menganggapnya INVALID
    // (rangeUnderflow) meski tidak lagi wajib diisi. Field invalid yang tersembunyi bikin
    // submit gagal TOTAL tanpa reaksi apa pun (browser tidak bisa fokus ke situ buat kasih
    // tahu). 'disabled' menyingkirkan field dari validasi HTML5 sekaligus dari pengiriman
    // form — server (LombaController) memang sudah mengabaikan nilainya untuk tipe 'single'.
    ['min_anggota', 'max_anggota'].forEach(id => {
      const el = document.getElementById(id);
      el.required = isTeam;
      el.disabled = !isTeam;
    });
  });
});
</script>
@endpush

