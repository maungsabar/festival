@extends('layouts.app')
@section('title', 'Login Admin — ' . $festivalName)

@section('content')
<div class="min-h-screen flex">

    {{-- ── Kiri: Branding Panel (hidden di mobile) ── --}}
    <div class="hidden lg:flex lg:w-[52%] xl:w-[58%] relative overflow-hidden
                bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900
                flex-col items-center justify-center p-12">

        {{-- Background decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 -left-32 w-80 h-80 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                        w-[600px] h-[600px] border border-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                        w-[400px] h-[400px] border border-white/5 rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                        w-[200px] h-[200px] border border-white/10 rounded-full"></div>
            {{-- Grid dots --}}
            <div class="absolute inset-0"
                 style="background-image:radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);background-size:32px 32px"></div>
        </div>

        {{-- Content --}}
        <div class="relative text-center max-w-sm">
            {{-- Logo --}}
            <div class="w-20 h-20 bg-white/80 border border-white/20 backdrop-blur rounded-3xl
                        flex items-center justify-center mx-auto mb-6 shadow-2xl overflow-hidden">
                @if($festivalLogo)
                    <img src="{{ asset('storage/logos/' . $festivalLogo) }}"
                         class="w-full h-full object-contain p-2" alt="{{ $festivalName }}">
                @else
                    <span class="text-4xl">🏆</span>
                @endif
            </div>

            <h1 class="text-3xl font-black text-white mb-3 leading-tight">
                {{ $festivalName }}<br>
                <span class="text-blue-300">{{ $festivalYear }}</span>
            </h1>

            @if($festivalTagline)
            <p class="text-blue-200 text-sm mb-8">{{ $festivalTagline }}</p>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3 mb-10">
                @foreach([
                    ['6+', 'Kategori Lomba'],
                    ['2',  'Divisi'],
                    ['🎖️', 'Berhadiah'],
                ] as [$val, $label])
                <div class="bg-white/10 border border-white/10 rounded-2xl p-3 text-center">
                    <div class="text-xl font-black text-white">{{ $val }}</div>
                    <div class="text-blue-300 text-[11px] mt-0.5">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            {{-- Role badges --}}
            <div class="space-y-2">
                <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider mb-3">Tingkat Akses</p>
                @foreach([
                    ['★', 'Super Admin',  'Akses penuh semua data',       'violet'],
                    ['♂', 'Admin Putra',  'Kelola data kategori putra',   'blue'],
                    ['♀', 'Admin Putri',  'Kelola data kategori putri',   'pink'],
                ] as [$icon, $role, $desc, $color])
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-left">
                    <div class="w-7 h-7 bg-{{ $color }}-500/30 border border-{{ $color }}-400/30
                                rounded-lg flex items-center justify-center text-{{ $color }}-300 text-sm shrink-0">
                        {{ $icon }}
                    </div>
                    <div>
                        <p class="text-white text-xs font-semibold leading-tight">{{ $role }}</p>
                        <p class="text-blue-300/70 text-[11px]">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Back to public --}}
        <div class="absolute bottom-8 left-0 right-0 text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Publik
            </a>
        </div>
    </div>

    {{-- ── Kanan: Form Panel ── --}}
    <div class="flex-1 flex flex-col min-h-screen bg-gray-50">

        {{-- Mobile: top bar --}}
        <div class="lg:hidden flex items-center justify-between px-5 py-4 bg-white border-b border-gray-100">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Beranda
            </a>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-600 rounded-lg flex items-center justify-center text-white text-xs overflow-hidden">
                    @if($festivalLogo)
                        <img src="{{ asset('storage/logos/' . $festivalLogo) }}" class="w-full h-full object-contain" alt="">
                    @else 🏆 @endif
                </div>
                <span class="text-sm font-bold text-gray-800">{{ $festivalName }}</span>
            </div>
        </div>

        {{-- Form area --}}
        <div class="flex-1 flex items-center justify-center px-5 py-10 sm:px-10">
            <div class="w-full max-w-md">

                {{-- Header --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/80 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-200 overflow-hidden">
                            @if($festivalLogo)
                                <img src="{{ asset('storage/logos/' . $festivalLogo) }}"
                                     class="w-full h-full object-contain p-1" alt="">
                            @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Portal Administrator</p>
                            <h1 class="text-xl font-black text-gray-900">Masuk ke Dashboard</h1>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Masukkan kredensial Anda untuk mengakses panel manajemen
                        <span class="font-semibold text-gray-700">{{ $festivalName }} {{ $festivalYear }}</span>.
                    </p>
                </div>

                {{-- Error alert --}}
                @if(session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5 mb-6">
                    <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-red-700 font-semibold text-sm">Login Gagal</p>
                        <p class="text-red-600 text-sm mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                {{-- Form Card --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7 mb-5">
                    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                        @csrf

                        {{-- Username --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="username" id="usernameInput"
                                       value="{{ old('username') }}"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl
                                              pl-11 pr-4 py-3.5 text-sm text-gray-900 placeholder-gray-400
                                              focus:outline-none focus:ring-2 focus:ring-blue-500/30
                                              focus:border-blue-500 focus:bg-white transition-all"
                                       placeholder="Masukkan username" autocomplete="username" required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-7">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-semibold text-gray-700">Password</label>
                            </div>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input type="password" name="password" id="passwordInput"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl
                                              pl-11 pr-12 py-3.5 text-sm text-gray-900 placeholder-gray-400
                                              focus:outline-none focus:ring-2 focus:ring-blue-500/30
                                              focus:border-blue-500 focus:bg-white transition-all"
                                       placeholder="Masukkan password" autocomplete="current-password" required>
                                <button type="button" id="togglePwBtn" onclick="togglePw()"
                                        class="absolute right-4 top-1/2 -translate-y-1/2
                                               text-gray-400 hover:text-gray-600 transition-colors p-0.5">
                                    <svg id="eyeShow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeHide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="submitBtn"
                                class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99]
                                       text-white font-bold py-3.5 rounded-2xl text-sm transition-all
                                       shadow-lg shadow-blue-200 flex items-center justify-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk ke Dashboard
                        </button>
                    </form>
                </div>

                {{-- Demo accounts --}}
                <!-- <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <button type="button" id="demoToggle" onclick="toggleDemo()"
                            class="w-full flex items-center justify-between px-5 py-4
                                   text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-semibold">Akun Demo Tersedia</span>
                        </div>
                        <svg id="demoChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="demoPanel" class="hidden border-t border-gray-100">
                        <div class="p-4 space-y-2">
                            @foreach([
                                ['superadmin', 'super123',  'Super Admin',  'Akses penuh semua data',     'violet', '★'],
                                ['admin_putra','putra123',  'Admin Putra',  'Kelola data putra',           'blue',   '♂'],
                                ['admin_putri','putri123',  'Admin Putri',  'Kelola data putri',           'pink',   '♀'],
                            ] as [$user, $pass, $role, $desc, $color, $icon])
                            <div class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-{{ $color }}-50
                                        border border-gray-100 hover:border-{{ $color }}-200
                                        rounded-2xl cursor-pointer transition-all group"
                                 onclick="fillLogin('{{ $user }}', '{{ $pass }}')">
                                <div class="w-9 h-9 bg-{{ $color }}-100 group-hover:bg-{{ $color }}-200
                                            rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                    <span class="text-{{ $color }}-600 font-bold text-sm">{{ $icon }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800">{{ $role }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $desc }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-mono text-xs bg-white border border-gray-200 text-gray-600 px-2 py-1 rounded-lg">
                                        {{ $user }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="px-5 py-3 bg-amber-50 border-t border-amber-100">
                            <p class="text-xs text-amber-600 text-center">
                                💡 Klik salah satu akun di atas untuk mengisi form otomatis
                            </p>
                        </div>
                    </div>
                </div> -->

                {{-- Footer --}}
                <p class="text-center text-xs text-gray-400 mt-6">
                    {{ $festivalName }} {{ $festivalYear }} • Sistem Manajemen Pendaftaran
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle password visibility
function togglePw() {
    const inp  = document.getElementById('passwordInput');
    const show = document.getElementById('eyeShow');
    const hide = document.getElementById('eyeHide');
    if (inp.type === 'password') {
        inp.type = 'text';
        show.classList.add('hidden');
        hide.classList.remove('hidden');
    } else {
        inp.type = 'password';
        show.classList.remove('hidden');
        hide.classList.add('hidden');
    }
    inp.focus();
}

// Toggle demo panel
function toggleDemo() {
    const panel   = document.getElementById('demoPanel');
    const chevron = document.getElementById('demoChevron');
    const isOpen  = !panel.classList.contains('hidden');
    if (isOpen) {
        panel.classList.add('hidden');
        chevron.style.transform = '';
    } else {
        panel.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    }
}

// Fill login form from demo
function fillLogin(user, pass) {
    const uInput = document.getElementById('usernameInput');
    const pInput = document.getElementById('passwordInput');

    // Animate fill
    uInput.value = '';
    pInput.value = '';
    uInput.focus();

    let i = 0;
    const typeUser = setInterval(() => {
        uInput.value += user[i++];
        if (i >= user.length) {
            clearInterval(typeUser);
            let j = 0;
            pInput.focus();
            const typePass = setInterval(() => {
                pInput.value += pass[j++];
                if (j >= pass.length) {
                    clearInterval(typePass);
                    uInput.focus();
                }
            }, 40);
        }
    }, 40);
}

// Submit loading state
document.getElementById('loginForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        Memverifikasi...`;
});

// Focus username on load
document.getElementById('usernameInput').focus();
</script>
@endpush
