<?php

declare(strict_types=1);

final class SeederTest
{
    public function testArticleMigrationCreatesRequiredTables(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Migrations/2026-11-20-000001_CreateArticleTables.php');
        foreach (['kategori_artikel', 'artikel', 'artikel_kategori'] as $table) {
            expectTrue(strpos($source, "'{$table}'") !== false, "Migration harus membuat {$table}.");
        }
        expectTrue(strpos($source, 'FULLTEXT') !== false, 'Migration harus punya index FULLTEXT artikel.');
    }

    public function testArticleImageColumnIsAddedOnlyByForwardMigration(): void
    {
        $createMigration = (string) file_get_contents(APP_ROOT . '/app/Database/Migrations/2026-11-20-000001_CreateArticleTables.php');
        $imageMigration = (string) file_get_contents(APP_ROOT . '/app/Database/Migrations/2026-11-24-000002_AddGambarToArticleTable.php');

        expectTrue(strpos($createMigration, "'gambar'") === false, 'Migration historis tidak boleh membuat kolom yang ditambahkan oleh migrasi baru.');
        expectTrue(strpos($imageMigration, "addColumn('artikel'") !== false, 'Migrasi lanjutan harus menambahkan kolom gambar.');
    }

    public function testDemoSeederUpsertsWithoutReset(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Seeds/DemoSeeder.php');
        expectTrue(strpos($source, 'TRUNCATE') === false, 'Seeder tidak boleh TRUNCATE.');
        expectTrue(strpos($source, 'DELETE FROM') === false, 'Seeder tidak boleh DELETE.');
        expectTrue(strpos($source, 'where(') !== false, 'Seeder harus mengecek natural key.');
    }

    public function testDemoSeederHasCompleteDomainRows(): void
    {
        $source = (string) file_get_contents(APP_ROOT . '/app/Database/Seeds/DemoSeeder.php');
        foreach (['seedGejala', 'seedPenyakit', 'seedSample', 'seedDecisionTree', 'seedHasil', 'seedArticles'] as $method) {
            expectTrue(strpos($source, 'function ' . $method) !== false, "Seeder butuh {$method}.");
        }
    }

    public function testRabbitHealthArticleDoesNotClaimRabbitsCanVomitOrAdviseWaiting(): void
    {
        foreach ([
            'app/Database/Seeds/ArticleSeeder.php',
            'app/Database/Seeds/DemoSeeder.php',
        ] as $path) {
            $source = (string) file_get_contents(APP_ROOT . '/' . $path);

            expectTrue(strpos($source, 'Kelinci tidak dapat muntah') !== false, $path . ': artikel harus mengoreksi mitos muntah.');
            expectTrue(strpos($source, 'segera hubungi dokter hewan') !== false, $path . ': artikel harus menganjurkan pemeriksaan segera.');
            expectTrue(strpos($source, 'lebih dari 24 jam') === false, $path . ': artikel tidak boleh menyuruh menunggu 24 jam.');
        }
    }
}
