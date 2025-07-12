<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AgeDistributionChart;
use App\Filament\Widgets\GenderDistributionChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UsersPerMonthChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            UsersPerMonthChart::class,
            AgeDistributionChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            GenderDistributionChart::class,
        ];
    }
}
