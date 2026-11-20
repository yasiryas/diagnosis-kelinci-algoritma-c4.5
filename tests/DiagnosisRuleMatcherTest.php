<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class DiagnosisRuleMatcherTest
{
    public function testMatchesRuleWhenUserSelectsExtraSymptoms(): void
    {
        $matcher = new App\Services\DiagnosisRuleMatcher();
        $result = $matcher->match(['3', '1', '2'], [['parent' => '1 2', 'keputusan' => 4]]);

        expectSame(4, $result['keputusan'], 'Expected matching rule to be returned');
    }

    public function testDoesNotMatchWhenUserSelectsTooFewSymptoms(): void
    {
        $matcher = new App\Services\DiagnosisRuleMatcher();
        $result = $matcher->match(['1'], [['parent' => '1 2', 'keputusan' => 4]]);

        expectNull($result, 'Incomplete symptom set must not match');
    }

    public function testChoosesMostSpecificMatchingRule(): void
    {
        $matcher = new App\Services\DiagnosisRuleMatcher();
        $rules = [
            ['parent' => '1', 'keputusan' => 2],
            ['parent' => '1  2', 'keputusan' => 3],
        ];

        $result = $matcher->match(['2', '1', '1'], $rules);

        expectSame(3, $result['keputusan'], 'More specific rule must win');
    }

    public function testReturnsNullForEmptySelection(): void
    {
        $matcher = new App\Services\DiagnosisRuleMatcher();
        $result = $matcher->match([], [['parent' => '1', 'keputusan' => 2]]);

        expectNull($result, 'Empty selection must not match');
    }
}
