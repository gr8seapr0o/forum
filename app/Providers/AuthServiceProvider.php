<?php

namespace App\Providers;

use App\Thread;
use App\Policies\ThreadPolicy;
use App\Reply;
use App\Policies\ReplyPolicy;
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
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->name === 'admin') return true;
        });
    }
}
