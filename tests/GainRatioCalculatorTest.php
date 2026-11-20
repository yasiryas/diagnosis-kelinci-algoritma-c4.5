<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class GainRatioCalculatorTest
{
    public function testDividesGainBySplitInfo(): void
    {
        $calculator = new App\Services\GainRatioCalculator();

        expectSame(0.25, $calculator->divide(0.5, 2), 'Gain ratio must be gain divided by split info');
    }

    public function testReturnsZeroWhenSplitInfoIsNotPositive(): void
    {
        $calculator = new App\Services\GainRatioCalculator();

        expectSame(0.0, $calculator->divide(0.5, 0), 'Zero split info must not produce division by zero');
    }
}
