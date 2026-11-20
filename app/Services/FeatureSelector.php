<?php

namespace App\Services;

class FeatureSelector
{
    public function selectCategory(array $rows): ?string
    {
        $bestCategory = null;
        $bestRatio = 0.0;

        foreach ($rows as $row) {
            $ratio = (float) $this->readField($row, 'gain_ratio');
            if ($ratio <= $bestRatio) {
                continue;
            }

            $bestRatio = $ratio;
            $bestCategory = (string) $this->readField($row, 'kategori');
        }

        return $bestCategory === null || $bestCategory === '' ? null : $bestCategory;
    }

    private function readField($row, string $field)
    {
        if (is_array($row)) {
            return $row[$field] ?? null;
        }

        return $row->{$field} ?? null;
    }
}
