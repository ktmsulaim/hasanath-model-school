<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $settings = Cache::rememberForever('settings', function () {
            $settings_all = Setting::all();
            $settings_ = new \stdClass;
            foreach ($settings_all as $name) {
                $settings_->{$name->name} = $name->value;
            }
            return $settings_;
        });
        View::share(['settings' => $settings]);
    }
}
