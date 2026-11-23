<?php

declare(strict_types=1);

final class QueryCleanupTest
{
    public function testCountMethodsUseCountStar(): void
    {
        foreach (['listModel.php', 'gejalaModel.php', 'penyakitModel.php', 'decisionTreeModel.php'] as $file) {
            $source = (string) file_get_contents(APP_ROOT . '/app/Models/' . $file);
            expectTrue(strpos($source, 'COUNT(*)') !== false, "{$file} harus memakai COUNT(*).");
        }
    }

    public function testUserSearchUsesFullname(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, "orLike('fullname'") !== false, 'Pencarian user harus memakai fullname.');
        expectTrue(strpos($source, "orLike('name'") === false, 'Kolom users.name tidak ada.');
    }

    public function testCountMethodsCastIds(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Models/listModel.php');
        expectTrue(strpos($source, '(int) $id') !== false, 'Parameter id harus di-cast.');
    }
}
