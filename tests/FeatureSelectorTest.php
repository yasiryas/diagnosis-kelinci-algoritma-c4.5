<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class FeatureSelectorTest
{
    public function testSelectsCategoryWithHighestGainRatio(): void
    {
        $selector = new App\Services\FeatureSelector();
        $rows = [
            ['kategori' => 'A', 'gain_ratio' => 0.2],
            ['kategori' => 'B', 'gain_ratio' => 0.7],
            ['kategori' => 'C', 'gain_ratio' => 0.4],
        ];

        expectSame('B', $selector->selectCategory($rows), 'Highest gain ratio must win');
    }

    public function testIgnoresNonPositiveRatios(): void
    {
        $selector = new App\Services\FeatureSelector();
        $rows = [
            ['kategori' => 'A', 'gain_ratio' => 0],
            ['kategori' => 'B', 'gain_ratio' => -1],
        ];

        expectNull($selector->selectCategory($rows), 'Non-positive ratios must not be selected');
    }
}
