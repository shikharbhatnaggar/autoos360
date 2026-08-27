<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Lead;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Services\TenantManager::class,
            fn() => new \App\Services\TenantManager()
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share legacy design SVGs seamlessly down across all front-end compilation views
        view()->share('BODY_ART_MARKUP', [
            'Hatchback' => '<svg viewBox="0 0 80 40" fill="none"><path d="M8 28h64l-3-9a5 5 0 00-3-3l-6-2-6-7a5 5 0 00-4-2H29a5 5 0 00-4 2l-6 8-6 2a5 5 0 00-4 5z" fill="currentColor" opacity=".16"/><path d="M8 28h64l-3-9a5 5 0 00-3-3l-6-2-6-7a5 5 0 00-4-2H29a5 5 0 00-4 2l-6 8-6 2a5 5 0 00-4 5z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M28 7v8M46 7v8" stroke="currentColor" stroke-width="1.8"/><circle cx="24" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/><circle cx="58" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/></svg>',
            'Sedan' => '<svg viewBox="0 0 80 40" fill="none"><path d="M6 28h68l-3-8a5 5 0 00-3-3l-9-2-7-7a5 5 0 00-4-2H30a5 5 0 00-4 2l-6 8-10 2a5 5 0 00-4 5z" fill="currentColor" opacity=".16"/><path d="M6 28h68l-3-8a5 5 0 00-3-3l-9-2-7-7a5 5 0 00-4-2H30a5 5 0 00-4 2l-6 8-10 2a5 5 0 00-4 5z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M29 7v8M45 7v8" stroke="currentColor" stroke-width="1.8"/><circle cx="23" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/><circle cx="59" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/></svg>',
            'SUV' => '<svg viewBox="0 0 80 40" fill="none"><path d="M7 28h66V17a5 5 0 00-4-5l-6-1-5-6a5 5 0 00-4-2H27a5 5 0 00-4 2l-5 6-7 1a5 5 0 00-4 5z" fill="currentColor" opacity=".16"/><path d="M7 28h66V17a5 5 0 00-4-5l-6-1-5-6a5 5 0 00-4-2H27a5 5 0 00-4 2l-5 6-7 1a5 5 0 00-4 5z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M27 4v8M45 4v8" stroke="currentColor" stroke-width="1.8"/><circle cx="23" cy="29" r="5.5" fill="#fff" stroke="currentColor" stroke-width="2.2"/><circle cx="58" cy="29" r="5.5" fill="#fff" stroke="currentColor" stroke-width="2.2"/></svg>',
            'Mini Commercial' => '<svg viewBox="0 0 80 40" fill="none"><path d="M6 28h68V14a5 5 0 00-4-5l-8-1-6-5a5 5 0 00-3-1H24a5 5 0 00-4 2l-7 6-4 1a5 5 0 00-3 5z" fill="currentColor" opacity=".16"/><path d="M6 28h68V14a5 5 0 00-4-5l-8-1-6-5a5 5 0 00-3-1H24a5 5 0 00-4 2l-7 6-4 1a5 5 0 00-3 5z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M26 3v6M44 3v6M58 4v5" stroke="currentColor" stroke-width="1.8"/><circle cx="22" cy="29" r="5.5" fill="#fff" stroke="currentColor" stroke-width="2.2"/><circle cx="59" cy="29" r="5.5" fill="#fff" stroke="currentColor" stroke-width="2.2"/></svg>',
            'Electric' => '<svg viewBox="0 0 80 40" fill="none"><path d="M8 28h64l-3-9a5 5 0 00-3-3l-7-2-6-7a5 5 0 00-4-2H30a5 5 0 00-4 2l-6 8-7 2a5 5 0 00-5 5z" fill="currentColor" opacity=".16"/><path d="M8 28h64l-3-9a5 5 0 00-3-3l-7-2-6-7a5 5 0 00-4-2H30a5 5 0 00-4 2l-6 8-7 2a5 5 0 00-5 5z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M42 6l-6 8h6l-5 8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/><circle cx="58" cy="29" r="5" fill="#fff" stroke="currentColor" stroke-width="2.2"/></svg>',
            'All' => '<svg viewBox="0 0 80 40" fill="none"><rect x="5" y="6" width="30" height="12" rx="3" stroke="currentColor" stroke-width="2.2"/><rect x="41" y="6" width="34" height="12" rx="3" stroke="currentColor" stroke-width="2.2" opacity=".45"/><rect x="5" y="23" width="34" height="12" rx="3" stroke="currentColor" stroke-width="2.2" opacity=".45"/><rect x="45" y="23" width="30" height="12" rx="3" stroke="currentColor" stroke-width="2.2"/></svg>'
        ]);

        // Share 'new' leads globally with your authenticated layout files
        View::composer(['layouts.app', 'partials.header', 'partials.sidebar'], function ($view) {
            if (auth()->check() && tenant()) {
                $newLeads = Lead::where('tenant_id', tenant()->id)
                    ->where('status', 'new')
                    ->latest()
                    ->get();

                $view->with('newLeadsNotificationCount', $newLeads->count())
                     ->with('newLeadsNotificationList', $newLeads);
            } else {
                $view->with('newLeadsNotificationCount', 0)
                     ->with('newLeadsNotificationList', collect());
            }
        });
    }

}
