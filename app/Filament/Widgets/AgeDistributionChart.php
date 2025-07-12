<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class AgeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'توزيع الأعمار';

    protected function getData(): array
    {
        // تحديد الفئات العمرية
        $ageGroups = [
            'أقل من 18' => 0,
            '18 - 24' => 0,
            '25 - 34' => 0,
            '35 - 44' => 0,
            '45 وأكثر' => 0,
        ];

        // احسب عدد المستخدمين في كل فئة عمرية
        $users = User::select('age')->get();

        foreach ($users as $user) {
            if ($user->age === null) continue;

            $age = $user->age;

            if ($age < 18) {
                $ageGroups['أقل من 18']++;
            } elseif ($age <= 24) {
                $ageGroups['18 - 24']++;
            } elseif ($age <= 34) {
                $ageGroups['25 - 34']++;
            } elseif ($age <= 44) {
                $ageGroups['35 - 44']++;
            } else {
                $ageGroups['45 وأكثر']++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد المستخدمين',
                    'data' => array_values($ageGroups),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#93c5fd',
                    'fill' => true,
                    'tension' => 0.4, // smooth curve
                ],
            ],
            'labels' => array_keys($ageGroups),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
