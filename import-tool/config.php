<?php
// =====================================================
// config.php — Database Connection (XAMPP defaults)
// Ganti DB_PASS jika XAMPP-mu punya password root
// =====================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Default XAMPP: kosong
define('DB_NAME', 'sim_orders');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// ---- Buat koneksi PDO ----
try {
    $pdo = new PDO(
        sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        ),
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Kembalikan JSON error jika dipanggil dari AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error'   => 'Koneksi database gagal: ' . $e->getMessage(),
            'hint'    => 'Pastikan XAMPP MySQL sudah running dan database "sim_orders" sudah dibuat via setup.php'
        ]);
        exit;
    }
    die('<b>Koneksi database gagal:</b> ' . htmlspecialchars($e->getMessage())
        . '<br>Jalankan <a href="setup.php">setup.php</a> terlebih dahulu.');
}
