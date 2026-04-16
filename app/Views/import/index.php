<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:#e2e8f0; display:flex; align-items:center; gap:10px; }
.page-title i { color:var(--accent); }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

.table-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; }
.table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.table-card-title  { font-size:.9rem; font-weight:600; color:#e2e8f0; }
.table-responsive  { overflow-x:auto; }
.sim-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.sim-table thead tr { background:#020617; }
.sim-table thead th { padding:11px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody tr { border-bottom:1px solid rgba(30,41,59,.6); transition:background .15s; }
.sim-table tbody tr:last-child { border-bottom:none; }
.sim-table tbody tr:hover { background:rgba(79,142,247,.04); }
.sim-table tbody td { padding:11px 16px; color:#cbd5e1; vertical-align:middle; }
.badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:600; padding:3px 9px; border-radius:20px; }
.badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.badge-warning { background:rgba(250,204,21,.12); color:#facc15; border:1px solid rgba(250,204,21,.2); }
.badge-danger  { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.2); }
.badge-info    { background:rgba(79,142,247,.12); color:#4f8ef7; border:1px solid rgba(79,142,247,.2); }
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; }
.sim-select, .sim-input { background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:6px 12px; font-size:.8rem; outline:none; }
.sim-select option { background:#1e293b; }
.btn-accent { background:linear-gradient(90deg, #4f8ef7, #7c5cfc); border:none; color:#fff; border-radius:8px; padding:6px 14px; font-size:.8rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-accent:hover { opacity:.85; }
.btn-ghost { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.8rem; cursor:pointer; transition:border-color .15s, color .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-ghost:hover { border-color:rgba(79,142,247,.4); color:#e2e8f0; }

/* Upload zone */
.upload-zone {
    border: 2px dashed var(--border);
    border-radius: 14px;
    padding: 40px 24px;
    text-align: center;
    margin-bottom: 28px;
    transition: border-color .2s, background .2s;
    cursor: pointer;
    position: relative;
}
.upload-zone:hover {
    border-color: rgba(79,142,247,.5);
    background: rgba(79,142,247,.03);
}
.upload-zone input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.upload-icon {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, rgba(79,142,247,.15), rgba(124,92,252,.15));
    border-radius: 16px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: #4f8ef7;
    margin-bottom: 14px;
}
.upload-title { font-size: 1rem; font-weight: 600; color: #e2e8f0; margin-bottom: 6px; }
.upload-sub   { font-size: .8rem; color: var(--text-muted); margin-bottom: 16px; }
.upload-formats {
    display: inline-flex; gap: 8px; justify-content: center; flex-wrap: wrap;
}
.fmt-tag {
    background: #1e293b; border: 1px solid var(--border); border-radius: 6px;
    padding: 3px 10px; font-size: .72rem; color: var(--text-muted);
}

/* Steps */
.steps-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:28px; }
.step-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; padding:18px; display:flex; gap:14px; align-items:flex-start; }
.step-num { width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#4f8ef7,#7c5cfc); display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; color:#fff; flex-shrink:0; }
.step-title { font-size:.85rem; font-weight:600; color:#e2e8f0; margin-bottom:4px; }
.step-desc  { font-size:.75rem; color:var(--text-muted); }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-cloud-arrow-down-fill"></i> Import Data</div>
        <div class="page-subtitle">Import data produk & bahan baku dari file eksternal (TikTok OrderSKUList)</div>
    </div>
    <div class="filter-bar">
        <a href="<?= base_url('/withdrawal') ?>" class="btn-ghost" style="color:#f59e0b; border-color:rgba(245,158,11,.4);"><i class="bi bi-shield-lock-fill"></i> Dashboard Pencairan (CEO)</a>
        <button class="btn-ghost"><i class="bi bi-file-earmark-arrow-down"></i> Unduh Template</button>
        <button class="btn-accent"><i class="bi bi-cloud-arrow-down-fill"></i> Mulai Import</button>
    </div>
</div>

<div class="grid">
    <!-- ====== LEFT: Upload Panel ====== -->
    <div>
        <div class="card">
            <p class="card-label"><i class="bi bi-cloud-arrow-up"></i>Upload File Excel</p>
            
            <!-- Drop Zone -->
            <div id="drop-zone" tabindex="0" role="button" class="upload-zone"
                 aria-label="Klik atau seret file Excel .xlsx ke area ini">
                <i class="bi bi-file-earmark-spreadsheet upload-icon"></i>
                <div class="upload-title">Seret & Lepas file di sini</div>
                <div class="upload-sub">atau <u style="color:var(--accent)">klik untuk memilih file</u></div>
                <div class="upload-formats">
                    <span class="fmt-tag">.xlsx</span>
                    <span class="fmt-tag">.xls</span>
                    <span class="fmt-tag">.csv</span>
                    <span class="fmt-tag">Maks: 50 MB</span>
                </div>
            </div>
            <input type="file" id="file-picker" accept=".xlsx,.xls,.csv" style="display:none">

            <!-- Import Button -->
            <button id="btn-import" class="btn-accent" style="width:100%; justify-content:center; padding:12px;" disabled>
                <i class="bi bi-database-up"></i>
                <span id="btn-text">Pilih file terlebih dahulu</span>
            </button>
        </div><!-- /.card -->
    </div><!-- /LEFT -->

    <!-- ====== RIGHT: Info Sidebar ====== -->
    <div>
        <!-- Business Rules -->
        <div class="card" style="margin-bottom:1rem">
            <p class="card-label"><i class="bi bi-shield-check"></i>Business Rules Aktif</p>
            <ul class="info-list" style="list-style:none; padding:0;">
                <li style="display:flex; gap:10px; margin-bottom:12px;">
                    <i class="bi bi-arrow-repeat" style="color:var(--warning)"></i>
                    <div class="it"><strong>Upsert by Order ID</strong><br><span style="font-size:0.75rem; color:var(--text-muted)">Sudah ada → UPDATE. Baru → INSERT. Tanpa duplikat.</span></div>
                </li>
                <li style="display:flex; gap:10px; margin-bottom:12px;">
                    <i class="bi bi-lock-fill" style="color:var(--success)"></i>
                    <div class="it"><strong>Status Pencairan CEO Aman</strong><br><span style="font-size:0.75rem; color:var(--text-muted)"><code>status_penarikan</code> TIDAK direset saat re-import.</span></div>
                </li>
            </ul>
        </div>
    </div><!-- /RIGHT -->
</div>

    <div class="grid">

      <!-- ====== LEFT: Upload Panel ====== -->
      <div>
        <div class="card">
          <p class="card-label"><i class="bi bi-cloud-arrow-up"></i>Upload File Excel</p>

          <!-- Drop Zone -->
          <div id="drop-zone" tabindex="0" role="button"
               aria-label="Klik atau seret file Excel .xlsx ke area ini">
            <i class="bi bi-file-earmark-spreadsheet dz-icon"></i>
            <div class="dz-title">Seret & Lepas file di sini</div>
            <div class="dz-sub">atau <u style="color:var(--accent)">klik untuk memilih file</u></div>
            <div class="dz-tag">Format: .xlsx &nbsp;·&nbsp; Maks: 50 MB</div>
          </div>
          <input type="file" id="file-picker" accept=".xlsx">

          <!-- File Strip -->
          <div id="file-strip">
            <i class="bi bi-file-earmark-excel-fill fs-icon"></i>
            <div>
              <div id="fs-name" class="fs-name"></div>
              <div id="fs-size" class="fs-size"></div>
            </div>
            <button id="btn-clear" title="Hapus file">&times;</button>
          </div>

          <!-- Import Button -->
          <button id="btn-import" disabled>
            <i class="bi bi-database-up" id="btn-icon"></i>
            <span id="btn-text">Pilih file terlebih dahulu</span>
          </button>

          <!-- Progress -->
          <div id="progress-wrap">
            <div class="prog-label pulsing" id="prog-label">Membaca file Excel…</div>
            <div class="prog-track">
              <div class="prog-fill" id="prog-fill"></div>
            </div>
          </div>
        </div><!-- /.card -->

        <!-- Results -->
        <div id="results-wrap">

          <!-- Stat Chips -->
          <div class="stat-row" id="stat-row"></div>

          <!-- Error Box -->
          <div class="err-box" id="err-box"></div>

          <!-- Preview Table -->
          <div class="table-card" id="preview-card">
            <div class="table-card-head">
              <i class="bi bi-table" style="color:var(--accent)"></i>
              <span class="card-label" style="margin:0">Preview (10 Pesanan Pertama)</span>
            </div>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Produk (Sample)</th>
                    <th>Items</th>
                    <th>Total (Rp)</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="preview-tbody"></tbody>
              </table>
            </div>
          </div>

        </div><!-- /#results-wrap -->
      </div><!-- /LEFT -->

      <!-- ====== RIGHT: Info Sidebar ====== -->
      <div>
        <!-- Business Rules -->
        <div class="card" style="margin-bottom:1rem">
          <p class="card-label"><i class="bi bi-shield-check"></i>Business Rules Aktif</p>
          <ul class="info-list">
            <li>
              <i class="bi bi-arrow-repeat ii" style="color:var(--warning)"></i>
              <div class="it"><strong>Upsert by Order ID</strong>
              <span>Sudah ada → UPDATE. Baru → INSERT. Tanpa duplikat.</span></div>
            </li>
            <li>
              <i class="bi bi-lock-fill ii" style="color:var(--success)"></i>
              <div class="it"><strong>Status Pencairan CEO Aman</strong>
              <span><code>status_penarikan</code> TIDAK direset saat re-import.</span></div>
            </li>
            <li>
              <i class="bi bi-funnel-fill ii" style="color:var(--accent)"></i>
              <div class="it"><strong>Laporan filter "Selesai"</strong>
              <span>View laporan hanya tampilkan pesanan berstatus <em>Selesai</em>.</span></div>
            </li>
            <li>
              <i class="bi bi-tags-fill ii" style="color:#c084fc"></i>
              <div class="it"><strong>Kombinasi Produk + Variasi</strong>
              <span>Variasi "Default" → cek tabel <code>product_mapping</code>. Non-Default → nama + variasi.</span></div>
            </li>
            <li>
              <i class="bi bi-recycle ii" style="color:var(--warning)"></i>
              <div class="it"><strong>Detail Rebuild Otomatis</strong>
              <span>Item lama dihapus & diganti versi terbaru setiap re-import.</span></div>
            </li>
          </ul>
        </div>

        <!-- Columns -->
        <div class="card" style="margin-bottom:1rem">
          <p class="card-label"><i class="bi bi-columns"></i>Kolom yang Dibaca</p>
          <ul class="info-list">
            <?php
            $cols = [
              ['Order ID',              'Primary Key pesanan (18-digit TikTok ID)'],
              ['Order Status',          'Selesai / Dikirim / Dibatalkan'],
              ['Product Name',          'Nama produk verbatim dari Excel'],
              ['Variation',             'Variasi SKU (incl. "Default")'],
              ['Quantity',              'Jumlah unit tiap SKU'],
              ['SKU Settlement Amount', 'Jumlah yang diterima seller'],
              ['Create Time & Paid Time', 'Waktu order & pembayaran'],
            ];
            foreach ($cols as [$title, $desc]):
            ?>
            <li>
              <i class="bi bi-check-circle-fill ii" style="color:var(--success)"></i>
              <div class="it"><strong><?= esc($title) ?></strong><span><?= esc($desc) ?></span></div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Setup -->
        <div class="card">
          <p class="card-label"><i class="bi bi-terminal"></i>Setup Pertama Kali</p>
          <div style="font-size:.8rem;color:var(--muted);line-height:1.9">
            <div>1️⃣ Jalankan database migration:</div>
            <pre class="cmd">php spark migrate</pre>
            <div>2️⃣ Server development:</div>
            <pre class="cmd">php spark serve</pre>
            <div style="margin-top:.5rem">3️⃣ Buka <a href="<?= base_url('/import') ?>" style="color:var(--accent)"><?= base_url('/import') ?></a></div>
          </div>
        </div>

      </div><!-- /RIGHT -->
    </div><!-- /.grid -->
  </main>

  <footer>
    SIM Import Tool &nbsp;·&nbsp; CodeIgniter 4 &nbsp;·&nbsp; Platform: <code>TikTok OrderSKUList</code> &nbsp;·&nbsp; DB: <code>sim_orders</code>
  </footer>
</div>

<!-- Steps -->
<div class="steps-grid">
    <div class="step-card">
        <div class="step-num">1</div>
        <div><div class="step-title">Unduh Template</div><div class="step-desc">Gunakan template Excel yang sudah disediakan</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">2</div>
        <div><div class="step-title">Isi Data</div><div class="step-desc">Lengkapi data sesuai kolom yang tersedia</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">3</div>
        <div><div class="step-title">Upload File</div><div class="step-desc">Drag & drop atau pilih file dari perangkat</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">4</div>
        <div><div class="step-title">Verifikasi</div><div class="step-desc">Periksa preview sebelum import final</div></div>
    </div>
</div>

<!-- Upload Zone -->
<div class="upload-zone">
    <input type="file" accept=".xlsx,.xls,.csv">
    <div class="upload-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
    <div class="upload-title">Drag & drop file di sini</div>
    <div class="upload-sub">atau klik untuk memilih file dari perangkat Anda</div>
    <div class="upload-formats">
        <span class="fmt-tag">.xlsx</span>
        <span class="fmt-tag">.xls</span>
        <span class="fmt-tag">.csv</span>
        <span class="fmt-tag">Maks. 10 MB</span>
    </div>
</div>

<!-- Riwayat Import -->
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-clock-history" style="color:var(--accent);margin-right:6px"></i>Riwayat Import</span>
        <input class="sim-input" type="text" placeholder="🔍 Cari…" style="width:180px">
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama File</th>
                    <th>Tipe</th>
                    <th>Total Baris</th>
                    <th>Berhasil</th>
                    <th>Gagal</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $imports = [
                    ['produk_bumbu_apr.xlsx','Produk Bumbu','150','150','0','12 Apr 2025','success','Selesai'],
                    ['bahan_baku_mar.xlsx','Bahan Baku','87','85','2','31 Mar 2025','warning','Sebagian'],
                    ['rekap_produk_q1.csv','Rekap Produk','320','320','0','28 Mar 2025','success','Selesai'],
                    ['withdrawal_feb.xlsx','Withdrawal','42','0','42','14 Feb 2025','danger','Gagal'],
                    ['bahan_baku_feb.xlsx','Bahan Baku','63','63','0','01 Feb 2025','success','Selesai'],
                ];
                foreach ($imports as $i => $imp): ?>
                <tr>
                    <td style="color:var(--text-muted)"><?= $i+1 ?></td>
                    <td style="color:#e2e8f0;font-weight:500"><i class="bi bi-file-earmark-spreadsheet" style="color:#4ade80;margin-right:6px"></i><?= $imp[0] ?></td>
                    <td><span class="badge-sim badge-info"><?= $imp[1] ?></span></td>
                    <td><?= $imp[2] ?></td>
                    <td style="color:#4ade80"><?= $imp[3] ?></td>
                    <td style="color:<?= $imp[4] > 0 ? '#f87171' : 'var(--text-muted)' ?>"><?= $imp[4] ?></td>
                    <td style="color:var(--text-muted)"><?= $imp[6] ?></td>
                    <td><span class="badge-sim badge-<?= $imp[5] ?>"><?= $imp[7] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>