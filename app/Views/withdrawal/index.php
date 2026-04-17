<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    /* ===== VARIABLES & STAT CARDS ===== */
    .stat-card {
      background: var(--bg-side); border: 1px solid var(--border);
      border-radius: 14px; padding: 1.5rem 1.4rem;
      position: relative; overflow: hidden; transition: all .25s;
    }
    .stat-card:hover { border-color: rgba(79,142,247,.28); transform: translateY(-2px); }
    .stat-card::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(circle at top right, var(--glow, rgba(79,142,247,.06)), transparent 65%);
      pointer-events: none;
    }
    .stat-card.card-gold   { --glow: rgba(245,158,11,.08); }
    .stat-card.card-green  { --glow: rgba(34,197,94,.07); }
    .stat-card.card-purple { --glow: rgba(124,92,252,.07); }
    .stat-card.card-blue   { --glow: rgba(79,142,247,.07); }

    .sc-icon { font-size: 1.5rem; margin-bottom: .75rem; }
    .sc-val  { font-size: 1.55rem; font-weight: 800; line-height: 1.1; color: #fff; }
    .sc-subval { font-size: .9rem; font-weight: 600; color: var(--text-muted); margin-top: .1rem; }
    .sc-label { font-size: .72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-top: .5rem; }

    /* ===== ACTION BAR ===== */
    .action-bar {
      display: flex; align-items: center; gap: 1rem;
      margin-bottom: 1.25rem; flex-wrap: wrap; margin-top: 2rem;
    }
    .action-bar h2 { font-size: 1.1rem; font-weight: 700; flex: 1; margin: 0; }
    .selected-info { font-size: .82rem; color: #f59e0b; display: none; align-items: center; gap: .4rem; }
    .selected-info.visible { display: flex; }

    /* ===== TABLE CARDS ===== */
    .table-card {
      background: var(--bg-side); border: 1px solid var(--border);
      border-radius: 14px; overflow: hidden; margin-bottom: 2rem;
    }
    .table-card-head {
      padding: 1rem 1.4rem; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: .6rem;
    }

    /* Tabs */
    .tabs { display: flex; gap: .15rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
    .tab-btn {
      padding: .65rem 1.3rem; background: none; border: none;
      font-size: .88rem; font-weight: 500;
      color: var(--text-muted); cursor: pointer; position: relative;
      transition: color .2s; border-bottom: 2px solid transparent; margin-bottom: -1px;
    }
    .tab-btn.active { color: #f59e0b; border-bottom-color: #f59e0b; }
    .tab-btn .tab-count {
      display: inline-flex; align-items: center; justify-content: center;
      width: 20px; height: 20px; border-radius: 50%; font-size: .65rem; font-weight: 700;
      margin-left: .4rem; background: rgba(245,158,11,.15); color: #f59e0b;
    }

    /* Modal & Overlay */
    .modal-overlay {
      display: none; position: fixed; inset: 0; z-index: 500;
      background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
      align-items: center; justify-content: center;
    }
    .modal-overlay.visible { display: flex; }
    .modal-box {
      background: #0c1230; border: 1px solid var(--border);
      border-radius: 18px; padding: 2rem; max-width: 420px; width: 90%;
    }

    #toast {
      position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;
      padding: 1rem 1.5rem; border-radius: 12px;
      transform: translateY(120px); opacity: 0;
      transition: all .35s; max-width: 360px; pointer-events: none;
    }
    #toast.show { transform: translateY(0); opacity: 1; pointer-events: all; }
    #toast.success { background: rgba(34,197,94,.15); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }
    #toast.error   { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.3); color: #fca5a5; }

    .amount { font-weight: 700; color: #4ade80; }
    .order-code { font-size: .73rem; color: #93c5fd; font-family: monospace; }
    .platform-badge {
      display: inline-flex; align-items: center; gap: .3rem;
      padding: .18rem .55rem; border-radius: 20px; font-size: .68rem; font-weight: 600;
      background: rgba(79,142,247,.12); color: #4f8ef7;
    }
    #loading-overlay {
      display: none; position: fixed; inset: 0; z-index: 999;
      background: rgba(7,9,26,.7); backdrop-filter: blur(4px);
      align-items: center; justify-content: center; flex-direction: column; gap: 1rem;
    }
    #loading-overlay.visible { display: flex; }
    .spinner {
      width: 48px; height: 48px; border-radius: 50%;
      border: 3px solid rgba(245,158,11,.2);
      border-top-color: #f59e0b;
      animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<!-- LOADING OVERLAY -->
<div id="loading-overlay">
  <div class="spinner"></div>
  <div class="loading-text" style="color:#f59e0b;font-weight:600">Memproses pencairan…</div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="confirm-modal">
  <div class="modal-box">
    <div class="modal-icon" style="font-size:2.5rem;margin-bottom:1rem">💰</div>
    <div class="modal-title" id="modal-title" style="font-size:1.15rem;font-weight:800;margin-bottom:.5rem">Konfirmasi Pencairan Dana</div>
    <div class="modal-desc" id="modal-desc" style="color:var(--text-muted);font-size:.88rem;line-height:1.65;margin-bottom:1.5rem">Apakah kamu yakin ingin mencairkan dana dari pesanan yang dipilih?</div>
    <div class="modal-amount" id="modal-amount" style="font-size:1.35rem;font-weight:800;color:#f59e0b;margin: .5rem 0 1rem;"></div>
    <div class="modal-actions" style="display:flex;gap:.75rem">
      <button class="btn btn-outline-secondary flex-grow-1" id="modal-cancel">Batal</button>
      <button class="btn btn-warning flex-grow-2" id="modal-confirm" style="font-weight:700">
        <i class="bi bi-cash-stack"></i> Ya, Cairkan Sekarang
      </button>
    </div>
  </div>
</div>

<div class="page-header">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard Pencairan Dana</h1>
            <p class="text-muted small">Kelola dan mencairkan dana dari pesanan yang sudah berstatus <strong>Selesai</strong>.</p>
        </div>
        <div class="header-actions">
            <span class="badge bg-warning text-dark"><i class="bi bi-shield-lock-fill"></i> CEO ACCESS</span>
        </div>
    </div>
</div>

<!-- SUMMARY STATS -->
<div class="row g-3 mb-4" id="stats-grid">
    <div class="col-md-3">
        <div class="stat-card card-gold">
            <div class="sc-icon">⏳</div>
            <div class="sc-val" id="stat-belum-val">—</div>
            <div class="sc-subval" id="stat-belum-count">— pesanan</div>
            <div class="sc-label">Belum Dicairkan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-green">
            <div class="sc-icon">✅</div>
            <div class="sc-val" id="stat-sudah-val">—</div>
            <div class="sc-subval" id="stat-sudah-count">— pesanan</div>
            <div class="sc-label">Sudah Dicairkan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-purple">
            <div class="sc-icon">📦</div>
            <div class="sc-val" id="stat-total-order">—</div>
            <div class="sc-subval">order selesai</div>
            <div class="sc-label">Total Pesanan Selesai</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card card-blue">
            <div class="sc-icon">💵</div>
            <div class="sc-val" id="stat-total-rev">—</div>
            <div class="sc-subval">kumulatif</div>
            <div class="sc-label">Total Pendapatan</div>
        </div>
    </div>
</div>

<!-- TABS -->
<div class="tabs">
    <button class="tab-btn active" data-tab="pending" onclick="switchTab('pending')">
        Belum Dicairkan <span class="tab-count" id="tab-count-pending">0</span>
    </button>
    <button class="tab-btn" data-tab="history" onclick="switchTab('history')">
        Riwayat Terakhir
    </button>
</div>

<!-- TAB: PENDING -->
<div id="tab-pending">
    <div class="action-bar">
        <h2><i class="bi bi-hourglass-split" style="color:#f59e0b"></i> Antrian Pencairan</h2>
        <span class="selected-info" id="selected-info">
            <i class="bi bi-check-circle-fill"></i>
            <span id="selected-count">0</span> dipilih ·
            <strong id="selected-amount">Rp 0</strong>
        </span>
        <button class="btn btn-sm btn-outline-info" id="btn-refresh" onclick="loadData()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
        <button class="btn btn-sm btn-warning" id="btn-tarik-selected" disabled onclick="confirmTarik(false)">
            <i class="bi bi-cash-coin"></i> Cairkan Dipilih
        </button>
        <button class="btn btn-sm btn-success" id="btn-tarik-all" onclick="confirmTarik(true)">
            <i class="bi bi-cash-stack"></i> Cairkan Semua
        </button>
    </div>

    <div class="table-card">
        <div class="table-card-head d-flex align-items-center">
            <i class="bi bi-list-check" style="color:#f59e0b;margin-right:8px"></i>
            <strong style="font-size:.85rem">Belum Dicairkan</strong>
            <div class="ms-auto d-flex align-items-center gap-2">
                <input type="checkbox" id="check-all" title="Pilih Semua">
                <label for="check-all" class="text-muted small mb-0" style="cursor:pointer">Pilih Semua</label>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="font-size:.82rem">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>Order ID</th>
                        <th>Platform</th>
                        <th>Tanggal Pesanan</th>
                        <th>Tanggal Bayar</th>
                        <th>Total Dana (Rp)</th>
                    </tr>
                </thead>
                <tbody id="pending-tbody">
                    <tr><td colspan="6" class="text-center py-5 text-muted">Memuat data…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- TAB: HISTORY -->
<div id="tab-history" style="display:none">
    <div class="table-card">
        <div class="table-card-head">
            <i class="bi bi-clock-history" style="color:#22c55e;margin-right:8px"></i>
            <strong style="font-size:.85rem">10 Pencairan Terakhir</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" style="font-size:.82rem">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Platform</th>
                        <th>Tanggal Bayar</th>
                        <th>Dana (Rp)</th>
                        <th>Terakhir Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="history-tbody">
                    <tr><td colspan="6" class="text-center py-5 text-muted">Memuat riwayat…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
let allPending = [];
let selectedIds = new Set();
let pendingConfirmFn = null;

function esc(t) { return String(t).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
function fmt(v) { return 'Rp ' + parseFloat(v||0).toLocaleString('id-ID'); }
function fmtK(v) { 
  v = parseFloat(v||0);
  if(v >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + ' Jt';
  return fmt(v);
}
function fmtDt(s) { return s ? new Date(s).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'}) : '—'; }

function switchTab(name) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === name));
  document.getElementById('tab-pending').style.display  = name === 'pending'  ? '' : 'none';
  document.getElementById('tab-history').style.display  = name === 'history'  ? '' : 'none';
}

function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  t.className = 'show ' + type;
  t.textContent = msg;
  setTimeout(() => { t.className = ''; }, 3500);
}

function setLoading(visible, text = 'Memproses pencairan…') {
  document.querySelector('#loading-overlay .loading-text').textContent = text;
  document.getElementById('loading-overlay').classList.toggle('visible', visible);
}

function renderSummary(s) {
  document.getElementById('stat-belum-val').textContent   = fmtK(s.total_belum_ditarik);
  document.getElementById('stat-belum-count').textContent = (s.jumlah_pending || 0) + ' pesanan';
  document.getElementById('stat-sudah-val').textContent   = fmtK(s.total_sudah_ditarik);
  document.getElementById('stat-sudah-count').textContent = (s.jumlah_ditarik || 0) + ' pesanan';
  document.getElementById('stat-total-order').textContent = (s.total_selesai || 0).toLocaleString('id-ID');
  document.getElementById('stat-total-rev').textContent   = fmtK(s.total_pendapatan);
}

function updateSelectedUI() {
  const infoEl = document.getElementById('selected-info');
  const btnTarikSel = document.getElementById('btn-tarik-selected');
  const count = selectedIds.size;
  if (count > 0) {
    const total = allPending.filter(r => selectedIds.has(r.order_id)).reduce((a, r) => a + parseFloat(r.total_amount || 0), 0);
    document.getElementById('selected-count').textContent = count;
    document.getElementById('selected-amount').textContent = fmt(total);
    infoEl.classList.add('visible');
    btnTarikSel.disabled = false;
  } else {
    infoEl.classList.remove('visible');
    btnTarikSel.disabled = true;
  }
}

function onRowCheck(orderId, checked) {
  if (checked) selectedIds.add(orderId); else selectedIds.delete(orderId);
  document.getElementById('check-all').indeterminate = selectedIds.size > 0 && selectedIds.size < allPending.length;
  document.getElementById('check-all').checked = selectedIds.size === allPending.length && allPending.length > 0;
  updateSelectedUI();
}

function renderPending(rows) {
  allPending = rows;
  selectedIds.clear();
  updateSelectedUI();
  const tbody = document.getElementById('pending-tbody');
  document.getElementById('tab-count-pending').textContent = rows.length;
  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5">🎉 Semua pesanan sudah dicairkan!</td></tr>`;
    document.getElementById('btn-tarik-all').disabled = true;
    return;
  }
  document.getElementById('btn-tarik-all').disabled = false;
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td><input type="checkbox" class="row-check" data-id="${esc(r.order_id)}"></td>
      <td><code class="order-code">${esc(r.order_id)}</code></td>
      <td><span class="platform-badge"><i class="bi bi-tiktok"></i> ${esc(r.platform)}</span></td>
      <td>${fmtDt(r.create_time)}</td>
      <td>${fmtDt(r.paid_time)}</td>
      <td class="amount">${fmt(r.total_amount)}</td>
    </tr>`).join('');

  tbody.querySelectorAll('.row-check').forEach(cb => {
    cb.addEventListener('change', () => onRowCheck(cb.dataset.id, cb.checked));
  });
}

function renderHistory(rows) {
  const tbody = document.getElementById('history-tbody');
  if (!rows.length) { tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5">Belum ada riwayat.</td></tr>`; return; }
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td><code class="order-code">${esc(r.order_id)}</code></td>
      <td><span class="platform-badge"><i class="bi bi-tiktok"></i> ${esc(r.platform)}</span></td>
      <td>${fmtDt(r.paid_time)}</td>
      <td class="amount">${fmt(r.total_amount)}</td>
      <td class="text-muted" style="font-size:0.7rem">${fmtDt(r.tanggal_update)}</td>
      <td><button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="resetOne('${esc(r.order_id)}')">Batal</button></td>
    </tr>`).join('');
}

async function loadData() {
  try {
    const resp = await fetch('<?= base_url('/withdrawal/data') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await resp.json();
    if (!data.success) throw new Error(data.error);
    renderSummary(data.summary || {});
    renderPending(data.pending || []);
    renderHistory(data.history || []);
  } catch (e) { showToast('Gagal memuat: ' + e.message, 'error'); }
}

function confirmTarik(tarikSemua) {
  const modal = document.getElementById('confirm-modal');
  const amountEl = document.getElementById('modal-amount');
  if (tarikSemua) {
    const totalAmt = allPending.reduce((a, r) => a + parseFloat(r.total_amount||0), 0);
    document.getElementById('modal-title').textContent = 'Cairkan Semua Dana Pending';
    document.getElementById('modal-desc').textContent = `Menandai semua ${allPending.length} pesanan sebagai "Sudah Dicairkan".`;
    amountEl.textContent = fmt(totalAmt);
    pendingConfirmFn = () => executeTarik(true, []);
  } else {
    const ids = [...selectedIds];
    const amt = allPending.filter(r => ids.includes(r.order_id)).reduce((a, r) => a + parseFloat(r.total_amount||0), 0);
    document.getElementById('modal-title').textContent = `Cairkan ${ids.length} Pesanan`;
    document.getElementById('modal-desc').textContent = `Konfirmasi pencairan untuk ${ids.length} pesanan terpilih.`;
    amountEl.textContent = fmt(amt);
    pendingConfirmFn = () => executeTarik(false, ids);
  }
  modal.classList.add('visible');
}

async function executeTarik(tarikSemua, orderIds) {
  setLoading(true);
  try {
    const body = tarikSemua ? { tarik_semua: true } : { order_ids: orderIds };
    const resp = await fetch('<?= base_url('/withdrawal/tarik') ?>', {
      method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify(body),
    });
    const data = await resp.json();
    if (!data.success) throw new Error(data.error);
    showToast(data.message, 'success');
    await loadData();
  } catch (e) { showToast('Gagal: ' + e.message, 'error'); } finally { setLoading(false); }
}

document.getElementById('modal-cancel').onclick = () => document.getElementById('confirm-modal').classList.remove('visible');
document.getElementById('modal-confirm').onclick = () => { document.getElementById('confirm-modal').classList.remove('visible'); if(pendingConfirmFn) pendingConfirmFn(); };

document.getElementById('check-all').onchange = (e) => {
  const checked = e.target.checked;
  allPending.forEach(r => { if(checked) selectedIds.add(r.order_id); else selectedIds.delete(r.order_id); });
  document.querySelectorAll('.row-check').forEach(cb => cb.checked = checked);
  updateSelectedUI();
};

loadData();
</script>
<?= $this->endSection() ?>
