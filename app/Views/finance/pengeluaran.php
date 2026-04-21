<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<style>
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:#e2e8f0; display:flex; align-items:center; gap:10px; }
.page-title i { color:var(--accent); }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

.stat-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px; }
.stat-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; padding:20px; position:relative; overflow:hidden; transition:border-color .2s, transform .2s; }
.stat-card:hover { border-color:rgba(79,142,247,.4); transform:translateY(-2px); }
.stat-card::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg, rgba(79,142,247,.04), rgba(124,92,252,.04)); }
.stat-card .stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:14px; }
.stat-card .stat-value { font-size:1.6rem; font-weight:700; color:#f1f5f9; line-height:1; margin-bottom:4px; }
.stat-card .stat-label { font-size:.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; }
.icon-yellow { background:rgba(250,204,21,.15);  color:#facc15; }

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

.badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:600; padding:3px 9px; border-radius:20px; }
.badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.badge-danger  { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.2); }

.filter-bar { display:flex; gap:8px; flex-wrap:wrap; }
.sim-select, .sim-input { width: 100%; background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:8px 12px; font-size:.8rem; outline:none; transition:border-color .15s; margin-bottom: 12px; }
.sim-select:focus, .sim-input:focus { border-color:rgba(79,142,247,.5); }
.sim-select:disabled, .sim-input[readonly] { background:rgba(30,41,59,.5); color:#64748b; cursor:not-allowed; }
.sim-select option { background:#1e293b; }

.btn-accent { background:linear-gradient(90deg, #4f8ef7, #7c5cfc); border:none; color:#fff; border-radius:8px; padding:8px 14px; font-size:.85rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; justify-content:center; gap:6px; width: 100%; }
.btn-accent:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-accent:hover:not(:disabled) { opacity:.85; }

.form-label { font-size:.8rem; color:#94a3b8; font-weight:500; margin-bottom:6px; display:block; }
.alert-sim { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 500; }
.alert-sim-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
.alert-sim-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #f87171; }
.grid-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; align-items: start; }
@media(max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-wallet2"></i> Kas Ledger</div>
        <div class="page-subtitle">Sistem Pencatatan Kas Masuk & Keluar Operasional</div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
  <div class="alert-sim alert-sim-success"><i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div class="alert-sim alert-sim-danger"><i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Stat Card Khusus Saldo -->
<div class="stat-grid">
    <div class="stat-card" style="text-align: center;">
        <div class="stat-label">TOTAL SALDO BERJALAN (LIVE)</div>
        <div class="stat-value" style="color: #facc15; font-size: 2.2rem; margin-top: 5px;">Rp <?= number_format($saldo, 2, ',', '.') ?></div>
    </div>
</div>

<div class="grid-2">
  <!-- FORM INPUT -->
  <div class="table-card" style="border-top: 4px solid #7c5cfc;">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-pencil-square" style="color:var(--accent);margin-right:6px"></i>Jurnal Transaksi Baru</span>
    </div>
    
    <div style="padding: 20px;">
        <form action="<?= base_url('/finance/store') ?>" method="post" id="formKas">
          <span class="form-label">Tanggal Transaksi</span>
          <input type="date" name="tanggal" class="sim-input" required value="<?= date('Y-m-d') ?>">
          
          <span class="form-label">Tipe Arus Kas</span>
          <select name="tipe_transaksi" id="tipe_transaksi" class="sim-select" required>
            <option value="" selected disabled>-- Pilih Identitas Arus --</option>
            <option value="Pengeluaran">🔴 Pengeluaran (Kas Keluar)</option>
            <option value="Pemasukan">🟢 Pemasukan (Kas Masuk)</option>
          </select>

          <span class="form-label">Kategori (Klasifikasi Ledger)</span>
          <select name="kategori" id="kategori" class="sim-select" required disabled>
            <option value="">(Pilih Tipe Kas Terlebih Dahulu)</option>
          </select>

          <!-- BLOK DINAMIS UNTUK GAJI -->
          <div id="blok_gaji" style="display: none; background: rgba(79, 142, 247, 0.05); padding: 15px; border-radius: 8px; border: 1px dashed rgba(79,142,247,0.4); margin-bottom: 15px;">
            <span class="form-label" style="color: #4f8ef7;"><i class="bi bi-person-lines-fill"></i> Pilih Tagihan Slip Gaji Aktif</span>
            <select name="id_penggajian" id="id_penggajian" class="sim-select" style="margin-bottom:8px">
              <option value="" selected disabled>-- Pilih Karyawan --</option>
              <?php foreach ($unpaidGaji as $g): ?>
                <option value="<?= $g['id_penggajian'] ?>" data-nominal="<?= $g['total_gaji'] ?>">
                   [<?= $g['periode_bulan'] ?>] <?= $g['nama_karyawan'] ?> - Rp <?= number_format($g['total_gaji'], 0, ',', '.') ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div style="font-size:0.7rem; color:var(--text-muted);"><i class="bi bi-info-circle"></i> Membayar lewat form ini akan otomats melunasi tagihan HRD.</div>
          </div>

          <!-- BLOK STANDAR -->
          <div id="blok_manual">
            <span class="form-label">Keterangan / Catatan Transaksi <span style="color:#f87171">*</span></span>
            <input type="text" name="keterangan" id="keterangan" class="sim-input" placeholder="Contoh: Bayar listrik pabrik">

            <span class="form-label">Nominal Pembayaran (IDR) <span style="color:#f87171">*</span></span>
            <input type="number" name="nominal" id="nominal" class="sim-input" placeholder="Contoh: 1500000" min="0">
          </div>

          <button type="submit" id="btnSimpan" class="btn-accent" style="margin-top:5px" disabled><i class="bi bi-save"></i> Eksekusi ke Ledger</button>
        </form>
    </div>
  </div>

  <!-- TABEL RIWAYAT TRANSAKSI -->
  <div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-journal-text" style="color:var(--accent);margin-right:6px"></i>Riwayat Buku Besar (Ledger)</span>
    </div>
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
      <table class="sim-table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Arus Kas</th>
            <th>Kategori Detail</th>
            <th style="text-align: right;">Nominal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($transaksi as $t): ?>
          <tr>
            <td style="white-space: nowrap; color: var(--text-muted)"><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
            <td>
              <?php if($t['tipe_transaksi'] == 'Pemasukan'): ?>
                <span class="badge-sim badge-success"><i class="bi bi-arrow-down-left-circle-fill"></i> Masuk</span>
              <?php else: ?>
                <span class="badge-sim badge-danger"><i class="bi bi-arrow-up-right-circle-fill"></i> Keluar</span>
              <?php endif; ?>
            </td>
            <td>
              <div style="font-weight: 600; color: #e2e8f0; font-size: 0.8rem;"><?= $t['kategori'] ?></div>
              <div style="font-size: 0.72rem; color: var(--text-muted); mt-1"><?= $t['keterangan'] ?></div>
            </td>
            <td style="text-align: right; font-weight: bold; font-family: monospace; font-size: 1em; color: #cbd5e1;">
              Rp <?= number_format($t['nominal'], 0, ',', '.') ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if(empty($transaksi)): ?>
         <div style="text-align: center; color: var(--text-muted); padding: 2rem; font-size: 0.8rem;">Belum ada sejarah pencatatan arus kas.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
  const elTipe = document.getElementById('tipe_transaksi');
  const elKategori = document.getElementById('kategori');
  const elBlokGaji = document.getElementById('blok_gaji');
  const elBlokManual = document.getElementById('blok_manual');
  const elIdPenggajian = document.getElementById('id_penggajian');
  const elNominal = document.getElementById('nominal');
  const elKeterangan = document.getElementById('keterangan');
  const elBtn = document.getElementById('btnSimpan');

  // Pisahkan array dropdown berdasar tipe! (Inilah yg diminta oleh CEO untuk laporan data mentah)
  const katPemasukan = [
    {val: 'Pendapatan Penjualan', text: 'Pendapatan Penjualan Bumbu'},
    {val: 'Pencairan / Withdrawal Platform', text: 'Pencairan Titipan Uang (Tiktok/Shopee)'},
    {val: 'Suntikan Modal / Lainnya', text: 'Lainnya'}
  ];

  const katPengeluaran = [
    {val: 'Listrik', text: 'Bayar Listrik / Air'},
    {val: 'Maintenance Mesin', text: 'Bayar Servis Mesin'},
    {val: 'Biaya Gaji / Upah Karyawan', text: '⚡ Bayar Gaji Karyawan (Interkoneksi Sistem)'},
    {val: 'Bahan Baku', text: 'Belanja Bahan Baku (Rekan Tim Inventory)'},
    {val: 'Operasional Lainnya', text: 'Operasional Harian Lainnya'}
  ];

  function renderOptions(array) {
    elKategori.innerHTML = '<option value="" selected disabled>-- Menunggu Pilihan Kategori --</option>';
    array.forEach(item => {
      elKategori.innerHTML += `<option value="${item.val}">${item.text}</option>`;
    });
    elKategori.disabled = false;
  }

  elTipe.addEventListener('change', function() {
    elBtn.disabled = true;
    elBlokGaji.style.display = 'none';
    elBlokManual.style.display = 'block';
    
    // Reset Data Form Security
    elIdPenggajian.value = '';
    elNominal.value = '';
    elNominal.readOnly = false;
    elKeterangan.value = '';
    elKeterangan.readOnly = false;

    if(this.value === 'Pemasukan') {
      renderOptions(katPemasukan);
    } else if(this.value === 'Pengeluaran') {
      renderOptions(katPengeluaran);
    }
  });

  elKategori.addEventListener('change', function() {
    elBtn.disabled = false;

    if(this.value === 'Biaya Gaji / Upah Karyawan') {
      // Mode God-Tier: Buka portal koneksi ke tabel Gaji di backend. Matikan form manual
      elBlokGaji.style.display = 'block';
      elNominal.readOnly = true;
      elKeterangan.readOnly = true;
      elIdPenggajian.required = true;
      elNominal.required = false; 
      
      // User tidak usah repot ngetik keterangan. Otomatisasi data kotor.
      elNominal.value = '';
      elKeterangan.value = 'Auto-synchronized by HRD Module System';
    } else {
      // Mode Reguler Kas Biasa
      elBlokGaji.style.display = 'none';
      elNominal.readOnly = false;
      elKeterangan.readOnly = false;
      elIdPenggajian.required = false;
      elNominal.required = true;

      // Beri helper prefix untuk kategori rekan tim
      if(this.value === 'Bahan Baku') {
         elKeterangan.value = '[INV] Pembelian: ';
      } else if (this.value === 'Pencairan / Withdrawal Platform') {
         elKeterangan.value = '[WD] Tarik dana dari ';
      } else {
         elKeterangan.value = '';
      }
    }
  });

  // Kosmetik: Ketika user memilih gaji siapa yang mau dibayar, angka "Nominal" menyala (hanya buat preview visual frontend)
  elIdPenggajian.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const nom = selectedOption.getAttribute('data-nominal');
    if(nom) {
      elNominal.value = nom;
    }
  });
</script>
<?= $this->endSection() ?>
