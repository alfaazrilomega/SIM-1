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
.stat-card .stat-change { position:absolute; top:20px; right:20px; font-size:.72rem; font-weight:600; padding:3px 8px; border-radius:20px; }
.change-up   { background:rgba(34,197,94,.12); color:#4ade80; }
.change-down { background:rgba(239,68,68,.12);  color:#f87171; }
.icon-blue   { background:rgba(79,142,247,.15); color:#4f8ef7; }
.icon-purple { background:rgba(124,92,252,.15); color:#7c5cfc; }
.icon-yellow { background:rgba(250,204,21,.15);  color:#facc15; }
.icon-green  { background:rgba(34,197,94,.15);   color:#4ade80; }

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
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; }
.sim-select, .sim-input { background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:6px 12px; font-size:.8rem; outline:none; }
.sim-select option { background:#1e293b; }
.btn-accent { background:linear-gradient(90deg, #4f8ef7, #7c5cfc); border:none; color:#fff; border-radius:8px; padding:6px 14px; font-size:.8rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-accent:hover { opacity:.85; }
.btn-ghost { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.8rem; cursor:pointer; transition:border-color .15s, color .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-ghost:hover { border-color:rgba(79,142,247,.4); color:#e2e8f0; }

/* month selector tabs */
.month-tabs { display:flex; gap:4px; flex-wrap:wrap; margin-bottom:20px; }
.month-tab { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.78rem; cursor:pointer; transition:all .15s; }
.month-tab:hover { border-color:rgba(79,142,247,.4); color:#e2e8f0; }
.month-tab.active { background:linear-gradient(90deg, rgba(79,142,247,.15), rgba(124,92,252,.1)); color:#fff; border-color:rgba(79,142,247,.3); }

/* summary row */
.summary-row td { background:#020617 !important; font-weight:700; color:#e2e8f0 !important; border-top:2px solid var(--border) !important; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-box-seam-fill"></i> Rekap Produk</div>
        <div class="page-subtitle">Rekap penjualan produk per periode</div>
    </div>
    <div class="filter-bar">
        <select class="sim-select"><option>2025</option><option>2024</option></select>
        <button class="btn-ghost"><i class="bi bi-printer"></i> Cetak</button>
        <button class="btn-accent"><i class="bi bi-download"></i> Export Excel</button>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-change change-up">+9.2%</span>
        <div class="stat-icon icon-blue"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-value">4.218</div>
        <div class="stat-label">Total Terjual (Ytd)</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-up">+11%</span>
        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-value">Rp 61,2 Jt</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-up">+3.4%</span>
        <div class="stat-icon icon-yellow"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-value">Rp 14.500</div>
        <div class="stat-label">Rata-rata / Produk</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-down">-1.2%</span>
        <div class="stat-icon icon-purple"><i class="bi bi-arrow-return-left"></i></div>
        <div class="stat-value">38</div>
        <div class="stat-label">Total Retur</div>
    </div>
</div>

<!-- Month Tabs -->
<div class="month-tabs">
    <?php
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    foreach ($months as $m): ?>
    <button class="month-tab <?= $m === 'Apr' ? 'active' : '' ?>"><?= $m ?></button>
    <?php endforeach; ?>
</div>

<!-- Table -->
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-box-seam-fill" style="color:var(--accent);margin-right:6px"></i>Rekap April 2025</span>
        <input class="sim-input" type="text" placeholder="🔍 Cari produk…" style="width:180px">
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Stok Awal</th>
                    <th>Produksi</th>
                    <th>Terjual</th>
                    <th>Retur</th>
                    <th>Stok Akhir</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rekapData = [
                    ['Bumbu Rendang 200g','BM-001','200','150','342','2','6','Rp 5.130.000'],
                    ['Bumbu Soto Ayam 150g','BM-002','180','100','289','0','0 ','Rp 3.757.000'],
                    ['Bumbu Opor 200g','BM-003','120','80','210','3','0 ','Rp 2.940.000'],
                    ['Bumbu Gulai 200g','BM-004','160','60','198','1','21 ','Rp 2.772.000'],
                    ['Bumbu Rawon 150g','BM-005','100','40','175','5','0 ','Rp 2.275.000'],
                    ['Bumbu Pecel 100g','BM-006','80','50','134','0','0 ','Rp 1.340.000'],
                ];
                foreach ($rekapData as $i => $r): ?>
                <tr>
                    <td style="color:var(--text-muted)"><?= $i+1 ?></td>
                    <td style="color:#e2e8f0;font-weight:500"><?= $r[0] ?></td>
                    <td style="color:var(--text-muted)"><?= $r[1] ?></td>
                    <td><?= $r[2] ?></td>
                    <td style="color:#4f8ef7"><?= $r[3] ?></td>
                    <td style="color:#4ade80;font-weight:600"><?= $r[4] ?></td>
                    <td style="color:<?= trim($r[5]) > 0 ? '#f87171' : 'var(--text-muted)' ?>"><?= $r[5] ?></td>
                    <td><?= $r[6] ?></td>
                    <td style="color:#facc15;font-weight:600"><?= $r[7] ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="summary-row">
                    <td colspan="5" style="text-align:right;padding-right:20px">Total</td>
                    <td>1.348</td>
                    <td>11</td>
                    <td>—</td>
                    <td style="color:#facc15">Rp 18.214.000</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>