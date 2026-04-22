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
        <p class="text-muted small">Ringkasan performa penjualan per produk.</p>
    </div>
    <div class="filter-bar d-flex gap-2">
        <select id="yearSelect" class="form-select form-select-sm border-secondary" style="width: auto;">
            <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
            <option value="<?= date('Y') - 1 ?>"><?= date('Y') - 1 ?></option>
            <option value="<?= date('Y') - 2 ?>"><?= date('Y') - 2 ?></option>
        </select>
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
        <button class="btn btn-sm btn-success"><i class="bi bi-download"></i> Export Excel</button>
    </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-value" id="stat-total-terjual">0</div>
        <div class="stat-label">Total Terjual (Bruto)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="bi bi-currency-dollar"></i></div>
        <div class="stat-value" id="stat-total-pendapatan">Rp 0</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-yellow"><i class="bi bi-box"></i></div>
        <div class="stat-value" id="stat-total-produk">0</div>
        <div class="stat-label">Varian Terjual</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple"><i class="bi bi-arrow-return-left"></i></div>
        <div class="stat-value" id="stat-total-retur">0</div>
        <div class="stat-label">Total Retur</div>
    </div>
</div>

<!-- Month Tabs -->
<div class="month-tabs">
    <?php
    $months = [
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agt',
        '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
    ];
    $currentMonth = date('m');
    foreach ($months as $num => $label): ?>
    <button class="month-tab <?= $num === $currentMonth ? 'active' : '' ?>" data-month="<?= $num ?>"><?= $label ?></button>
    <?php endforeach; ?>
</div>

<div class="table-card">
    <div class="table-card-header">
        <span class="table-card-title"><i class="bi bi-box-seam-fill" style="color:var(--accent);margin-right:6px"></i>Rekap <span id="rekap-label-title">Bulan Ini</span></span>
        <input class="form-control form-control-sm border-secondary" type="text" id="searchInput" placeholder="🔍 Cari produk…" style="width:180px">
    </div>
    <div class="table-responsive">
        <table class="sim-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Variasi</th>
                    <th>Terjual (Bruto)</th>
                    <th>Retur</th>
                    <th>Terjual (Bersih)</th>
                    <th>Harga Satuan</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr><td colspan="8" class="text-center py-4">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const yearSelect = document.getElementById('yearSelect');
    const monthTabs = document.querySelectorAll('.month-tab');
    const tableBody = document.getElementById('table-body');
    const rekapLabelTitle = document.getElementById('rekap-label-title');
    const searchInput = document.getElementById('searchInput');

    let allRows = [];

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    async function fetchData() {
        const year = yearSelect.value;
        const activeMonthTab = document.querySelector('.month-tab.active');
        const month = activeMonthTab ? activeMonthTab.dataset.month : new Date().getMonth() + 1;
        const monthName = activeMonthTab ? activeMonthTab.textContent : '';
        
        rekapLabelTitle.textContent = `${monthName} ${year}`;
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm" role="status"></div> Memuat data...</td></tr>`;
        
        const period = `${year}-${month.toString().padStart(2, '0')}`;
        const url = `/rekap-produk/unit-terjual?from=${period}&to=${period}`;

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const data = await res.json();

            if (data.success) {
                allRows = data.rows || [];
                renderTable(allRows);
                updateStats(allRows);
            } else {
                tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Gagal memuat data</td></tr>`;
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Terjadi kesalahan koneksi</td></tr>`;
        }
    }

    function renderTable(rows) {
        if (rows.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data untuk periode ini</td></tr>`;
            return;
        }

        let html = '';
        let totalBruto = 0;
        let totalRetur = 0;
        let totalBersih = 0;
        let totalPendapatan = 0;

        rows.forEach((r, idx) => {
            const netto = parseFloat(r.total_qty_bersih || 0);
            const bruto = parseFloat(r.total_qty_bruto || 0);
            const retur = parseFloat(r.total_retur || 0);
            const pendapatan = parseFloat(r.total_subtotal || 0);

            totalBruto += bruto;
            totalRetur += retur;
            totalBersih += netto;
            totalPendapatan += pendapatan;

            html += `<tr>
                <td>${idx + 1}</td>
                <td class="fw-bold">${r.nama_produk_raw || '-'}</td>
                <td><span class="badge-sim ${r.variasi_raw ? 'badge-success' : 'bg-secondary text-white'}">${r.variasi_raw || 'Default'}</span></td>
                <td class="text-primary fw-bold">${formatNumber(bruto)}</td>
                <td class="text-danger small fw-bold">${formatNumber(retur)}</td>
                <td class="text-success fw-bold">${formatNumber(netto)}</td>
                <td>${formatRupiah(r.avg_harga_satuan || 0)}</td>
                <td class="text-warning fw-bold">${formatRupiah(pendapatan)}</td>
            </tr>`;
        });

        html += `<tr class="summary-row">
            <td colspan="3" class="text-end pe-4">Total Keseluruhan</td>
            <td class="text-primary">${formatNumber(totalBruto)}</td>
            <td class="text-danger">${formatNumber(totalRetur)}</td>
            <td class="text-success">${formatNumber(totalBersih)}</td>
            <td>—</td>
            <td class="text-warning fw-bold">${formatRupiah(totalPendapatan)}</td>
        </tr>`;

        tableBody.innerHTML = html;
    }

    function updateStats(rows) {
        const sumBruto = rows.reduce((acc, r) => acc + parseFloat(r.total_qty_bruto || 0), 0);
        const sumRetur = rows.reduce((acc, r) => acc + parseFloat(r.total_retur || 0), 0);
        const sumPendapatan = rows.reduce((acc, r) => acc + parseFloat(r.total_subtotal || 0), 0);
        
        document.getElementById('stat-total-terjual').textContent = formatNumber(sumBruto);
        document.getElementById('stat-total-retur').textContent = formatNumber(sumRetur);
        document.getElementById('stat-total-pendapatan').textContent = formatRupiah(sumPendapatan);
        document.getElementById('stat-total-produk').textContent = rows.length;
    }

    monthTabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            monthTabs.forEach(t => t.classList.remove('active'));
            e.target.classList.add('active');
            fetchData();
        });
    });

    yearSelect.addEventListener('change', fetchData);

    searchInput.addEventListener('input', (e) => {
        const val = e.target.value.toLowerCase();
        if (!val) {
            renderTable(allRows);
            return;
        }
        const filtered = allRows.filter(r => 
            (r.nama_produk_raw && r.nama_produk_raw.toLowerCase().includes(val)) || 
            (r.variasi_raw && r.variasi_raw.toLowerCase().includes(val))
        );
        renderTable(filtered);
    });

    // Ambil data pertama kali saat halaman dimuat
    fetchData();
});
</script>

<?= $this->endSection() ?>