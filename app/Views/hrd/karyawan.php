<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:var(--text-main); display:flex; align-items:center; gap:10px; }
.page-title i { color:var(--accent); }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

.table-card { background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,.05); }
.table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.table-card-title { font-size:.9rem; font-weight:600; color:var(--text-main); }
.table-responsive { overflow-x:auto; }
.sim-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.sim-table thead tr { background:#f8fafc; }
.sim-table thead th { padding:11px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody tr { border-bottom:1px solid var(--border); transition:background .15s; }
.sim-table tbody tr:last-child { border-bottom:none; }
.sim-table tbody tr:hover { background:#f8fafc; }
.sim-table tbody td { padding:11px 16px; color:#334155; vertical-align:middle; }

.sim-input { width:100%; background:#fff; border:1px solid var(--border); color:var(--text-main); border-radius:8px; padding:8px 12px; font-size:.8rem; outline:none; transition:border-color .15s; margin-bottom:12px; }
.sim-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(59,130,246,.1); }

.btn-accent { background:linear-gradient(90deg, #3b82f6, #6366f1); border:none; color:#fff; border-radius:8px; padding:8px 14px; font-size:.85rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; justify-content:center; gap:6px; width:100%; }
.btn-accent:hover { opacity:.85; }

.btn-ghost { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:4px 9px; font-size:.75rem; cursor:pointer; transition:border-color .15s, color .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-ghost:hover { border-color:var(--accent); color:var(--accent); }

.form-label { font-size:.8rem; color:var(--text-muted); font-weight:500; margin-bottom:6px; display:block; }
.alert-sim { padding:12px 16px; border-radius:8px; margin-bottom:20px; display:flex; align-items:center; gap:10px; font-size:.85rem; font-weight:500; border:1px solid transparent; }
.alert-sim-success { background:rgba(16,185,129,.08); border-color:rgba(16,185,129,.25); color:#059669; }
.alert-sim-error   { background:rgba(239,68,68,.08);  border-color:rgba(239,68,68,.25);  color:#dc2626; }
.grid-2 { display:grid; grid-template-columns:350px 1fr; gap:20px; align-items:start; }
@media(max-width: 900px) { .grid-2 { grid-template-columns:1fr; } }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-people-fill" style="color:#4f8ef7"></i> Kelola Karyawan</div>
        <div class="page-subtitle">Manajemen data pegawai dan posisi pekerjaan</div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
  <div class="alert-sim alert-sim-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="grid-2">
  <div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title" id="form-title"><i class="bi bi-person-plus-fill" style="color:var(--accent);margin-right:6px"></i>Registrasi Karyawan</span>
    </div>
    <div style="padding: 20px;">
        <form action="<?= base_url('/hrd/store-karyawan') ?>" method="post" id="form-karyawan">
          <input type="hidden" name="id_karyawan" id="id_karyawan">
          
          <span class="form-label">Nama Lengkap</span>
          <input type="text" name="nama_karyawan" id="nama_karyawan" class="sim-input" required>
          
          <span class="form-label">Posisi / Job Desk</span>
          <input type="text" name="posisi" id="posisi" class="sim-input" placeholder="Contoh: Admin Produksi" required>
          
          <span class="form-label">Rate Gaji per Jam (IDR)</span>
          <input type="number" name="rate_gaji_per_jam" id="rate_gaji_per_jam" class="sim-input" value="10000" required>
          
          <button type="submit" id="btn-submit" class="btn-accent" style="margin-top:10px">Tambah Karyawan</button>
          <button type="button" id="btn-cancel" class="btn-ghost" style="display:none; width:100%; justify-content:center; margin-top:8px" onclick="resetForm()">Batal Edit</button>
        </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-people-fill" style="color:var(--accent);margin-right:6px"></i>Daftar Karyawan Aktif</span>
    </div>
    <div class="table-responsive">
      <table class="sim-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Posisi</th>
          <th>Rate/Jam</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($karyawan as $k): ?>
        <tr>
          <td style="color:#4f8ef7; font-family:monospace;">#<?= $k['id_karyawan'] ?></td>
          <td style="color:var(--text-main); font-weight:600;"><?= $k['nama_karyawan'] ?></td>
          <td style="color:var(--text-muted);"><?= $k['posisi'] ?></td>
          <td style="font-weight:bold; color:#4ade80;">Rp <?= number_format($k['rate_gaji_per_jam'], 0, ',', '.') ?></td>
          <td>
            <div style="display:flex; gap:6px;">
                <button type="button" class="btn-ghost" style="color:#4f8ef7; border-color:rgba(79,142,247,.3)" onclick="editKaryawan(<?= htmlspecialchars(json_encode($k)) ?>)">
                   <i class="bi bi-pencil"></i>
                </button>
                <form action="<?= base_url('/hrd/delete-karyawan/' . $k['id_karyawan']) ?>" method="post" style="display:inline; margin:0;" onsubmit="return confirm('Hapus karyawan ini? (Data absen dan gaji terkait akan ikut terhapus)')">
                  <button type="submit" class="btn-ghost" style="color:#f87171; border-color:rgba(239,68,68,.3)"><i class="bi bi-trash"></i></button>
                </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function editKaryawan(data) {
    document.getElementById('form-title').innerText = 'Edit Karyawan';
    document.getElementById('id_karyawan').value = data.id_karyawan;
    document.getElementById('nama_karyawan').value = data.nama_karyawan;
    document.getElementById('posisi').value = data.posisi;
    document.getElementById('rate_gaji_per_jam').value = data.rate_gaji_per_jam;
    document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
    document.getElementById('btn-cancel').style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form-title').innerText = 'Registrasi Karyawan';
    document.getElementById('form-karyawan').reset();
    document.getElementById('id_karyawan').value = '';
    document.getElementById('btn-submit').innerText = 'Tambah Karyawan';
    document.getElementById('btn-cancel').style.display = 'none';
}
</script>

<?= $this->endSection() ?>
