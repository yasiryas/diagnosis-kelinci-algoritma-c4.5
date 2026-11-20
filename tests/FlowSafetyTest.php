<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class FlowSafetyTest
{
    public function testProfileUpdateDoesNotTrustPostedCurrentImage(): void
    {
        $source = file_get_contents(APP_ROOT . '/app/Controllers/User.php');
        $start = strpos($source, 'public function updateProfile');
        $end = strpos($source, 'private function deleteProfileImage', $start);
        $body = substr($source, $start, $end - $start);

        expectTrue(strpos($body, "getPost('userImageOld')") === false, 'Current image must not be read from POST');
        expectTrue(strpos($body, 'userModel->find') !== false, 'Current image must be read from the database');
    }

    public function testMiningModelUsesTransactionalDeletes(): void
    {
        $source = file_get_contents(APP_ROOT . '/app/Models/miningModel.php');

        expectTrue(strpos($source, 'TRUNCATE TABLE') === false, 'Mining model must not use TRUNCATE inside transactions');
        expectTrue(strpos($source, 'DELETE FROM') !== false, 'Mining model must clear tables with DELETE');
    }

    public function testDeletingGejalaCleansAllDependentMiningTables(): void
    {
        $source = file_get_contents(APP_ROOT . '/app/Controllers/Admin.php');
        $start = strpos($source, 'public function deleteGejala');
        $end = strpos($source, 'public function penyakit', $start);
        $body = substr($source, $start, $end - $start);

        foreach (['mining_sample', 'mining_entropy', 'entropy', 'mining_kasus'] as $table) {
            expectTrue(strpos($body, $table) !== false, "deleteGejala must clean {$table}");
        }

        expectTrue(strpos($body, 'sampleModel') !== false, 'deleteGejala must clean data_sample through sampleModel');
        expectTrue(strpos($body, 'gejalaModel') !== false, 'deleteGejala must clean data_gejala through gejalaModel');
    }
}
