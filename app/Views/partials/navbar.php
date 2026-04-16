<?php
$activeNav = $activeNav ?? '';

// Tentukan title manual (simple & aman)
$currentTitle = 'Dashboard';
$currentIcon  = '📁';

if ($activeNav === 'analitik') {
  $currentTitle = 'SIM Analytics';
  $currentIcon  = '📊';
} elseif ($activeNav === 'bahan-baku') {
  $currentTitle = 'Bahan Baku';
  $currentIcon  = '🧺';
} elseif ($activeNav === 'produk-bumbu') {
  $currentTitle = 'Produk Bumbu';
  $currentIcon  = '🫙';
} elseif ($activeNav === 'rekap-produk') {
  $currentTitle = 'Rekap Produk';
  $currentIcon  = '📦';
} elseif ($activeNav === 'withdrawal') {
  $currentTitle = 'Withdrawal';
  $currentIcon  = '💰';
} elseif ($activeNav === 'karyawan' || $activeNav === 'penggajian') {
  $currentTitle = 'HRD Portal';
  $currentIcon  = '👥';
} elseif ($activeNav === 'pengeluaran') {
  $currentTitle = 'Finance & Ledger';
  $currentIcon  = '💸';
}
?>

<header style="display:flex;justify-content:space-between;align-items:center">

  <!-- LOGO DINAMIS -->
  <a href="<?= base_url('/') ?>" style="display:flex;gap:10px;text-decoration:none;color:white;">
    <div><?= $currentIcon ?></div>
    <div><?= $currentTitle ?></div>
  </a>

  <!-- DROPDOWN -->
  <div style="position:relative">
    <button onclick="toggleMenu()" style="padding:10px 16px;border-radius:10px;background:#0d1225;color:#fff;border:1px solid #333">
      Menu ⌄
    </button>

    <div id="menuBox" style="display:none;position:absolute;right:0;margin-top:10px;background:#0d1225;border:1px solid #333;border-radius:10px;padding:5px;min-width:200px">

      <a href="<?= base_url('/analytics') ?>" style="display:block;padding:10px">Analytics</a>
      <a href="<?= base_url('/bahan-baku') ?>" style="display:block;padding:10px">Bahan Baku</a>
      <a href="<?= base_url('/import') ?>" style="display:block;padding:10px;text-decoration:none;color:white;">Import</a>
      <a href="<?= base_url('/produk-bumbu') ?>" style="display:block;padding:10px;text-decoration:none;color:white;">Produk Bumbu</a>
      <a href="<?= base_url('/rekap-produk') ?>" style="display:block;padding:10px;text-decoration:none;color:white;">Rekap Produk</a>
      <a href="<?= base_url('/withdrawal') ?>" style="display:block;padding:10px;text-decoration:none;color:white;">Withdrawal</a>
      <div style="height:1px;background:#333;margin:5px 0;"></div>
      <a href="<?= base_url('/hrd/karyawan') ?>" style="display:block;padding:10px;color:var(--accent);text-decoration:none;">👥 Data Karyawan (HRD)</a>
      <a href="<?= base_url('/hrd/penggajian') ?>" style="display:block;padding:10px;color:var(--accent);text-decoration:none;">📄 Slip Gaji (HRD)</a>
      <a href="<?= base_url('/finance/pengeluaran') ?>" style="display:block;padding:10px;color:var(--success);text-decoration:none;">💸 Arus Kas Ledger (Finance)</a>

    </div>
  </div>

</header>

<script>
function toggleMenu() {
  const menu = document.getElementById('menuBox');
  menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// klik luar = tutup
document.addEventListener('click', function(e){
  if (!e.target.closest('#menuBox') && !e.target.closest('button')) {
    document.getElementById('menuBox').style.display = 'none';
  }
});
</script>