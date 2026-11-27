<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGambarToArticleTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('artikel', [
            'gambar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'published_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('artikel', 'gambar');
    }
}