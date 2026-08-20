<?php

namespace App\Http\Controllers\Concerns;

trait ScopesByAdminGender
{
    /**
     * Daftar nilai gender ('Putra'/'Putri') yang boleh diakses oleh admin
     * yang sedang login, berdasarkan role di session('admin_user.role').
     * superadmin (atau role tak dikenal) mendapat akses ke keduanya.
     */
    protected function allowedGenders(): array
    {
        return match (session('admin_user.role')) {
            'admin_putra' => ['Putra'],
            'admin_putri' => ['Putri'],
            default       => ['Putra', 'Putri'],
        };
    }
}
