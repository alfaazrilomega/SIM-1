<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeamTables extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // Tabel mitra_bisnis — Pemasukan (Reseller/Maklon)
        // -------------------------------------------------------
        $this->forge->addField([
            'id_mitra'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_mitra' => ['type' => 'VARCHAR', 'constraint' => 150],
            'tipe_mitra' => ['type' => 'ENUM', 'constraint' => ['Reseller', 'Maklon']],
            'no_hp'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'alamat'     => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addPrimaryKey('id_mitra');
        $this->forge->createTable('mitra_bisnis', true); // true = IF NOT EXISTS

        // -------------------------------------------------------
        // Tabel produksi — Catatan Produksi Bumbu
        // -------------------------------------------------------
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_produksi' => ['type' => 'VARCHAR', 'constraint' => 200],
            'tanggal'       => ['type' => 'DATE'],
            'id_produk'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah_hasil'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'catatan'       => ['type' => 'TEXT', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['selesai', 'dibatalkan'], 'default' => 'selesai'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('produksi', true); // true = IF NOT EXISTS

        // -------------------------------------------------------
        // Tabel produksi_bahan — Detail bahan per produksi
        // -------------------------------------------------------
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produksi' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_bahan'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah'      => ['type' => 'DECIMAL', 'constraint' => '12,3', 'default' => 0],
            'satuan'      => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('produksi_bahan', true); // true = IF NOT EXISTS
    }

    public function down(): void
    {
        $this->forge->dropTable('produksi_bahan', true);
        $this->forge->dropTable('produksi', true);
        $this->forge->dropTable('mitra_bisnis', true);
    }
}
