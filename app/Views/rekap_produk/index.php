<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .stat-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:28px; }
    .stat-card { background:#ffffff; border:1px solid var(--border); border-radius:14px; padding:20px; position:relative; overflow:hidden; transition:border-color .2s, transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .stat-card:hover { border-color:rgba(59,130,246,.3); transform:translateY(-2px); }
    .stat-card::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg, rgba(59,130,246,.02), rgba(99,102,241,.02)); }
    .stat-card .stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:14px; }
    .stat-card .stat-value { font-size:1.6rem; font-weight:700; color:var(--text-main); line-height:1; margin-bottom:4px; }
    .stat-card .stat-label { font-size:.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; }
    .stat-card .stat-change { position:absolute; top:20px; right:20px; font-size:.72rem; font-weight:600; padding:3px 8px; border-radius:20px; }
    
    .icon-blue   { background:rgba(79,142,247,.15); color:#4f8ef7; }
    .icon-purple { background:rgba(124,92,252,.15); color:#7c5cfc; }
    .icon-yellow { background:rgba(250,204,21,.15);  color:#facc15; }
    .icon-green  { background:rgba(34,197,94,.15);   color:#4ade80; }
    .change-up   { background:rgba(34,197,94,.12); color:#4ade80; }
    .change-down { background:rgba(239,68,68,.12);  color:#f87171; }

    .table-card { background:#ffffff; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .table-card-title  { font-size:.9rem; font-weight:600; color:var(--text-main); }
    
    .sim-table { width:100%; border-collapse:collapse; font-size:.83rem; }
    .sim-table thead tr { background:#f8fafc; }
    .sim-table thead th { padding:11px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
    .sim-table tbody td { padding:11px 16px; color:#334155; vertical-align:middle; border-bottom: 1px solid var(--border); }
    
    .badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:600; padding:3px 9px; border-radius:20px; }
    .badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
    .badge-warning { background:rgba(250,204,21,.12); color:#facc15; border:1px solid rgba(250,204,21,.2); }

    .month-tabs { display:flex; gap:4px; flex-wrap:wrap; margin-bottom:20px; }
    .month-tab { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.78rem; cursor:pointer; transition:all .15s; }
    .month-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); }

    .summary-row td { background:#f8fafc !important; font-weight:700; color:var(--text-main) !important; border-top:2px solid var(--border) !important; }
</style>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-box-seam-fill text-info"></i> Rekap Produk</h1>
        <p class="text-muted small">Ringkasan stok dan performa penjualan per produk.</p>
    </div>
    <div class="filter-bar d-flex gap-2">
        <select class="form-select form-select-sm border-secondary" style="width: auto;"><option>2025</option><option>2024</option></select>
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
        <button class="btn btn-sm btn-success"><i class="bi bi-download"></i> Export Excel</button>
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

<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-box-seam-fill" style="color:var(--accent);margin-right:6px"></i>Rekap April 2025</span>
        <input class="form-control form-control-sm border-secondary" type="text" placeholder="🔍 Cari produk…" style="width:180px">
Reference: [rekap_produk/index.php](file:///d:/laragon/www/SIM/app/Views/rekap_produk/index.php)
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
                    <td><?= $i+1 ?></td>
                    <td class="fw-bold"><?= $r[0] ?></td>
                    <td><code><?= $r[1] ?></code></td>
                    <td><?= $r[2] ?></td>
                    <td class="text-primary"><?= $r[3] ?></td>
                    <td class="text-success fw-bold"><?= $r[4] ?></td>
                    <td class="text-danger small"><?= $r[5] ?></td>
                    <td><?= $r[6] ?></td>
                    <td class="text-warning fw-bold"><?= $r[7] ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="summary-row">
                    <td colspan="5" class="text-end pe-4">Total</td>
                    <td>1.348</td>
                    <td>11</td>
                    <td>—</td>
                    <td class="text-warning fw-bold">Rp 18.214.000</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>