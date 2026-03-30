<?php

namespace App\Policies;

use App\Models\LaporanPengaduan;
use App\Models\User;

class LaporanPengaduanPolicy
{
    /**
     * Admin & Kasubag bisa lihat daftar laporan
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','kasubag']);
    }

    /**
     * View specific laporan
     */
    public function view(User $user, LaporanPengaduan $laporan): bool
    {
        // Admin bisa lihat semua
        if ($user->role === 'admin') {
            return true;
        }

        // Kasubag hanya lihat yang menunggu keputusan
        if ($user->role === 'kasubag') {
            return $laporan->status_laporan === 'MENUNGGU_KEPUTUSAN_KASUBAG';
        }

        // Pegawai hanya boleh lihat laporan miliknya sendiri
        if ($user->role === 'pegawai') {
            return $laporan->id_user === $user->id;
        }

        return false;
    }

    /**
     * Admin boleh edit laporan
     */
    public function update(User $user, LaporanPengaduan $laporan): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Delete tidak diperbolehkan (audit integrity)
     */
    public function delete(User $user, LaporanPengaduan $laporan): bool
    {
        return false;
    }
}