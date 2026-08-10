<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\Pendaftar;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('admin_user.role');

        $baseQuery = fn() => Pendaftar::query()
            ->when($role === 'admin_putra', fn($q) => $q->where('gender', 'Putra'))
            ->when($role === 'admin_putri', fn($q) => $q->where('gender', 'Putri'));

        $totalPutra = Pendaftar::where('gender', 'Putra')->count();
        $totalPutri = Pendaftar::where('gender', 'Putri')->count();

        $belum      = $baseQuery()->where('status_verifikasi', 'Belum')->count();
        $terverif   = $baseQuery()->where('status_verifikasi', 'Terverifikasi')->count();
        $ditolak    = $baseQuery()->where('status_verifikasi', 'Ditolak')->count();

        $lombaStats = Lomba::withCount('pendaftars')
            ->when($role === 'admin_putra', fn($q) => $q->where('gender', 'Putra'))
            ->when($role === 'admin_putri', fn($q) => $q->where('gender', 'Putri'))
            ->orderBy('gender')->orderBy('nama_lomba')
            ->get();

        // Count pendaftaran per jenjang
        $jenjangStats = Pendaftar::join('lombas', 'pendaftars.id_lomba', '=', 'lombas.id')
            ->selectRaw('lombas.jenjang, pendaftars.gender, count(pendaftars.id) as total')
            ->when($role === 'admin_putra', fn($q) => $q->where('pendaftars.gender', 'Putra'))
            ->when($role === 'admin_putri', fn($q) => $q->where('pendaftars.gender', 'Putri'))
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
            ->when($role === 'admin_putra', fn($q) => $q->where('gender', 'Putra'))
            ->when($role === 'admin_putri', fn($q) => $q->where('gender', 'Putri'))
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
