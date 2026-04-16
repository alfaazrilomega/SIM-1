<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelianBahanTable extends Migration
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
            'id_bahan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,3',
                'null'       => false,
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'supplier' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'default'    => null,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_bahan');
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('id_bahan', 'bahan_baku', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('pembelian_bahan');
    }

    public function down(): void
    {
        $this->forge->dropTable('pembelian_bahan', true);
    }
}