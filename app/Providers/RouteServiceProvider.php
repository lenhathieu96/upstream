<?php

namespace App\Providers;

use App\Models\IpBanned;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // allow 600 request per minutes
        RateLimiter::for('api', function (Request $request) {                 
            return Limit::perMinute(600)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(function ($request, $headers) {
                    $ipBanned = IpBanned::where('ip', $request->ip())->first();
                    if (empty($ipBanned)) {
                        IpBanned::clearIpCache();
                        IpBanned::create(['ip' => $request->ip()]);
                    }
                    abort(429);
                });                 
            });              

        // allow 200 request per minutes
        RateLimiter::for('global', function (Request $request) {               
            return Limit::perMinute(200)                 
                ->by(optional($request->user())->id ?: $request->ip())                     
                ->response(function ($request, $headers) {
                    $ipBanned = IpBanned::where('ip', $request->ip())->first();
                    if (empty($ipBanned)) {
                        IpBanned::clearIpCache();
                        IpBanned::create(['ip' => $request->ip()]);
                    }                   

                    abort(429);                 
            });             
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/sample.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }
}
