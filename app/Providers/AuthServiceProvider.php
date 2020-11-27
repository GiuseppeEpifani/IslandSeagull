<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //aca definimos los permisos, automaticamente esta funcion recibe una isntalncia de usuario que esta auth
        Gate::define('is-admin', function ($user) {
            return $user->role == 'Administrador';
        });

        Gate::define('is-scientific', function ($user) {
            return $user->role == 'Cientifico';
        });

        
    }
}
