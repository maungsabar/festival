@extends('layouts.admin')
@section('title', 'Data Penjualan')

@section('admin_content')

<div class="flex flex-wrap items-start justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Data Penjualan</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $penjualans->total() }} pesanan ditemukan</p>
  </div>
</div>

{{-- Filter card --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4 sm:p-5 mb-5">
  <form method="GET" action="{{ route('admin.penjualan.index') }}">
    <div class="flex flex-col sm:flex-row gap-3">
      <div class="flex-1 relative">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
               placeholder="Cari nama pembeli, no. HP...">
      </div>
      @if($role === 'superadmin')
      <select name="gender"
              class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
        <option value="">Semua Gender</option>
        <option value="Putra" {{ request('gender')==='Putra'?'selected':'' }}>♂ Putra</option>
        <option value="Putri" {{ request('gender')==='Putri'?'selected':'' }}>♀ Putri</option>
      </select>
      @endif
      <select name="status"
              class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
        <option value="">Semua Status</option>
        <option value="Menunggu Verifikasi" {{ request('status')==='Menunggu Verifikasi'?'selected':'' }}>⏳ Menunggu Verifikasi</option>
        <option value="Dikonfirmasi"        {{ request('status')==='Dikonfirmasi'?'selected':'' }}>✅ Dikonfirmasi</option>
        <option value="Dibatalkan"          {{ request('status')==='Dibatalkan'?'selected':'' }}>❌ Dibatalkan</option>
      </select>
      <div class="flex gap-2">
        <button type="submit"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2
                       bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm
                       px-4 py-2.5 rounded-xl transition-all active:scale-95">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Filter
        </button>
        <a href="{{ route('admin.penjualan.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-200
                  bg-white hover:bg-gray-50 text-gray-600 text-sm font-semibold transition-all active:scale-95">
          Reset
        </a>
      </div>
    </div>
  </form>
</div>

{{-- Table card --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full min-w-[720px]">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembeli</th>
          @if($role === 'superadmin')
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
          @endif
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($penjualans as $p)
        <tr class="data-row hover:bg-blue-50/30 transition-colors">
          <td class="px-4 py-3.5 text-xs text-gray-400 tabular-nums">{{ $penjualans->firstItem() + $loop->index }}</td>
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                          {{ $p->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                {{ strtoupper(substr($p->nama_pembeli, 0, 1)) }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->nama_pembeli }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $p->hp_pembeli }}</p>
              </div>
            </div>
          </td>
          @if($role === 'superadmin')
          <td class="px-4 py-3.5">
            <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                         {{ $p->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
              {{ $p->gender==='Putra' ? '♂' : '♀' }} {{ $p->gender }}
            </span>
          </td>
          @endif
          <td class="px-4 py-3.5 text-sm text-gray-600">{{ $p->merchandise?->nama ?? '-' }}</td>
          <td class="px-4 py-3.5 text-sm text-gray-600 tabular-nums">{{ $p->jumlah }}</td>
          <td class="px-4 py-3.5 text-sm font-semibold text-gray-900 whitespace-nowrap">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</td>
          <td class="px-4 py-3.5">
            @if($p->status==='Dikonfirmasi')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Dikonfirmasi
              </span>
            @elseif($p->status==='Dibatalkan')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Dibatalkan
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Menunggu
              </span>
            @endif
          </td>
          <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">{{ $p->created_at->format('d/m/Y') }}</td>
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-1.5 flex-wrap">
              <a href="{{ route('admin.penjualan.show', $p) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium active:scale-95">
                Detail
              </a>
              <form method="POST" action="{{ route('admin.penjualan.status', $p) }}">
                @csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white cursor-pointer
                               focus:outline-none focus:ring-1 focus:ring-blue-400 transition-all">
                  <option value="Menunggu Verifikasi" {{ $p->status==='Menunggu Verifikasi'?'selected':'' }}>⏳ Menunggu</option>
                  <option value="Dikonfirmasi"        {{ $p->status==='Dikonfirmasi'?'selected':'' }}>✅ Konfirmasi</option>
                  <option value="Dibatalkan"          {{ $p->status==='Dibatalkan'?'selected':'' }}>❌ Batalkan</option>
                </select>
              </form>
              <form method="POST" action="{{ route('admin.penjualan.destroy', $p) }}"
                    data-confirm='Hapus data pesanan {{ addslashes($p->nama_pembeli) }}? Stok TIDAK otomatis dikembalikan — batalkan dulu pesanan ini kalau ingin stok kembali.'>
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all active:scale-95">
                  Hapus
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="px-6 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-gray-400">
              <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 7h6m0 10v-3m-3 3v-3m-3 3v-3m9-8H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2z"/>
                </svg>
              </div>
              <p class="text-sm">Belum ada data penjualan.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($penjualans->hasPages())
  <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
    {{ $penjualans->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection
