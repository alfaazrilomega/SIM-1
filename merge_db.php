<?php
// Script untuk menggabungkan (merge) data sim_orders2.sql ke database sim_orders yang sudah ada
$host = 'localhost';
$user = 'root';
$pass = ''; // Sesuaikan jika xampp anda pakai password
$dbname = 'sim_orders';

echo "Memulai proses merge data database...\n";

$db = new mysqli($host, $user, $pass, $dbname);
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error . "\n");
}

$file_cPath = __DIR__ . '/sim_orders2.sql';
if (!file_exists($file_cPath)) {
    die("File sim_orders2.sql tidak ditemukan!\n");
}

$sql = file_get_contents($file_cPath);

// 1. Ubah CREATE TABLE menjadi CREATE TABLE IF NOT EXISTS agar tabel lama tidak tertimpa/error
$sql = str_replace('CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $sql);

// 2. Ubah INSERT INTO menjadi INSERT IGNORE INTO agar tidak error jika id sudah ada (data lama aman)
$sql = preg_replace('/INSERT INTO(.*?)\(/i', 'INSERT IGNORE INTO$1(', $sql);

// 3. Ubah VIEW menjadi aman ditimpa agar tidak terjadi error #1050 untuk view
$sql = str_replace('CREATE ALGORITHM=', 'CREATE OR REPLACE ALGORITHM=', $sql);
$sql = str_replace('CREATE VIEW `', 'CREATE OR REPLACE VIEW `', $sql);

// 4. Hapus bagian deklarasi ALTER TABLE (primary key dll) dari akhir file
// Karena database Anda sudah memiliki Primary Key, phpMyAdmin akan error jika ini dieksekusi lagi.
$indexMarker = '-- Indexes for dumped tables';
$indexPos = strpos($sql, $indexMarker);
if ($indexPos !== false) {
    $sql = substr($sql, 0, $indexPos) . "\nCOMMIT;\n";
}

echo "Membuat file SQL baru yang aman untuk di-merge...\n";
$outputFile = __DIR__ . '/sim_orders_merged.sql';
file_put_contents($outputFile, $sql);

echo "✅ File baru berhasil dibuat: sim_orders_merged.sql\n";
echo "Silakan buka phpMyAdmin kembali, lalu pilih Import dan masukkan file sim_orders_merged.sql ini.\n";

$db->close();
