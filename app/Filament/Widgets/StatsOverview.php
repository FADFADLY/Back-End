<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)->count();
        $newUsersThisYear = User::whereYear('created_at', Carbon::now()->year)->count();


        return [
            Stat::make('إجمالي المستخدمين', $totalUsers)
                ->description('إجمالي عدد المستخدمين المسجلين')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('المستخدمون الجدد هذا الشهر', $newUsersThisMonth)
                ->description('عدد المستخدمين الجدد الذين سجلوا هذا الشهر')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
            Stat::make('المستخدمون الجدد هذا العام', $newUsersThisYear)
                ->description('عدد المستخدمين الجدد الذين سجلوا هذا العام')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }


}
