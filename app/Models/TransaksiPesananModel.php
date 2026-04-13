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
        'order_id', 'platform', 'status_pesanan', 'order_substatus', 'cancelation_return_type',
        'total_amount', 'order_amount', 'order_refund_amount',
        'total_quantity', 'total_sku_quantity_of_return',
        'shipping_fee_after_discount', 'original_shipping_fee', 'shipping_fee_seller_discount',
        'shipping_fee_platform_discount', 'payment_platform_discount',
        'buyer_service_fee', 'handling_fee',
        'create_time', 'paid_time', 'rts_time', 'shipped_time', 'delivered_time', 'cancelled_time',
        'cancel_by', 'cancel_reason', 'fulfillment_type', 'warehouse_name', 'tracking_id',
        'delivery_option', 'shipping_provider', 'buyer_username', 'recipient', 'phone',
        'zipcode', 'country', 'province', 'regency_and_city', 'districts', 'villages',
        'detail_address', 'additional_address', 'payment_method', 'weight_kg',
        'package_id', 'buyer_message'
        // status_penarikan SENGAJA tidak masuk allowedFields
        // supaya tidak bisa diubah secara tidak sengaja via model->save()
    ];

    protected $useTimestamps = false; // pakai tanggal_update manual (ON UPDATE)

    // -------------------------------------------------------
    // UPSERT: Insert baru atau UPDATE tanpa menyentuh status_penarikan
    // -------------------------------------------------------
    public function upsert(array $data): bool
    {
        $fields = array_keys($data);
        // exclude status_penarikan explicitly just in case it was accidentally passed
        $fields = array_filter($fields, fn($f) => $f !== 'status_penarikan');

        $colsObj = implode(', ', $fields);
        $valsObj = implode(', ', array_map(fn($f) => ":{$f}:", $fields));
        $updObj  = implode(', ', array_map(fn($f) => "{$f} = VALUES({$f})", $fields));

        $sql = "
            INSERT INTO transaksi_pesanan ({$colsObj})
            VALUES ({$valsObj})
            ON DUPLICATE KEY UPDATE {$updObj}
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
