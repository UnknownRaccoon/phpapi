<?php

namespace App\Policies;

use App\User;
use App\Album;
use App\Permission;
class UserPolicy extends AbstractPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function index(User $user)
    {
        return false;
    }
    public function store(User $user)
    {
        return true;
    }
    public function show(User $user, $account)
    {
        return $user->id === $account->id;
    }
    public function update(User $user, $account)
    {
        return $this->show($user, $account);
    }
    public function destroy(User $user, $account)
    {
        return $this->show($user, $account);
    }
}
