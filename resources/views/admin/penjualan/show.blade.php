@extends('layouts.admin')
@section('title', 'Detail Pesanan — ' . $penjualan->nama_pembeli)
@section('admin_content')

<div class="mb-6">
  <a href="{{ route('admin.penjualan.index') }}"
     class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>Kembali ke Daftar
  </a>
  <h1 class="text-xl sm:text-2xl font-black text-gray-900">Detail Pesanan</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- ── Info Utama ── --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Profile card --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
      <div class="h-16 bg-gradient-to-r {{ $penjualan->gender==='Putra'?'from-blue-500 to-blue-700':'from-pink-500 to-pink-700' }}"></div>
      <div class="px-5 pb-5">
        <div class="flex items-end gap-3 -mt-7 mb-4">
          <div class="w-14 h-14 rounded-2xl border-4 border-white shadow flex items-center justify-center text-2xl font-black
                      {{ $penjualan->gender==='Putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700' }}">
            {{ strtoupper(substr($penjualan->nama_pembeli,0,1)) }}
          </div>
          <div class="pb-1 flex-1 min-w-0">
            <h2 class="font-bold text-gray-900 text-base truncate">{{ $penjualan->nama_pembeli }}</h2>
            <p class="text-sm text-gray-500 font-mono">{{ $penjualan->hp_pembeli }}</p>
          </div>
          <div class="pb-1">
            @if($penjualan->status==='Dikonfirmasi')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Dikonfirmasi
              </span>
            @elseif($penjualan->status==='Dibatalkan')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Dibatalkan
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Menunggu Verifikasi
              </span>
            @endif
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Gender</p>
            <span class="text-sm font-semibold {{ $penjualan->gender==='Putra'?'text-blue-700':'text-pink-700' }}">
              {{ $penjualan->gender==='Putra'?'♂':'♀' }} {{ $penjualan->gender }}
            </span>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">No. WhatsApp</p>
            <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($penjualan->hp_pembeli) }}" target="_blank"
               class="text-sm font-semibold text-blue-600 hover:underline">{{ $penjualan->hp_pembeli }}</a>
          </div>
          <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Tanggal Order</p>
            <p class="text-sm text-gray-700">{{ $penjualan->created_at->format('d M Y, H:i') }}</p>
          </div>

          <div class="bg-gray-50 rounded-xl p-3 col-span-2 sm:col-span-3">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Produk Dipesan</p>
            <div class="flex items-center gap-3">
              @if($penjualan->merchandise?->gambar)
              <div class="w-12 h-12 rounded-lg border border-gray-200 bg-white overflow-hidden shrink-0">
                <img src="{{ asset('storage/merchandise/'.$penjualan->merchandise->gambar) }}" class="w-full h-full object-cover">
              </div>
              @endif
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $penjualan->merchandise?->nama ?? 'Produk sudah dihapus' }}</p>
                <p class="text-xs text-gray-500">{{ $penjualan->jumlah }} × Rp{{ number_format($penjualan->harga_satuan,0,',','.') }}</p>
              </div>
            </div>
          </div>

          <div class="bg-{{ $penjualan->gender==='Putra'?'blue':'pink' }}-50 rounded-xl p-3 col-span-2 sm:col-span-3">
            <p class="text-[10px] text-{{ $penjualan->gender==='Putra'?'blue':'pink' }}-500 font-semibold uppercase tracking-wider mb-1">Total Pembayaran</p>
            <p class="text-lg font-black text-gray-900">Rp{{ number_format($penjualan->total_harga,0,',','.') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Bukti Transfer --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 text-sm">Bukti Transfer</h3>
      <div class="border border-gray-100 rounded-xl p-4 max-w-xs">
        <a href="{{ route('admin.uploads.serve',['folder'=>'bukti_transfer_merchandise','filename'=>$penjualan->bukti_transfer]) }}" target="_blank"
           class="flex items-center gap-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl p-3 transition-all group">
          <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div class="min-w-0">
            <p class="text-sm font-semibold text-emerald-700">Lihat Bukti Transfer</p>
            <p class="text-xs text-emerald-400 truncate">{{ $penjualan->bukti_transfer }}</p>
          </div>
        </a>
      </div>
    </div>
  </div>

  {{-- ── Sidebar Aksi ── --}}
  <div class="space-y-5">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 text-sm">Ubah Status</h3>
      <form method="POST" action="{{ route('admin.penjualan.status',$penjualan) }}">
        @csrf @method('PATCH')
        <div class="space-y-2 mb-4">
          @foreach([['Menunggu Verifikasi','amber','⏳ Menunggu Verifikasi'],['Dikonfirmasi','emerald','✅ Dikonfirmasi'],['Dibatalkan','red','❌ Dibatalkan']] as [$val,$color,$label])
          <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                        {{ $penjualan->status===$val?'border-'.$color.'-400 bg-'.$color.'-50':'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
            <input type="radio" name="status" value="{{ $val }}" {{ $penjualan->status===$val?'checked':'' }} class="accent-blue-600">
            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
          </label>
          @endforeach
        </div>
        <p class="text-xs text-gray-400 mb-4">Mengubah ke "Dibatalkan" otomatis mengembalikan stok produk. Membatalkan lagi (kembali ke status aktif) akan mengurangi stok ulang.</p>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all active:scale-95">
          Simpan Status
        </button>
      </form>
    </div>

    <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
      <h3 class="font-bold text-red-700 mb-1 text-sm">Hapus Data</h3>
      <p class="text-xs text-red-400 mb-4">Tindakan ini tidak bisa dibatalkan. Stok TIDAK otomatis dikembalikan — batalkan pesanan dulu kalau ingin stok kembali.</p>
      <form method="POST" action="{{ route('admin.penjualan.destroy',$penjualan) }}"
            data-confirm='Yakin hapus data pesanan {{ addslashes($penjualan->nama_pembeli) }}?'>
        @csrf @method('DELETE')
        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-all active:scale-95">
          🗑️ Hapus Pesanan
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
