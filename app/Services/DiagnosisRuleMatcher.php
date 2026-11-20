<?php

namespace App\Services;

class DiagnosisRuleMatcher
{
    public function match(array $selectedGejala, array $rules): ?array
    {
        $selected = $this->normalize($selectedGejala);

        if ($selected === []) {
            return null;
        }

        $bestRule = null;
        $bestCount = 0;

        foreach ($rules as $rule) {
            $ruleSet = $this->normalize(explode(' ', (string) ($rule['parent'] ?? '')));

            if ($ruleSet === [] || !$this->containsAll($selected, $ruleSet)) {
                continue;
            }

            if (count($ruleSet) > $bestCount) {
                $bestRule = $rule;
                $bestCount = count($ruleSet);
            }
        }

        return $bestRule;
    }

    private function normalize(array $values): array
    {
        $values = array_map('strval', $values);
        $values = array_values(array_filter($values, static fn (string $value): bool => $value !== ''));
        $values = array_values(array_unique($values));
        sort($values, SORT_STRING);

        return $values;
    }

    private function containsAll(array $selected, array $ruleSet): bool
    {
        return array_diff($ruleSet, $selected) === [];
    }
}
