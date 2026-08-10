@extends('layouts.admin')
@section('title', 'Kelola Sponsor')
@section('admin_content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Kelola Sponsor</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $sponsors->count() }} sponsor terdaftar</p>
  </div>
  <a href="{{ route('admin.sponsor.create') }}"
     class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
            font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>Tambah Sponsor
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
  @forelse($sponsors as $s)
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm {{ !$s->aktif?'opacity-60':'' }}">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center bg-gray-50 overflow-hidden shrink-0">
        <img src="{{ asset('storage/sponsors/' . $s->logo) }}" class="w-full h-full object-contain p-1" alt="{{ $s->nama }}">
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-gray-900 text-sm truncate">{{ $s->nama }}</p>
        <p class="text-xs text-gray-400 truncate">{{ $s->link ?? '-' }}</p>
      </div>
      <div class="flex items-center gap-1 shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $s->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
        <span class="text-xs {{ $s->aktif?'text-emerald-600':'text-gray-400' }}">{{ $s->aktif?'Aktif':'Nonaktif' }}</span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.sponsor.edit', $s) }}"
         class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg font-medium transition-all">Edit</a>
      <form method="POST" action="{{ route('admin.sponsor.destroy', $s) }}" class="flex-1"
            data-confirm='Hapus sponsor {{ addslashes($s->nama) }}?'>
        @csrf @method('DELETE')
        <button class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg transition-all">Hapus</button>
      </form>
    </div>
  </div>
  @empty
  <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm text-gray-400 text-sm">Belum ada sponsor.</div>
  @endforelse
</div>

{{-- Desktop Table --}}
<div class="hidden sm:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Logo</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Sponsor</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Website Link</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Status</th>
          <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($sponsors as $s)
        <tr class="hover:bg-gray-50/70 transition-colors {{ !$s->aktif?'opacity-60':'' }}">
          <td class="px-5 py-3">
            <div class="w-14 h-10 rounded-lg border border-gray-100 flex items-center justify-center bg-gray-50 overflow-hidden">
              <img src="{{ asset('storage/sponsors/' . $s->logo) }}" class="w-full h-full object-contain p-1" alt="{{ $s->nama }}">
            </div>
          </td>
          <td class="px-4 py-3">
            <p class="font-semibold text-sm text-gray-900">{{ $s->nama }}</p>
          </td>
          <td class="px-4 py-3">
            @if($s->link)
              <a href="{{ $s->link }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                {{ $s->link }}
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
            @else
              <span class="text-xs text-gray-300">—</span>
            @endif
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <span class="w-1.5 h-1.5 rounded-full {{ $s->aktif?'bg-emerald-500':'bg-gray-300' }}"></span>
              <span class="text-xs text-gray-600">{{ $s->aktif?'Aktif':'Nonaktif' }}</span>
            </div>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.sponsor.edit', $s) }}"
                 class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium">Edit</a>
              <form method="POST" action="{{ route('admin.sponsor.destroy', $s) }}"
                    data-confirm='Hapus sponsor {{ addslashes($s->nama) }}?'>
                @csrf @method('DELETE')
                <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada sponsor.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
