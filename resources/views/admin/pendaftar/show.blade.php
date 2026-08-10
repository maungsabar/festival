@extends('layouts.admin')
@section('title', 'Detail — ' . $pendaftar->nama)
@section('admin_content')

<div class="mb-6">
  <a href="{{ route('admin.pendaftar.index') }}"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>Kembali ke Daftar
  </a>
  <h1 class="text-xl sm:text-2xl font-black text-gray-900">Detail Pendaftar</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- ── Info Utama ── --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Profile card --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
      <div class="h-16 bg-gradient-to-r {{ $pendaftar->gender==='Putra'?'from-blue-500 to-blue-700':'from-pink-500 to-pink-700' }}"></div>
      <div class="px-5 pb-5">
        <div class="flex items-end gap-3 -mt-7 mb-4">
          <div class="w-14 h-14 rounded-2xl border-4 border-white shadow flex items-center justify-center text-2xl font-black
                      {{ $pendaftar->gender==='Putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700' }}">
            {{ strtoupper(substr($pendaftar->nama,0,1)) }}
          </div>
          <div class="pb-1 flex-1 min-w-0">
            <h2 class="font-bold text-gray-900 text-base truncate">{{ $pendaftar->nama }}</h2>
            <p class="text-sm text-gray-500 font-mono">{{ $pendaftar->nisn }}</p>
          </div>
          <div class="pb-1">
            @if($pendaftar->status_verifikasi==='Terverifikasi')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Terverifikasi
              </span>
            @elseif($pendaftar->status_verifikasi==='Ditolak')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Ditolak
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Belum
              </span>
            @endif
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Gender</p>
            <span class="text-sm font-semibold {{ $pendaftar->gender==='Putra'?'text-blue-700':'text-pink-700' }}">
              {{ $pendaftar->gender==='Putra'?'♂':'♀' }} {{ $pendaftar->gender }}
            </span>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Lomba</p>
            <p class="text-sm font-semibold text-gray-900 truncate">{{ $pendaftar->lomba?->nama_lomba??'-' }}</p>
            @if($pendaftar->lomba)
            <span class="text-[10px] {{ $pendaftar->lomba->tipe==='team'?'text-violet-600':'text-gray-500' }}">
              {{ $pendaftar->lomba->tipe==='team'?'👥 Beregu':'👤 Perorangan' }}
            </span>
            @endif
          </div>
          @if($pendaftar->nama_tim)
          <div class="bg-violet-50 rounded-xl p-3">
            <p class="text-[10px] text-violet-400 font-semibold uppercase tracking-wider mb-1">Nama Tim</p>
            <p class="text-sm font-bold text-violet-800">{{ $pendaftar->nama_tim }}</p>
          </div>
          @endif
          <div class="bg-gray-50 rounded-xl p-3 col-span-2 sm:col-span-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Sekolah</p>
            <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nama_sekolah }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $pendaftar->alamat_sekolah }}</p>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">HP Peserta</p>
            <a href="tel:{{ $pendaftar->hp_peserta }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ $pendaftar->hp_peserta??'-' }}</a>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Pendamping</p>
            <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nama_pendamping??'-' }}</p>
            @if($pendaftar->hp_pendamping)
            <a href="tel:{{ $pendaftar->hp_pendamping }}" class="text-xs text-blue-600 hover:underline">{{ $pendaftar->hp_pendamping }}</a>
            @endif
          </div>
          @if($pendaftar->link_twibbon)
          <div class="bg-pink-50 rounded-xl p-3">
            <p class="text-[10px] text-pink-400 font-semibold uppercase tracking-wider mb-1">Twibbon</p>
            <a href="{{ $pendaftar->link_twibbon }}" target="_blank"
               class="text-xs text-pink-600 hover:text-pink-800 font-semibold flex items-center gap-1">
              Lihat Link
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>
          </div>
          @endif
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Tanggal Daftar</p>
            <p class="text-sm text-gray-700">{{ $pendaftar->created_at->format('d M Y, H:i') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Anggota Tim --}}
    @if($pendaftar->lomba?->tipe === 'team' && $pendaftar->anggotaTim->count() > 0)
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 text-sm">
        <div class="w-6 h-6 bg-violet-100 rounded-lg flex items-center justify-center">
          <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        Anggota Tim — {{ $pendaftar->nama_tim }}
      </h3>

      {{-- Kapten (pendaftar utama) --}}
      <div class="flex items-center gap-3 p-3 bg-violet-50 border border-violet-200 rounded-xl mb-2">
        <div class="w-8 h-8 bg-violet-200 rounded-lg flex items-center justify-center text-violet-700 text-xs font-bold shrink-0">K</div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-gray-900">{{ $pendaftar->nama }}</p>
          <p class="text-xs text-gray-500 font-mono">{{ $pendaftar->nisn }}</p>
        </div>
        <span class="text-[10px] bg-violet-200 text-violet-700 px-2 py-0.5 rounded-full font-semibold shrink-0">Kapten</span>
      </div>

      {{-- Anggota lain --}}
      @foreach($pendaftar->anggotaTim as $a)
      <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl mb-2">
        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 text-xs font-bold shrink-0">
          {{ $a->urutan }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-900">{{ $a->nama }}</p>
          <div class="flex items-center gap-2 text-xs text-gray-400">
            @if($a->nisn)<span class="font-mono">{{ $a->nisn }}</span>@endif
            @if($a->kelas)<span>{{ $a->kelas }}</span>@endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- Dokumen --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 text-sm">Dokumen Upload</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach([['kartu_siswa',$pendaftar->file_kartu_siswa,'Kartu Siswa','blue'],['bukti_pembayaran',$pendaftar->file_bukti_pembayaran,'Bukti Pembayaran','emerald']] as [$folder,$file,$label,$c])
        <div class="border border-gray-100 rounded-xl p-4">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ $label }}</p>
          @if(str_starts_with($file,'dummy_'))
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-5 text-center text-xs text-gray-400">Data Demo</div>
          @else
            <a href="{{ route('admin.uploads.serve',['folder'=>$folder,'filename'=>$file]) }}" target="_blank"
               class="flex items-center gap-3 bg-{{ $c }}-50 hover:bg-{{ $c }}-100 border border-{{ $c }}-200 rounded-xl p-3 transition-all group">
              <div class="w-9 h-9 bg-{{ $c }}-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-{{ $c }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-{{ $c }}-700">Lihat File</p>
                <p class="text-xs text-{{ $c }}-400 truncate">{{ $file }}</p>
              </div>
            </a>
          @endif
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ── Sidebar Aksi ── --}}
  <div class="space-y-5">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 text-sm">Ubah Status</h3>
      <form method="POST" action="{{ route('admin.pendaftar.verifikasi',$pendaftar) }}">
        @csrf @method('PATCH')
        <div class="space-y-2 mb-4">
          @foreach([['Belum','amber','⏳ Belum'],['Terverifikasi','emerald','✅ Terverifikasi'],['Ditolak','red','❌ Ditolak']] as [$val,$color,$label])
          <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                        {{ $pendaftar->status_verifikasi===$val?'border-'.$color.'-400 bg-'.$color.'-50':'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
            <input type="radio" name="status" value="{{ $val }}" {{ $pendaftar->status_verifikasi===$val?'checked':'' }} class="accent-blue-600">
            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
          </label>
          @endforeach
        </div>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all active:scale-95">
          Simpan Status
        </button>
      </form>
    </div>

    <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
      <h3 class="font-bold text-red-700 mb-1 text-sm">Hapus Data</h3>
      <p class="text-xs text-red-400 mb-4">Tindakan ini tidak bisa dibatalkan.</p>
      <form method="POST" action="{{ route('admin.pendaftar.destroy',$pendaftar) }}"
            data-confirm='Yakin hapus data {{ addslashes($pendaftar->nama) }}?'>
        @csrf @method('DELETE')
        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all active:scale-95">
          🗑️ Hapus Pendaftar
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
