<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogStokProdukTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_produk' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['masuk', 'keluar'],
                'null'       => false,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('id_produk', false, false, 'idx_lsp_produk');
        $this->forge->addKey('tanggal', false, false, 'idx_lsp_tanggal');
        $this->forge->createTable('log_stok_produk', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('log_stok_produk', true);
    }
}