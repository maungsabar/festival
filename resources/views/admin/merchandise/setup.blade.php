@extends('layouts.admin')
@section('title', 'Setup Merchandise')
@section('admin_content')

@php
  $isPutra = $gender === 'Putra';
  $accent  = $isPutra ? 'blue' : 'pink';
@endphp

<div class="mb-6">
  <h1 class="text-xl sm:text-2xl font-black text-gray-900 flex items-center gap-2 flex-wrap">
    Setup Merchandise
    <span class="inline-flex items-center gap-1 text-sm font-bold px-2.5 py-1 rounded-full
                 {{ $isPutra ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
      {{ $isPutra ? '♂' : '♀' }} {{ $gender }}
    </span>
  </h1>
  <p class="text-gray-400 text-sm mt-0.5">Header katalog, nomor WhatsApp pemesanan, dan rekening pembayaran khusus merchandise kategori {{ $gender }}</p>
</div>

@if(count($allowed) > 1)
<div class="inline-flex items-center gap-1 bg-gray-100 p-1 rounded-xl border border-gray-200 mb-6">
  @foreach(['Putra','Putri'] as $g)
  <a href="{{ route('admin.merchandise.setup', ['gender' => strtolower($g)]) }}"
     class="px-4 py-2 text-sm font-bold rounded-lg transition-all
            {{ $gender === $g ? ($g==='Putra' ? 'bg-blue-600 text-white shadow-sm' : 'bg-pink-600 text-white shadow-sm') : 'text-gray-500 hover:text-gray-800' }}">
    {{ $g==='Putra' ? '♂' : '♀' }} {{ $g }}
  </a>
  @endforeach
</div>
@endif

@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-5 text-sm text-emerald-800">
  <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
  <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  <ul class="space-y-1">@foreach($errors->all() as $e)<li class="text-sm text-red-700">{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="max-w-3xl space-y-5">

  {{-- ── Header Katalog Publik ── --}}
  <form method="POST" action="{{ route('admin.merchandise.setup.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <input type="hidden" name="gender" value="{{ $gender }}">

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-1.5 flex items-center gap-2">
        <span class="w-6 h-6 bg-{{ $accent }}-100 text-{{ $accent }}-600 rounded-lg flex items-center justify-center text-xs">🖼️</span>
        Header Katalog Publik
      </h2>
      <p class="text-[11px] text-gray-400 mb-4">Tampil di bagian atas halaman <code>/lomba/{{ strtolower($gender) }}/merchandise</code>. Jika gambar kosong, sistem memakai gradasi warna bawaan.</p>

      <div class="mb-4">
        <p class="text-xs font-semibold text-gray-600 mb-2">Gambar Header</p>
        <label id="merchHeroLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-{{ $accent }}-200
               hover:border-{{ $accent }}-400 hover:bg-{{ $accent }}-50 rounded-2xl p-5 cursor-pointer transition-all bg-white">
          <input type="file" name="merchandise_hero_image" id="merchHeroInput" accept=".jpg,.jpeg,.png,.webp" class="sr-only"
                 data-max-size-kb="2048" data-error-target="merchHeroSizeError">
          <div id="merchHeroIdle" class="text-center">
            <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="text-xs text-gray-500 font-semibold">{{ $settings['merchandise_hero_image'] ? 'Ganti Gambar Header' : 'Klik upload gambar header' }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">JPG, PNG, WebP • Max 2MB</p>
          </div>
          <div id="merchHeroDone" class="hidden text-center">
            <img id="merchHeroPreviewNew" src="" class="w-14 h-14 object-cover rounded-xl border border-gray-200 mx-auto mb-1" alt="">
            <p id="merchHeroFileName" class="text-xs font-semibold text-gray-700 truncate max-w-[200px] mx-auto"></p>
            <p class="text-[10px] text-{{ $accent }}-600 mt-0.5">✓ File dipilih</p>
          </div>
        </label>
        <p id="merchHeroSizeError" class="hidden text-red-500 text-xs mt-1">Ukuran file terlalu besar! Maksimal ukuran file adalah 2 MB.</p>
        @error('merchandise_hero_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        @if($settings['merchandise_hero_image'])
        <div class="flex items-center gap-3 mt-3">
          <div class="w-14 h-14 border border-gray-200 bg-gray-50 rounded-lg flex items-center justify-center overflow-hidden shrink-0">
            <img src="{{ asset('storage/merchandise_header/' . $settings['merchandise_hero_image']) }}" class="w-full h-full object-cover">
          </div>
          <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="checkbox" name="remove_merchandise_hero_image" value="1" class="w-3.5 h-3.5 accent-red-500">
            <span class="text-xs text-red-500 font-medium">Hapus gambar header</span>
          </label>
        </div>
        @endif
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Judul Header</label>
          <input type="text" name="merchandise_judul" maxlength="150"
                 value="{{ old('merchandise_judul', $settings['merchandise_judul']) }}"
                 placeholder="Merchandise {{ $gender }}"
                 class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-3 text-sm bg-white
                        focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
          <p class="text-[10px] text-gray-400 mt-1">Kosongkan untuk memakai judul bawaan "Merchandise {{ $gender }}".</p>
          @error('merchandise_judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tagline Header</label>
          <input type="text" name="merchandise_tagline" maxlength="200"
                 value="{{ old('merchandise_tagline', $settings['merchandise_tagline']) }}"
                 placeholder="Dukung festival dengan merchandise resmi kami."
                 class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-xl px-4 py-3 text-sm bg-white
                        focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20 transition-all">
          @error('merchandise_tagline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
      </div>
    </div>

    {{-- ── Nomor WhatsApp Pemesanan ── --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6 mb-4">
      <h2 class="font-bold text-gray-900 text-sm mb-1.5 flex items-center gap-2">
        <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xs">📞</span>
        Nomor WhatsApp Pemesanan
      </h2>
      <p class="text-[11px] text-gray-400 mb-4">
        Pilih salah satu nomor WhatsApp yang sudah dikonfigurasi di menu Pengaturan Kategori {{ $gender }} untuk dipakai tombol "Pesan via WhatsApp" di katalog merchandise.
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach(['1','2'] as $slot)
        @php $ref = $waRef[$slot]; $kosong = empty($ref['nomor']); @endphp
        <label class="relative {{ $kosong ? 'cursor-not-allowed opacity-50' : 'cursor-pointer' }}">
          <input type="radio" name="merchandise_whatsapp_pilihan" value="{{ $slot }}" class="peer sr-only"
                 {{ $kosong ? 'disabled' : '' }}
                 {{ old('merchandise_whatsapp_pilihan', $settings['merchandise_whatsapp_pilihan']) === $slot ? 'checked' : '' }}>
          <div class="border-2 rounded-xl p-3.5 transition-all
                      peer-checked:border-{{ $accent }}-500 peer-checked:bg-{{ $accent }}-50
                      border-gray-200 {{ $kosong ? '' : 'hover:border-'.$accent.'-300' }}">
            <p class="font-bold text-xs text-gray-800">WhatsApp {{ $slot }} {{ !$kosong && $ref['nama'] ? '— '.$ref['nama'] : '' }}</p>
            <p class="text-xs text-gray-500 mt-0.5 font-mono">{{ $ref['nomor'] ?: 'Belum diatur' }}</p>
          </div>
        </label>
        @endforeach
      </div>
      @error('merchandise_whatsapp_pilihan')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
      <p class="text-[11px] text-gray-400 mt-3">
        Untuk mengubah nomor atau nama di atas, buka menu
        <a href="{{ route('admin.settings') }}" class="text-{{ $accent }}-600 hover:underline font-semibold">Pengaturan Kategori</a>.
      </p>
    </div>

    <div class="flex justify-end">
      <button type="submit"
              class="inline-flex items-center justify-center gap-2 bg-{{ $accent }}-600 hover:bg-{{ $accent }}-700
                     text-white font-bold text-sm px-6 py-3 rounded-xl transition-all active:scale-95 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Setup {{ $gender }}
      </button>
    </div>
  </form>

  {{-- ── Rekening Pembayaran Merchandise ── --}}
  <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-6">
    <h2 class="font-bold text-gray-900 text-sm mb-1.5 flex items-center gap-2">
      <span class="w-6 h-6 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xs">🏦</span>
      Rekening Pembayaran Merchandise
    </h2>
    <p class="text-[11px] text-gray-400 mb-4">
      Boleh rekening yang sama dengan pendaftaran lomba, atau rekening baru khusus merchandise — tinggal pilih dari Kelola Pembayaran di bawah, atau tambahkan baru.
    </p>

    <div class="space-y-2.5 mb-5">
      @forelse($rekenings as $r)
      <div id="rekening-view-{{ $r->id }}" class="flex items-center gap-3 border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 {{ !$r->aktif ? 'opacity-60' : '' }}">
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-sm text-gray-900 truncate">
            {{ $r->nama_bank }} — <span class="font-mono">{{ $r->nomor_rekening }}</span>
            @if($r->untuk_pendaftaran)
            <span class="ml-1 text-[9px] font-bold bg-{{ $accent }}-100 text-{{ $accent }}-700 px-1.5 py-0.5 rounded-full align-middle">Dipakai juga di Pendaftaran</span>
            @endif
          </p>
          <p class="text-xs text-gray-400 truncate">a.n. {{ $r->atas_nama }} · {{ $r->aktif ? 'Aktif' : 'Nonaktif' }}</p>
        </div>
        <button type="button"
                onclick="document.getElementById('rekening-view-{{ $r->id }}').classList.add('hidden'); document.getElementById('rekening-edit-{{ $r->id }}').classList.remove('hidden');"
                class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg transition-all font-medium shrink-0">Edit</button>
        <form method="POST" action="{{ route('admin.merchandise.rekening.destroy', $r) }}" class="shrink-0"
              data-confirm='{{ $r->untuk_pendaftaran ? "Lepas rekening ".addslashes($r->nama_bank)." dari daftar merchandise? (rekening tetap ada di Kelola Pembayaran)" : "Hapus rekening merchandise ".addslashes($r->nama_bank)."?" }}'>
          @csrf @method('DELETE')
          <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg transition-all font-medium">{{ $r->untuk_pendaftaran ? 'Lepas' : 'Hapus' }}</button>
        </form>
      </div>

      <div id="rekening-edit-{{ $r->id }}" class="hidden border border-{{ $accent }}-200 bg-{{ $accent }}-50/30 rounded-xl p-4">
        <form method="POST" action="{{ route('admin.merchandise.rekening.update', $r) }}">
          @csrf @method('PUT')
          <input type="hidden" name="gender" value="{{ $gender }}">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
            <input type="text" name="nama_bank" value="{{ $r->nama_bank }}" placeholder="Nama Bank" required
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
            <input type="text" name="nomor_rekening" value="{{ $r->nomor_rekening }}" placeholder="Nomor Rekening" required
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
            <input type="text" name="atas_nama" value="{{ $r->atas_nama }}" placeholder="Atas Nama" required
                   class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
          </div>
          <div class="flex items-center justify-between gap-3">
            <label class="flex items-center gap-1.5 cursor-pointer">
              <input type="checkbox" name="aktif" value="1" {{ $r->aktif ? 'checked' : '' }} class="w-3.5 h-3.5 accent-{{ $accent }}-500">
              <span class="text-xs text-gray-600 font-medium">Aktif</span>
            </label>
            <div class="flex items-center gap-2">
              <button type="button"
                      onclick="document.getElementById('rekening-edit-{{ $r->id }}').classList.add('hidden'); document.getElementById('rekening-view-{{ $r->id }}').classList.remove('hidden');"
                      class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg transition-all font-medium">Batal</button>
              <button type="submit"
                      class="text-xs bg-{{ $accent }}-600 hover:bg-{{ $accent }}-700 text-white px-3 py-1.5 rounded-lg transition-all font-medium">Simpan</button>
            </div>
          </div>
        </form>
      </div>
      @empty
      <div class="border border-dashed border-gray-200 rounded-xl p-6 text-center text-sm text-gray-400">
        Belum ada rekening merchandise untuk kategori {{ $gender }}.
      </div>
      @endforelse
    </div>

    {{-- ── Pilih dari Kelola Pembayaran ── --}}
    <div class="border-t border-gray-100 pt-4 mb-5">
      <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Pilih dari Kelola Pembayaran</p>
      <p class="text-[11px] text-gray-400 mb-3">Pakai nomor rekening yang sudah ada di menu Kelola Pembayaran, tanpa input ulang.</p>
      @forelse($rekeningsPilihan as $r)
      <div class="flex items-center gap-3 border border-dashed border-gray-200 rounded-xl px-4 py-3 mb-2">
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-sm text-gray-900 truncate">{{ $r->nama_bank }} — <span class="font-mono">{{ $r->nomor_rekening }}</span></p>
          <p class="text-xs text-gray-400 truncate">a.n. {{ $r->atas_nama }}</p>
        </div>
        <form method="POST" action="{{ route('admin.merchandise.rekening.attach', $r) }}" class="shrink-0">
          @csrf @method('PATCH')
          <button class="text-xs bg-{{ $accent }}-50 hover:bg-{{ $accent }}-100 text-{{ $accent }}-700 px-3 py-1.5 rounded-lg transition-all font-medium">
            + Gunakan untuk Merchandise
          </button>
        </form>
      </div>
      @empty
      <p class="text-xs text-gray-400 italic">
        Semua rekening dari Kelola Pembayaran sudah dipakai di merchandise, atau belum ada rekening pendaftaran untuk kategori {{ $gender }}.
        <a href="{{ route('admin.pembayaran.index') }}" class="text-{{ $accent }}-600 hover:underline font-semibold not-italic">Buka Kelola Pembayaran</a>
      </p>
      @endforelse
    </div>

    <form method="POST" action="{{ route('admin.merchandise.rekening.store') }}" class="border-t border-gray-100 pt-4">
      @csrf
      <input type="hidden" name="gender" value="{{ $gender }}">
      <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Tambah Rekening Baru</p>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
        <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" placeholder="Nama Bank" required
               class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Nomor Rekening" required
               class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
        <input type="text" name="atas_nama" value="{{ old('atas_nama') }}" placeholder="Atas Nama" required
               class="w-full border border-gray-200 focus:border-{{ $accent }}-500 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $accent }}-500/20">
      </div>
      <button type="submit"
              class="inline-flex items-center gap-2 bg-{{ $accent }}-600 hover:bg-{{ $accent }}-700 text-white
                     font-semibold text-sm px-4 py-2.5 rounded-xl transition-all active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Rekening
      </button>
    </form>
  </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('merchHeroInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const r = new FileReader();
  r.onload = e => {
    document.getElementById('merchHeroIdle').classList.add('hidden');
    document.getElementById('merchHeroDone').classList.remove('hidden');
    document.getElementById('merchHeroPreviewNew').src = e.target.result;
    document.getElementById('merchHeroFileName').textContent = file.name;
  };
  r.readAsDataURL(file);
});
</script>
@endpush
