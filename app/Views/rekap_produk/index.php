<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIM — Rekap Produk</title>
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
        radial-gradient(ellipse 50% 35% at 90% 10%, rgba(6,182,212,.05) 0%, transparent 60%),
        radial-gradient(ellipse 40% 45% at 5% 90%, rgba(34,197,94,.04) 0%, transparent 60%);
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
      background: linear-gradient(135deg, var(--teal), var(--success));
      border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      font-size: 19px; box-shadow: 0 4px 16px rgba(6,182,212,.3);
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
    .btn-nav.active { background: rgba(6,182,212,.15); color: #67e8f9; border-color: rgba(6,182,212,.3); }
    .btn-nav.gold { color: #f59e0b; border-color: rgba(245,158,11,.4); background: rgba(245,158,11,.08); }
    .btn-nav.gold:hover { background: rgba(245,158,11,.18); }

    /* ===== MAIN ===== */
    main { flex: 1; padding: 2rem 1.5rem; max-width: 1200px; margin: 0 auto; width: 100%; }

    .page-header {
      display: flex; align-items: flex-end; justify-content: space-between;
      margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .page-header h1 {
      font-family: var(--font-head); font-size: 1.9rem; font-weight: 800; letter-spacing: -.5px;
      background: linear-gradient(135deg, #fff 20%, #67e8f9 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .page-header p { color: var(--muted); font-size: .88rem; margin-top: .3rem; }

    .controls { display: flex; gap: .75rem; flex-wrap: wrap; align-items: center; margin-bottom: 1.5rem; }
    .control-group { display: flex; align-items: center; gap: .4rem; }
    .control-label { font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
    .range-btn {
      padding: .38rem .8rem; border: 1px solid var(--border);
      border-radius: 20px; font-size: .75rem; color: var(--muted);
      background: transparent; cursor: pointer; font-family: var(--font-body); transition: all .2s;
    }
    .range-btn.active { background: rgba(6,182,212,.15); color: #67e8f9; border-color: rgba(6,182,212,.3); }
    .range-btn:hover  { background: rgba(255,255,255,.05); color: var(--text); }

    select.ctrl-select {
      padding: .38rem .8rem; border: 1px solid var(--border);
      border-radius: 8px; font-size: .8rem; color: var(--text);
      background: var(--bg-card); font-family: var(--font-body); cursor: pointer;
      outline: none; appearance: none; padding-right: 1.8rem;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%235a6a8a'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right .6rem center;
    }

    .summary-grid {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;
    }
    @media(max-width:900px) { .summary-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:500px) { .summary-grid { grid-template-columns: 1fr; } }

    .sum-card {
      background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r);
      padding: 1.25rem 1.2rem; position: relative; overflow: hidden; transition: all .25s;
    }
    .sum-card:hover { border-color: rgba(79,142,247,.28); transform: translateY(-2px); }
    .sum-card::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(circle at top right, var(--glow, rgba(6,182,212,.06)), transparent 70%);
    }
    .sum-card.c-teal   { --glow: rgba(6,182,212,.07); }
    .sum-card.c-green  { --glow: rgba(34,197,94,.07); }
    .sum-card.c-purple { --glow: rgba(124,92,252,.08); }
    .sum-card.c-gold   { --glow: rgba(245,158,11,.07); }
    .sc-icon  { font-size: 1.35rem; margin-bottom: .6rem; }
    .sc-val   { font-family: var(--font-head); font-size: 1.5rem; font-weight: 800; line-height: 1.1; }
    .sc-label { font-size: .7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-top: .4rem; }

    .chart-row { display: grid; gap: 1.2rem; margin-bottom: 1.5rem; }
    .chart-row.col-2 { grid-template-columns: 1fr 1fr; }
    @media(max-width:800px) { .chart-row.col-2 { grid-template-columns: 1fr; } }

    .chart-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; }
    .chart-head {
      padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
      background: var(--bg-card2); display: flex; align-items: center; gap: .5rem;
    }
    .chart-head h3 { font-family: var(--font-head); font-size: .92rem; font-weight: 700; flex: 1; }
    .chart-body { padding: 1.25rem; }

    .section-title {
      font-family: var(--font-head); font-size: .72rem; text-transform: uppercase;
      letter-spacing: 2px; color: var(--muted);
      margin: 2rem 0 1rem; display: flex; align-items: center; gap: .6rem;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .table-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; }
    .table-card-head {
      padding: .9rem 1.25rem; border-bottom: 1px solid var(--border);
      background: var(--bg-card2); display: flex; align-items: center; gap: .5rem;
    }
    .table-card-head span { font-size: .78rem; color: var(--muted); margin-left: auto; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .82rem; }
    thead th {
      background: rgba(255,255,255,.03); padding: .65rem 1rem;
      text-align: left; color: var(--muted); font-weight: 500;
      white-space: nowrap; border-bottom: 1px solid var(--border);
      cursor: pointer; user-select: none;
    }
    thead th:hover { color: var(--text); }
    thead th.sort-active { color: #67e8f9; }
    thead th.sort-active::after { content: ' ↓'; font-size: .65rem; }
    thead th.sort-active.asc::after { content: ' ↑'; }
    thead th.text-right { text-align: right; }
    tbody td {
      padding: .65rem 1rem; border-bottom: 1px solid rgba(255,255,255,.04);
      white-space: nowrap; vertical-align: middle;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: rgba(79,142,247,.03); }

    .rank-badge {
      display: inline-flex; align-items: center; justify-content: center;
      width: 24px; height: 24px; border-radius: 8px; font-size: .72rem;
      font-family: var(--font-head); font-weight: 800;
    }
    .rank-1 { background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; }
    .rank-2 { background: rgba(148,163,184,.2); color: #94a3b8; }
    .rank-3 { background: rgba(180,116,73,.2); color: #c08060; }
    .rank-n { background: rgba(255,255,255,.06); color: var(--muted); }

    .produk-name   { font-weight: 600; color: var(--text); white-space: normal; max-width: 280px; line-height: 1.3; }
    .produk-varian { font-size: .72rem; color: var(--muted); margin-top: .15rem; }
    .num-green  { font-weight: 700; color: #4ade80; }
    .num-teal   { font-weight: 700; color: #67e8f9; }
    .num-gold   { font-weight: 700; color: #fcd34d; }
    .num-red    { font-weight: 600; color: #fca5a5; }
    .num-muted  { color: var(--muted); }
    .text-right { text-align: right; }

    .retur-bar   { display: flex; align-items: center; gap: .5rem; }
    .retur-track { width: 48px; height: 5px; background: rgba(255,255,255,.07); border-radius: 99px; flex-shrink: 0; }
    .retur-fill  { height: 100%; border-radius: 99px; background: #ef4444; }

    #loading {
      display: none; position: fixed; inset: 0; z-index: 999;
      background: rgba(7,9,26,.7);
      align-items: center; justify-content: center;
    }
    #loading.on { display: flex; }
    .spinner {
      width: 44px; height: 44px; border-radius: 50%;
      border: 3px solid rgba(6,182,212,.2); border-top-color: #67e8f9;
      animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

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
    <a href="<?= base_url('/rekap-produk') ?>" class="logo">
      <div class="logo-icon">📦</div>
      <div>
        <div class="logo-name">SIM Rekap Produk</div>
        <div class="logo-sub">Performa Penjualan per Produk</div>
      </div>
    </a>
    <div class="header-nav">
      <a href="<?= base_url('/analytics') ?>"    class="btn-nav"><i class="bi bi-bar-chart-fill"></i> Analitik</a>
      <a href="<?= base_url('/rekap-produk') ?>"  class="btn-nav active"><i class="bi bi-box-seam"></i> Rekap Produk</a>
      <a href="<?= base_url('/withdrawal') ?>"    class="btn-nav gold"><i class="bi bi-shield-lock-fill"></i> Withdrawal</a>
      <a href="<?= base_url('/import') ?>"        class="btn-nav"><i class="bi bi-cloud-arrow-up"></i> Import</a>
    </div>
  </header>

  <main>
    <div class="page-header">
      <div>
        <h1>Rekap Produk</h1>
        <p>Performa penjualan, retur, dan revenue per kombinasi produk</p>
      </div>
    </div>

    <div class="controls">
      <div class="control-group">
        <span class="control-label">Periode:</span>
        <button class="range-btn" data-range="30">30 Hari</button>
        <button class="range-btn" data-range="90">90 Hari</button>
        <button class="range-btn" data-range="365">1 Tahun</button>
        <button class="range-btn active" data-range="all">Semua</button>
      </div>
      <div class="control-group" style="margin-left:auto">
        <span class="control-label">Urutkan:</span>
        <select class="ctrl-select" id="sort-select">
          <option value="qty">Qty Terjual</option>
          <option value="revenue">Revenue</option>
          <option value="orders">Jumlah Order</option>
          <option value="return">Retur</option>
        </select>
        <select class="ctrl-select" id="order-select">
          <option value="desc">Tertinggi dulu</option>
          <option value="asc">Terendah dulu</option>
        </select>
      </div>
    </div>

    <div class="summary-grid">
      <div class="sum-card c-teal"><div class="sc-icon">📦</div><div class="sc-val" id="sum-qty">—</div><div class="sc-label">Total Qty Terjual</div></div>
      <div class="sum-card c-green"><div class="sc-icon">💵</div><div class="sc-val" id="sum-revenue">—</div><div class="sc-label">Total Revenue</div></div>
      <div class="sum-card c-purple"><div class="sc-icon">🛒</div><div class="sc-val" id="sum-orders">—</div><div class="sc-label">Total Order</div></div>
      <div class="sum-card c-gold"><div class="sc-icon">🔢</div><div class="sc-val" id="sum-varian">—</div><div class="sc-label">Varian Produk</div></div>
    </div>

    <div class="section-title"><i class="bi bi-graph-up"></i> Tren & Distribusi</div>
    <div class="chart-row col-2">
      <div class="chart-card">
        <div class="chart-head"><i class="bi bi-bar-chart" style="color:var(--teal)"></i><h3>Top 5 Produk — Qty Terjual per Minggu</h3></div>
        <div class="chart-body"><canvas id="chart-tren" height="160"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-head"><i class="bi bi-pie-chart" style="color:var(--gold)"></i><h3>Distribusi Revenue per Produk</h3></div>
        <div class="chart-body"><canvas id="chart-dist" height="160"></canvas></div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-arrow-return-left"></i> Analisis Retur</div>
    <div class="chart-row" style="grid-template-columns:1fr">
      <div class="chart-card">
        <div class="chart-head">
          <i class="bi bi-exclamation-triangle" style="color:var(--danger)"></i>
          <h3>Produk dengan Retur Tertinggi</h3>
          <span style="font-size:.72rem;color:var(--muted);margin-left:auto" id="retur-meta"></span>
        </div>
        <div class="chart-body"><canvas id="chart-retur" height="80"></canvas></div>
      </div>
    </div>

    <div class="section-title"><i class="bi bi-table"></i> Tabel Detail Produk</div>
    <div class="table-card">
      <div class="table-card-head">
        <i class="bi bi-box-seam" style="color:var(--teal)"></i>
        <strong style="font-size:.85rem;font-family:var(--font-head)">Semua Kombinasi Produk</strong>
        <span id="table-count-label">— varian</span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width:40px;text-align:center">#</th>
              <th>Produk / Varian</th>
              <th class="text-right sort-col" data-sort="qty">Qty Terjual</th>
              <th class="text-right sort-col" data-sort="revenue">Revenue</th>
              <th class="text-right sort-col" data-sort="orders">Jml Order</th>
              <th class="text-right">Harga Rata²</th>
              <th class="text-right">Rev/Unit</th>
              <th class="text-right sort-col" data-sort="return">Retur</th>
              <th class="text-right">Diskon</th>
            </tr>
          </thead>
          <tbody id="produk-tbody"></tbody>
        </table>
      </div>
    </div>
  </main>

  <footer>SIM Rekap Produk &nbsp;·&nbsp; CodeIgniter 4 &nbsp;·&nbsp; DB: <code>sim_orders</code></footer>
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
let currentRange = 'all';
let currentSort  = 'qty';
let currentOrder = 'desc';

const COLORS = ['#06b6d4','#22c55e','#7c5cfc','#f59e0b','#ec4899','#4f8ef7','#f97316','#a78bfa','#34d399','#fb923c'];

document.querySelectorAll('.range-btn[data-range]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.range-btn[data-range]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentRange = btn.dataset.range;
    loadData();
  });
});
document.getElementById('sort-select').addEventListener('change', e => { currentSort = e.target.value; loadData(); });
document.getElementById('order-select').addEventListener('change', e => { currentOrder = e.target.value; loadData(); });
document.querySelectorAll('.sort-col').forEach(th => {
  th.addEventListener('click', () => {
    const s = th.dataset.sort;
    if (currentSort === s) currentOrder = currentOrder === 'desc' ? 'asc' : 'desc';
    else { currentSort = s; currentOrder = 'desc'; }
    document.getElementById('sort-select').value  = currentSort;
    document.getElementById('order-select').value = currentOrder;
    loadData();
  });
});

const loadingEl = document.getElementById('loading');
function setLoading(v) { loadingEl.classList.toggle('on', v); }
function destroyChart(id) { if (charts[id]) { charts[id].destroy(); delete charts[id]; } }

async function loadData() {
  setLoading(true);
  try {
    const resp = await fetch(`<?= base_url('/rekap-produk/data') ?>?range=${currentRange}&sort=${currentSort}&order=${currentOrder}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await resp.json();
    if (!data.success) throw new Error(data.error || 'Gagal memuat data');
    renderAll(data);
  } catch(e) { alert('Error: ' + e.message); }
  finally { setLoading(false); }
}

function renderAll(d) {
  renderSummary(d.summary || {});
  renderTrenChart(d.tren_data || [], d.produk || []);
  renderDistChart(d.produk || []);
  renderReturChart(d.top_retur || []);
  renderTable(d.produk || []);
}

function renderSummary(s) {
  document.getElementById('sum-qty').textContent     = fmtN(s.total_qty);
  document.getElementById('sum-revenue').textContent = fmtK(s.total_revenue);
  document.getElementById('sum-orders').textContent  = fmtN(s.total_orders);
  document.getElementById('sum-varian').textContent  = fmtN(s.jumlah_varian) + ' varian';
}

function renderTrenChart(trenData, produk) {
  destroyChart('tren');
  if (!trenData.length) return;
  const weeks = [...new Set(trenData.map(r => r.minggu_mulai))].sort();
  const labels = weeks.map(w => new Date(w).toLocaleDateString('id-ID', {day:'2-digit', month:'short'}));
  const produkList = [...new Set(trenData.map(r => r.kombinasi_produk))];
  const datasets = produkList.map((p, i) => ({
    label: p.length > 28 ? p.substring(0, 28) + '…' : p,
    data: weeks.map(w => { const r = trenData.find(r => r.kombinasi_produk === p && r.minggu_mulai === w); return r ? parseInt(r.qty) : 0; }),
    backgroundColor: COLORS[i % COLORS.length], borderRadius: 4, borderWidth: 0,
  }));
  const ctx = document.getElementById('chart-tren').getContext('2d');
  charts['tren'] = new Chart(ctx, {
    type: 'bar', data: { labels, datasets },
    options: { responsive: true, plugins: { legend:{ labels:{ boxWidth:10, padding:12, font:{ size:11 } } }, tooltip:{ callbacks:{ label: c => ` ${c.dataset.label}: ${fmtN(c.raw)} pcs` } } }, scales: { x:{ stacked:true, grid:{ display:false } }, y:{ stacked:true, grid:{ color:'rgba(255,255,255,.04)' } } } }
  });
}

function renderDistChart(produk) {
  destroyChart('dist');
  if (!produk.length) return;
  const top8    = produk.slice(0, 8);
  const restRev = produk.slice(8).reduce((a, r) => a + parseFloat(r.total_subtotal||0), 0);
  const labels  = top8.map(r => { const n = r.kombinasi_produk || '—'; return n.length > 22 ? n.substring(0, 22) + '…' : n; });
  const vals    = top8.map(r => parseFloat(r.total_subtotal || 0));
  if (restRev > 0) { labels.push('Lainnya'); vals.push(restRev); }
  const ctx = document.getElementById('chart-dist').getContext('2d');
  charts['dist'] = new Chart(ctx, {
    type: 'doughnut',
    data: { labels, datasets: [{ data: vals, backgroundColor: COLORS.slice(0, labels.length), borderWidth: 0, hoverOffset: 8 }] },
    options: { cutout:'55%', plugins: { legend:{ position:'right', labels:{ boxWidth:10, padding:10, font:{ size:10 } } }, tooltip:{ callbacks:{ label: c => ` ${c.label}: ${fmtK(c.raw)}` } } } }
  });
}

function renderReturChart(rows) {
  destroyChart('retur');
  if (!rows.length) { document.getElementById('retur-meta').textContent = 'Tidak ada data retur'; return; }
  const labels    = rows.map(r => { const n = r.kombinasi_produk || '—'; return n.length > 30 ? n.substring(0, 30) + '…' : n; });
  const qtyData   = rows.map(r => parseInt(r.total_qty));
  const returData = rows.map(r => parseInt(r.total_retur));
  document.getElementById('retur-meta').textContent = 'Persen retur = retur ÷ qty terjual';
  const ctx = document.getElementById('chart-retur').getContext('2d');
  charts['retur'] = new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets: [
      { label:'Qty Terjual', data:qtyData, backgroundColor:'rgba(6,182,212,.35)', borderColor:'#06b6d4', borderWidth:1, borderRadius:5 },
      { label:'Qty Retur',   data:returData, backgroundColor:'rgba(239,68,68,.5)', borderColor:'#ef4444', borderWidth:1, borderRadius:5 }
    ]},
    options: { responsive: true, plugins: { tooltip:{ callbacks:{ afterLabel: c => { if (c.datasetIndex === 1) { const qty = qtyData[c.dataIndex], ret = returData[c.dataIndex]; return ` → ${qty > 0 ? (ret/qty*100).toFixed(1) : 0}% retur rate`; } } } } }, scales: { x:{ grid:{ display:false } }, y:{ grid:{ color:'rgba(255,255,255,.04)' }, ticks:{ stepSize:1 } } } }
  });
}

function renderTable(rows) {
  const tbody    = document.getElementById('produk-tbody');
  document.getElementById('table-count-label').textContent = rows.length.toLocaleString('id-ID') + ' varian';
  document.querySelectorAll('.sort-col').forEach(th => {
    th.classList.remove('sort-active', 'asc');
    if (th.dataset.sort === currentSort) { th.classList.add('sort-active'); if (currentOrder === 'asc') th.classList.add('asc'); }
  });
  if (!rows.length) { tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:var(--muted);padding:3rem">Tidak ada data produk pada periode ini</td></tr>`; return; }
  tbody.innerHTML = rows.map((r, i) => {
    const rank  = i + 1;
    const rc    = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : 'rank-n';
    const pctR  = r.total_qty > 0 ? (r.total_retur / r.total_qty * 100).toFixed(1) : 0;
    const diskon = (parseFloat(r.total_diskon_platform||0) + parseFloat(r.total_diskon_seller||0));
    return `<tr>
      <td style="text-align:center"><span class="rank-badge ${rc}">${rank}</span></td>
      <td><div class="produk-name">${esc(r.kombinasi_produk || '—')}</div><div class="produk-varian">${esc(r.variasi_raw || '')}</div></td>
      <td class="text-right"><span class="num-teal">${fmtN(r.total_qty)}</span></td>
      <td class="text-right"><span class="num-green">${fmtK(r.total_subtotal)}</span></td>
      <td class="text-right"><span class="num-muted">${fmtN(r.total_orders)}</span></td>
      <td class="text-right"><span class="num-muted">${fmt(r.avg_harga_satuan)}</span></td>
      <td class="text-right"><span class="num-gold">${fmt(r.avg_revenue_per_unit)}</span></td>
      <td class="text-right"><div class="retur-bar" style="justify-content:flex-end"><span class="${parseInt(r.total_retur) > 0 ? 'num-red' : 'num-muted'}">${fmtN(r.total_retur)}</span><div class="retur-track"><div class="retur-fill" style="width:${Math.min(pctR, 100)}%"></div></div><span style="font-size:.7rem;color:var(--muted);width:32px">${pctR}%</span></div></td>
      <td class="text-right"><span class="num-muted">${fmtK(diskon)}</span></td>
    </tr>`;
  }).join('');
}

loadData();
</script>
</body>
</html>