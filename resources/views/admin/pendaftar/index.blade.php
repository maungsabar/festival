@extends('layouts.admin')
@section('title', 'Data Pendaftar')

@section('admin_content')

<div class="flex flex-wrap items-start justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Data Pendaftar</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $pendaftars->total() }} peserta ditemukan</p>
  </div>
<div class="relative" id="exportDropdown">
    <button onclick="document.getElementById('exportMenu').classList.toggle('hidden')"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50
                   text-gray-700 hover:text-blue-700 font-semibold text-sm px-4 py-2.5 rounded-xl
                   shadow-sm transition-all duration-200 active:scale-95">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Export
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </button>
    <div id="exportMenu" class="hidden absolute right-0 top-full mt-1.5 bg-white border border-gray-100
                                rounded-2xl shadow-xl z-20 w-44 py-1.5 overflow-hidden">
      <a href="{{ route('admin.pendaftar.export', array_merge(request()->only(['search','status','gender','jenjang','id_lomba']), ['format'=>'csv'])) }}"
         class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export CSV
      </a>
      <a href="{{ route('admin.pendaftar.export', array_merge(request()->only(['search','status','gender','jenjang','id_lomba']), ['format'=>'excel'])) }}"
         class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        Export Excel
      </a>
      <a href="{{ route('admin.pendaftar.export', array_merge(request()->only(['search','status','gender','jenjang','id_lomba']), ['format'=>'pdf'])) }}"
         class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Export PDF
      </a>
    </div>
  </div>
</div>

{{-- Filter card --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4 sm:p-5 mb-5">
  <form method="GET" action="{{ route('admin.pendaftar.index') }}">
    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3">
      <div class="flex-1 relative min-w-[180px]">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
               placeholder="Cari nama, NISN, sekolah...">
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
      <select name="jenjang"
              class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
        <option value="">Semua Jenjang</option>
        <option value="SMP"  {{ request('jenjang')==='SMP'?'selected':'' }}>SMP</option>
        <option value="SMA"  {{ request('jenjang')==='SMA'?'selected':'' }}>SMA</option>
        <option value="UMUM" {{ request('jenjang')==='UMUM'?'selected':'' }}>UMUM</option>
      </select>
      <select name="id_lomba"
              class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white max-w-[180px]
                     focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
        <option value="">Semua Lomba</option>
        @foreach($lombas as $l)
        <option value="{{ $l->id }}" {{ (string) request('id_lomba')===(string) $l->id?'selected':'' }}>{{ $l->nama_lomba }}</option>
        @endforeach
      </select>
      <select name="status"
              class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all">
        <option value="">Semua Status</option>
        <option value="Belum"         {{ request('status')==='Belum'?'selected':'' }}>⏳ Belum</option>
        <option value="Terverifikasi" {{ request('status')==='Terverifikasi'?'selected':'' }}>✅ Terverifikasi</option>
        <option value="Ditolak"       {{ request('status')==='Ditolak'?'selected':'' }}>❌ Ditolak</option>
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
        <a href="{{ route('admin.pendaftar.index') }}"
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
    <table class="w-full min-w-[640px]">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
          @if($role === 'superadmin')
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
          @endif
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenjang</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lomba</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($pendaftars as $p)
        <tr class="data-row hover:bg-blue-50/30 transition-colors">
          <td class="px-4 py-3.5 text-xs text-gray-400 tabular-nums">{{ $pendaftars->firstItem() + $loop->index }}</td>
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                          {{ $p->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                {{ strtoupper(substr($p->nama, 0, 1)) }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->nama }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $p->nisn }}</p>
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
          <td class="px-4 py-3.5">
            @if($p->lomba?->jenjang)
            <span class="inline-flex items-center text-xs font-semibold px-2 py-1 rounded-full
                         {{ match($p->lomba->jenjang) { 'SMP' => 'bg-teal-100 text-teal-700', 'SMA' => 'bg-indigo-100 text-indigo-700', default => 'bg-amber-100 text-amber-700' } }}">
              {{ $p->lomba->jenjang }}
            </span>
            @else
            <span class="text-xs text-gray-300">-</span>
            @endif
          </td>
          <td class="px-4 py-3.5 text-sm text-gray-600">{{ $p->lomba?->nama_lomba ?? '-' }}</td>
          <td class="px-4 py-3.5">
            @if($p->status_verifikasi==='Terverifikasi')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Terverifikasi
              </span>
            @elseif($p->status_verifikasi==='Ditolak')
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Ditolak
              </span>
            @else
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Belum
              </span>
            @endif
          </td>
          <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">{{ $p->created_at->format('d/m/Y') }}</td>
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-1.5 flex-wrap">
              <a href="{{ route('admin.pendaftar.show', $p) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium active:scale-95">
                Detail
              </a>
              <form method="POST" action="{{ route('admin.pendaftar.verifikasi', $p) }}">
                @csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white cursor-pointer
                               focus:outline-none focus:ring-1 focus:ring-blue-400 transition-all">
                  <option value="Belum"         {{ $p->status_verifikasi==='Belum'?'selected':'' }}>⏳ Belum</option>
                  <option value="Terverifikasi" {{ $p->status_verifikasi==='Terverifikasi'?'selected':'' }}>✅ Verifikasi</option>
                  <option value="Ditolak"       {{ $p->status_verifikasi==='Ditolak'?'selected':'' }}>❌ Tolak</option>
                </select>
              </form>
              <form method="POST" action="{{ route('admin.pendaftar.destroy', $p) }}"
                    data-confirm='Hapus {{ addslashes($p->nama) }}?'>
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
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <p class="text-sm">Tidak ada data pendaftar.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($pendaftars->hasPages())
  <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
    {{ $pendaftars->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('click', function(e) {
  const menu = document.getElementById('exportMenu');
  const drop = document.getElementById('exportDropdown');
  if (menu && drop && !drop.contains(e.target)) menu.classList.add('hidden');
});
</script>
@endpush
