<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\OpenApi;

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
        Paginator::useTailwind();

        Gate::define('admin',  function ($user): bool {
            return $user->is_admin == true;
        });

        Scramble::configure()
            ->routes(routeResolver: function (Route $route): bool {
                return Str::startsWith(haystack: $route->uri, needles: 'api/');
            })
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    securityScheme: SecurityScheme::http(scheme: 'bearer')
                );
            });
    }
}
