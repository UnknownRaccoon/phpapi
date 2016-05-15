<?php

namespace App\Policies;

use App\User;

class UserPolicy extends Policy
{

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function access(User $user, User $account)
    {
        return $user->id === $account->id;
    }
}
