<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'order_id',
        'nama_produk_raw',
        'variasi_raw',
        'kombinasi_produk',
        'quantity',
        'sku_order_amount',
        'sku_settlement_amt',
    ];

    protected $useTimestamps = false;

    // -------------------------------------------------------
    // Hapus semua detail lama untuk order_id ini
    // (dipanggil sebelum insert ulang pada re-import)
    // -------------------------------------------------------
    public function deleteByOrderId(string $orderId): bool
    {
        return $this->where('order_id', $orderId)->delete();
    }

    // -------------------------------------------------------
    // Bulk insert items untuk satu order_id
    // -------------------------------------------------------
    public function bulkInsert(string $orderId, array $items): bool
    {
        if (empty($items)) return true;

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [
                'order_id'           => $orderId,
                'nama_produk_raw'    => $item['nama_produk_raw'],
                'variasi_raw'        => $item['variasi_raw'],
                'kombinasi_produk'   => $item['kombinasi_produk'],
                'quantity'           => (int)$item['quantity'],
                'sku_order_amount'   => (float)$item['sku_order_amount'],
                'sku_settlement_amt' => (float)$item['sku_settlement_amt'],
            ];
        }

        return $this->insertBatch($rows) !== false;
    }

    // -------------------------------------------------------
    // Rekap quantity per kombinasi produk (hanya Selesai)
    // -------------------------------------------------------
    public function getRekapProduk(): array
    {
        return $this->db->query("
            SELECT
                d.kombinasi_produk,
                SUM(d.quantity)            AS total_qty,
                SUM(d.sku_settlement_amt)  AS total_revenue,
                COUNT(DISTINCT d.order_id) AS total_orders
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
            GROUP BY d.kombinasi_produk
            ORDER BY total_qty DESC
        ")->getResultArray();
    }
}
