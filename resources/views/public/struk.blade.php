@extends('layouts.app')
@section('title', 'Struk Pembelian — ' . $festivalName)

@section('content')
<style>
  @media print {
    .no-print { display: none !important; }
    body { background: #fff !important; }
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/50 to-teal-100/50
            flex items-center justify-center p-4">

    {{-- Decorative blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden no-print">
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-emerald-300/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-teal-300/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            {{-- Top banner --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute inset-0" style="background-image:radial-gradient(circle,rgba(255,255,255,0.07) 1px,transparent 1px);background-size:20px 20px"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 border border-white/30 rounded-3xl
                                flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black text-white mb-1">Pesanan Berhasil!</h1>
                    <p class="text-emerald-100 text-sm">{{ $festivalName }} {{ $festivalYear }}</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">

                {{-- Struk itemized --}}
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">No. Referensi</p>
                        <p class="text-sm font-black text-gray-900 font-mono tracking-widest">{{ strtoupper(substr($penjualan->struk_token, 0, 8)) }}</p>
                    </div>

                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Tanggal</span>
                            <span class="text-gray-700 font-medium">{{ $penjualan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Pembeli</span>
                            <span class="text-gray-700 font-medium">{{ $penjualan->nama_pembeli }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">No. WhatsApp</span>
                            <span class="text-gray-700 font-medium">{{ $penjualan->hp_pembeli }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Produk</span>
                            <span class="text-gray-700 font-medium text-right">{{ $penjualan->merchandise?->nama ?? 'Produk sudah dihapus' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Jumlah</span>
                            <span class="text-gray-700 font-medium">{{ $penjualan->jumlah }} &times; Rp{{ number_format($penjualan->harga_satuan,0,',','.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Status</span>
                            @if($penjualan->status==='Dikonfirmasi')
                              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Dikonfirmasi
                              </span>
                            @elseif($penjualan->status==='Dibatalkan')
                              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-red-100 text-red-700 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Dibatalkan
                              </span>
                            @else
                              <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Menunggu Verifikasi
                              </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t-2 border-dashed border-gray-200 mt-4 pt-4 flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-700">Total Pembayaran</span>
                        <span class="text-xl font-black text-emerald-700">Rp{{ number_format($penjualan->total_harga,0,',','.') }}</span>
                    </div>
                </div>

                <p class="text-xs text-gray-400 text-center mb-6">Simpan struk ini sebagai bukti pemesanan. Admin akan segera memverifikasi bukti transfer Anda.</p>

                {{-- Actions --}}
                <div class="space-y-2.5 no-print">
                    @if($waNomor)
                    <a href="https://wa.me/{{ \App\Support\WhatsApp::normalize($waNomor) }}?text={{ urlencode('Halo, saya '.$penjualan->nama_pembeli.' ingin konfirmasi pembayaran untuk pesanan '.($penjualan->merchandise?->nama ?? '-').' (jumlah '.$penjualan->jumlah.', total Rp'.number_format($penjualan->total_harga,0,',','.').', ref '.strtoupper(substr($penjualan->struk_token,0,8)).'). Mohon segera diverifikasi, terima kasih!') }}"
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700
                              text-white font-bold py-3.5 rounded-2xl text-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Konfirmasi Pembayaran via WhatsApp
                    </a>
                    @else
                    <button type="button" disabled title="Kontak admin belum diatur"
                            class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-400
                                   font-bold py-3.5 rounded-2xl text-sm cursor-not-allowed">
                        Kontak Admin Belum Tersedia
                    </button>
                    @endif

                    <a href="{{ route('merchandise.order.struk.download', $penjualan->struk_token) }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                              text-white font-bold py-3.5 rounded-2xl text-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh Struk (PDF)
                    </a>
                    <button type="button" onclick="window.print()"
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200
                              text-gray-700 font-semibold py-3.5 rounded-2xl text-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak
                    </button>
                    <a href="{{ route('merchandise.index', strtolower($penjualan->gender)) }}"
                       class="w-full flex items-center justify-center gap-2 text-gray-500 hover:text-gray-700
                              font-semibold py-2 text-sm transition-all">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5 no-print">{{ $festivalName }} {{ $festivalYear }}</p>
    </div>
</div>
@endsection
