<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStokBumbuLogTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['masuk', 'keluar'],
                'null'       => false,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_produk');
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('id_produk', 'produk_bumbu', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('stok_bumbu_log');
    }

    public function down(): void
    {
        $this->forge->dropTable('stok_bumbu_log', true);
    }
}