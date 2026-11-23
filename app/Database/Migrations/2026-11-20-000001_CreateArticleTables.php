<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArticleTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('kategori_artikel', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'ringkasan' => [
                'type' => 'TEXT',
            ],
            'konten' => [
                'type' => 'LONGTEXT',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['publish', 'draft'],
                'default' => 'draft',
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['status', 'published_at']);
        $this->forge->createTable('artikel', true);
        $this->db->query('ALTER TABLE `artikel` ADD FULLTEXT KEY `artikel_fulltext` (`judul`, `ringkasan`, `konten`)');

        $this->forge->addField([
            'artikel_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'kategori_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey(['artikel_id', 'kategori_id'], true);
        $this->forge->addForeignKey('artikel_id', 'artikel', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kategori_id', 'kategori_artikel', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('artikel_kategori', true);
    }

    public function down()
    {
        $this->forge->dropTable('artikel_kategori', true);
        $this->forge->dropTable('artikel', true);
        $this->forge->dropTable('kategori_artikel', true);
    }
}
