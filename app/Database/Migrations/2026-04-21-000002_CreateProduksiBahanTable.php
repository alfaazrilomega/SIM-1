<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduksiBahanTable extends Migration
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
            'id_produksi' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_bahan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('id_produksi', false, false, 'idx_pb_produksi');
        $this->forge->addKey('id_bahan', false, false, 'idx_pb_bahan');
        $this->forge->createTable('produksi_bahan', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('produksi_bahan', true);
    }
}