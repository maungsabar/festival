@extends('layouts.admin')
@section('title', 'Kelola Merchandise')
@section('admin_content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Kelola Merchandise</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $merchandises->count() }} merchandise terdaftar</p>
  </div>
  <a href="{{ route('admin.merchandise.create') }}"
     class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
            font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>Tambah Merchandise
  </a>
</div>

@if(session('success'))
<div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-5 text-sm text-emerald-800">
  <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  {{ session('success') }}
</div>
@endif

{{-- Mobile Cards --}}
<div class="space-y-3 sm:hidden">
  @forelse($merchandises as $m)
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm {{ !$m->aktif?'opacity-60':'' }}">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-12 h-12 rounded-xl border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
        @if($m->gambar)
          <img src="{{ asset('storage/merchandise/' . $m->gambar) }}" class="w-full h-full object-cover">
        @else
          <span class="text-gray-300 text-lg">🛍️</span>
        @endif
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-gray-900 text-sm truncate">{{ $m->nama }}</p>
        <p class="text-xs text-gray-400">Rp{{ number_format($m->harga, 0, ',', '.') }} · {{ $m->stok === null ? 'Stok tak terbatas' : 'Stok '.$m->stok }}</p>
      </div>
      <div class="flex items-center gap-1 shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $m->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
        <span class="text-xs {{ $m->aktif?'text-emerald-600':'text-gray-400' }}">{{ $m->aktif?'Aktif':'Nonaktif' }}</span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.merchandise.edit', $m) }}"
         class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg font-medium transition-all">Edit</a>
      <form method="POST" action="{{ route('admin.merchandise.destroy', $m) }}" class="flex-1"
            data-confirm='Hapus merchandise {{ addslashes($m->nama) }}?'>
        @csrf @method('DELETE')
        <button class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg transition-all">Hapus</button>
      </form>
    </div>
  </div>
  @empty
  <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm text-gray-400 text-sm">Belum ada merchandise.</div>
  @endforelse
</div>

{{-- Desktop Table --}}
<div class="hidden sm:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Foto</th>
          @if($role === 'superadmin')
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Kategori</th>
          @endif
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Status</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($merchandises as $m)
        <tr class="hover:bg-gray-50/70 transition-colors {{ !$m->aktif?'opacity-60':'' }}">
          <td class="px-5 py-3">
            <div class="w-11 h-11 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden">
              @if($m->gambar)
                <img src="{{ asset('storage/merchandise/' . $m->gambar) }}" class="w-full h-full object-cover">
              @else
                <span class="text-gray-300">🛍️</span>
              @endif
            </div>
          </td>
          @if($role === 'superadmin')
          <td class="px-4 py-3">
            <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                         {{ $m->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
              {{ $m->gender==='Putra' ? '♂' : '♀' }} {{ $m->gender }}
            </span>
          </td>
          @endif
          <td class="px-4 py-3"><p class="font-semibold text-sm text-gray-900">{{ $m->nama }}</p></td>
          <td class="px-4 py-3"><p class="text-sm text-gray-700">Rp{{ number_format($m->harga, 0, ',', '.') }}</p></td>
          <td class="px-4 py-3">
            <p class="text-sm {{ $m->stok !== null && $m->stok <= 0 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
              {{ $m->stok === null ? 'Tak terbatas' : $m->stok }}
            </p>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full {{ $m->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
              <span class="text-xs text-gray-600">{{ $m->aktif?'Aktif':'Nonaktif' }}</span>
            </div>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.merchandise.edit', $m) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium">Edit</a>
              <form method="POST" action="{{ route('admin.merchandise.destroy', $m) }}"
                    data-confirm='Hapus merchandise {{ addslashes($m->nama) }}?'>
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada merchandise.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
