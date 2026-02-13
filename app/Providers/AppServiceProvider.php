<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // This app is deployed behind a reverse proxy under a subpath (/mentor).
        // Nginx strips the /mentor prefix before forwarding, so the upstream request
        // cannot infer the public base URL. Force URL generation to use APP_URL.
        $rootUrl = config('app.url');
        if (is_string($rootUrl) && $rootUrl !== '') {
            // Ensure the base URL ends with "/" so links to the index route become
            // ".../mentor/?domain=cpp" (not ".../mentor?domain=cpp", which nginx redirects and drops query).
            URL::forceRootUrl(rtrim($rootUrl, '/') . '/');

            $scheme = parse_url($rootUrl, PHP_URL_SCHEME);
            if (is_string($scheme) && $scheme !== '') {
                URL::forceScheme($scheme);
            }
        }
    }
}
