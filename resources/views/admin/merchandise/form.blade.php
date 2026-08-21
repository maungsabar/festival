@extends('layouts.admin')
@section('title', $merchandise ? 'Edit Merchandise' : 'Tambah Merchandise')

@section('admin_content')
<div class="max-w-2xl mx-auto">
  <div class="mb-6">
    <a href="{{ route('admin.merchandise.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>Kembali
    </a>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">{{ $merchandise ? 'Edit Merchandise' : 'Tambah Merchandise' }}</h1>
  </div>

  @if($errors->any())
  <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <ul class="space-y-1">@foreach($errors->all() as $e)<li class="text-sm text-red-700">{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  <form method="POST" action="{{ $merchandise ? route('admin.merchandise.update', $merchandise) : route('admin.merchandise.store') }}" enctype="multipart/form-data">
    @csrf @if($merchandise) @method('PUT') @endif

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
            <option value="Putra" {{ old('gender', $merchandise?->gender)==='Putra'?'selected':'' }}>♂ Putra</option>
            <option value="Putri" {{ old('gender', $merchandise?->gender)==='Putri'?'selected':'' }}>♀ Putri</option>
          </select>
        @endif
      </div>

      {{-- Nama --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Merchandise <span class="text-red-500">*</span></label>
        <input type="text" name="nama" value="{{ old('nama', $merchandise?->nama) }}"
               class="w-full border {{ $errors->has('nama')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                      rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
               placeholder="Contoh: Kaos Festival, Gantungan Kunci" required>
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-gray-400 font-normal text-xs">(opsional)</span></label>
        <textarea name="deskripsi" rows="3"
                  class="w-full border {{ $errors->has('deskripsi')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                         rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                  placeholder="Bahan, ukuran, varian warna, dll.">{{ old('deskripsi', $merchandise?->deskripsi) }}</textarea>
      </div>

      {{-- Harga & Stok --}}
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
          <input type="number" name="harga" min="0" step="500" value="{{ old('harga', $merchandise?->harga) }}"
                 class="w-full border {{ $errors->has('harga')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                        rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="50000" required>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok</label>
          <input type="number" name="stok" min="0" value="{{ old('stok', $merchandise?->stok) }}"
                 class="w-full border {{ $errors->has('stok')?'border-red-400 bg-red-50':'border-gray-200 focus:border-blue-500' }}
                        rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Kosongkan = tak terbatas">
        </div>
      </div>

      {{-- Foto --}}
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Merchandise <span class="text-gray-400 text-xs font-normal">(PNG/JPG/WebP, maks 2MB)</span></label>
        <input type="file" name="gambar" accept="image/*"
               data-max-size-kb="2048" data-error-target="gambarSizeError"
               class="w-full border border-gray-200 focus:border-blue-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <p id="gambarSizeError" class="hidden text-red-500 text-xs mt-1">Ukuran file terlalu besar! Maksimal ukuran file adalah 2 MB.</p>
        @if($merchandise && $merchandise->gambar)
          <div class="mt-3 flex items-center gap-3">
            <div class="w-16 h-16 border border-gray-200 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden">
              <img src="{{ asset('storage/merchandise/' . $merchandise->gambar) }}" class="w-full h-full object-cover">
            </div>
            <div class="text-xs text-gray-500">
              <p class="font-semibold text-gray-700">Foto saat ini</p>
              <p class="truncate max-w-xs text-gray-400">{{ $merchandise->gambar }}</p>
            </div>
          </div>
        @endif
        @error('gambar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Status Aktif --}}
      <div>
        <label class="flex items-center gap-3 cursor-pointer select-none p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors w-full">
          <div class="relative shrink-0">
            <input type="checkbox" name="aktif" value="1"
                   {{ old('aktif', $merchandise ? ($merchandise->aktif ? '1' : '') : '1') ? 'checked' : '' }} class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </div>
          <div>
            <p class="font-semibold text-gray-700 text-sm">Merchandise Aktif</p>
            <p class="text-xs text-gray-400">Tampil di katalog publik & menu "Merchandise" muncul otomatis di navbar</p>
          </div>
        </label>
      </div>
    </div>

    <div class="flex gap-3">
      <button type="submit"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition-all active:scale-95">
        {{ $merchandise ? 'Simpan Perubahan' : 'Tambah Merchandise' }}
      </button>
      <a href="{{ route('admin.merchandise.index') }}"
         class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200
                text-gray-700 font-semibold px-5 py-3 rounded-xl text-sm transition-all active:scale-95">Batal</a>
    </div>
  </form>
</div>
@endsection
