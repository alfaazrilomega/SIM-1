<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
/* shared styles — identik dengan analytics */
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
.icon-red    { background:rgba(239,68,68,.15);   color:#f87171; }

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
.badge-warning { background:rgba(250,204,21,.12); color:#facc15; border:1px solid rgba(250,204,21,.2); }
.badge-danger  { background:rgba(239,68,68,.12);  color:#f87171; border:1px solid rgba(239,68,68,.2); }
.filter-bar { display:flex; gap:8px; flex-wrap:wrap; }
.sim-select, .sim-input { background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:6px 12px; font-size:.8rem; outline:none; transition:border-color .15s; }
.sim-select:focus, .sim-input:focus { border-color:rgba(79,142,247,.5); }
.sim-select option { background:#1e293b; }
.btn-accent { background:linear-gradient(90deg, #4f8ef7, #7c5cfc); border:none; color:#fff; border-radius:8px; padding:6px 14px; font-size:.8rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-accent:hover { opacity:.85; }
.btn-ghost { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.8rem; cursor:pointer; transition:border-color .15s, color .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-ghost:hover { border-color:rgba(79,142,247,.4); color:#e2e8f0; }

/* progress bar stok */
.stok-bar { background:#1e293b; border-radius:6px; height:6px; overflow:hidden; width:80px; }
.stok-bar-fill { height:100%; border-radius:6px; }
.fill-green  { background:linear-gradient(90deg, #4ade80, #22c55e); }
.fill-yellow { background:linear-gradient(90deg, #facc15, #eab308); }
.fill-red    { background:linear-gradient(90deg, #f87171, #ef4444); }

/* warning row */
.row-warn td:first-child { border-left:3px solid #f87171; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-basket2-fill"></i> Bahan Baku</div>
        <div class="page-subtitle">Manajemen stok & inventaris bahan baku</div>
    </div>
    <div class="filter-bar">
        <input  class="sim-input" type="text" placeholder="🔍 Cari bahan baku…">
        <select class="sim-select"><option>Semua Kategori</option><option>Rempah</option><option>Bumbu Dasar</option><option>Minyak</option></select>
        <button class="btn-accent"><i class="bi bi-plus-lg"></i> Tambah Bahan</button>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="bi bi-basket2-fill"></i></div>
        <div class="stat-value">87</div>
        <div class="stat-label">Total Jenis Bahan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-value">64</div>
        <div class="stat-label">Stok Aman</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="stat-value">18</div>
        <div class="stat-label">Stok Tipis</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-red"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-value">5</div>
        <div class="stat-label">Stok Habis</div>
    </div>
</div>

<!-- Table -->
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-basket2-fill" style="color:var(--accent);margin-right:6px"></i>Daftar Bahan Baku</span>
        <div class="filter-bar">
            <button class="btn-ghost"><i class="bi bi-arrow-down-up"></i> Urutkan</button>
            <button class="btn-ghost"><i class="bi bi-download"></i> Export</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bahans = [
                    ['Bawang Merah','Rempah','12,5','kg',85,'success','Aman'],
                    ['Bawang Putih','Rempah','8,2','kg',60,'success','Aman'],
                    ['Kemiri','Rempah','3,4','kg',32,'warning','Tipis'],
                    ['Ketumbar','Rempah','1,2','kg',15,'warning','Tipis'],
                    ['Lengkuas','Rempah','0','kg',0,'danger','Habis'],
                    ['Minyak Goreng','Minyak','25','liter',72,'success','Aman'],
                    ['Kunyit','Bumbu Dasar','0,8','kg',10,'warning','Tipis'],
                ];
                foreach ($bahans as $i => $b):
                    $pct = $b[4];
                    $fill = $pct >= 50 ? 'fill-green' : ($pct >= 20 ? 'fill-yellow' : 'fill-red');
                    $rowClass = $b[5] === 'danger' ? 'row-warn' : '';
                ?>
                <tr class="<?= $rowClass ?>">
                    <td style="color:var(--text-muted)"><?= $i+1 ?></td>
                    <td style="color:#e2e8f0;font-weight:500"><?= $b[0] ?></td>
                    <td><span class="badge-sim badge-warning"><?= $b[1] ?></span></td>
                    <td style="font-weight:600"><?= $b[2] ?></td>
                    <td style="color:var(--text-muted)"><?= $b[3] ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="stok-bar"><div class="stok-bar-fill <?= $fill ?>" style="width:<?= $pct ?>%"></div></div>
                            <span style="font-size:.72rem;color:var(--text-muted)"><?= $pct ?>%</span>
                        </div>
                    </td>
                    <td><span class="badge-sim badge-<?= $b[5] ?>"><?= $b[6] ?></span></td>
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

<?= $this->endSection() ?>