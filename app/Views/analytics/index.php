<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
      --bg-dash:   #0f172a;
      --bg-card:   #1e293b;
      --border-sim:rgba(255,255,255,0.08);
      --accent:    #3b82f6;
      --accent2:   #6366f1;
      --success:   #10b981;
      --warning:   #f59e0b;
      --danger:    #ef4444;
      --text-main: #f1f5f9;
      --text-muted:#94a3b8;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-sim);
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
        background: radial-gradient(circle at top right, rgba(79,142,247,.08), transparent 70%);
    }
    .stat-card .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 14px;
    }
    .stat-card .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-main);
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
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
    }
    .change-up   { background: rgba(34,197,94,.12); color: #4ade80; }
    .change-down { background: rgba(239,68,68,.12);  color: #f87171; }

    .icon-blue   { background: rgba(59,130,246,.15); color: #3b82f6; }
    .icon-purple { background: rgba(99,102,241,.15); color: #6366f1; }
    .icon-yellow { background: rgba(245,158,11,.15); color: #f59e0b; }
    .icon-green  { background: rgba(16,185,129,.15); color: #10b981; }

    .chart-row { display: grid; gap: 1.25rem; margin-bottom: 1.25rem; }
    .chart-row.col-2    { grid-template-columns: 1fr 1fr; }
    .chart-row.col-3    { grid-template-columns: 2fr 1fr; }
    @media(max-width:992px) { .chart-row.col-2, .chart-row.col-3 { grid-template-columns: 1fr; } }

    .chart-card { background: var(--bg-card); border: 1px solid var(--border-sim); border-radius: 14px; overflow: hidden; }
    .chart-head {
      padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--border-sim);
      display: flex; align-items: center; gap: .75rem;
    }
    .chart-head h3 { font-size: .95rem; font-weight: 700; flex: 1; margin: 0; color: var(--text-main); }
    .chart-body { padding: 1.5rem; }

    .sim-table {
        width: 100%; border-collapse: collapse; font-size: .85rem;
    }
    .sim-table thead th {
        padding: 12px 16px; text-align: left;
        color: var(--text-muted); font-size: .72rem;
        text-transform: uppercase; letter-spacing: .06em;
        border-bottom: 1px solid var(--border-sim); background: rgba(0,0,0,0.1);
    }
    .sim-table tbody td {
        padding: 14px 16px; color: #cbd5e1; border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .badge-sim {
        padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 700;
    }
    .badge-success { background: rgba(16,185,129,0.15); color: #34d399; }
    .badge-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .badge-danger  { background: rgba(239,68,68,0.15); color: #f87171; }
</style>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-bar-chart-fill text-primary"></i> Business Analytics</h1>
        <p class="text-muted small">Ringkasan performa penjualan & distribusi produk periode ini.</p>
    </div>
    <div class="header-actions d-flex gap-2">
        <select class="form-select form-select-sm bg-dark text-light border-secondary" style="width: auto;">
            <option>Bulan Ini</option>
            <option>3 Bulan Terakhir</option>
            <option>Tahun Ini</option>
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Export Reports</button>
    </div>
</div>

<!-- STAT CARDS -->
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
        <div class="stat-label">Produk Terjual</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-down">-2.3%</span>
        <div class="stat-icon icon-yellow"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-value">Rp 6,7 Jt</div>
        <div class="stat-label">Total Pencairan</div>
    </div>
    <div class="stat-card">
        <span class="stat-change change-up">+5.6%</span>
        <div class="stat-icon icon-green"><i class="bi bi-jar-fill"></i></div>
        <div class="stat-value">24</div>
        <div class="stat-label">Varian Aktif</div>
    </div>
</div>

<div class="chart-row col-2">
    <div class="chart-card">
        <div class="chart-head">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <h3>Tren Penjualan Harian</h3>
        </div>
        <div class="chart-body">
            <canvas id="salesChart" height="280"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-head">
            <i class="bi bi-pie-chart-fill text-info"></i>
            <h3>Komposisi Platform</h3>
        </div>
        <div class="chart-body">
            <canvas id="platformChart" height="280"></canvas>
        </div>
    </div>
</div>

<!-- TABLE SECTION -->
<div class="chart-card mt-4">
    <div class="chart-head">
        <i class="bi bi-award-fill text-primary"></i>
        <h3>Top Produk Terlaris</h3>
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
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
                    <td><?= $i+1 ?></td>
                    <td class="fw-bold"><?= $r[0] ?></td>
                    <td><span class="badge bg-secondary opacity-75 fw-normal" style="font-size: .65rem;"><?= $r[1] ?></span></td>
                    <td><?= $r[2] ?></td>
                    <td class="text-success fw-bold"><?= $r[3] ?></td>
                    <td><span class="badge-sim badge-<?= $r[4] ?>"><?= $r[5] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Sales Chart
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: ['01 Apr','05 Apr','10 Apr','15 Apr','20 Apr','25 Apr','30 Apr'],
            datasets: [{
                label: 'Penjualan (Jt)',
                data: [12, 19, 15, 25, 22, 30, 48],
                borderColor: '#4f8ef7',
                backgroundColor: 'rgba(79,142,247,0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });

    // Platform Chart
    const ctxPlat = document.getElementById('platformChart').getContext('2d');
    new Chart(ctxPlat, {
        type: 'doughnut',
        data: {
            labels: ['TikTok Shop','Tokopedia','Shopee','Manual'],
            datasets: [{
                data: [65, 15, 10, 10],
                backgroundColor: ['#4f8ef7','#7c5cfc','#facc15','#10b981'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { size: 11 }, usePointStyle: true, padding: 20 }
                }
            },
            cutout: '70%'
        }
    });
</script>
<?= $this->endSection() ?>