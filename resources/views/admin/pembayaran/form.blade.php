@extends('layouts.admin')
@section('title', $rekening ? 'Edit Rekening' : 'Tambah Rekening')

@section('admin_content')
<div class="max-w-2xl mx-auto">
  <div class="mb-6">
    <a href="{{ route('admin.pembayaran.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>Kembali
    </a>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">{{ $rekening ? 'Edit Rekening' : 'Tambah Rekening' }}</h1>
  </div>

  @if($errors->any())
  <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <ul class="space-y-1">@foreach($errors->all() as $e)<li class="text-sm text-red-700">{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form method="POST" action="{{ $rekening ? route('admin.pembayaran.update', $rekening) : route('admin.pembayaran.store') }}">
    @csrf @if($rekening) @method('PUT') @endif

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-4 space-y-4">

      {{-- Kategori / Gender — terkunci untuk admin_putra & admin_putri --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
        @if(count($allowed) === 1)
          @php $g = $allowed[0]; @endphp
          <input type="hidden" name="gender" value="{{ $g }}">
          <div class="flex items-center gap-2.5 border border-gray-200 bg-gray-50 rounded-xl px-4 py-3">
            <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full
                         {{ $g==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
              {{ $g==='Putra' ? '♂' : '♀' }} {{ $g }}
            </span>
            <span class="text-xs text-gray-400">Otomatis terkunci sesuai hak akses akun Anda</span>
          </div>
        @else
          <select name="gender" required
                  class="w-full border {{ $errors->has('gender')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                         rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
            <option value="">— Pilih kategori —</option>
            <option value="Putra" {{ old('gender', $rekening?->gender)==='Putra'?'selected':'' }}>♂ Putra</option>
            <option value="Putri" {{ old('gender', $rekening?->gender)==='Putri'?'selected':'' }}>♀ Putri</option>
          </select>
        @endif
      </div>

      {{-- Nama Bank --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
        <input type="text" name="nama_bank" value="{{ old('nama_bank', $rekening?->nama_bank) }}"
               class="w-full border {{ $errors->has('nama_bank')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                      rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Contoh: Bank BCA, Bank Mandiri" required>
      </div>

      {{-- Nomor Rekening --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Rekening <span class="text-red-500">*</span></label>
        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $rekening?->nomor_rekening) }}"
               class="w-full border {{ $errors->has('nomor_rekening')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                      rounded-xl px-4 py-3 text-sm font-mono tracking-wide focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Contoh: 1234567890" required>
      </div>

      {{-- Atas Nama --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Atas Nama <span class="text-red-500">*</span></label>
        <input type="text" name="atas_nama" value="{{ old('atas_nama', $rekening?->atas_nama) }}"
               class="w-full border {{ $errors->has('atas_nama')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                      rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Nama pemilik rekening" required>
      </div>

      {{-- Status Aktif --}}
      <div>
        <label class="flex items-center gap-3 cursor-pointer select-none p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors w-full">
          <div class="relative shrink-0">
            <input type="checkbox" name="aktif" value="1"
                   {{ old('aktif', $rekening ? ($rekening->aktif ? '1' : '') : '1') ? 'checked' : '' }} class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </div>
          <div>
            <p class="font-semibold text-gray-700 text-sm">Rekening Aktif</p>
            <p class="text-xs text-gray-400">Tampil ke pendaftar publik di formulir pendaftaran</p>
          </div>
        </label>
      </div>
    </div>

    <div class="flex gap-3">
      <button type="submit"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition-all active:scale-95">
        {{ $rekening ? 'Simpan Perubahan' : 'Tambah Rekening' }}
      </button>
      <a href="{{ route('admin.pembayaran.index') }}"
         class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200
                text-gray-700 font-semibold px-5 py-3 rounded-xl text-sm transition-all active:scale-95">Batal</a>
    </div>
  </form>
</div>
@endsection
