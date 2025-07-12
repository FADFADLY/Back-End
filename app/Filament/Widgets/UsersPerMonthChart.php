<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class UsersPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'المستخدمون الجدد لكل شهر';

    protected function getData(): array
    {
        $year = now()->year;

        $monthlyUsers = collect(range(1, 12))->map(function ($month) use ($year) {
            return User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'عدد المستخدمين الجدد',
                    'data' => $monthlyUsers->toArray(),
                    'backgroundColor' => '#3b82f6', // blue-500
                ],
            ],
            'labels' => [
                'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // ممكن تخليها 'line' لو تحب
    }
}
