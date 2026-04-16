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

/* Product grid mode toggle */
.view-toggle button { background:none; border:1px solid var(--border); color:var(--text-muted); padding:5px 10px; cursor:pointer; font-size:.85rem; }
.view-toggle button:first-child { border-radius:8px 0 0 8px; }
.view-toggle button:last-child  { border-radius:0 8px 8px 0; }
.view-toggle button.active { background:#1e293b; color:#e2e8f0; border-color:rgba(79,142,247,.4); }

/* Product card for grid view */
.product-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px; margin-bottom:20px; }
.product-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; padding:18px; transition:border-color .2s, transform .2s; }
.product-card:hover { border-color:rgba(79,142,247,.4); transform:translateY(-2px); }
.product-card-img { width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg,rgba(250,204,21,.15),rgba(79,142,247,.15)); display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:12px; }
.product-card-name { font-size:.87rem; font-weight:600; color:#e2e8f0; margin-bottom:4px; }
.product-card-sku  { font-size:.72rem; color:var(--text-muted); margin-bottom:10px; }
.product-card-price{ font-size:1rem; font-weight:700; color:#facc15; margin-bottom:8px; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-jar-fill"></i> Produk Bumbu</div>
        <div class="page-subtitle">Katalog & manajemen produk bumbu</div>
    </div>
    <div class="filter-bar">
        <input class="sim-input" type="text" placeholder="🔍 Cari produk…">
        <select class="sim-select"><option>Semua Status</option><option>Tersedia</option><option>Stok Tipis</option><option>Habis</option></select>
        <button class="btn-accent"><i class="bi bi-plus-lg"></i> Tambah Produk</button>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon icon-yellow"><i class="bi bi-jar-fill"></i></div>
        <div class="stat-value">24</div>
        <div class="stat-label">Total Varian</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-value">18</div>
        <div class="stat-label">Stok Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-value">Rp 15.000</div>
        <div class="stat-label">Harga Rata-rata</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple"><i class="bi bi-star-fill"></i></div>
        <div class="stat-value">Rendang</div>
        <div class="stat-label">Terlaris</div>
    </div>
</div>

<!-- Product Grid -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
    <span style="font-size:.9rem;font-weight:600;color:#e2e8f0"><i class="bi bi-jar-fill" style="color:var(--accent);margin-right:6px"></i>Daftar Produk Bumbu</span>
    <div class="view-toggle" id="viewToggle">
        <button class="active" onclick="setView('grid',this)"><i class="bi bi-grid-3x3-gap-fill"></i></button>
        <button onclick="setView('table',this)"><i class="bi bi-table"></i></button>
    </div>
</div>

<!-- Grid View -->
<div class="product-grid" id="gridView">
    <?php
    $products = [
        ['🌶️','Bumbu Rendang 200g','BM-001','Rp 15.000','success','Tersedia'],
        ['🍲','Bumbu Soto Ayam 150g','BM-002','Rp 13.000','success','Tersedia'],
        ['🥘','Bumbu Opor 200g','BM-003','Rp 14.000','warning','Stok Tipis'],
        ['🫕','Bumbu Gulai 200g','BM-004','Rp 14.500','success','Tersedia'],
        ['🍜','Bumbu Rawon 150g','BM-005','Rp 12.000','danger','Habis'],
        ['🥗','Bumbu Pecel 100g','BM-006','Rp 10.000','success','Tersedia'],
        ['🌊','Bumbu Ikan Bakar 120g','BM-007','Rp 11.000','success','Tersedia'],
        ['🫙','Bumbu Semur 180g','BM-008','Rp 13.500','warning','Stok Tipis'],
    ];
    foreach ($products as $p): ?>
    <div class="product-card">
        <div class="product-card-img"><?= $p[0] ?></div>
        <div class="product-card-name"><?= $p[1] ?></div>
        <div class="product-card-sku"><?= $p[2] ?></div>
        <div class="product-card-price"><?= $p[3] ?></div>
        <span class="badge-sim badge-<?= $p[4] ?>"><?= $p[5] ?></span>
    </div>
    <?php endforeach; ?>
</div>

<!-- Table View (hidden) -->
<div class="table-card" id="tableView" style="display:none">
    <div class="table-responsive">
        <table class="sim-table">
            <thead><tr><th>#</th><th>Produk</th><th>SKU</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($products as $i => $p): ?>
            <tr>
                <td style="color:var(--text-muted)"><?= $i+1 ?></td>
                <td><span style="margin-right:8px"><?= $p[0] ?></span><span style="color:#e2e8f0;font-weight:500"><?= $p[1] ?></span></td>
                <td style="color:var(--text-muted)"><?= $p[2] ?></td>
                <td style="color:#facc15;font-weight:600"><?= $p[3] ?></td>
                <td>—</td>
                <td><span class="badge-sim badge-<?= $p[4] ?>"><?= $p[5] ?></span></td>
                <td>
                    <button class="btn-ghost" style="padding:4px 9px;font-size:.75rem"><i class="bi bi-pencil"></i></button>
                    <button class="btn-ghost" style="padding:4px 9px;font-size:.75rem;color:#f87171;border-color:rgba(239,68,68,.3)"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function setView(v, btn) {
    document.getElementById('gridView').style.display  = v === 'grid'  ? 'grid'  : 'none';
    document.getElementById('tableView').style.display = v === 'table' ? 'block' : 'none';
    document.querySelectorAll('#viewToggle button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>

<?= $this->endSection() ?>