<?php

namespace App\Policies;

use App\Models\Borrow;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Determine whether the user can update the model.
     * Logika untuk mengembalikan buku ada di sini.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Borrow $borrow)
    {
        // Izinkan aksi HANYA JIKA ID user yang login
        // sama dengan user_id pada data peminjaman.
        return $user->id === $borrow->user_id;
    }
}
