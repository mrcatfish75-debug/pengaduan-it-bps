<?php

namespace App\Policies;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarangPolicy
{
    /**
     * Determine whether the user can view any models.
     */
   public function viewAny(User $user): bool
{
    return in_array($user->role, ['admin','kasubag']);
}

public function view(User $user, Barang $barang): bool
{
    return in_array($user->role, ['admin','kasubag']);
}

public function update(User $user, Barang $barang): bool
{
    return $user->role === 'admin';
}

public function delete(User $user, Barang $barang): bool
{
    return false; // tidak boleh dihapus permanen
}
}
