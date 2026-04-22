<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
      --bg-dash:   #f8fafc;
      --bg-card:   #ffffff;
      --border-sim:rgba(0,0,0,0.06);
      --accent:    #3b82f6;
      --accent2:   #6366f1;
      --success:   #10b981;
      --warning:   #f59e0b;
      --danger:    #ef4444;
      --text-main: #0f172a;
      --text-muted:#64748b;
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
        border-color: rgba(59,130,246,.3);
        transform: translateY(-2px);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(59,130,246,.03), transparent 70%);
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
    .change-up   { background: rgba(34,197,94,.1); color: #059669; }
    .change-down { background: rgba(239,68,68,.1);  color: #dc2626; }

    .icon-blue   { background: rgba(59,130,246,.15); color: #3b82f6; }
    .icon-purple { background: rgba(99,102,241,.15); color: #6366f1; }
    .icon-yellow { background: rgba(245,158,11,.15); color: #f59e0b; }
    .icon-green  { background: rgba(16,185,129,.15); color: #10b981; }

    .chart-row { display: grid; gap: 1.25rem; margin-bottom: 1.25rem; }
    .chart-row.col-full { grid-template-columns: 1fr; }
    .chart-row.col-2-side { grid-template-columns: 2fr 1fr; }
    .chart-row.col-2    { grid-template-columns: 1fr; }
    .chart-row.col-3    { grid-template-columns: 2fr 1fr; }
    @media(max-width:992px) {
        .chart-row.col-2-side,
        .chart-row.col-2,
        .chart-row.col-3 {
            grid-template-columns: 1fr;
        }
    }

    .chart-card { background: var(--bg-card); border: 1px solid var(--border-sim); border-radius: 14px; overflow: hidden; }
    .chart-head {
      padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--border-sim);
      display: flex; align-items: center; gap: .75rem;
    }
    .chart-head h3 { font-size: .95rem; font-weight: 700; flex: 1; margin: 0; color: var(--text-main); }
    .chart-body { padding: 1.5rem; position: relative; width: 100%; height: 100%; }

    .chart-body canvas {
        max-width: 100%;
        height: auto !important;
        display: block;
    }

    .sim-table {
        width: 100%; border-collapse: collapse; font-size: .85rem;
    }
    .sim-table thead th {
        padding: 12px 16px; text-align: left;
        color: var(--text-muted); font-size: .72rem;
        text-transform: uppercase; letter-spacing: .06em;
        border-bottom: 1px solid var(--border-sim); background: #f8fafc;
    }
    .sim-table tbody td {
        padding: 14px 16px; color: #334155; border-bottom: 1px solid var(--border-sim);
    }
    .badge-sim {
        padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 700;
    }
    .badge-success { background: rgba(16,185,129,0.15); color: #34d399; }
    .badge-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .badge-danger  { background: rgba(239,68,68,0.15); color: #f87171; }

    .export-dropdown {
        position: relative;
        display: inline-block;
    }

    .export-menu {
        display: none;
        position: absolute;
        background-color: #ffffff;
        min-width: 200px;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        padding: 12px 16px;
        z-index: 1;
        border-radius: 8px;
        border: 1px solid var(--border-sim);
        right: 0;
        top: 100%;
        margin-top: 4px;
    }

    .export-menu a {
        color: var(--text-main);
        padding: 12px 0;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-sim);
    }

    .export-menu a:last-child {
        border-bottom: none;
    }

    .export-menu a:hover {
        color: var(--accent);
    }

    .export-menu.show {
        display: block;
    }

    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #10b981;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease-in;
    }

    .toast-notification.error {
        background-color: #ef4444;
    }

    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }

    .loading-spinner {
        display: inline-block;
        width: 20px; height: 20px;
        border: 3px solid rgba(59,130,246,0.3);
        border-radius: 50%;
        border-top-color: #3b82f6;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-bar-chart-fill text-primary"></i> Business Analytics</h1>
        <p class="text-muted small">Ringkasan performa penjualan & distribusi produk periode ini.</p>
    </div>
    <div class="header-actions d-flex gap-2">
        <select id="periodSelect" class="form-select form-select-sm bg-dark text-light border-secondary" style="width: auto;">
            <option value="7">7 Hari Terakhir</option>
            <option value="30">Bulan Ini</option>
            <option value="90">3 Bulan Terakhir</option>
            <option value="365">Tahun Ini</option>
            <option value="all" selected>Semua Data</option>
        </select>
        <div class="export-dropdown">
            <button id="exportBtn" class="btn btn-sm btn-primary">
                <i class="bi bi-download"></i> Export
            </button>
            <div id="exportMenu" class="export-menu">
                <a id="exportPDF" href="#">
                    <i class="bi bi-file-pdf"></i> Export as PDF
                </a>
                <a id="exportCSV" href="#">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export as CSV
                </a>
            </div>
        </div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid" id="statCardsContainer">
    <div class="stat-card skeleton" style="height: 140px;"></div>
    <div class="stat-card skeleton" style="height: 140px;"></div>
    <div class="stat-card skeleton" style="height: 140px;"></div>
    <div class="stat-card skeleton" style="height: 140px;"></div>
</div>

<div class="chart-row col-2-side">
    <div class="chart-card">
        <div class="chart-head">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            <h3>Tren Penjualan Harian</h3>
        </div>
        <div class="chart-body">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-head">
            <i class="bi bi-wallet2 text-info"></i>
            <h3>Metode Pembayaran</h3>
        </div>
        <div class="chart-body">
            <canvas id="platformChart"></canvas>
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
        <table class="sim-table" id="topProductsTable">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Nama Produk</th>
                    <th>Qty Terjual</th>
                    <th>Pendapatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <div class="loading-spinner"></div> Loading data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    let analyticsData = null;
    let salesChart    = null;
    let platformChart = null;

    // ========================================
    // Toast Notification
    // ========================================
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification' + (type === 'error' ? ' error' : '');
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // ========================================
    // Format Currency
    // ========================================
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // ========================================
    // Load Analytics Data
    // ========================================
    function loadAnalyticsData(range = 'all') {
        fetch(`<?= base_url('analytics/data') ?>?range=${range}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                analyticsData = data;
                updateStatCards(data);
                updateCharts(data);
                updateProductTable(data);
                showToast('Data berhasil dimuat', 'success');
            } else {
                throw new Error(data.error || 'Failed to load data');
            }
        })
        .catch(error => {
            console.error('Error loading analytics data:', error);
            showToast('Gagal memuat data. Silakan coba lagi.', 'error');
        });
    }

    // ========================================
    // Update Stat Cards
    // ========================================
    function updateStatCards(data) {
        const kpi               = data.kpi || {};
        const totalRevenue      = parseFloat(kpi.total_revenue) || 0;
        const totalQty          = parseInt(kpi.total_qty_terjual) || 0;
        const pendingWithdrawal = parseFloat(kpi.dana_pending) || 0;
        const lastMonthRevenue  = parseFloat(data.rev_bulan_lalu) || totalRevenue;
        const changePercent     = lastMonthRevenue > 0
            ? ((totalRevenue - lastMonthRevenue) / lastMonthRevenue * 100).toFixed(1)
            : 0;

        document.getElementById('statCardsContainer').innerHTML = `
            <div class="stat-card">
                <span class="stat-change ${changePercent >= 0 ? 'change-up' : 'change-down'}">
                    ${changePercent >= 0 ? '+' : ''}${changePercent}%
                </span>
                <div class="stat-icon icon-blue"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="stat-value">${formatCurrency(totalRevenue)}</div>
                <div class="stat-label">Total Penjualan</div>
            </div>
            <div class="stat-card">
                <span class="stat-change change-up">+${totalQty}</span>
                <div class="stat-icon icon-purple"><i class="bi bi-box-seam-fill"></i></div>
                <div class="stat-value">${totalQty.toLocaleString('id-ID')}</div>
                <div class="stat-label">Produk Terjual</div>
            </div>
            <div class="stat-card">
                <span class="stat-change change-down">${data.kpi_cancel ? '-' + data.kpi_cancel.total_cancel : 0}</span>
                <div class="stat-icon icon-yellow"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-value">${formatCurrency(pendingWithdrawal)}</div>
                <div class="stat-label">Dana Pending</div>
            </div>
            <div class="stat-card">
                <span class="stat-change change-up">+${(totalQty / 10).toFixed(0)}</span>
                <div class="stat-icon icon-green"><i class="bi bi-bag-check-fill"></i></div>
                <div class="stat-value">${kpi.total_order || 0}</div>
                <div class="stat-label">Total Pesanan</div>
            </div>
        `;
    }

    // ========================================
    // Update Charts
    // ========================================
    function updateCharts(data) {
        if (data.revenue_chart && data.revenue_chart.length > 0) {
            const labels   = data.revenue_chart.map(d => new Date(d.tgl).toLocaleDateString('id-ID'));
            const revenues = data.revenue_chart.map(d => parseFloat(d.revenue) / 1000000);

            if (salesChart) salesChart.destroy();

            salesChart = new Chart(document.getElementById('salesChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Penjualan (Jt)',
                        data: revenues,
                        borderColor: '#4f8ef7',
                        backgroundColor: 'rgba(79,142,247,0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#4f8ef7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    animation: { duration: 0 },
                    plugins: {
                        legend: { display: false },
                        filler: { propagate: true }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { color: '#64748b' },
                            beginAtZero: true
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }

        if (data.payment_chart && data.payment_chart.length > 0) {
            const labels = data.payment_chart.map(d => d.payment_method || 'Lainnya');
            const values = data.payment_chart.map(d => parseInt(d.jml));
            const colors = ['#4f8ef7','#7c5cfc','#facc15','#10b981','#f59e0b','#ef4444','#06b6d4','#ec4899'];

            if (platformChart) platformChart.destroy();

            platformChart = new Chart(document.getElementById('platformChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.2,
                    animation: { duration: 0 },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#64748b',
                                font: { size: 11 },
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    }

    // ========================================
    // Update Product Table
    // ========================================
    function updateProductTable(data) {
        const tbody = document.getElementById('productTableBody');

        if (!data.top_products || data.top_products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data produk</td></tr>';
            return;
        }

        tbody.innerHTML = data.top_products.map((product, index) => {
            const statusClass = product.total_return == 0 ? 'success' : 'warning';
            const statusText  = product.total_return == 0 ? 'Tersedia' : 'Ada Retur';
            return `
                <tr>
                    <td>${index + 1}</td>
                    <td class="fw-bold">${product.nama_produk_raw || 'N/A'}</td>
                    <td>${product.total_qty || 0}</td>
                    <td class="text-success fw-bold">${formatCurrency(product.total_revenue || 0)}</td>
                    <td><span class="badge-sim badge-${statusClass}">${statusText}</span></td>
                </tr>
            `;
        }).join('');
    }

    // ========================================
    // Export Dropdown Toggle
    // ========================================
    document.getElementById('exportBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('exportMenu').classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!document.querySelector('.export-dropdown').contains(e.target)) {
            document.getElementById('exportMenu').classList.remove('show');
        }
    });

    // ========================================
    // Export as PDF — jsPDF + html2canvas
    // ========================================
    document.getElementById('exportPDF').addEventListener('click', function(e) {
        e.preventDefault();

        const btn          = document.getElementById('exportBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML      = '<i class="bi bi-hourglass-split"></i> Generating...';
        btn.disabled       = true;
        document.getElementById('exportMenu').classList.remove('show');

        const element = document.querySelector('.page-header').parentElement;

        html2canvas(element, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            foreignObjectRendering: false,
            ignoreElements: function(el) {
                // Abaikan elemen dropdown supaya tidak ikut ter-capture
                return el.id === 'exportMenu';
            }
        }).then(function(canvas) {
            const { jsPDF }  = window.jspdf;
            const pdf        = new jsPDF('p', 'mm', 'a4');
            const pageWidth  = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const imgWidth   = pageWidth;
            const imgHeight  = (canvas.height * imgWidth) / canvas.width;
            const imgData    = canvas.toDataURL('image/jpeg', 0.95);

            let heightLeft = imgHeight;
            let position   = 0;

            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            pdf.save('business-analytics-' + new Date().toISOString().split('T')[0] + '.pdf');
            showToast('PDF berhasil diunduh!', 'success');

        }).catch(function(err) {
            console.error('PDF Error:', err);
            showToast('Gagal membuat PDF. Silakan coba lagi.', 'error');
        }).finally(function() {
            btn.innerHTML = originalText;
            btn.disabled  = false;
        });
    });

    // ========================================
    // Export as CSV
    // ========================================
    document.getElementById('exportCSV').addEventListener('click', function(e) {
        e.preventDefault();

        try {
            const table = document.querySelector('.sim-table');
            if (!table) {
                showToast('Tabel tidak ditemukan.', 'error');
                return;
            }

            const csv = [];
            table.querySelectorAll('tr').forEach(row => {
                const cells = [];
                row.querySelectorAll('td, th').forEach(cell => {
                    cells.push(`"${cell.textContent.trim().replace(/"/g, '""')}"`);
                });
                csv.push(cells.join(','));
            });

            const csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
            const link       = document.createElement('a');
            link.setAttribute('href', csvContent);
            link.setAttribute('download', 'products-report-' + new Date().toISOString().split('T')[0] + '.csv');
            link.click();

            showToast('CSV berhasil diunduh!', 'success');
            document.getElementById('exportMenu').classList.remove('show');

        } catch (err) {
            console.error('CSV Export Error:', err);
            showToast('Gagal membuat CSV. Silakan coba lagi.', 'error');
        }
    });

    // ========================================
    // Period Change Handler
    // ========================================
    document.getElementById('periodSelect').addEventListener('change', function(e) {
        loadAnalyticsData(e.target.value);
    });

    // ========================================
    // Initial Load
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        loadAnalyticsData('all');
    });
</script>
<?= $this->endSection() ?>