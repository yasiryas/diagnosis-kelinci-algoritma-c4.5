<?php

namespace App\Services;

class DashboardChartData
{
    public const DOUGHNUT_COLORS = ['#4e73df', '#1cc88a', '#f6c23e'];
    public const BAR_COLOR = '#e74a3b';

    public static function payload(array $composition, array $consultations): array
    {
        $labels = [];
        $values = [];
        foreach ([['gejala', 'Gejala'], ['penyakit', 'Penyakit'], ['sample', 'Sample']] as [$key, $label]) {
            $labels[] = $label;
            $values[] = (int) ($composition[$key] ?? 0);
        }

        $barLabels = [];
        $barValues = [];
        foreach ($consultations as $row) {
            $barLabels[] = (string) ($row['penyakit'] ?? '');
            $barValues[] = (int) ($row['total'] ?? 0);
        }

        return [
            'doughnut' => [
                'labels' => $labels,
                'values' => $values,
                'colors' => self::DOUGHNUT_COLORS,
            ],
            'bar' => [
                'labels' => $barLabels,
                'values' => $barValues,
                'color' => self::BAR_COLOR,
            ],
        ];
    }
}
