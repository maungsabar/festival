<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Struk Pembelian</title>
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1f2937; margin: 0; padding: 20px; }
    h2 { text-align: center; margin: 0 0 4px 0; font-size: 18px; }
    p.sub { text-align: center; color: #6b7280; margin: 0 0 20px 0; }
    table.info { width: 100%; border-collapse: collapse; margin: 14px 0; }
    table.info td { padding: 6px 0; border-bottom: 1px solid #f3f4f6; }
    table.info td.label { color: #6b7280; width: 40%; }
    table.info td.value { text-align: right; }
    table.total { width: 100%; border-top: 2px dashed #d1d5db; margin-top: 8px; padding-top: 10px; }
    table.total td { font-size: 16px; font-weight: bold; }
    table.total td.value { text-align: right; color: #047857; }
    .badge { padding: 2px 10px; border-radius: 999px; font-weight: bold; font-size: 11px; }
    .footer-note { text-align: center; color: #9ca3af; font-size: 10px; margin-top: 24px; }
</style>
</head>
<body>
    <h2>Struk Pembelian</h2>
    <p class="sub">{{ $fest }}</p>

    <table class="info">
        <tr>
            <td class="label">No. Referensi</td>
            <td class="value">{{ strtoupper(substr($penjualan->struk_token, 0, 8)) }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="value">{{ $penjualan->created_at->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Pembeli</td>
            <td class="value">{{ $penjualan->nama_pembeli }}</td>
        </tr>
        <tr>
            <td class="label">No. WhatsApp</td>
            <td class="value">{{ $penjualan->hp_pembeli }}</td>
        </tr>
        <tr>
            <td class="label">Produk</td>
            <td class="value">{{ $penjualan->merchandise?->nama ?? 'Produk sudah dihapus' }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td class="value">{{ $penjualan->jumlah }} x Rp{{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value">
                @if($penjualan->status === 'Dikonfirmasi')
                    <span class="badge" style="color:#065f46;background:#d1fae5;">Dikonfirmasi</span>
                @elseif($penjualan->status === 'Dibatalkan')
                    <span class="badge" style="color:#991b1b;background:#fee2e2;">Dibatalkan</span>
                @else
                    <span class="badge" style="color:#92400e;background:#fef3c7;">Menunggu Verifikasi</span>
                @endif
            </td>
        </tr>
    </table>

    <table class="total">
        <tr>
            <td>Total Pembayaran</td>
            <td class="value">Rp{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="footer-note">Simpan struk ini sebagai bukti pemesanan.</p>
</body>
</html>
