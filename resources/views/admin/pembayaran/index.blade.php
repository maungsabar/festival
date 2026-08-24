@extends('layouts.admin')
@section('title', 'Kelola Pembayaran')
@section('admin_content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Kelola Pembayaran</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $rekenings->count() }} rekening terdaftar</p>
  </div>
  <a href="{{ route('admin.pembayaran.create') }}"
     class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
            font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>Tambah Rekening
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
  @forelse($rekenings as $r)
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm {{ !$r->aktif?'opacity-60':'' }}">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                  {{ $r->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
        {{ $r->gender==='Putra' ? '♂' : '♀' }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-gray-900 text-sm truncate">
          {{ $r->nama_bank }} — {{ $r->nomor_rekening }}
          @if($r->untuk_merchandise)
          <span class="ml-1 text-[9px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full align-middle">Juga di Merchandise</span>
          @endif
        </p>
        <p class="text-xs text-gray-400 truncate">a.n. {{ $r->atas_nama }}</p>
      </div>
      <div class="flex items-center gap-1 shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $r->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
        <span class="text-xs {{ $r->aktif?'text-emerald-600':'text-gray-400' }}">{{ $r->aktif?'Aktif':'Nonaktif' }}</span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.pembayaran.edit', $r) }}"
         class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg font-medium transition-all">Edit</a>
      <form method="POST" action="{{ route('admin.pembayaran.destroy', $r) }}" class="flex-1"
            data-confirm='{{ $r->untuk_merchandise ? "Lepas rekening ".addslashes($r->nama_bank)." dari daftar pendaftaran? (rekening tetap ada di Setup Merchandise)" : "Hapus rekening ".addslashes($r->nama_bank)."?" }}'>
        @csrf @method('DELETE')
        <button class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg transition-all">{{ $r->untuk_merchandise ? 'Lepas' : 'Hapus' }}</button>
      </form>
    </div>
  </div>
  @empty
  <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm text-gray-400 text-sm">Belum ada rekening pembayaran.</div>
  @endforelse
</div>

{{-- Desktop Table --}}
<div class="hidden sm:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          @if($role === 'superadmin')
          <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Kategori</th>
          @endif
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bank</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Rekening</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Atas Nama</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Status</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($rekenings as $r)
        <tr class="hover:bg-gray-50/70 transition-colors {{ !$r->aktif?'opacity-60':'' }}">
          @if($role === 'superadmin')
          <td class="px-5 py-3">
            <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                         {{ $r->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
              {{ $r->gender==='Putra' ? '♂' : '♀' }} {{ $r->gender }}
            </span>
          </td>
          @endif
          <td class="px-4 py-3">
            <p class="font-semibold text-sm text-gray-900">
              {{ $r->nama_bank }}
              @if($r->untuk_merchandise)
              <span class="ml-1 text-[9px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full align-middle">Juga di Merchandise</span>
              @endif
            </p>
          </td>
          <td class="px-4 py-3"><p class="text-sm text-gray-700 font-mono tracking-wide">{{ $r->nomor_rekening }}</p></td>
          <td class="px-4 py-3"><p class="text-sm text-gray-600">{{ $r->atas_nama }}</p></td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full {{ $r->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
              <span class="text-xs text-gray-600">{{ $r->aktif?'Aktif':'Nonaktif' }}</span>
            </div>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.pembayaran.edit', $r) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium">Edit</a>
              <form method="POST" action="{{ route('admin.pembayaran.destroy', $r) }}"
                    data-confirm='{{ $r->untuk_merchandise ? "Lepas rekening ".addslashes($r->nama_bank)." dari daftar pendaftaran? (rekening tetap ada di Setup Merchandise)" : "Hapus rekening ".addslashes($r->nama_bank)."?" }}'>
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all">{{ $r->untuk_merchandise ? 'Lepas' : 'Hapus' }}</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada rekening pembayaran.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
