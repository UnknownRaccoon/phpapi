<?php

namespace App\Policies;

use App\User;
use App\Album;

class AlbumPolicy extends Policy
{

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function access(User $user, Album $album)
    {
        return in_array($user->getAccess($album), ['read', 'full']);
    }

    public function edit(User $user, Album $album)
    {
        return $user->getAccess($album) === 'full';
    }
}
