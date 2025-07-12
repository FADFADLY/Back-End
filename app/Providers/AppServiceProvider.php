<?php

namespace App\Providers;

use App\Filament\Widgets\AgeDistributionChart;
use App\Filament\Widgets\GenderDistributionChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UsersPerMonthChart;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        Livewire::component('app.filament.widgets.gender-distribution-chart', GenderDistributionChart::class);
        Livewire::component('app.filament.widgets.age-distribution-chart', AgeDistributionChart::class);
        Livewire::component('app.filament.widgets.stats-overview', StatsOverview::class);
        Livewire::component('app.filament.widgets.users-per-month-chart', UsersPerMonthChart::class);
    }
}
