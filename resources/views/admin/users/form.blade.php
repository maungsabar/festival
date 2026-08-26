@extends('layouts.admin')
@section('title', $user ? 'Edit User' : 'Tambah User')
@section('admin_content')

<div class="max-w-lg mx-auto">
  <div class="mb-6">
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>
    <h1 class="text-xl sm:text-2xl font-black text-gray-900">
      {{ $user ? 'Edit User' : 'Tambah User Baru' }}
    </h1>
  </div>

  @if($errors->any())
  <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4 mb-5">
    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <ul class="space-y-1">
      @foreach($errors->all() as $e)<li class="text-sm text-red-700">{{ $e }}</li>@endforeach
    </ul>
  </div>
  @endif

  <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
    <form method="POST"
          action="{{ $user ? route('admin.users.update',$user) : route('admin.users.store') }}">
      @csrf
      @if($user) @method('PUT') @endif

      {{-- Username --}}
      <div class="mb-5">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Username <span class="text-red-500">*</span>
        </label>
        <div class="relative">
          <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <input type="text" name="username"
                 value="{{ old('username', $user?->username) }}"
                 class="w-full border {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-gray-200 focus:border-blue-500' }}
                        rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Contoh: admin_baru" required>
        </div>
        <p class="text-xs text-gray-400 mt-1.5">Hanya huruf, angka, dan underscore (_)</p>
        @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Role --}}
      <div class="mb-5">
        <label class="block text-sm font-semibold text-gray-700 mb-2">
          Role <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          @foreach([
            ['superadmin','★ Super Admin','Akses penuh semua data','violet'],
            ['admin_putra','♂ Admin Putra','Kelola data putra','blue'],
            ['admin_putri','♀ Admin Putri','Kelola data putri','pink'],
          ] as [$val,$label,$desc,$color])
          <label class="relative cursor-pointer">
            <input type="radio" name="role" value="{{ $val }}" class="peer sr-only"
                   {{ old('role', $user?->role ?? 'admin_putra') === $val ? 'checked' : '' }}>
            <div class="border-2 rounded-2xl p-3.5 text-center transition-all duration-200
                        peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-50
                        border-gray-200 hover:border-{{ $color }}-300 hover:bg-{{ $color }}-50/30">
              <div class="text-xl mb-1">{{ explode(' ',$label)[0] }}</div>
              <p class="font-bold text-xs text-gray-800">{{ implode(' ',array_slice(explode(' ',$label),1)) }}</p>
              <p class="text-[10px] text-gray-400 mt-0.5">{{ $desc }}</p>
            </div>
            <div class="absolute top-2.5 right-2.5 w-4 h-4 rounded-full border-2 border-gray-300
                        peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-500 transition-all">
            </div>
          </label>
          @endforeach
        </div>
        @error('role')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
      </div>

      {{-- Password --}}
      <div class="mb-5">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Password {{ $user ? '' : '*' }}
          @if($user)<span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>@endif
        </label>
        <div class="relative">
          <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
          </div>
          <input type="password" name="password" id="pwInput"
                 class="w-full border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 focus:border-blue-500' }}
                        rounded-xl pl-10 pr-11 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                 {{ $user ? '' : 'required' }}>
          <button type="button" onclick="togglePw('pwInput','eyePw1','eyePw2')"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <svg id="eyePw1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="eyePw2" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
          </button>
        </div>
        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Konfirmasi Password --}}
      <div class="mb-7">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
          Konfirmasi Password {{ $user ? '' : '*' }}
        </label>
        <div class="relative">
          <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <input type="password" name="password_confirmation" id="pwConfirm"
                 class="w-full border border-gray-200 focus:border-blue-500 rounded-xl pl-10 pr-11 py-3 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                 placeholder="Ulangi password" {{ $user ? '' : 'required' }}>
          <button type="button" onclick="togglePw('pwConfirm','eyeCf1','eyeCf2')"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
            <svg id="eyeCf1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="eyeCf2" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="flex gap-3">
        <button type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition-all active:scale-95">
          {{ $user ? 'Simpan Perubahan' : 'Tambah User' }}
        </button>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200
                  text-gray-700 font-semibold px-5 py-3 rounded-xl text-sm transition-all active:scale-95">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function togglePw(inputId, eye1, eye2) {
  const inp = document.getElementById(inputId);
  const e1  = document.getElementById(eye1);
  const e2  = document.getElementById(eye2);
  if (inp.type === 'password') {
    inp.type = 'text'; e1.classList.add('hidden'); e2.classList.remove('hidden');
  } else {
    inp.type = 'password'; e1.classList.remove('hidden'); e2.classList.add('hidden');
  }
}
</script>
@endpush
