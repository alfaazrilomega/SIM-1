<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
/* ── PAGE STYLES ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.page-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.page-title i {
    color: var(--accent);
}
.page-subtitle {
    font-size: .8rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* ── STAT CARDS ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.stat-card {
    background: #0f172a;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: border-color .2s, transform .2s;
}
.stat-card:hover {
    border-color: rgba(79,142,247,.4);
    transform: translateY(-2px);
}
.stat-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(79,142,247,.04), rgba(124,92,252,.04));
}
.stat-card .stat-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    margin-bottom: 14px;
}
.stat-card .stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: #f1f5f9;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card .stat-label {
    font-size: .75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
}
.stat-card .stat-change {
    position: absolute;
    top: 20px; right: 20px;
    font-size: .72rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
}
.change-up   { background: rgba(34,197,94,.12); color: #4ade80; }
.change-down { background: rgba(239,68,68,.12);  color: #f87171; }

/* icon colors */
.icon-blue   { background: rgba(79,142,247,.15); color: #4f8ef7; }
.icon-purple { background: rgba(124,92,252,.15); color: #7c5cfc; }
.icon-yellow { background: rgba(250,204,21,.15); color: #facc15; }
.icon-green  { background: rgba(34,197,94,.15);  color: #4ade80; }

/* ── TABLE CARD ── */
.table-card {
    background: #0f172a;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.table-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.table-card-title {
    font-size: .9rem;
    font-weight: 600;
    color: #e2e8f0;
}
.table-responsive { overflow-x: auto; }
.sim-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .83rem;
}
.sim-table thead tr {
    background: #020617;
}
.sim-table thead th {
    padding: 11px 16px;
    text-align: left;
    color: var(--text-muted);
    font-weight: 600;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.sim-table tbody tr {
    border-bottom: 1px solid rgba(30,41,59,.6);
    transition: background .15s;
}
.sim-table tbody tr:last-child { border-bottom: none; }
.sim-table tbody tr:hover { background: rgba(79,142,247,.04); }
.sim-table tbody td {
    padding: 11px 16px;
    color: #cbd5e1;
    vertical-align: middle;
}

/* badge */
.badge-sim {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: .7rem;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
}
.badge-success { background: rgba(34,197,94,.12); color: #4ade80; border: 1px solid rgba(34,197,94,.2); }
.badge-warning { background: rgba(250,204,21,.12); color: #facc15; border: 1px solid rgba(250,204,21,.2); }
.badge-danger  { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }

/* filter bar */
.filter-bar {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.sim-select, .sim-input {
    background: #1e293b;
    border: 1px solid var(--border);
    color: #e2e8f0;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: .8rem;
    outline: none;
    transition: border-color .15s;
}
.sim-select:focus, .sim-input:focus {
    border-color: rgba(79,142,247,.5);
}
.sim-select option { background: #1e293b; }

.btn-accent {
    background: linear-gradient(90deg, #4f8ef7, #7c5cfc);
    border: none;
    color: #fff;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-accent:hover { opacity: .85; }

.btn-ghost {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text-muted);
    border-radius: 8px;
    padding: 6px 14px;
    font-size: .8rem;
    cursor: pointer;
    transition: border-color .15s, color .15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-ghost:hover { border-color: rgba(79,142,247,.4); color: #e2e8f0; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title">
            <i class="bi bi-bar-chart-fill"></i>
            Analytics
        </div>
        <div class="page-subtitle">Ringkasan performa penjualan & produk</div>
    </div>
    <div class="filter-bar">
        <select class="sim-select">
            <option>Bulan Ini</option>
            <option>3 Bulan</option>
            <option>6 Bulan</option>
            <option>Tahun Ini</option>
        </select>
        <button class="btn-accent"><i class="bi bi-download"></i> Export</button>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-change change-up">+12.4%</span>
        <div class="stat-icon icon-blue"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-value">Rp 48,2 Jt</div>
        <div class="stat-label">Total Penjualan</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-up">+8.1%</span>
        <div class="stat-icon icon-purple"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-value">1.348</div>
        <div class="stat-label">Total Produk Terjual</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-down">-2.3%</span>
        <div class="stat-icon icon-yellow"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-value">Rp 6,7 Jt</div>
        <div class="stat-label">Total Withdrawal</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-up">+5.6%</span>
        <div class="stat-icon icon-green"><i class="bi bi-jar-fill"></i></div>
        <div class="stat-value">24</div>
        <div class="stat-label">Varian Produk Aktif</div>
    </div>
</div>

<!-- Table -->
<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-list-ul" style="color:var(--accent);margin-right:6px"></i>Top Produk Bulan Ini</span>
        <button class="btn-ghost"><i class="bi bi-funnel"></i> Filter</button>
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Qty Terjual</th>
                    <th>Pendapatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rows = [
                    ['Bumbu Rendang 200g','Bumbu','342','Rp 5.130.000','success','Tersedia'],
                    ['Bumbu Soto Ayam 150g','Bumbu','289','Rp 3.757.000','success','Tersedia'],
                    ['Bumbu Opor 200g','Bumbu','210','Rp 2.940.000','warning','Stok Tipis'],
                    ['Bumbu Gulai 200g','Bumbu','198','Rp 2.772.000','success','Tersedia'],
                    ['Bumbu Rawon 150g','Bumbu','175','Rp 2.275.000','danger','Habis'],
                ];
                foreach ($rows as $i => $r): ?>
                <tr>
                    <td style="color:var(--text-muted)"><?= $i+1 ?></td>
                    <td style="color:#e2e8f0;font-weight:500"><?= $r[0] ?></td>
                    <td><span class="badge-sim badge-warning"><?= $r[1] ?></span></td>
                    <td><?= $r[2] ?></td>
                    <td style="color:#4ade80;font-weight:600"><?= $r[3] ?></td>
                    <td><span class="badge-sim badge-<?= $r[4] ?>"><?= $r[5] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>