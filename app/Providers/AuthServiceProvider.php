<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        $gate->define('create-album', function($user) {
            return $user->role === 'admin' || $user->role === 'photographer';
        });
        $gate->define('change-album', function($user, $album) {
            return $user->id === $album->author || $user->role === 'admin' || Permission::where('user', $user->id)->where('album', $album->id)->where('access', 'full')->first() !== null;
        });
        $gate->define('view-album', function($user, $album) {
            return $user->id === $album->author || $user->role === 'admin' || Permission::where('user', $user->id)->where('album', $album->id)->whereNotNull('access')->first() !== null;
        });
        $gate->define('change-profile', function($user, $profile) {
            return $user->id === $profile->id || $user->role === 'admin';
        });
        $gate->define('view-profiles', function($user) {
            return $user->role === 'admin';
        });
    }
}
