<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo-myfest.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Festival Sekolah'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn .4s ease both',
                        'slide-up': 'slideUp .4s ease both',
                    },
                    keyframes: {
                        fadeIn:  { from: { opacity: 0 }, to: { opacity: 1 } },
                        slideUp: { from: { opacity: 0, transform: 'translateY(12px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                    }
                }
            }
        }
    </script>
    <style>
        /* Global smooth scroll */
        html { scroll-behavior: smooth; }

        /* Page transition */
        body { animation: fadeIn .25s ease; }

        /* Force Montserrat font everywhere */
        * { font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif; }

        /* Broken image fallback */
        img[src]:not([src='']) { min-width: 8px; min-height: 8px; }

        /* Focus ring cleanup */
        :focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }

        /* Custom scrollbar for body/page */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        @keyframes fadeIn  { from { opacity:0 } to { opacity:1 } }
        @keyframes slideUp { from { opacity:0; transform:translateY(12px) } to { opacity:1; transform:translateY(0) } }

        /* Flash notification */
        .flash-wrap { animation: slideUp .35s ease; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('info') || session('warning'))
<div id="flash-container" class="fixed top-4 right-4 z-50 space-y-2 w-80 max-w-[calc(100vw-2rem)] pointer-events-none">
    @foreach([
        'success' => ['bg-white border-emerald-200 text-emerald-800', 'bg-emerald-100 text-emerald-600', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'error'   => ['bg-white border-red-200 text-red-800',     'bg-red-100 text-red-600',     'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'warning' => ['bg-white border-amber-200 text-amber-800', 'bg-amber-100 text-amber-600', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'info'    => ['bg-white border-blue-200 text-blue-800',   'bg-blue-100 text-blue-600',   'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ] as $type => [$wrap, $icon, $path])
        @if(session($type))
        <div class="flash-wrap pointer-events-auto flex items-start gap-3 border rounded-2xl px-4 py-3.5 shadow-xl shadow-black/5 {{ $wrap }}">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $icon }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                </svg>
            </div>
            <p class="flex-1 text-sm font-medium pt-1 leading-snug">{{ session($type) }}</p>
            <button onclick="this.closest('.flash-wrap').remove()"
                    class="shrink-0 opacity-40 hover:opacity-70 transition-opacity mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif
    @endforeach
</div>
<script>
    setTimeout(() => {
        const c = document.getElementById('flash-container');
        if (c) { c.style.transition='opacity .5s'; c.style.opacity='0'; setTimeout(()=>c.remove(),500); }
    }, 4500);
</script>
@endif

@yield('content')

<!-- Confirmation modal -->
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div id="confirm-backdrop" class="fixed inset-0 bg-black/40" aria-hidden="true"></div>
        <div role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title" class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full mx-auto p-5 transform transition-all scale-95 opacity-0">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 id="confirm-modal-title" class="text-lg font-semibold text-gray-900">Konfirmasi</h3>
                    <p id="confirm-modal-message" class="text-sm text-gray-600 mt-1">Apakah Anda yakin?</p>
                </div>
            </div>
            <div class="mt-4 flex gap-3 justify-end">
                <button id="confirm-cancel" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700">Batal</button>
                <button id="confirm-ok" class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold">Ya, hapus</button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        const modal = document.getElementById('confirm-modal');
        const backdrop = document.getElementById('confirm-backdrop');
        if (!modal) return;
        const dialog = modal.querySelector('[role=dialog]');
        const titleEl = document.getElementById('confirm-modal-title');
        const msgEl = document.getElementById('confirm-modal-message');
        const okBtn = document.getElementById('confirm-ok');
        const cancelBtn = document.getElementById('confirm-cancel');
        let resolveFn = null;
        let previousActive = null;
        function openAnim(){ dialog.classList.remove('opacity-0','scale-95'); }
        function closeAnim(cb){ dialog.classList.add('opacity-0','scale-95'); setTimeout(cb, 150); }
        function showConfirm(message, options = {}){
            const { title, okText = 'Ya, hapus', cancelText = 'Batal', okClass } = options;
            if (title) titleEl.textContent = title;
            msgEl.textContent = message;
            okBtn.textContent = okText;
            cancelBtn.textContent = cancelText;
            if (okClass){ okBtn.className = okClass; }
            previousActive = document.activeElement;
            modal.classList.remove('hidden');
            // trigger entry animation
            requestAnimationFrame(openAnim);
            // focus management: focus cancel to avoid accidental destructive enter
            cancelBtn.focus();
            return new Promise((resolve)=>{ resolveFn = resolve; });
        }
        function close(result){
            closeAnim(()=>{ modal.classList.add('hidden'); if (resolveFn){ resolveFn(result); resolveFn = null; } if (previousActive) previousActive.focus(); });
        }
        okBtn.addEventListener('click', ()=> close(true));
        cancelBtn.addEventListener('click', ()=> close(false));
        // close on backdrop click (unless element opts out)
        backdrop.addEventListener('click', ()=> close(false));
        // allow Escape to cancel
        document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape' && !modal.classList.contains('hidden')) close(false); });

        // Click handler for elements with data-confirm (supports custom attributes)
        document.addEventListener('click', function(e){
            const el = e.target.closest('[data-confirm]');
            if (!el) return;
            e.preventDefault();
            const msg = (el.getAttribute('data-confirm') || 'Apakah Anda yakin?').replace(/\\n/g, '\n');
            const opts = {
                title: el.getAttribute('data-confirm-title') || null,
                okText: el.getAttribute('data-confirm-ok') || el.getAttribute('data-confirm-yes') || 'Ya, hapus',
                cancelText: el.getAttribute('data-confirm-cancel') || 'Batal',
                okClass: el.getAttribute('data-confirm-ok-class') || null
            };
            showConfirm(msg, opts).then(ok=>{
                if (!ok) return;
                if (el.tagName === 'A'){
                    window.location.href = el.href;
                } else {
                    const targetForm = el.form || (el.getAttribute('form') ? document.getElementById(el.getAttribute('form')) : null);
                    if (targetForm) targetForm.submit();
                    else {
                        const f = el.closest('form'); if (f) f.submit();
                    }
                }
            });
        }, true);

        // Submit handler for forms with data-confirm attribute
        document.addEventListener('submit', function(e){
            const form = e.target;
            if (!form || !form.dataset) return;
            if (!form.dataset.confirm) return;
            e.preventDefault();
            const msg = form.dataset.confirm.replace(/\\n/g, '\n');
            const opts = {
                title: form.dataset.confirmTitle || null,
                okText: form.dataset.confirmOk || form.dataset.confirmYes || 'Ya, hapus',
                cancelText: form.dataset.confirmCancel || 'Batal',
                okClass: form.dataset.confirmOkClass || null
            };
            showConfirm(msg, opts).then(ok=>{ if (ok) form.submit(); });
        }, true);
    })();
    </script>

    {{-- Validasi ukuran file di sisi klien — mencegah error 413 Request Entity Too Large
         dengan menolak file yang kelebihan ukuran SEBELUM sempat dikirim ke server.
         Pola sama seperti [data-confirm] di atas: cukup tambahkan atribut di Blade,
         tidak perlu tulis ulang JS di tiap halaman.
         Cara pakai pada <input type="file">:
           data-max-size-kb="2048"        -> batas ukuran dalam KB (2048 = 2MB)
           data-error-target="idElemenPesan" -> id elemen <p>/<div> tempat pesan error ditampilkan
         Dipasang di fase CAPTURE (true) supaya jalan LEBIH DULU daripada listener
         'change' lain yang mungkin sudah dipasang langsung pada input itu (mis. preview
         gambar), sehingga input sudah kosong sebelum listener lain sempat membaca file. --}}
    <script>
    document.addEventListener('change', function(e){
        const input = e.target;
        if (!input.matches || !input.matches('input[type="file"][data-max-size-kb]')) return;

        const maxKB = parseInt(input.dataset.maxSizeKb, 10);
        const errorEl = input.dataset.errorTarget ? document.getElementById(input.dataset.errorTarget) : null;
        const files = Array.from(input.files || []);

        if (errorEl) errorEl.classList.add('hidden');
        if (!files.length) return;

        const tooBig = files.find(f => f.size > maxKB * 1024);
        if (tooBig) {
            const maxMB = maxKB / 1024;
            const label = Number.isInteger(maxMB) ? maxMB : maxMB.toFixed(1);
            const msg = `Ukuran file terlalu besar! Maksimal ukuran file adalah ${label} MB.`;
            if (errorEl) {
                errorEl.textContent = msg;
                errorEl.classList.remove('hidden');
            } else {
                alert(msg);
            }
            input.value = '';
        }
    }, true);
    </script>

    @stack('scripts')
</body>
</html>
