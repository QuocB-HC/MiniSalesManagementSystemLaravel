<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define a gate for admin access control
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        View::composer('*', function ($view) {
            $cart = session()->get('cart', []);

            $cartCount = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['quantity'] ?? 0);
            }, 0);

            $view->with('cartCount', $cartCount);
        });
    }
}
