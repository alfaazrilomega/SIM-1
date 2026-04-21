<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table            = 'mitra_bisnis';
    protected $primaryKey       = 'id_mitra';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['nama_mitra', 'tipe_mitra', 'no_hp', 'alamat'];

    // Dates
    protected $useTimestamps = false; 
    // we use raw DATETIME DEFAULT CURRENT_TIMESTAMP in SQL, so CI doesn't strictly need to manage it.
}
