@extends('layouts.admin')
@section('title','Kelola Lomba')
@section('admin_content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Kelola Lomba</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $lombas->count() }} lomba terdaftar</p>
  </div>
  <a href="{{ route('admin.lomba.create') }}"
     class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
            font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>Tambah Lomba
  </a>
</div>

{{-- Mobile cards --}}
<div class="space-y-3 sm:hidden">
  @forelse($lombas as $l)
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm {{ !$l->aktif?'opacity-60':'' }}">
    <div class="flex items-start gap-3 mb-3">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 overflow-hidden
                  {{ $l->gender==='Putra'?'bg-blue-100':'bg-pink-100' }}">
        @if($l->gambar)
          <img src="{{ asset('storage/lomba_images/' . $l->gambar) }}" class="w-full h-full object-cover">
        @else
          {{ $l->gender==='Putra'?'♂':'♀' }}
        @endif
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-gray-900 text-sm {{ !$l->aktif?'line-through text-gray-400':'' }}">{{ $l->nama_lomba }}</p>
        @if($l->file_guidebook)
          <a href="{{ asset('storage/guidebooks/' . $l->file_guidebook) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-violet-600 hover:text-violet-800 font-semibold mt-0.5">
            📄 Guidebook
          </a>
        @endif
        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
          <span class="text-[10px] font-medium px-2 py-0.5 rounded-full {{ $l->gender==='Putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700' }}">{{ $l->gender }}</span>
          <span class="text-[10px] font-medium px-2 py-0.5 rounded-full 
            @if($l->jenjang==='SMP') bg-teal-100 text-teal-700 
            @elseif($l->jenjang==='SMA') bg-indigo-100 text-indigo-700 
            @else bg-amber-100 text-amber-700 @endif">{{ $l->jenjang }}</span>
          <span class="text-[10px] font-medium px-2 py-0.5 rounded-full {{ $l->tipe==='team'?'bg-violet-100 text-violet-700':'bg-gray-100 text-gray-600' }}">
            {{ $l->tipe==='team'?'👥 Beregu':'👤 Perorangan' }}
          </span>
          @if($l->kuota)
          <span class="text-[10px] text-gray-500">{{ $l->pendaftars_count }}/{{ $l->kuota }} kuota</span>
          @else
          <span class="text-[10px] text-gray-400">{{ $l->pendaftars_count }} peserta</span>
          @endif
          @if($l->isFull())
          <span class="text-[10px] font-semibold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Penuh!</span>
          @endif
        </div>
      </div>
      <div class="flex items-center gap-1 shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $l->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
        <span class="text-xs {{ $l->aktif?'text-emerald-600':'text-gray-400' }}">{{ $l->aktif?'Aktif':'Nonaktif' }}</span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.lomba.edit',$l) }}"
         class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg font-medium transition-all">Edit</a>
      <form method="POST" action="{{ route('admin.lomba.toggle',$l) }}" class="flex-1">
        @csrf @method('PATCH')
        <button class="w-full text-xs py-2 rounded-lg font-medium transition-all
                       {{ $l->aktif?'bg-amber-50 hover:bg-amber-100 text-amber-700':'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }}">
          {{ $l->aktif?'Nonaktifkan':'Aktifkan' }}
        </button>
      </form>
      <form method="POST" action="{{ route('admin.lomba.destroy',$l) }}" class="flex-1"
            data-confirm='Hapus {{ addslashes($l->nama_lomba) }}?'>
        @csrf @method('DELETE')
        <button class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg transition-all">Hapus</button>
      </form>
    </div>
  </div>
  @empty
  <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm text-gray-400 text-sm">Belum ada lomba.</div>
  @endforelse
</div>

{{-- Desktop table --}}
<div class="hidden sm:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lomba</th>
          @if(session('admin_user.role')==='superadmin')
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
          @endif
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenjang</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kuota</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemenang</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($lombas as $l)
        <tr class="hover:bg-gray-50/70 transition-colors {{ !$l->aktif?'opacity-60':'' }}">
          <td class="px-5 py-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg overflow-hidden shrink-0 border border-gray-100 flex items-center justify-center bg-gray-50">
                @if($l->gambar)
                  <img src="{{ asset('storage/lomba_images/' . $l->gambar) }}" class="w-full h-full object-cover">
                @else
                  <span class="text-xs text-gray-400">🖼️</span>
                @endif
              </div>
              <div>
                <p class="font-semibold text-sm text-gray-900 {{ !$l->aktif?'line-through text-gray-400':'' }}">{{ $l->nama_lomba }}</p>
                @if($l->file_guidebook)
                  <a href="{{ asset('storage/guidebooks/' . $l->file_guidebook) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-violet-600 hover:text-violet-800 font-semibold mt-0.5">
                    📄 Guidebook
                  </a>
                @endif
              </div>
            </div>
          </td>
          @if(session('admin_user.role')==='superadmin')
          <td class="px-4 py-4">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $l->gender==='Putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700' }}">
              {{ $l->gender==='Putra'?'♂':'♀' }} {{ $l->gender }}
            </span>
          </td>
          @endif
          <td class="px-4 py-4">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full 
              @if($l->jenjang==='SMP') bg-teal-100 text-teal-700 
              @elseif($l->jenjang==='SMA') bg-indigo-100 text-indigo-700 
              @else bg-amber-100 text-amber-700 @endif">
              {{ $l->jenjang }}
            </span>
          </td>
          <td class="px-4 py-4">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $l->tipe==='team'?'bg-violet-100 text-violet-700':'bg-gray-100 text-gray-600' }}">
              {{ $l->tipe==='team'?'👥 Beregu':'👤 Perorangan' }}
            </span>
            @if($l->tipe==='team')
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $l->min_anggota }}–{{ $l->max_anggota }} org</p>
            @endif
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full {{ $l->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
              <span class="text-xs text-gray-600">{{ $l->aktif?'Aktif':'Nonaktif' }}</span>
            </div>
          </td>
          <td class="px-4 py-4">
            @if($l->kuota)
              <div class="flex items-center gap-2">
                <div class="w-16 bg-gray-100 rounded-full h-1.5">
                  <div class="h-1.5 rounded-full {{ $l->isFull()?'bg-red-500':'bg-emerald-500' }}"
                       style="width:{{ min(100, round($l->pendaftars_count/$l->kuota*100)) }}%"></div>
                </div>
                <span class="text-xs tabular-nums {{ $l->isFull()?'text-red-600 font-semibold':'text-gray-500' }}">
                  {{ $l->pendaftars_count }}/{{ $l->kuota }}
                  @if($l->isFull())<span class="text-red-500"> Penuh</span>@endif
                </span>
              </div>
            @else
              <span class="text-sm font-bold text-gray-900">{{ $l->pendaftars_count }}</span>
              <span class="text-xs text-gray-400 ml-1">∞</span>
            @endif
          </td>
          <td class="px-4 py-4">
            @if($l->pemenang && $l->tampil_pemenang)
              <div class="flex items-center gap-1.5">
                <span class="text-amber-500">🏆</span>
                <span class="text-xs text-gray-700 font-medium truncate max-w-[120px]">{{ $l->pemenang }}</span>
              </div>
            @elseif($l->pemenang)
              <span class="text-xs text-gray-400 italic">Disimpan (disembunyikan)</span>
            @else
              <span class="text-xs text-gray-300">—</span>
            @endif
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.lomba.edit',$l) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium">Edit</a>
              <form method="POST" action="{{ route('admin.lomba.toggle',$l) }}">
                @csrf @method('PATCH')
                <button class="text-xs px-2.5 py-1.5 rounded-lg transition-all font-medium
                               {{ $l->aktif?'bg-amber-50 hover:bg-amber-100 text-amber-700':'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }}">
                  {{ $l->aktif?'Nonaktifkan':'Aktifkan' }}
                </button>
              </form>
              <form method="POST" action="{{ route('admin.lomba.destroy',$l) }}"
                    data-confirm='Hapus {{ addslashes($l->nama_lomba) }}?'>
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada lomba.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
  <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
  </svg>
  <p class="text-xs text-amber-700">Lomba yang kuotanya penuh akan <strong>otomatis dinonaktifkan</strong> setelah pendaftar terakhir masuk. Lomba yang sudah memiliki pendaftar tidak dapat dihapus.</p>
</div>
@endsection
