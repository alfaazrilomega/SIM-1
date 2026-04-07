<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiPesananModel extends Model
{
    protected $table            = 'transaksi_pesanan';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'order_id',
        'platform',
        'status_pesanan',
        'total_amount',
        'create_time',
        'paid_time',
        // status_penarikan SENGAJA tidak masuk allowedFields
        // supaya tidak bisa diubah secara tidak sengaja via model->save()
    ];

    protected $useTimestamps = false; // pakai tanggal_update manual (ON UPDATE)

    // -------------------------------------------------------
    // UPSERT: Insert baru atau UPDATE tanpa menyentuh status_penarikan
    // -------------------------------------------------------
    public function upsert(array $data): bool
    {
        $sql = "
            INSERT INTO transaksi_pesanan
                (order_id, platform, status_pesanan, total_amount, create_time, paid_time)
            VALUES
                (:order_id:, :platform:, :status_pesanan:, :total_amount:, :create_time:, :paid_time:)
            ON DUPLICATE KEY UPDATE
                status_pesanan = VALUES(status_pesanan),
                total_amount   = VALUES(total_amount),
                create_time    = VALUES(create_time),
                paid_time      = VALUES(paid_time)
                -- status_penarikan TIDAK disentuh (intentional!)
        ";
        return $this->db->query($sql, $data);
    }

    // -------------------------------------------------------
    // Cek apakah order sudah ada (untuk count inserted vs updated)
    // -------------------------------------------------------
    public function exists(string $orderId): bool
    {
        return $this->where('order_id', $orderId)->countAllResults() > 0;
    }

    // -------------------------------------------------------
    // Laporan: hanya pesanan Selesai
    // -------------------------------------------------------
    public function getPesananSelesai(int $limit = 100, int $offset = 0): array
    {
        return $this->where('status_pesanan', 'Selesai')
                    ->orderBy('create_time', 'DESC')
                    ->findAll($limit, $offset);
    }

    // -------------------------------------------------------
    // Update status_penarikan (CEO only)
    // -------------------------------------------------------
    public function updateStatusPenarikan(string $orderId, string $status): bool
    {
        return $this->db->query(
            "UPDATE transaksi_pesanan SET status_penarikan = ? WHERE order_id = ?",
            [$status, $orderId]
        );
    }
}
