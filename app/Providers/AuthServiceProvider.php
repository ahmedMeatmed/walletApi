<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\Transaction;
use App\Policies\ServicePolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Service::class => ServicePolicy::class,
    ]; 

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

    }

   
}
