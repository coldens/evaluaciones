<?php

namespace App\Providers;

use App\Models\Quiz;
use App\Models\Reply;
use App\Observers\CalculateQuizScoreOnFinished;
use App\Observers\CalculateQuizScoreOnReply;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Reply::observe(CalculateQuizScoreOnReply::class);
        Quiz::observe(CalculateQuizScoreOnFinished::class);
    }
}
