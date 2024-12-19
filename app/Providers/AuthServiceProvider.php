<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // user gates
            Gate::define('manage_users', function(User $user) {
                return $user->hasRole(['Admin']);
            });

        // roles gates
            Gate::define('create-role', function(User $user) {
                return $user->hasRole('Admin');
            });
            Gate::define('update-role', function(User $user) {
                return $user->hasRole('Admin');
            });
            Gate::define('delete-role', function(User $user) {
                return $user->hasRole('Admin');
            });
            Gate::define('get-user-permissions', function(User $user) {
                return $user->hasRole(['Admin']);
            });
            Gate::define('update-user-permissions', function(User $user) {
                return $user->hasRole(['Admin']);
            });

        // permissions gates
            Gate::define('create-permission', function(User $user) {
                return $user->hasRole('Admin');
            });
            Gate::define('update-permission', function(User $user) {
                return $user->hasRole('Admin');
            });
            Gate::define('delete-permission', function(User $user) {
                return $user->hasRole('Admin');
            });

        // categories gates
            Gate::define('create-category', function(User $user) {
                return $user->hasRole(['Admin']);
            });
            Gate::define('update-category', function(User $user) {
                return $user->hasRole(['Admin']);
            });
            Gate::define('delete-category', function(User $user) {
                return $user->hasRole(['Admin']);
            });

    }
}
