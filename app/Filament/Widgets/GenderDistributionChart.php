<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'توزيع النوع';


    protected function getData(): array
    {
        $males = User::where('gender', 'male')->count();
        $females = User::where('gender', 'female')->count();

        return [
            'datasets' => [
                [
                    'data' => [$males, $females],
                    'backgroundColor' => ['#60a5fa', '#f472b6'], // أزرق ووردي
                ],
            ],
            'labels' => ['ذكور', 'إناث'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
