<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
             'App\Models\Contracts\Repositories\UserRepository', // Repository (Interface)
             'App\Models\Concrete\Eloquent\EloquentUserRepository' // Eloquent (Class)
        );
        
        if ($this->app->environment('local')) {
            $this->app->register('IoDigital\Repo\RepoServiceProvider');
        } 
    }
}
