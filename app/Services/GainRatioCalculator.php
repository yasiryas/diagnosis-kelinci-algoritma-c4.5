<?php

namespace App\Services;

class GainRatioCalculator
{
    public function divide(float $gain, float $splitInfo): float
    {
        if ($splitInfo <= 0) {
            return 0.0;
        }

        return $gain / $splitInfo;
    }
}
