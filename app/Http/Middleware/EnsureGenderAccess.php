<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menolak (403) akses ke record hasil route-model-binding yang gender-nya
 * di luar cakupan admin yang sedang login (admin_putra hanya boleh
 * menyentuh record 'Putra', admin_putri hanya 'Putri', superadmin bebas).
 *
 * Berlaku otomatis untuk route mana pun yang memakai implicit route-model-
 * binding pada model ber-kolom `gender` (mis. {lomba}, {pendaftar}) —
 * tidak perlu didaftarkan ulang per-route. Untuk route tanpa model
 * (index/create/store) middleware ini tidak melakukan apa-apa; filtering
 * daftar data tetap jadi tanggung jawab query controller (whereIn gender).
 *
 * Wajib dipasang SETELAH SubstituteBindings (default pada grup 'web' di
 * Laravel 11) supaya parameter route sudah berupa instance Model, bukan ID.
 */
class EnsureGenderAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = match (session('admin_user.role')) {
            'admin_putra' => ['Putra'],
            'admin_putri' => ['Putri'],
            default       => ['Putra', 'Putri'],
        };

        foreach ($request->route()?->parameters() ?? [] as $param) {
            if (
                $param instanceof Model
                && array_key_exists('gender', $param->getAttributes())
                && !in_array($param->gender, $allowed, true)
            ) {
                abort(403, 'Anda tidak memiliki akses ke data kategori ini.');
            }
        }

        return $next($request);
    }
}
