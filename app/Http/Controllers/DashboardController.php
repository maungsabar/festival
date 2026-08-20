<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\Pendaftar;
use App\Http\Controllers\Concerns\ScopesByAdminGender;

class DashboardController extends Controller
{
    use ScopesByAdminGender;

    public function index()
    {
        $role    = session('admin_user.role');
        $allowed = $this->allowedGenders();

        $baseQuery = fn() => Pendaftar::query()->whereIn('gender', $allowed);

        // SECURITY: jangan hitung total gender di luar $allowed — admin_putra/admin_putri
        // tidak boleh tahu jumlah pendaftar gender lawan sekalipun cuma angka agregat.
        $totalPutra = in_array('Putra', $allowed, true) ? Pendaftar::where('gender', 'Putra')->count() : null;
        $totalPutri = in_array('Putri', $allowed, true) ? Pendaftar::where('gender', 'Putri')->count() : null;

        $belum      = $baseQuery()->where('status_verifikasi', 'Belum')->count();
        $terverif   = $baseQuery()->where('status_verifikasi', 'Terverifikasi')->count();
        $ditolak    = $baseQuery()->where('status_verifikasi', 'Ditolak')->count();

        $lombaStats = Lomba::withCount('pendaftars')
            ->whereIn('gender', $allowed)
            ->orderBy('gender')->orderBy('nama_lomba')
            ->get();

        // Count pendaftaran per jenjang
        $jenjangStats = Pendaftar::join('lombas', 'pendaftars.id_lomba', '=', 'lombas.id')
            ->selectRaw('lombas.jenjang, pendaftars.gender, count(pendaftars.id) as total')
            ->whereIn('pendaftars.gender', $allowed)
            ->groupBy('lombas.jenjang', 'pendaftars.gender')
            ->get();

        $jenjangData = [
            'SMP' => ['Putra' => 0, 'Putri' => 0],
            'SMA' => ['Putra' => 0, 'Putri' => 0],
            'UMUM' => ['Putra' => 0, 'Putri' => 0],
        ];

        foreach ($jenjangStats as $stat) {
            $jenjangData[$stat->jenjang][$stat->gender] = (int) $stat->total;
        }

        // Recent registrants (last 5)
        $recentPendaftar = Pendaftar::with('lomba')
            ->whereIn('gender', $allowed)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPutra', 'totalPutri',
            'belum', 'terverif', 'ditolak',
            'lombaStats', 'recentPendaftar', 'role',
            'jenjangData'
        ));
    }
}
