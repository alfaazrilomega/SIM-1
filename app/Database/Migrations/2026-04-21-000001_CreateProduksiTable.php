<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduksiTable extends Migration
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
            'nama_produksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'id_produk' => [
                'type'     => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null'     => false,
            ],
            'jumlah_hasil' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['selesai', 'dibatalkan'],
                'null'       => false,
                'default'    => 'selesai',
            ],
            'dibuat_oleh' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('tanggal', false, false, 'idx_produksi_tanggal');
        $this->forge->addKey('id_produk', false, false, 'idx_produksi_id_produk');
        $this->forge->addKey('status', false, false, 'idx_produksi_status');
        $this->forge->createTable('produksi', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('produksi', true);
    }
}