<?php

namespace App\Policies;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function before(User $user, $ability)
    {
        if($user->role === 'admin') {
            return true;
        }
    }
    public function store(User $user)
    {
        return true;
    }
    public function update(User $user, $id)
    {
        return true;
    }
    public function show(User $user, $id)
    {
        return true;
    }
    public function destroy(User $user, $id)
    {
        return true;
    }
    public function index(User $user)
    {
        return true;
    }
}
