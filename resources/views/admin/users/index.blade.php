@extends('layouts.admin')
@section('title','Kelola User')
@section('admin_content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">Kelola User</h1>
    <p class="text-gray-400 text-sm mt-0.5">{{ $users->count() }} user terdaftar</p>
  </div>
  <a href="{{ route('admin.users.create') }}"
     class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
            font-semibold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all active:scale-95">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah User
  </a>
</div>

{{-- Mobile cards --}}
<div class="space-y-3 sm:hidden">
  @forelse($users as $u)
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-white shrink-0
                  {{ $u->role==='superadmin'?'bg-violet-600':($u->role==='admin_putra'?'bg-blue-600':'bg-pink-600') }}">
        {{ strtoupper(substr($u->username,0,1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-gray-900 text-sm">{{ $u->username }}</p>
        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full
              {{ $u->role==='superadmin'?'bg-violet-100 text-violet-700':($u->role==='admin_putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700') }}">
          {{ match($u->role){'superadmin'=>'★ Super Admin','admin_putra'=>'♂ Admin Putra',default=>'♀ Admin Putri'} }}
        </span>
      </div>
      @if($u->id === session('admin_user.id'))
      <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-semibold">Anda</span>
      @endif
    </div>
    <div class="flex gap-2">
      <a href="{{ route('admin.users.edit',$u) }}"
         class="flex-1 text-center text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 rounded-lg font-medium transition-all">Edit</a>
      @if($u->id !== session('admin_user.id'))
      <form method="POST" action="{{ route('admin.users.destroy',$u) }}" class="flex-1"
            data-confirm='Hapus user {{ addslashes($u->username) }}?'>
        @csrf @method('DELETE')
        <button class="w-full text-xs bg-red-50 hover:bg-red-100 text-red-600 py-2 rounded-lg transition-all">Hapus</button>
      </form>
      @endif
    </div>
  </div>
  @empty
  <div class="bg-white rounded-2xl p-10 text-center text-gray-400 text-sm">Belum ada user.</div>
  @endforelse
</div>

{{-- Desktop table --}}
<div class="hidden sm:block bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
  <table class="w-full">
    <thead>
      <tr class="bg-gray-50 border-b border-gray-100">
        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-50">
      @forelse($users as $i => $u)
      <tr class="hover:bg-gray-50/70 transition-colors">
        <td class="px-5 py-4 text-sm text-gray-400">{{ $i+1 }}</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-white shrink-0
                        {{ $u->role==='superadmin'?'bg-violet-600':($u->role==='admin_putra'?'bg-blue-600':'bg-pink-600') }}">
              {{ strtoupper(substr($u->username,0,1)) }}
            </div>
            <div>
              <p class="font-semibold text-sm text-gray-900">{{ $u->username }}</p>
              @if($u->id===session('admin_user.id'))
              <span class="text-[10px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded-full font-semibold">Anda</span>
              @endif
            </div>
          </div>
        </td>
        <td class="px-5 py-4">
          <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full
                {{ $u->role==='superadmin'?'bg-violet-100 text-violet-700':($u->role==='admin_putra'?'bg-blue-100 text-blue-700':'bg-pink-100 text-pink-700') }}">
            {{ match($u->role){'superadmin'=>'★ Super Admin','admin_putra'=>'♂ Admin Putra',default=>'♀ Admin Putri'} }}
          </span>
        </td>
        <td class="px-5 py-4 text-xs text-gray-400">{{ $u->created_at->format('d/m/Y') }}</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.edit',$u) }}"
               class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 px-2.5 py-1.5 rounded-lg transition-all font-medium">Edit</a>
            @if($u->id!==session('admin_user.id'))
            <form method="POST" action="{{ route('admin.users.destroy',$u) }}"
                  data-confirm='Hapus user {{ addslashes($u->username) }}?'>
              @csrf @method('DELETE')
              <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded-lg transition-all">Hapus</button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">Belum ada user.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
