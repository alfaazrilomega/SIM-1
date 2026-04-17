<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:#e2e8f0; display:flex; align-items:center; gap:10px; }
.page-title i { color:var(--accent); }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

.table-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; }
.table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.table-card-title { font-size:.9rem; font-weight:600; color:#e2e8f0; }
.table-responsive { overflow-x:auto; }
.sim-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.sim-table thead tr { background:#020617; }
.sim-table thead th { padding:11px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody tr { border-bottom:1px solid rgba(30,41,59,.6); transition:background .15s; }
.sim-table tbody tr:last-child { border-bottom:none; }
.sim-table tbody tr:hover { background:rgba(79,142,247,.04); }
.sim-table tbody td { padding:11px 16px; color:#cbd5e1; vertical-align:middle; }

.badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:600; padding:4px 10px; border-radius:20px; }
.badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.badge-warning { background:rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }

.sim-select, .sim-input { width: 100%; background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:8px 12px; font-size:.8rem; outline:none; transition:border-color .15s; margin-bottom: 12px; }
.sim-select:focus, .sim-input:focus { border-color:rgba(79,142,247,.5); }
.sim-select option { background:#1e293b; }

.btn-accent { background:linear-gradient(90deg, #4f8ef7, #7c5cfc); border:none; color:#fff; border-radius:8px; padding:8px 14px; font-size:.85rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; justify-content:center; gap:6px; width: 100%; }
.btn-accent:hover { opacity:.85; }

.btn-pay { background: linear-gradient(135deg, #22c55e, #16a34a); padding: 6px 12px; border-radius: 6px; border: none; color: white; font-size: 11px; font-weight: 600; cursor: pointer; display:inline-flex; align-items:center; }
.btn-pay:hover { opacity: 0.9; }

.form-label { font-size:.8rem; color:#94a3b8; font-weight:500; margin-bottom:6px; display:block; }
.alert-sim { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 500; border: 1px solid transparent;}
.alert-sim-success { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.3); color: #4ade80; }
.alert-sim-error { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #f87171; }
.grid-2 { display: grid; grid-template-columns: 350px 1fr; gap: 20px; align-items: start; }
@media(max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-envelope-paper-fill" style="color:#7c5cfc"></i> Hitung & Bayar Gaji</div>
        <div class="page-subtitle">Kalkulasi absensi dan penerbitan slip penggajian</div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
  <div class="alert-sim alert-sim-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div class="alert-sim alert-sim-error"><i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="grid-2">
  <div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-calculator" style="color:var(--accent);margin-right:6px"></i>Generate Slip Gaji</span>
    </div>
    <div style="padding: 20px;">
        <p style="color:#94a3b8; font-size:12.5px; line-height: 1.4; margin-bottom:15px; margin-top:0;">Otomatis menghitung total jam disilang dengan Rate/Jam karyawan pada bulan tertentu.</p>
        <form action="<?= base_url('/hrd/generate-gaji') ?>" method="post">
          <span class="form-label">Pilih Karyawan</span>
          <select name="id_karyawan" class="sim-select" required>
            <?php foreach($karyawan as $k): ?>
              <option value="<?= $k['id_karyawan'] ?>"><?= $k['nama_karyawan'] ?> (<?= $k['posisi'] ?>)</option>
            <?php endforeach; ?>
          </select>
          
          <span class="form-label">Periode Bulan (YYYY-MM)</span>
          <input type="month" name="periode_bulan" class="sim-input" value="<?= date('Y-m') ?>" required>
          
          <button type="submit" class="btn-accent" style="margin-top:10px">Kalkulasi Gaji</button>
        </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-envelope-paper-fill" style="color:var(--accent);margin-right:6px"></i>Riwayat Pembayaran Gaji</span>
    </div>
    <div class="table-responsive">
    <table class="sim-table">
      <thead>
        <tr>
          <th>Periode</th>
          <th>Karyawan</th>
          <th>Total Jam</th>
          <th>Total Gaji (Rp)</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($penggajian as $p): ?>
        <tr>
          <td><b style="color:#4f8ef7; font-family:monospace;"><?= $p['periode_bulan'] ?></b></td>
          <td style="color:#e2e8f0; font-weight:500;"><?= $p['nama_karyawan'] ?></td>
          <td style="color:var(--text-muted);"><?= $p['total_jam'] ?> Jam</td>
          <td style="font-weight:bold; color:#cbd5e1;">Rp <?= number_format($p['total_gaji'], 0, ',', '.') ?></td>
          <td>
            <?php if($p['status_pembayaran'] === 'Belum Dibayar'): ?>
                <span class="badge-sim badge-warning">Menunggu</span>
            <?php else: ?>
                <span class="badge-sim badge-success"><i class="bi bi-check-circle-fill"></i> Lunas (<?= date('d/m/y', strtotime($p['tanggal_bayar'])) ?>)</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($p['status_pembayaran'] === 'Belum Dibayar'): ?>
              <form action="<?= base_url('/hrd/bayar-gaji/' . $p['id_penggajian']) ?>" method="post" style="margin:0">
                <button type="submit" class="btn-pay" onclick="return confirm('Bayar gaji ini menggunakan Uang Kas Operasional?')"><i class="bi bi-cash"></i> Bayar</button>
              </form>
            <?php else: ?>
              <span style="color:var(--text-muted); font-size:12px;">✅</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
