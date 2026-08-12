@extends('layouts.app')

@section('content')
@php
    $role       = session('admin_user.role');
    $username   = session('admin_user.username');
    $accentFrom = match($role) { 'admin_putra' => '#1d4ed8', 'admin_putri' => '#be185d', default => '#4f46e5' };
    $accentTo   = match($role) { 'admin_putra' => '#1e3a8a', 'admin_putri' => '#831843', default => '#312e81' };
    $roleLabel       = match($role) { 'admin_putra' => 'Admin Putra', 'admin_putri' => 'Admin Putri', default => 'Super Admin' };
    $roleIcon        = match($role) { 'admin_putra' => '♂', 'admin_putri' => '♀', default => '★' };
    $rolePillSidebar = match($role) { 'admin_putra' => 'bg-blue-500/25 text-blue-200', 'admin_putri' => 'bg-pink-500/25 text-pink-200', default => 'bg-violet-500/25 text-violet-200' };
    $rolePillHeader  = match($role) { 'admin_putra' => 'bg-blue-50 text-blue-700 border border-blue-200', 'admin_putri' => 'bg-pink-50 text-pink-700 border border-pink-200', default => 'bg-violet-50 text-violet-700 border border-violet-200' };
    $roleDesc        = match($role) { 'admin_putra' => 'Mengelola data kategori Putra', 'admin_putri' => 'Mengelola data kategori Putri', default => 'Akses penuh — semua data' };

    $navItems = [];
    if ($role === 'superadmin') {
        $navItems = [
            ['route' => 'admin.dashboard',                 'label' => 'Dashboard',          'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'match' => 'admin.dashboard'],
            ['route' => 'admin.pendaftar.index',           'label' => 'Pendaftar Putra',    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'match' => '', 'params' => ['gender' => 'Putra'], 'active_gender' => 'Putra'],
            ['route' => 'admin.pendaftar.index',           'label' => 'Pendaftar Putri',    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'match' => '', 'params' => ['gender' => 'Putri'], 'active_gender' => 'Putri'],
            ['route' => 'admin.pendaftar.index',           'label' => 'Semua Pendaftar',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'match' => 'admin.pendaftar.*', 'params' => []],
            ['route' => 'admin.lomba.index',               'label' => 'Kelola Lomba',       'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'match' => 'admin.lomba.*'],
            ['route' => 'admin.sponsor.index',             'label' => 'Kelola Sponsor',     'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'match' => 'admin.sponsor.*'],
            ['route' => 'admin.settings',                  'label' => 'Pengaturan',         'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'match' => 'admin.settings*'],
            ['route' => 'admin.users.index',               'label' => 'Kelola User',        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'match' => 'admin.users.*'],
        ];
    } else {
        $navItems = [
            ['route' => 'admin.dashboard',       'label' => 'Dashboard',      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'match' => 'admin.dashboard'],
            ['route' => 'admin.pendaftar.index', 'label' => 'Data Pendaftar', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'match' => 'admin.pendaftar.*'],
            ['route' => 'admin.lomba.index',     'label' => 'Kelola Lomba',   'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'match' => 'admin.lomba.*'],
        ];
    }
    $exportItems = $role === 'superadmin'
        ? [['admin.pendaftar.export', 'Export Semua', []], ['admin.pendaftar.export', 'Export Putra', ['gender'=>'Putra']], ['admin.pendaftar.export', 'Export Putri', ['gender'=>'Putri']]]
        : [['admin.pendaftar.export', 'Export CSV', []]];
@endphp

<style>
  /* Clean custom scrollbar for sidebar */
  #sidebar-nav::-webkit-scrollbar { width: 3px; }
  #sidebar-nav::-webkit-scrollbar-track { background: transparent; }
  #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 99px; }
  #sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
  #sidebar-nav { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.15) transparent; }

  /* Smooth nav link transitions */
  .nav-link { transition: background 0.18s ease, color 0.18s ease, transform 0.15s ease; }
  .nav-link:hover { transform: translateX(2px); }
  .nav-link.active { background: rgba(255,255,255,0.18); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12); }

  /* Card hover glow effect */
  .stat-card { transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s; }
  .stat-card:hover { transform: translateY(-2px); }

  /* Table row hover */
  .data-row { transition: background 0.15s; }

  /* Glassmorphism card */
  .glass-card { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }

  /* Action button hover */
  .action-card { transition: all 0.2s ease; }
  .action-card:hover { transform: translateY(-1px); }
</style>

<div class="flex min-h-screen bg-gray-50">

  {{-- ── Overlay (mobile) ── --}}
  <div id="overlay" onclick="closeSidebar()"
       class="fixed inset-0 bg-black/50 backdrop-blur-[2px] z-20 hidden lg:hidden"></div>

  {{-- ── Sidebar ── --}}
  <aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 z-30 flex flex-col shadow-2xl
           -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
    style="background: linear-gradient(170deg, {{ $accentFrom }}, {{ $accentTo }})">

    {{-- Brand (Header height matched to h-[60px]) --}}
    <div class="shrink-0 flex items-center gap-3 px-4 h-[60px] border-b border-white/10">
      <div class="w-8 h-8 rounded-xl bg-white/80 flex items-center justify-center shrink-0 overflow-hidden border border-white/20">
        @if($festivalLogo)
          <img src="{{ asset('storage/logos/'.$festivalLogo) }}" class="w-full h-full object-contain p-0.5" alt="">
        @else
          <span class="text-white text-base">🏆</span>
        @endif
      </div>
      <div class="min-w-0 flex-1">
        <p class="text-white font-bold text-sm leading-tight truncate">{{ $festivalName }}</p>
        <span class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded-full mt-0.5 {{ $rolePillSidebar }}">
          {{ $roleIcon }} {{ $roleLabel }}
        </span>
      </div>
      <button onclick="closeSidebar()" class="lg:hidden text-white/50 hover:text-white transition-colors shrink-0 p-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- Navigation with clean scrollbar --}}
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
      <p class="text-[10px] text-white/35 uppercase tracking-widest px-3 pb-2 font-semibold">Menu Utama</p>

      @foreach($navItems as $item)
      @php
        $isActive = false;
        if (!empty($item['active_gender'])) {
          $isActive = request()->routeIs('admin.pendaftar.*') && request('gender') === $item['active_gender'];
        } elseif (isset($item['params']) && empty($item['params']) && !empty($item['match']) && str_contains($item['match'], 'pendaftar')) {
          $isActive = request()->routeIs('admin.pendaftar.*') && !request('gender');
        } else {
          $isActive = !empty($item['match']) && request()->routeIs($item['match']);
        }
        $href = isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']);
      @endphp
      <a href="{{ $href }}"
         class="nav-link {{ $isActive ? 'active' : 'hover:bg-white/10' }}
                flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/{{ $isActive ? '100' : '70' }} font-{{ $isActive ? 'semibold' : 'medium' }}">
        <svg class="w-[17px] h-[17px] shrink-0 opacity-{{ $isActive ? '100' : '75' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2.2' : '1.8' }}" d="{{ $item['icon'] }}"/>
        </svg>
        <span class="truncate">{{ $item['label'] }}</span>
        @if($isActive)
          <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white shrink-0"></span>
        @endif
      </a>
      @endforeach

      <div class="pt-3 pb-1">
        <p class="text-[10px] text-white/35 uppercase tracking-widest px-3 pb-2 font-semibold">Export</p>
        @foreach($exportItems as [$eroute, $elabel, $eparams])
        <a href="{{ route($eroute, $eparams) }}"
           class="nav-link hover:bg-white/10 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/70 font-medium">
          <svg class="w-[17px] h-[17px] shrink-0 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span class="truncate">{{ $elabel }}</span>
        </a>
        @endforeach
      </div>
    </nav>

    {{-- User card --}}
    <div class="shrink-0 px-3 py-3 border-t border-white/10">
      <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-white/8 mb-1.5">
        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-xs font-bold text-white uppercase shrink-0">
          {{ strtoupper(substr($username, 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-white text-sm font-semibold truncate leading-tight">{{ $username }}</p>
          <p class="text-white/45 text-[11px] truncate">{{ $roleLabel }}</p>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl
                       text-sm text-red-300 hover:text-white hover:bg-red-500/20 transition-all font-medium">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Logout
        </button>
      </form>
    </div>
  </aside>

  {{-- ── Main wrapper ── --}}
  <div class="flex-1 flex flex-col min-h-screen lg:ml-64">

    {{-- Sticky Top Header (Compact h-[60px] & Fixed on Scroll) --}}
    <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-gray-200/80 shadow-sm px-4 sm:px-6 h-[60px] flex items-center justify-between transition-all">
      {{-- Left Side: Mobile Menu + Greeting, Hand Wave, Role & Access Status --}}
      <div class="flex items-center gap-3">
        <button onclick="openSidebar()" class="lg:hidden p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-600 focus:outline-none" aria-label="Buka Menu">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <div>
          <div class="flex items-center gap-1.5 flex-wrap">
            <span class="text-xs text-gray-400 font-medium hidden xs:inline">Selamat datang,</span>
            <h1 class="text-sm sm:text-base font-black text-gray-900 leading-tight flex items-center gap-1">
              <span>{{ $username }}</span>
              <span class="inline-block animate-bounce" style="animation-duration:2s">👋</span>
            </h1>
            <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $rolePillHeader }}">
              {{ $roleIcon }} {{ $roleLabel }}
            </span>
          </div>
          <p class="text-[11px] sm:text-xs text-gray-400 font-medium truncate max-w-[220px] sm:max-w-none">
            {{ $roleDesc }}
          </p>
        </div>
      </div>

      {{-- Right Side: Hari, Tanggal, Bulan, Tahun & Jam Live --}}
      <div class="flex items-center gap-2.5">
        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200/70 rounded-xl px-3 py-1.5 text-xs text-gray-600 font-semibold shadow-sm">
          <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shrink-0"></span>
          <span id="top-admin-datetime" class="capitalize"></span>
        </div>
      </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
      <div class="max-w-7xl mx-auto">
        @yield('admin_content')
      </div>
    </main>

    {{-- Footer --}}
    <footer class="text-center text-xs text-gray-400 py-4 border-t border-gray-100 bg-white">
      &copy; DNK Official — {{ $festivalName }} {{ $festivalYear }}
    </footer>
  </div>
</div>

@push('scripts')
<script>
function openSidebar() {
  document.getElementById('sidebar').classList.remove('-translate-x-full');
  document.getElementById('overlay').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  document.getElementById('sidebar').classList.add('-translate-x-full');
  document.getElementById('overlay').classList.add('hidden');
  document.body.style.overflow = '';
}
window.addEventListener('resize', () => {
  if (window.innerWidth >= 1024) closeSidebar();
});
// Top header clock (Hari, Tanggal, Bulan, Tahun & Jam)
function topClock() {
  const el = document.getElementById('top-admin-datetime');
  if (!el) return;
  const now = new Date();
  const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
  el.textContent = `${dateStr} • ${timeStr}`;
}
topClock(); setInterval(topClock, 1000);
</script>
@endpush

@endsection
