<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\AdSlotService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        View::composer('*', function ($view): void {
            if (! Schema::hasTable('settings')) {
                return;
            }

            $view->with('siteSettings', Setting::query()->pluck('value', 'key'));
            $view->with('adsenseGlobalScript', app(AdSlotService::class)->globalScript());
        });
    }
}
