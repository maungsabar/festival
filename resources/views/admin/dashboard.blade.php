@extends('layouts.admin')
@section('title', 'Dashboard')

@section('admin_content')
@php $role = session('admin_user.role'); @endphp

{{-- Page header --}}
<div class="flex flex-wrap items-start justify-between gap-3 mb-6">
    <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Selamat datang,</p>
        <h1 class="text-xl sm:text-2xl font-black text-gray-900 mt-0.5">
            {{ session('admin_user.username') }} 👋
        </h1>
        <p class="text-sm text-gray-400 mt-0.5">
            @if($role==='superadmin') Akses penuh — semua data
            @elseif($role==='admin_putra') Mengelola data kategori Putra
            @else Mengelola data kategori Putri
            @endif
        </p>
    </div>
    <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 py-2 shadow-sm text-xs text-gray-500">
        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
        <span id="live-time"></span>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-2 {{ $role==='superadmin' ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} gap-3 sm:gap-4 mb-6">

    @if($role !== 'admin_putri')
    <div class="stat-card group bg-white border border-gray-100 rounded-2xl p-4 sm:p-5
                hover:border-blue-200 hover:shadow-lg hover:shadow-blue-50/80 cursor-default relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-blue-600 bg-blue-50 group-hover:bg-blue-100 px-2 py-0.5 rounded-full transition-colors">♂ Putra</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-gray-900 tabular-nums" data-count="{{ $totalPutra }}">0</div>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Pendaftar Putra</p>
            <a href="{{ route('admin.pendaftar.index', ['gender'=>'Putra']) }}"
               class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 mt-2 font-medium transition-colors">
                Lihat <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @endif

    @if($role !== 'admin_putra')
    <div class="stat-card group bg-white border border-gray-100 rounded-2xl p-4 sm:p-5
                hover:border-pink-200 hover:shadow-lg hover:shadow-pink-50/80 cursor-default relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-pink-100 group-hover:bg-pink-200 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-pink-600 bg-pink-50 group-hover:bg-pink-100 px-2 py-0.5 rounded-full transition-colors">♀ Putri</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-gray-900 tabular-nums" data-count="{{ $totalPutri }}">0</div>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Pendaftar Putri</p>
            <a href="{{ route('admin.pendaftar.index', ['gender'=>'Putri']) }}"
               class="inline-flex items-center gap-1 text-xs text-pink-600 hover:text-pink-800 mt-2 font-medium transition-colors">
                Lihat <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @endif

    @if($role === 'superadmin')
    <div class="stat-card group bg-white border border-gray-100 rounded-2xl p-4 sm:p-5
                hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-50/80 cursor-default relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-emerald-100 group-hover:bg-emerald-200 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-emerald-600 bg-emerald-50 group-hover:bg-emerald-100 px-2 py-0.5 rounded-full transition-colors">Total</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-gray-900 tabular-nums" data-count="{{ $totalPutra + $totalPutri }}">0</div>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Semua Pendaftar</p>
            <a href="{{ route('admin.pendaftar.index') }}"
               class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 mt-2 font-medium transition-colors">
                Lihat semua <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @endif

    <div class="stat-card group bg-white border border-gray-100 rounded-2xl p-4 sm:p-5
                hover:border-violet-200 hover:shadow-lg hover:shadow-violet-50/80 cursor-default relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-violet-100 group-hover:bg-violet-200 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-violet-600 bg-violet-50 group-hover:bg-violet-100 px-2 py-0.5 rounded-full transition-colors">Lomba</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-gray-900 tabular-nums" data-count="{{ $lombaStats->count() }}">0</div>
            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Total Lomba</p>
            <a href="{{ route('admin.lomba.index') }}"
               class="inline-flex items-center gap-1 text-xs text-violet-600 hover:text-violet-800 mt-2 font-medium transition-colors">
                Kelola <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- ── Charts Row ── --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">

    {{-- Donut: Komposisi --}}
    <div class="xl:col-span-2 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Komposisi Peserta</h2>
                <p class="text-xs text-gray-400 mt-0.5">Putra vs Putri</p>
            </div>
        </div>
        <div class="flex items-center gap-4 mb-3 flex-wrap">
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>Putra ({{ $totalPutra }})
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
                <span class="w-2.5 h-2.5 rounded-full bg-pink-500 shrink-0"></span>Putri ({{ $totalPutri }})
            </div>
        </div>
        <div style="height:180px;position:relative">
            <canvas id="donutChart"></canvas>
        </div>
        <div class="text-center mt-3">
            <p class="text-2xl font-black text-gray-900 tabular-nums">{{ $totalPutra + $totalPutri }}</p>
            <p class="text-xs text-gray-400">Total Peserta</p>
        </div>
    </div>

    {{-- Bar: Per Lomba --}}
    <div class="xl:col-span-3 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Peserta per Lomba</h2>
                <p class="text-xs text-gray-400 mt-0.5">Distribusi pendaftar</p>
            </div>
            <a href="{{ route('admin.lomba.index') }}"
               class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-semibold transition-colors">
                Kelola →
            </a>
        </div>
        @php $barH = max(200, $lombaStats->count() * 42 + 40); @endphp
        <div style="height:{{ $barH }}px;position:relative">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Jenjang Chart Row ── --}}
<div class="grid grid-cols-1 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Pendaftar per Jenjang</h2>
                <p class="text-xs text-gray-400 mt-0.5">Komparasi jumlah peserta Putra dan Putri di setiap jenjang (SMP, SMA, UMUM)</p>
            </div>
        </div>
        <div style="height:250px;position:relative">
            <canvas id="jenjangChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Verif + Recent ── --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">

    {{-- Status Verifikasi --}}
    <div class="xl:col-span-2 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <h2 class="font-bold text-gray-900 text-sm mb-1">Status Verifikasi</h2>
        <p class="text-xs text-gray-400 mb-4">Progres pemeriksaan berkas</p>
        <div style="height:160px;position:relative">
            <canvas id="statusChart"></canvas>
        </div>
        @php $totalVerif = max($belum + $terverif + $ditolak, 1); @endphp
        <div class="mt-5 space-y-3">
            @foreach([['Terverifikasi',$terverif,'bg-emerald-500'],['Belum',$belum,'bg-amber-400'],['Ditolak',$ditolak,'bg-red-400']] as [$lbl,$val,$bg])
            <div>
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-gray-500">{{ $lbl }}</span>
                    <span class="font-bold text-gray-700 tabular-nums">{{ $val }}
                        <span class="text-gray-400 font-normal">({{ round($val/$totalVerif*100) }}%)</span>
                    </span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="{{ $bg }} h-full rounded-full transition-all duration-1000"
                         style="width:0%" data-width="{{ round($val/$totalVerif*100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pendaftar Terbaru --}}
    <div class="xl:col-span-3 bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Pendaftar Terbaru</h2>
                <p class="text-xs text-gray-400 mt-0.5">5 pendaftar terakhir</p>
            </div>
            <a href="{{ route('admin.pendaftar.index') }}"
               class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg font-semibold transition-colors">
                Semua →
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentPendaftar as $p)
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50/70 transition-colors">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                            {{ $p->gender==='Putra' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                    {{ strtoupper(substr($p->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $p->lomba?->nama_lomba ?? '-' }} · {{ $p->nama_sekolah }}</p>
                </div>
                <div class="shrink-0 text-right">
                    @if($p->status_verifikasi==='Terverifikasi')
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                            <span class="w-1 h-1 bg-emerald-500 rounded-full"></span>Verif
                        </span>
                    @elseif($p->status_verifikasi==='Ditolak')
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                            <span class="w-1 h-1 bg-red-500 rounded-full"></span>Tolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                            <span class="w-1 h-1 bg-amber-500 rounded-full animate-pulse"></span>Belum
                        </span>
                    @endif
                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $p->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-5 py-12 text-center text-sm text-gray-400">Belum ada pendaftar.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Quick Actions ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
    @foreach([
        [route('admin.pendaftar.index'), 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'bg-blue-50 group-hover:bg-blue-100 text-blue-600', 'Data Pendaftar', 'Lihat & verifikasi peserta'],
        [route('admin.lomba.index'), 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'bg-violet-50 group-hover:bg-violet-100 text-violet-600', 'Kelola Lomba', 'Tambah & atur perlombaan'],
        [route('admin.pendaftar.export'), 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'bg-emerald-50 group-hover:bg-emerald-100 text-emerald-600', 'Export CSV', 'Download data peserta'],
    ] as [$href,$icon,$iconCls,$title,$desc])
    <a href="{{ $href }}"
       class="action-card group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 sm:p-5
              hover:border-gray-200 hover:shadow-md transition-all duration-200">
        <div class="w-10 h-10 {{ $iconCls }} rounded-xl flex items-center justify-center shrink-0 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="font-bold text-gray-900 text-sm">{{ $title }}</p>
            <p class="text-gray-400 text-xs mt-0.5 truncate">{{ $desc }}</p>
        </div>
        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 ml-auto shrink-0 transition-colors"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endforeach
</div>

<script>
window._dashData = {
    totalPutra: {{ $totalPutra }},
    totalPutri: {{ $totalPutri }},
    belum: {{ $belum }},
    terverif: {{ $terverif }},
    ditolak: {{ $ditolak }},
    lombaLabels: @json($lombaStats->pluck('nama_lomba')),
    lombaData:   @json($lombaStats->pluck('pendaftars_count')),
    lombaGender: @json($lombaStats->pluck('gender')),
    jenjangPutra: [{{ $jenjangData['SMP']['Putra'] }}, {{ $jenjangData['SMA']['Putra'] }}, {{ $jenjangData['UMUM']['Putra'] }}],
    jenjangPutri: [{{ $jenjangData['SMP']['Putri'] }}, {{ $jenjangData['SMA']['Putri'] }}, {{ $jenjangData['UMUM']['Putri'] }}],
};
</script>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
// ── Clock ────────────────────────────────────────────────────
const clockEl = document.getElementById('live-time');
function tick() {
    if (!clockEl) return;
    const n = new Date();
    clockEl.textContent = n.toLocaleDateString('id-ID',{weekday:'short',day:'numeric',month:'short'})
        + ' · ' + n.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
tick(); setInterval(tick, 1000);

// ── Count-up ─────────────────────────────────────────────────
document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    if (!target) { el.textContent = '0'; return; }
    let cur = 0;
    const step = Math.max(1, Math.ceil(target / 28));
    const t = setInterval(() => {
        cur = Math.min(cur + step, target);
        el.textContent = cur.toLocaleString('id-ID');
        if (cur >= target) clearInterval(t);
    }, 30);
});

// ── Progress bars ─────────────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('[data-width]').forEach(el => { el.style.width = el.dataset.width; });
}, 400);

// ── Chart defaults ────────────────────────────────────────────
Chart.defaults.font.family = 'ui-sans-serif, system-ui, sans-serif';
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

const d = window._dashData;
const total = d.totalPutra + d.totalPutri || 1;

// Donut: Komposisi
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Putra','Putri'],
        datasets: [{ data:[d.totalPutra,d.totalPutri],
            backgroundColor:['#3b82f6','#ec4899'],
            borderColor:'#fff', borderWidth:3, hoverOffset:6 }]
    },
    options: {
        responsive:true, maintainAspectRatio:false, cutout:'72%',
        plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>` ${c.label}: ${c.parsed}`}} },
        animation:{ animateRotate:true, duration:900, easing:'easeOutQuart' }
    }
});

// Bar: Per Lomba
new Chart(document.getElementById('barChart'), {
    type:'bar',
    data:{
        labels: d.lombaLabels,
        datasets:[{ label:'Pendaftar', data:d.lombaData,
            backgroundColor: d.lombaGender.map(g=>g==='Putra'?'#3b82f6':'#ec4899'),
            borderRadius:6, borderSkipped:false }]
    },
    options:{
        indexAxis:'y', responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>` ${c.parsed.x} peserta`}} },
        scales:{
            x:{ grid:{color:'rgba(0,0,0,0.04)'}, ticks:{stepSize:1,precision:0}, beginAtZero:true },
            y:{ grid:{display:false}, ticks:{font:{size:11,weight:'500'}} }
        },
        animation:{ duration:900, easing:'easeOutQuart' }
    }
});

// Donut: Status
new Chart(document.getElementById('statusChart'), {
    type:'doughnut',
    data:{
        labels:['Belum','Terverifikasi','Ditolak'],
        datasets:[{ data:[d.belum,d.terverif,d.ditolak],
            backgroundColor:['#fbbf24','#10b981','#f87171'],
            borderColor:'#fff', borderWidth:3, hoverOffset:6 }]
    },
    options:{
        responsive:true, maintainAspectRatio:false, cutout:'65%',
        plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>` ${c.label}: ${c.parsed}`}} },
        animation:{ animateRotate:true, duration:900, easing:'easeOutQuart' }
    }
});

// Bar: Per Jenjang
new Chart(document.getElementById('jenjangChart'), {
    type: 'bar',
    data: {
        labels: ['SMP', 'SMA', 'UMUM'],
        datasets: [
            {
                label: 'Putra',
                data: d.jenjangPutra,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                borderSkipped: false
            },
            {
                label: 'Putri',
                data: d.jenjangPutri,
                backgroundColor: '#ec4899',
                borderRadius: 6,
                borderSkipped: false
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: { size: 11, weight: '600' }
                }
            },
            tooltip: {
                callbacks: {
                    label: c => ` ${c.dataset.label}: ${c.parsed.y} pendaftar`
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 11, weight: '600' } }
            },
            y: {
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { stepSize: 1, precision: 0 },
                beginAtZero: true
            }
        },
        animation: { duration: 900, easing: 'easeOutQuart' }
    }
});
</script>
@endpush
