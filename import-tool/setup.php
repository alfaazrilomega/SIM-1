<?php
// =====================================================
// setup.php — First-run database creator
// Akses sekali: http://localhost/sim-import/setup.php
// =====================================================

$host = 'localhost';
$user = 'root';
$pass = '';
$port = 3306;

$sqlFile = __DIR__ . '/../database/sim_orders.sql';

$log    = [];
$errors = [];

try {
    // Koneksi TANPA memilih database dulu (supaya bisa CREATE DATABASE)
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Jalankan SQL file statement by statement
    $sql = file_get_contents($sqlFile);

    // Split by semicolon, tapi hati-hati dengan DELIMITER di stored procedure
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => strlen($s) > 5
    );

    foreach ($statements as $stmt) {
        try {
            $pdo->exec($stmt);
            // Ambil komentar pertama sebagai label
            preg_match('/--\s*(.+)/', $stmt, $m);
            $label = $m[1] ?? substr(str_replace(["\n", "\r"], ' ', $stmt), 0, 60);
            $log[] = ['ok', $label];
        } catch (PDOException $e) {
            // Beberapa statement mungkin sudah ada (IF NOT EXISTS), skip jika itu
            if (
                str_contains($e->getMessage(), 'already exists') ||
                str_contains($e->getMessage(), 'Duplicate')
            ) {
                $log[] = ['skip', 'Already exists — skip'];
            } else {
                $errors[] = $e->getMessage() . ' | SQL: ' . substr($stmt, 0, 80);
            }
        }
    }

} catch (PDOException $e) {
    $errors[] = 'Gagal konek ke MySQL: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Setup Database — SIM Import Tool</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background: #0d1117; color: #e6edf3; font-family: 'Segoe UI', sans-serif; padding: 2rem; }
  .card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; }
  .badge-ok   { background: #1a7f37; color: #fff; }
  .badge-skip { background: #9e6a03; color: #fff; }
  .badge-err  { background: #b62324; color: #fff; }
  pre { background: #0d1117; border: 1px solid #30363d; padding: 1rem; border-radius: 8px; overflow-x: auto; }
</style>
</head>
<body>
<div class="container" style="max-width:800px">
  <h2 class="mb-1">⚙️ Setup Database — SIM Import Tool</h2>
  <p class="text-secondary mb-4">Menjalankan <code>sim_orders.sql</code> ke MySQL XAMPP...</p>

  <div class="card p-4 mb-4">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><strong>❌ Ada Error:</strong>
        <ul class="mb-0 mt-2">
          <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($log)): ?>
      <table class="table table-sm table-dark table-borderless">
        <thead><tr><th>Status</th><th>Keterangan</th></tr></thead>
        <tbody>
        <?php foreach ($log as [$status, $msg]): ?>
          <tr>
            <td><span class="badge badge-<?= $status ?>"><?= strtoupper($status) ?></span></td>
            <td><small><?= htmlspecialchars($msg) ?></small></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <?php if (empty($errors)): ?>
      <div class="alert alert-success mt-3 mb-0">
        ✅ <strong>Database berhasil disetup!</strong>
        <a href="index.php" class="btn btn-success btn-sm ms-3">→ Buka Import Tool</a>
      </div>
    <?php endif; ?>
  </div>

  <small class="text-secondary">
    Host: <code><?= $host ?>:<?= $port ?></code> |
    User: <code><?= $user ?></code> |
    Database: <code>sim_orders</code>
  </small>
</div>
</body>
</html>
