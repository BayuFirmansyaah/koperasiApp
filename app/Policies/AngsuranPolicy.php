<?php

namespace App\Policies;

use App\Models\Angsuran;
use App\Models\User;

class AngsuranPolicy
{
    /**
     * Determine if the user can view the angsuran.
     */
    public function view(User $user, Angsuran $angsuran): bool
    {
        // Admin dan bendahara bisa lihat semua angsuran
        if ($user->hasRole(['admin', 'bendahara'])) {
            return true;
        }

        // Anggota hanya bisa lihat angsuran sendiri
        if ($user->hasRole('anggota') && $user->anggota?->id === $angsuran->pinjaman->anggota_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create angsuran.
     */
    public function create(User $user): bool
    {
        return $user->can('create-angsuran');
    }

    /**
     * Determine if the user can update the angsuran.
     */
    public function update(User $user, Angsuran $angsuran): bool
    {
        return $user->can('update-angsuran');
    }

    /**
     * Determine if the user can delete the angsuran.
     */
    public function delete(User $user, Angsuran $angsuran): bool
    {
        return $user->can('delete-angsuran');
    }

    /**
     * Determine if the user can verify the angsuran.
     */
    public function verify(User $user, Angsuran $angsuran): bool
    {
        return $user->can('verify-angsuran') && $angsuran->status === 'pending';
    }
}
