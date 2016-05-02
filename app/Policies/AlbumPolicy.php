<?php

namespace App\Policies;

use App\User;
use App\Album;
use App\Permission;
class AlbumPolicy extends AbstractPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function show(User $user, $album)
    {
        return $user->id === $album->author ||
            Permission::where('user', $user->id)->where('album', $album->id)->first() !== null;
    }
    public function store(User $user)
    {
        return $user->role === 'photographer';
    }
    public function update(User $user, $album)
    {
        return $user->id === $album->author ||
            Permission::where('user', $user->id)->where('album', $album->id)->where('access', 'full')->first() !== null;
    }
    public function destroy(User $user, $album)
    {
        return $this->update;
    }
}
