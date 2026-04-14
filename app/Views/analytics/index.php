<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIM — Dashboard Analitik</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    :root {
      --bg:        #07091a;
      --bg-card:   #0c1230;
      --bg-card2:  #111a3e;
      --border:    rgba(100,149,255,.13);
      --accent:    #4f8ef7;
      --accent2:   #7c5cfc;
      --success:   #22c55e;
      --warning:   #f59e0b;
      --danger:    #ef4444;
      --gold:      #f59e0b;
      --teal:      #06b6d4;
      --pink:      #ec4899;
      --text:      #e2e8f0;
      --muted:     #5a6a8a;
      --r:         14px;
      --font-head: 'Syne', sans-serif;
      --font-body: 'DM Sans', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { min-height: 100vh; background: var(--bg); color: var(--text); font-family: var(--font-body); font-size: 15px; }

    body::before {
      content: ''; position: fixed; inset: 0; z-index: 0;
      background: linear-gradient(rgba(79,142,247,.04) 1px, transparent 1px),
                  linear-gradient(90deg, rgba(79,142,247,.04) 1px, transparent 1px);
      background-size: 44px 44px; pointer-events: none;
    }
    body::after {
      content: ''; position: fixed; inset: 0; z-index: 0;
      background:
        radial-gradient(ellipse 50% 35% at 5% 15%, rgba(124,92,252,.06) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 95% 85%, rgba(6,182,212,.05) 0%, transparent 60%);
      pointer-events: none;
    }
    .page { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; }

    /* ===== HEADER ===== */
    header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1.1rem 2rem;
      background: rgba(7,9,26,.95);
      border-bottom: 1px solid var(--border);
      position: sticky; top: 0; z-index: 100;
    }
    .logo { display: flex; align-items: center; gap: .75rem; text-decoration: none; color: var(--text); }
    .logo-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, var(--accent2), var(--accent));
      border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      font-size: 19px; box-shadow: 0 4px 16px rgba(124,92,252,.35);
    }
    .logo-name { font-family: var(--font-head); font-weight: 800; font-size: 1.05rem; letter-spacing: -.3px; }
    .logo-sub  { font-size: .68rem; color: var(--muted); }
    .header-nav { display: flex; gap: .5rem; align-items: center; }
    .btn-nav {
      padding: .35rem .85rem; border: 1px solid var(--border);
      border-radius: 20px; font-size: .75rem; color: var(--accent);
      background: rgba(79,142,247,.08); text-decoration: none;
      transition: all .2s; display: inline-flex; align-items: center; gap: .3rem;
    }
    .btn-nav:hover { background: rgba(79,142,247,.2); }
    .btn-nav.active { background: rgba(124,92,252,.2); color: #a78bfa; border-color: rgba(124,92,252,.3); }
    .btn-nav.gold {
      color: #f59e0b; border-color: rgba(245,158,11,.4);
      background: rgba(245,158,11,.08);
    }
    .btn-nav.gold:hover { background: rgba(245,158,11,.18); }

    /* ===== MAIN ===== */
    main { flex: 1; padding: 2rem 1.5rem; max-width: 1200px; margin: 0 auto; width: 100%; }

    .page-header {
      display: flex; align-items: flex-end; justify-content: space-between;
      margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .page-header h1 {
      font-family: var(--font-head); font-size: 1.9rem; font-weight: 800; letter-spacing: -.5px;
      background: linear-gradient(135deg, #fff 20%, #a78bfa 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .page-header p { color: var(--muted); font-size: .88rem; margin-top: .3rem; }

    .range-picker { display: flex; gap: .35rem; }
    .range-btn {
      padding: .4rem .85rem; border: 1px solid var(--border);
      border-radius: 20px; font-size: .75rem; color: var(--muted);
      background: transparent; cursor: pointer; font-family: var(--font-body); transition: all .2s;
    }
    .range-btn:hover  { background: rgba(255,255,255,.05); color: var(--text); }
    .range-btn.active { background: rgba(124,92,252,.2); color: #a78bfa; border-color: rgba(124,92,252,.35); }

    .kpi-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;
    }
    @media(max-width:900px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:500px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
      background: var(--bg-card); border: 1px solid var(--border);
      border-radius: var(--r); padding: 1.4rem;
      position: relative; overflow: hidden; transition: all .25s;
    }
    .kpi-card:hover { border-color: rgba(79,142,247,.28); transform: translateY(-2px); }
    .kpi-card::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(circle at top right, var(--glow, rgba(79,142,247,.06)), transparent 70%);
    }
    .kpi-card.c-purple { --glow: rgba(124,92,252,.09); }
    .kpi-card.c-teal   { --glow: rgba(6,182,212,.07); }
    .kpi-card.c-gold   { --glow: rgba(245,158,11,.08); }
    .kpi-card.c-green  { --glow: rgba(34,197,94,.07); }
    .kpi-card.c-red    { --glow: rgba(239,68,68,.07); }
    .kpi-card.c-pink   { --glow: rgba(236,72,153,.07); }

    .kpi-icon  { font-size: 1.4rem; margin-bottom: .6rem; }
    .kpi-val   { font-family: var(--font-head); font-size: 1.6rem; font-weight: 800; line-height: 1.1; }
    .kpi-label { font-size: .7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-top: .45rem; }
    .kpi-delta {
      display: inline-flex; align-items: center; gap: .25rem;
      font-size: .72rem; font-weight: 600; margin-top: .3rem;
      padding: .15rem .45rem; border-radius: 20px;
    }
    .kpi-delta.up   { background: rgba(34,197,94,.12); color: #4ade80; }
    .kpi-delta.down { background: rgba(239,68,68,.12); color: #fca5a5; }

    .chart-row { display: grid; gap: 1.2rem; margin-bottom: 1.2rem; }
    .chart-row.col-2    { grid-template-columns: 1fr 1fr; }
    .chart-row.col-3    { grid-template-columns: 2fr 1fr; }
    .chart-row.col-full { grid-template-columns: 1fr; }
    @media(max-width:800px) { .chart-row.col-2, .chart-row.col-3 { grid-template-columns: 1fr; } }

    .chart-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; }
    .chart-head {
      padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
      background: var(--bg-card2); display: flex; align-items: center; gap: .5rem;
    }
    .chart-head h3 { font-family: var(--font-head); font-size: .92rem; font-weight: 700; flex: 1; }
    .chart-head .chart-meta { font-size: .72rem; color: var(--muted); }
    .chart-body { padding: 1.25rem; }
    .chart-body canvas { width: 100% !important; }

    .bar-list { display: flex; flex-direction: column; gap: .65rem; }
    .bar-item-head { display: flex; justify-content: space-between; font-size: .8rem; margin-bottom: .3rem; }
    .bar-item-name { font-weight: 600; color: var(--text); }
    .bar-item-val  { color: var(--muted); }
    .bar-track { height: 6px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }
    .bar-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--accent2), var(--accent)); transition: width .6s cubic-bezier(.4,0,.2,1); }

    .donut-legend { display: flex; flex-direction: column; gap: .5rem; margin-top: 1rem; }
    .legend-item  { display: flex; align-items: center; gap: .5rem; font-size: .8rem; }
    .legend-dot   { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    .compare-card {
      background: var(--bg-card); border: 1px solid var(--border);
      border-radius: var(--r); padding: 1.4rem; margin-bottom: 1.2rem;
      display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;
    }
    @media(max-width:600px) { .compare-card { grid-template-columns: 1fr; } }
    .compare-label   { font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: .4rem; }
    .compare-val     { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; }
    .compare-sub     { font-size: .78rem; color: var(--muted); margin-top: .2rem; }
    .compare-divider { width: 1px; background: var(--border); }

    #loading {
      display: none; position: fixed; inset: 0; z-index: 999;
      background: rgba(7,9,26,.7);
      align-items: center; justify-content: center;
    }
    #loading.on { display: flex; }
    .spinner {
      width: 44px; height: 44px; border-radius: 50%;
      border: 3px solid rgba(124,92,252,.2); border-top-color: #a78bfa;
      animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .section-title {
      font-family: var(--font-head); font-size: .72rem; text-transform: uppercase;
      letter-spacing: 2px; color: var(--muted);
      margin: 2rem 0 1rem; display: flex; align-items: center; gap: .6rem;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .reason-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
    .reason-table th {
      text-align: left; color: var(--muted); font-weight: 500;
      padding: .5rem .75rem; border-bottom: 1px solid var(--border);
    }
    .reason-table td { padding: .55rem .75rem; border-bottom: 1px solid rgba(255,255,255,.04); }
    .reason-table tr:last-child td { border-bottom: none; }
    .badge-by { padding: .15rem .45rem; border-radius: 20px; font-size: .65rem; font-weight: 600; background: rgba(239,68,68,.12); color: #fca5a5; }
    .badge-by.system { background: rgba(245,158,11,.1); color: #fcd34d; }

    footer {
      text-align: center; padding: 1.2rem; border-top: 1px solid var(--border);
      color: var(--muted); font-size: .75rem;
    }
    footer code { color: var(--accent); }
  </style>
</head>
<body>
<div class="page">
  <div id="loading"><div class="spinner"></div></div>

  <header>
    <a href="<?= base_url('/analytics') ?>" class="logo">
      <div class="logo-icon">📊</div>
      <div>
        <div class="logo-name">SIM Analytics</div>
        <div class="logo-sub">Dashboard Analitik Bisnis</div>
      </div>
    </a>
    <div class="header-nav">
      <a href="<?= base_url('/analytics') ?>"   class="btn-nav active"><i class="bi bi-bar-chart-fill"></i> Analitik</a>
      <a href="<?= base_url('/rekap-produk') ?>" class="btn-nav"><i class="bi bi-box-seam"></i> Rekap Produk</a>
      <a href="<?= base_url('/withdrawal') ?>"   class="btn-nav gold"><i class="bi bi-shield-lock-fill"></i> Withdrawal</a>
      <a href="<?= base_url('/import') ?>"       class="btn-nav"><i class="bi bi-cloud-arrow-up"></i> Import</a>
    </div>
  </header>

  <main>
    <div class="page-header">
      <div>
        <h1>Dashboard Analitik</h1>
        <p>Ringkasan performa bisnis berdasarkan data pesanan TikTok Shop</p>
      </div>
      <div class="range-picker">
        <button class="range-btn" data-range="7">7 Hari</button>
        <button class="range-btn active" data-range="30">30 Hari</button>
        <button class="range-btn" data-range="90">90 Hari</button>
        <button class="range-btn" data-range="365">1 Tahun</button>
        <button class="range-btn" data-range="all">Semua</button>
      </div>
    </div>

    <div class="kpi-grid" id="kpi-grid">
      <div class="kpi-card c-purple">
        <div class="kpi-icon">💰</div>
        <div class="kpi-val" id="kpi-revenue">—</div>
        <div class="kpi-label">Total Revenue</div>
        <div class="kpi-delta up" id="kpi-rev-delta" style="display:none"></div>
      </div>
      <div class="kpi-card c-teal">
        <div class="kpi-icon">📦</div>
        <div class="kpi-val" id="kpi-orders">—</div>
        <div class="kpi-label">Total Order Selesai</div>
      </div>
      <div class="kpi-card c-gold">
        <div class="kpi-icon">🛒</div>
        <div class="kpi-val" id="kpi-aov">—</div>
        <div class="kpi-label">Rata-rata Nilai Order</div>
      </div>
      <div class="kpi-card c-green">
        <div class="kpi-icon">📫</div>
        <div class="kpi-val" id="kpi-qty">—</div>
        <div class="kpi-label">Total Produk Terjual</div>
      </div>
      <div class="kpi-card c-red">
        <div class="kpi-icon">↩️</div>
        <div class="kpi-val" id="kpi-retur">—</div>
        <div class="kpi-label">Total Retur</div>
      </div>
      <div class="kpi-card c-pink">
        <div class="kpi-icon">❌</div>
        <div class="kpi-val" id="kpi-cancel">—</div>
        <div class="kpi-label">Pesanan Dibatalkan</div>
      </div>
      <div class="kpi-card c-gold">
        <div class="kpi-icon">⏳</div>
        <div class="kpi-val" id="kpi-pending">—</div>
        <div class="kpi-label">Dana Belum Ditarik</div>
      </div>
      <div class="kpi-card c-teal">
        <div class="kpi-icon">📋</div>
        <div class="kpi-val" id="kpi-pending-count">—</div>
        <div class="kpi-label">Order Pending Withdrawal</div>
      </div>
    </div>

    <div class="compare-card" id="compare-card">
      <div class="compare-item">
        <div class="compare-label">Revenue Bulan Ini</div>
        <div class="compare-val" id="rev-bulan-ini">—</div>
        <div class="compare-sub" id="rev-bulan-ini-sub"></div>
      </div>
      <div class="compare-divider"></div>
      <div class="compare-item">
        <div class="compare-label">Revenue Bulan Lalu</div>
        <div class="compare-val" id="rev-bulan-lalu">—</div>
        <div class="compare-sub" id="rev-bulan-lalu-sub"></div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-graph-up"></i> Tren Revenue & Order</div>
    <div class="chart-row col-full">
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-graph-up-arrow" style="color:#a78bfa"></i>
          <h3>Revenue Harian</h3>
          <div id="chart-rev-meta" class="chart-meta"></div>
          <div style="display:flex;gap:.4rem;margin-left:auto">
            <button class="range-btn" id="btn-harian"   onclick="setChartMode('harian')"   style="padding:.25rem .6rem;font-size:.7rem">Harian</button>
            <button class="range-btn active" id="btn-mingguan" onclick="setChartMode('mingguan')" style="padding:.25rem .6rem;font-size:.7rem">Mingguan</button>
          </div>
        </div>
        <div class="chart-body"><canvas id="chart-revenue" height="90"></canvas></div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-geo-alt"></i> Sebaran & Komposisi</div>
    <div class="chart-row col-3">
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-map" style="color:var(--teal)"></i>
          <h3>Top 10 Provinsi (Revenue)</h3>
        </div>
        <div class="chart-body">
          <div class="bar-list" id="provinsi-list"></div>
        </div>
      </div>
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-pie-chart" style="color:var(--pink)"></i>
          <h3>Status Pesanan</h3>
        </div>
        <div class="chart-body">
          <canvas id="chart-status" height="160"></canvas>
          <div class="donut-legend" id="status-legend"></div>
        </div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-credit-card"></i> Pembayaran & Diskon</div>
    <div class="chart-row col-2">
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-wallet2" style="color:var(--gold)"></i>
          <h3>Metode Pembayaran</h3>
        </div>
        <div class="chart-body"><canvas id="chart-payment" height="140"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-tags" style="color:var(--success)"></i>
          <h3>Breakdown Diskon</h3>
        </div>
        <div class="chart-body">
          <canvas id="chart-diskon" height="140"></canvas>
          <div id="diskon-detail" style="margin-top:.75rem;font-size:.78rem;color:var(--muted)"></div>
        </div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-x-circle"></i> Analisis Pembatalan</div>
    <div class="chart-row col-full">
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-x-octagon" style="color:var(--danger)"></i>
          <h3>Alasan Pembatalan Terbanyak</h3>
        </div>
        <div class="chart-body">
          <table class="reason-table">
            <thead><tr><th>Alasan</th><th>Dibatalkan Oleh</th><th style="text-align:right">Jumlah</th></tr></thead>
            <tbody id="cancel-tbody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <footer>SIM Analytics &nbsp;·&nbsp; CodeIgniter 4 &nbsp;·&nbsp; DB: <code>sim_orders</code></footer>
</div>

<script>
const fmt  = n => 'Rp ' + (parseFloat(n)||0).toLocaleString('id-ID', {minimumFractionDigits:0});
const fmtK = n => {
  const v = parseFloat(n) || 0;
  if (v >= 1_000_000_000) return 'Rp ' + (v/1e9).toFixed(2) + ' M';
  if (v >= 1_000_000)     return 'Rp ' + (v/1e6).toFixed(1) + ' Jt';
  if (v >= 1_000)         return 'Rp ' + (v/1e3).toFixed(0) + ' Rb';
  return 'Rp ' + v.toLocaleString('id-ID');
};
const fmtN = n => (parseInt(n)||0).toLocaleString('id-ID');
const esc  = s => String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

Chart.defaults.color = '#5a6a8a';
Chart.defaults.borderColor = 'rgba(100,149,255,.1)';
Chart.defaults.font.family = "'DM Sans', sans-serif";

let charts = {};
let currentRange = '30';
let chartMode    = 'mingguan';
let cachedData   = null;

const COLORS = ['#7c5cfc','#4f8ef7','#06b6d4','#22c55e','#f59e0b','#ec4899','#f97316','#a78bfa','#34d399','#fb923c'];

document.querySelectorAll('.range-btn[data-range]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.range-btn[data-range]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentRange = btn.dataset.range;
    loadData();
  });
});

function setChartMode(mode) {
  chartMode = mode;
  document.getElementById('btn-harian').classList.toggle('active', mode === 'harian');
  document.getElementById('btn-mingguan').classList.toggle('active', mode === 'mingguan');
  if (cachedData) renderRevenueChart(cachedData);
}

const loadingEl = document.getElementById('loading');
function setLoading(v) { loadingEl.classList.toggle('on', v); }
function destroyChart(id) { if (charts[id]) { charts[id].destroy(); delete charts[id]; } }

async function loadData() {
  setLoading(true);
  try {
    const resp = await fetch(`<?= base_url('/analytics/data') ?>?range=${currentRange}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await resp.json();
    if (!data.success) throw new Error(data.error || 'Gagal memuat data');
    cachedData = data;
    renderAll(data);
  } catch(e) { alert('Error: ' + e.message); }
  finally { setLoading(false); }
}

function renderAll(d) {
  renderKPI(d); renderCompare(d); renderRevenueChart(d);
  renderProvinsiList(d.top_provinsi || []);
  renderStatusChart(d.status_chart || []);
  renderPaymentChart(d.payment_chart || []);
  renderDiskonChart(d.diskon || {});
  renderCancelTable(d.cancel_reasons || []);
}

function renderKPI(d) {
  const k = d.kpi || {}, c = d.kpi_cancel || {};
  document.getElementById('kpi-revenue').textContent       = fmtK(k.total_revenue);
  document.getElementById('kpi-orders').textContent        = fmtN(k.total_order);
  document.getElementById('kpi-aov').textContent           = fmtK(k.avg_order_value);
  document.getElementById('kpi-qty').textContent           = fmtN(k.total_qty_terjual);
  document.getElementById('kpi-retur').textContent         = fmtN(k.total_retur);
  document.getElementById('kpi-cancel').textContent        = fmtN(c.total_cancel);
  document.getElementById('kpi-pending').textContent       = fmtK(k.dana_pending);
  document.getElementById('kpi-pending-count').textContent = fmtN(k.pending_withdrawal) + ' order';
  const ini = parseFloat(d.rev_bulan_ini || 0), lalu = parseFloat(d.rev_bulan_lalu || 0);
  if (lalu > 0) {
    const pct = ((ini - lalu) / lalu * 100).toFixed(1);
    const el  = document.getElementById('kpi-rev-delta');
    el.style.display = '';
    el.className  = 'kpi-delta ' + (pct >= 0 ? 'up' : 'down');
    el.textContent = (pct >= 0 ? '▲ ' : '▼ ') + Math.abs(pct) + '% vs bulan lalu';
  }
}

function renderCompare(d) {
  const ini = parseFloat(d.rev_bulan_ini || 0), lalu = parseFloat(d.rev_bulan_lalu || 0);
  document.getElementById('rev-bulan-ini').textContent  = fmtK(ini);
  document.getElementById('rev-bulan-lalu').textContent = fmtK(lalu);
  const now = new Date();
  document.getElementById('rev-bulan-ini-sub').textContent  = now.toLocaleDateString('id-ID', {month:'long', year:'numeric'});
  document.getElementById('rev-bulan-lalu-sub').textContent = new Date(now.getFullYear(), now.getMonth()-1).toLocaleDateString('id-ID', {month:'long', year:'numeric'});
  if (ini > 0 && lalu > 0) {
    const pct = ((ini - lalu) / lalu * 100).toFixed(1);
    document.getElementById('rev-bulan-ini-sub').innerHTML +=
      ` <span style="color:${pct >= 0 ? '#4ade80' : '#fca5a5'};font-weight:700">${pct >= 0 ? '▲' : '▼'} ${Math.abs(pct)}%</span>`;
  }
}

function renderRevenueChart(d) {
  destroyChart('revenue');
  const rows   = chartMode === 'harian' ? (d.revenue_chart || []) : (d.weekly_chart || []);
  const labels = rows.map(r => new Date(r.tgl || r.minggu_mulai).toLocaleDateString('id-ID', {day:'2-digit', month:'short'}));
  const revData   = rows.map(r => parseFloat(r.revenue || 0));
  const orderData = rows.map(r => parseInt(r.jml_order || 0));
  document.getElementById('chart-rev-meta').textContent = rows.length ? `${labels[0]} – ${labels[labels.length-1]}` : '';
  const ctx  = document.getElementById('chart-revenue').getContext('2d');
  const grad = ctx.createLinearGradient(0, 0, 0, 300);
  grad.addColorStop(0, 'rgba(124,92,252,.35)');
  grad.addColorStop(1, 'rgba(124,92,252,.0)');
  charts['revenue'] = new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets: [
      { label: 'Revenue', data: revData, backgroundColor: grad, borderColor: '#7c5cfc', borderWidth: 2, borderRadius: 5, yAxisID: 'y' },
      { label: 'Jumlah Order', data: orderData, type: 'line', borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,.15)', borderWidth: 2, pointRadius: 3, tension: .4, fill: true, yAxisID: 'y1' }
    ]},
    options: {
      responsive: true, interaction: { mode: 'index', intersect: false },
      plugins: { legend: { labels: { boxWidth: 12, padding: 16 } }, tooltip: { callbacks: { label: c => c.datasetIndex === 0 ? ' Revenue: ' + fmtK(c.raw) : ' Order: ' + fmtN(c.raw) } } },
      scales: { y: { position:'left', ticks:{ callback: v => fmtK(v) }, grid:{ color:'rgba(255,255,255,.04)' } }, y1: { position:'right', ticks:{ stepSize:1 }, grid:{ display:false } }, x: { grid:{ display:false } } }
    }
  });
}

function renderProvinsiList(rows) {
  const el = document.getElementById('provinsi-list');
  if (!rows.length) { el.innerHTML = '<p style="color:var(--muted);text-align:center;font-size:.82rem">Tidak ada data</p>'; return; }
  const max = Math.max(...rows.map(r => parseFloat(r.revenue || 0)));
  el.innerHTML = rows.map(r => {
    const pct = max > 0 ? (parseFloat(r.revenue) / max * 100).toFixed(1) : 0;
    return `<div class="bar-item"><div class="bar-item-head"><span class="bar-item-name">${esc(r.province)}</span><span class="bar-item-val">${fmtK(r.revenue)} · ${fmtN(r.jml_order)} order</span></div><div class="bar-track"><div class="bar-fill" style="width:${pct}%"></div></div></div>`;
  }).join('');
}

function renderStatusChart(rows) {
  destroyChart('status');
  const labels = rows.map(r => r.status_pesanan);
  const vals   = rows.map(r => parseInt(r.jml));
  const colors = { 'Selesai':'#22c55e', 'Dibatalkan':'#ef4444', 'In Transit':'#06b6d4', 'default':'#7c5cfc' };
  const bg     = labels.map(l => colors[l] || colors.default);
  const ctx = document.getElementById('chart-status').getContext('2d');
  charts['status'] = new Chart(ctx, {
    type: 'doughnut',
    data: { labels, datasets: [{ data: vals, backgroundColor: bg, borderWidth: 0, hoverOffset: 6 }] },
    options: { cutout:'65%', plugins: { legend:{ display:false }, tooltip:{ callbacks:{ label: c => ` ${c.label}: ${fmtN(c.raw)}` } } } }
  });
  const total = vals.reduce((a,b) => a+b, 0);
  document.getElementById('status-legend').innerHTML = rows.map((r,i) =>
    `<div class="legend-item"><div class="legend-dot" style="background:${bg[i]}"></div><span style="flex:1">${esc(r.status_pesanan)}</span><span style="color:var(--muted)">${fmtN(r.jml)} <small>(${total > 0 ? (r.jml/total*100).toFixed(1) : 0}%)</small></span></div>`
  ).join('');
}

function renderPaymentChart(rows) {
  destroyChart('payment');
  const labels = rows.map(r => r.payment_method || 'Lainnya');
  const vals   = rows.map(r => parseInt(r.jml));
  const ctx    = document.getElementById('chart-payment').getContext('2d');
  charts['payment'] = new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets: [{ label:'Jumlah Order', data:vals, backgroundColor: COLORS.slice(0, labels.length), borderRadius:6, borderWidth:0 }] },
    options: { indexAxis:'y', plugins:{ legend:{ display:false } }, scales: { x:{ grid:{ color:'rgba(255,255,255,.04)' }, ticks:{ callback: v => fmtN(v) } }, y:{ grid:{ display:false } } } }
  });
}

function renderDiskonChart(d) {
  destroyChart('diskon');
  const vals   = [parseFloat(d.total_diskon_sku_platform||0), parseFloat(d.total_diskon_seller||0), parseFloat(d.total_diskon_platform||0)];
  const labels = ['Diskon Platform (SKU)', 'Diskon Seller', 'Diskon Payment Platform'];
  const colors = ['#7c5cfc', '#f59e0b', '#06b6d4'];
  const ctx = document.getElementById('chart-diskon').getContext('2d');
  charts['diskon'] = new Chart(ctx, {
    type: 'pie',
    data: { labels, datasets: [{ data:vals, backgroundColor:colors, borderWidth:0, hoverOffset:8 }] },
    options: { plugins: { legend:{ labels:{ boxWidth:10, padding:14, font:{ size:11 } } }, tooltip:{ callbacks:{ label: c => ` ${c.label}: ${fmtK(c.raw)}` } } } }
  });
  const total = vals.reduce((a,b) => a+b, 0);
  document.getElementById('diskon-detail').innerHTML =
    `Total diskon diberikan: <strong style="color:#a78bfa">${fmtK(total)}</strong> dari total revenue <strong style="color:#4ade80">${fmtK(d.total_revenue)}</strong> <em>(${total > 0 && d.total_revenue > 0 ? (total/d.total_revenue*100).toFixed(1) : 0}% dari revenue)</em>`;
}

function renderCancelTable(rows) {
  const tbody = document.getElementById('cancel-tbody');
  if (!rows.length) { tbody.innerHTML = `<tr><td colspan="3" style="text-align:center;color:var(--muted);padding:2rem">Tidak ada pembatalan pada periode ini</td></tr>`; return; }
  tbody.innerHTML = rows.map(r =>
    `<tr><td>${esc(r.cancel_reason)}</td><td><span class="badge-by ${r.cancel_by==='System'?'system':''}">${esc(r.cancel_by||'—')}</span></td><td style="text-align:right;font-weight:700">${fmtN(r.jml)}</td></tr>`
  ).join('');
}

loadData();
</script>
</body>
</html>