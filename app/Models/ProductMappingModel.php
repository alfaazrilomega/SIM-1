<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductMappingModel extends Model
{
    protected $table      = 'product_mapping';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_produk_raw',
        'variasi_raw',
        'kombinasi_label',
        'keterangan',
    ];

    protected $useTimestamps = false;

    private array $cache = [];

    // -------------------------------------------------------
    // Load semua mapping ke memory cache (dipanggil sekali di awal import)
    // -------------------------------------------------------
    public function loadCache(): void
    {
        $rows = $this->findAll();
        foreach ($rows as $row) {
            $key = $this->makeKey($row['nama_produk_raw'], $row['variasi_raw']);
            $this->cache[$key] = $row['kombinasi_label'];
        }
    }

    // -------------------------------------------------------
    // Lookup kombinasi label dari cache
    // -------------------------------------------------------
    public function getKombinasi(string $namaProduk, string $variasi): ?string
    {
        return $this->cache[$this->makeKey($namaProduk, $variasi)] ?? null;
    }

    private function makeKey(string $nama, string $variasi): string
    {
        return strtolower(trim($nama)) . '|||' . strtolower(trim($variasi));
    }
}
