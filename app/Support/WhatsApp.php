<?php

namespace App\Support;

class WhatsApp
{
    /**
     * Normalisasi nomor telepon Indonesia ke format internasional tanpa '+' untuk
     * link wa.me — ganti '0' di depan jadi '62', BUKAN cuma membuang karakter
     * non-angka. Kalau cuma dibuang non-angkanya ('082299365648' -> '082299365648'
     * dikirim apa adanya ke wa.me), WhatsApp salah membaca 2 digit pertama ('82')
     * sebagai kode negara Korea Selatan (+82), bukan Indonesia (+62).
     */
    public static function normalize(?string $number): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $number);

        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }
        if (!str_starts_with($digits, '62')) {
            return '62' . $digits;
        }

        return $digits;
    }
}
