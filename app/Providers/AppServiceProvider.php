<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureRateLimiters();
    }

    protected function configureRateLimiters(): void
    {
        // Checkout: 10 attempts per minute per user (or IP for guests)
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return redirect()->route('shop.cart.index')
                        ->with('cart_error', 'Too many checkout attempts. Please wait a minute.');
                });
        });

        // Cart: 60 actions per minute (add/update/remove) — generous for real users
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Vendor writes (product create/update/delete): 30 per minute
        RateLimiter::for('vendor-writes', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // Contact form: 3 messages per hour per IP (spam protection)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(3)
                ->by($request->ip())
                ->response(function () {
                    return redirect()->route('shop.contact')
                        ->withErrors(['message' => 'Too many messages. Please try again later.']);
                });
        });
    }
}