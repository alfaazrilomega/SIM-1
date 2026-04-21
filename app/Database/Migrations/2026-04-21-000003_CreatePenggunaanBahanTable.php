<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenggunaanBahanTable extends Migration
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
            'id_bahan' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,3',
                'null'       => false,
            ],
            'keperluan' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('id_bahan', false, false, 'idx_pgb_bahan');
        $this->forge->addKey('tanggal', false, false, 'idx_pgb_tanggal');
        $this->forge->createTable('penggunaan_bahan', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('penggunaan_bahan', true);
    }
}