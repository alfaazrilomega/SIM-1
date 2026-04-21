<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
/* ── Page Header ── */
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-title  { font-size:1.4rem; font-weight:700; color:#e2e8f0; display:flex; align-items:center; gap:10px; }
.page-subtitle { font-size:.8rem; color:var(--text-muted); margin-top:2px; }

/* ── Steps ── */
.steps-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin-bottom:24px; }
.step-card  { background:#0f172a; border:1px solid var(--border); border-radius:12px; padding:16px; display:flex; gap:12px; align-items:flex-start; }
.step-num   { width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,#4f8ef7,#7c5cfc); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:#fff; flex-shrink:0; }
.step-title { font-size:.82rem; font-weight:600; color:#e2e8f0; margin-bottom:3px; }
.step-desc  { font-size:.73rem; color:var(--text-muted); }

/* ── Drop Zone ── */
.upload-zone {
    border: 2px dashed rgba(79,142,247,.3);
    border-radius: 14px;
    padding: 44px 24px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s, transform .2s;
    background: rgba(79,142,247,.02);
    position: relative;
    margin-bottom: 20px;
}
.upload-zone:hover         { border-color: rgba(79,142,247,.55); background: rgba(79,142,247,.04); }
.upload-zone.drag-over     { border-color: #4f8ef7; background: rgba(79,142,247,.08); transform: scale(1.01); }
.upload-zone.file-selected { border-color: #22c55e; background: rgba(34,197,94,.04); }
.upload-zone .uz-icon      { font-size: 2.8rem; color: #4f8ef7; display: block; margin-bottom: 12px; transition: transform .3s; }
.upload-zone:hover .uz-icon { transform: translateY(-4px); }
.upload-zone .uz-title     { font-size: .95rem; font-weight: 600; color: #e2e8f0; margin-bottom: 5px; }
.upload-zone .uz-sub       { font-size: .8rem; color: var(--text-muted); }
.upload-zone .uz-formats   { display:inline-flex; gap:8px; justify-content:center; margin-top:14px; flex-wrap:wrap; }
.fmt-tag { background:#1e293b; border:1px solid var(--border); border-radius:6px; padding:3px 10px; font-size:.72rem; color:var(--text-muted); }
#excel-input { display: none; }

/* ── File Info Box ── */
#file-info {
    margin-bottom: 14px;
    padding: 12px 16px;
    background: rgba(34,197,94,.07);
    border: 1px solid rgba(34,197,94,.2);
    border-radius: 10px;
    display: none;
    align-items: center;
    gap: 10px;
}
#file-info .fi-icon { font-size: 1.4rem; color: #4ade80; }
#file-info .fi-name { font-weight: 600; font-size: .85rem; color: #e2e8f0; }
#file-info .fi-size { font-size: .75rem; color: var(--text-muted); }
#btn-clear { margin-left: auto; background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.1rem; padding: 2px 6px; }
#btn-clear:hover { color: #f87171; }

/* ── Import Button ── */
.btn-import {
    width: 100%;
    padding: 11px 18px;
    background: linear-gradient(90deg, #4f8ef7, #7c5cfc);
    border: none; border-radius: 10px; color: #fff;
    font-size: .9rem; font-weight: 600; cursor: pointer;
    transition: opacity .2s, transform .2s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-bottom: 16px;
}
.btn-import:hover:not(:disabled) { opacity: .88; transform: translateY(-1px); }
.btn-import:disabled { opacity: .4; cursor: not-allowed; transform: none; }

/* ── Progress ── */
#progress-section { display:none; margin-bottom:16px; }
.progress-bar-wrap { height:5px; background:rgba(255,255,255,.08); border-radius:99px; overflow:hidden; margin:8px 0; }
.progress-bar-inner { height:100%; border-radius:99px; background:linear-gradient(90deg,#4f8ef7,#7c5cfc); transition:width .4s ease; }
.progress-label { font-size:.78rem; color:var(--text-muted); }
@keyframes loadingPulse { 0%,100%{opacity:.5} 50%{opacity:1} }
.loading-pulse { animation: loadingPulse 1.4s ease infinite; }

/* ── Result Cards ── */
#results-section { display:none; }
.stat-chips { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; margin-bottom:18px; }
.stat-chip { background:#0f172a; border:1px solid var(--border); border-radius:12px; padding:16px; text-align:center; }
.stat-chip .sv { font-size:1.7rem; font-weight:700; }
.stat-chip .sl { font-size:.7rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.8px; margin-top:3px; }
.chip-total   .sv { color:#4f8ef7; }
.chip-inserted .sv { color:#4ade80; }
.chip-updated .sv { color:#fbbf24; }
.chip-rows    .sv { color:#94a3b8; }

/* ── Preview Table ── */
.table-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; }
.table-card-header { padding:14px 18px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; }
.table-card-title { font-size:.85rem; font-weight:600; color:#e2e8f0; }
.table-responsive { overflow-x:auto; max-height:380px; overflow-y:auto; }
.sim-table { width:100%; border-collapse:collapse; font-size:.8rem; }
.sim-table thead tr { background:#020617; position:sticky; top:0; z-index:1; }
.sim-table thead th { padding:10px 14px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.7rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody tr { border-bottom:1px solid rgba(30,41,59,.6); transition:background .15s; }
.sim-table tbody tr:hover { background:rgba(79,142,247,.04); }
.sim-table tbody td { padding:10px 14px; color:#cbd5e1; vertical-align:middle; }
.badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.68rem; font-weight:600; padding:3px 9px; border-radius:20px; }
.badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.badge-warning { background:rgba(245,158,11,.12); color:#fbbf24; border:1px solid rgba(245,158,11,.2); }
.badge-danger  { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.2); }
.badge-info    { background:rgba(79,142,247,.12); color:#4f8ef7; border:1px solid rgba(79,142,247,.2); }

/* ── Error Box ── */
.alert-sim { padding:12px 16px; border-radius:8px; margin-bottom:14px; display:flex; align-items:flex-start; gap:10px; font-size:.82rem; font-weight:500; border:1px solid transparent; }
.alert-danger  { background:rgba(239,68,68,.08); border-color:rgba(239,68,68,.25); color:#fca5a5; }

/* ── Riwayat table ── */
.riwayat-card { background:#0f172a; border:1px solid var(--border); border-radius:14px; overflow:hidden; }
.filter-bar  { display:flex; gap:8px; flex-wrap:wrap; }
.sim-input   { background:#1e293b; border:1px solid var(--border); color:#e2e8f0; border-radius:8px; padding:6px 12px; font-size:.8rem; outline:none; }
.btn-ghost   { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:6px 14px; font-size:.8rem; cursor:pointer; transition:border-color .15s,color .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-ghost:hover { border-color:rgba(79,142,247,.4); color:#e2e8f0; }
.btn-accent  { background:linear-gradient(90deg,#4f8ef7,#7c5cfc); border:none; color:#fff; border-radius:8px; padding:6px 14px; font-size:.8rem; font-weight:600; cursor:pointer; transition:opacity .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-accent:hover { opacity:.85; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-cloud-arrow-down-fill"></i> Import</div>
        <div class="page-subtitle">Import data pesanan TikTok dari file .xlsx</div>
    </div>
    <div class="filter-bar">
        <button class="btn-ghost" id="btn-template" onclick="alert('Template belum tersedia. Gunakan file export dari TikTok Seller Center.')">
            <i class="bi bi-file-earmark-arrow-down"></i> Unduh Template
        </button>
        <button class="btn-accent" id="btn-trigger-import" onclick="document.getElementById('excel-input').click()">
            <i class="bi bi-cloud-arrow-down-fill"></i> Pilih File
        </button>
    </div>
</div>

<!-- Steps -->
<div class="steps-grid">
    <div class="step-card">
        <div class="step-num">1</div>
        <div><div class="step-title">Export dari TikTok</div><div class="step-desc">Download OrderSKUList dari TikTok Seller Center</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">2</div>
        <div><div class="step-title">Upload File</div><div class="step-desc">Drag & drop atau klik untuk pilih file .xlsx</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">3</div>
        <div><div class="step-title">Klik Import</div><div class="step-desc">Data akan di-upsert otomatis ke database</div></div>
    </div>
    <div class="step-card">
        <div class="step-num">4</div>
        <div><div class="step-title">Verifikasi</div><div class="step-desc">Periksa preview hasil import di bawah</div></div>
    </div>
</div>

<!-- Hidden real file input -->
<input type="file" id="excel-input" accept=".xlsx">

<!-- Upload Zone -->
<div class="upload-zone" id="drop-zone" tabindex="0" role="button" aria-label="Klik atau drop file Excel di sini">
    <i class="bi bi-cloud-arrow-up-fill uz-icon"></i>
    <div class="uz-title">Drag &amp; drop file di sini</div>
    <div class="uz-sub">atau klik untuk memilih file dari perangkat Anda</div>
    <div class="uz-formats">
        <span class="fmt-tag">.xlsx</span>
        <span class="fmt-tag">Maks. 50 MB</span>
    </div>
</div>

<!-- File Info -->
<div id="file-info">
    <i class="bi bi-file-earmark-excel-fill fi-icon"></i>
    <div>
        <div id="fi-name" class="fi-name"></div>
        <div id="fi-size" class="fi-size"></div>
    </div>
    <button id="btn-clear" aria-label="Hapus file">&times;</button>
</div>

<!-- Import Button -->
<button class="btn-import" id="btn-import" disabled>
    <i class="bi bi-database-up" id="btn-icon"></i>
    <span id="btn-text">Pilih file terlebih dahulu</span>
</button>

<!-- Progress -->
<div id="progress-section">
    <div class="progress-label loading-pulse" id="progress-label">Membaca file Excel…</div>
    <div class="progress-bar-wrap">
        <div class="progress-bar-inner" id="progress-bar" style="width:0%"></div>
    </div>
</div>

<!-- Results Section -->
<div id="results-section">
    <div class="stat-chips" id="stat-chips"></div>
    <div id="error-box" class="alert-sim alert-danger" style="display:none"></div>

    <div class="table-card" id="preview-card" style="display:none">
        <div class="table-card-header">
            <i class="bi bi-table" style="color:var(--accent)"></i>
            <span class="table-card-title">Preview (10 Pesanan Pertama)</span>
        </div>
        <div class="table-responsive">
            <table class="sim-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Produk (Sample)</th>
                        <th>Items</th>
                        <th>Total (Rp)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="preview-tbody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Riwayat Import (dari DB) -->
<div class="riwayat-card">
    <div class="table-card-header" style="border-bottom:1px solid var(--border); padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <span class="table-card-title"><i class="bi bi-clock-history" style="color:var(--accent);margin-right:6px"></i>Riwayat Import</span>
        <input class="sim-input" type="text" id="search-riwayat" placeholder="🔍 Cari…" style="width:180px" oninput="filterRiwayat(this.value)">
    </div>
    <div class="table-responsive">
        <table class="sim-table" id="riwayat-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama File</th>
                    <th>Total Baris</th>
                    <th>Insert</th>
                    <th>Update</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="riwayat-tbody">
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">Memuat riwayat…</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// ============================================================
// SIM Import — Frontend Logic
// ============================================================
const BASE_URL   = '<?= base_url() ?>';
const IMPORT_URL = '<?= base_url('/import/process') ?>';

const dropZone    = document.getElementById('drop-zone');
const fileInput   = document.getElementById('excel-input');
const fileInfo    = document.getElementById('file-info');
const fiName      = document.getElementById('fi-name');
const fiSize      = document.getElementById('fi-size');
const btnClear    = document.getElementById('btn-clear');
const btnImport   = document.getElementById('btn-import');
const btnText     = document.getElementById('btn-text');
const btnIcon     = document.getElementById('btn-icon');
const progSection = document.getElementById('progress-section');
const progLabel   = document.getElementById('progress-label');
const progBar     = document.getElementById('progress-bar');
const resultsSec  = document.getElementById('results-section');
const statChips   = document.getElementById('stat-chips');
const previewCard = document.getElementById('preview-card');
const previewTbody = document.getElementById('preview-tbody');
const errorBox    = document.getElementById('error-box');

let selectedFile  = null;
let riwayatData   = [];

// ---- Helpers ----
function fmtSize(bytes) {
    if (bytes < 1024)      return bytes + ' B';
    if (bytes < 1048576)   return (bytes/1024).toFixed(1) + ' KB';
    return (bytes/1048576).toFixed(2) + ' MB';
}
function fmtRp(n) {
    return 'Rp ' + Number(n || 0).toLocaleString('id-ID');
}
function escHtml(s) {
    return String(s || '')
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function statusClass(s) {
    const l = (s || '').toLowerCase();
    if (l.includes('selesai'))                       return 'color:#4ade80';
    if (l.includes('kirim'))                         return 'color:#60a5fa';
    if (l.includes('batal') || l.includes('cancel')) return 'color:#f87171';
    return 'color:var(--text-muted)';
}

// ---- Set file ----
function setFile(file) {
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    if (ext !== 'xlsx') {
        alert('❌ Hanya file .xlsx yang diizinkan.\nFile kamu: ' + file.name);
        return;
    }
    if (file.size > 52428800) {
        alert('❌ File terlalu besar. Maksimum 50 MB.');
        return;
    }
    selectedFile = file;
    fiName.textContent = file.name;
    fiSize.textContent = fmtSize(file.size);
    fileInfo.style.display    = 'flex';
    dropZone.classList.add('file-selected');
    btnImport.disabled        = false;
    btnText.textContent       = 'Import ke Database';
    resultsSec.style.display  = 'none';
    errorBox.style.display    = 'none';
}

// ---- Clear file ----
function clearFile() {
    selectedFile = null;
    fileInput.value = '';
    fileInfo.style.display    = 'none';
    dropZone.classList.remove('file-selected');
    btnImport.disabled        = true;
    btnText.textContent       = 'Pilih file terlebih dahulu';
    resultsSec.style.display  = 'none';
    progSection.style.display = 'none';
}

// ---- Drop zone events ----
dropZone.addEventListener('click',    () => fileInput.click());
dropZone.addEventListener('keydown',  e => { if (e.key === 'Enter' || e.key === ' ') fileInput.click(); });
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave',() => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const f = e.dataTransfer.files[0];
    if (f) setFile(f);
});
fileInput.addEventListener('change', () => { if (fileInput.files[0]) setFile(fileInput.files[0]); });
btnClear.addEventListener('click',   e => { e.stopPropagation(); clearFile(); });

// ---- Progress ----
let progInterval = null;
function startFakeProgress() {
    let pct = 0;
    progBar.style.width = '0%';
    progBar.style.background = '';
    progSection.style.display = 'block';
    progInterval = setInterval(() => {
        pct = Math.min(pct + Math.random() * 7, 85);
        progBar.style.width = pct + '%';
        if      (pct < 30) progLabel.textContent = 'Membaca file Excel…';
        else if (pct < 60) progLabel.textContent = 'Memproses baris data…';
        else               progLabel.textContent = 'Mengupdate database…';
    }, 350);
}
function finishProgress() {
    clearInterval(progInterval);
    progBar.style.width = '100%';
    progLabel.classList.remove('loading-pulse');
    progLabel.textContent = '✅ Selesai!';
    setTimeout(() => {
        progSection.style.display = 'none';
        progLabel.classList.add('loading-pulse');
    }, 2000);
}
function failProgress(msg) {
    clearInterval(progInterval);
    progBar.style.background = '#ef4444';
    progBar.style.width      = '100%';
    progLabel.classList.remove('loading-pulse');
    progLabel.textContent = '❌ ' + msg;
    setTimeout(() => {
        progBar.style.background = '';
        progBar.style.width = '0%';
        progLabel.classList.add('loading-pulse');
        progSection.style.display = 'none';
    }, 3500);
}

// ---- Render results ----
function renderResults(data) {
    statChips.innerHTML = `
        <div class="stat-chip chip-total">
            <div class="sv">${Number(data.total_orders||0).toLocaleString()}</div>
            <div class="sl">Total Order</div>
        </div>
        <div class="stat-chip chip-inserted">
            <div class="sv">${Number(data.inserted||0).toLocaleString()}</div>
            <div class="sl">Baru (INSERT)</div>
        </div>
        <div class="stat-chip chip-updated">
            <div class="sv">${Number(data.updated||0).toLocaleString()}</div>
            <div class="sl">Diperbarui (UPDATE)</div>
        </div>
        <div class="stat-chip chip-rows">
            <div class="sv">${Number(data.total_rows||0).toLocaleString()}</div>
            <div class="sl">Baris Diproses</div>
        </div>
    `;

    if (data.errors && data.errors.length) {
        errorBox.style.display = 'flex';
        errorBox.innerHTML = '<i class="bi bi-exclamation-triangle" style="flex-shrink:0"></i><div><strong>Error:</strong> '
            + data.errors.map(e => escHtml(e)).join('<br>') + '</div>';
    } else {
        errorBox.style.display = 'none';
    }

    previewTbody.innerHTML = '';
    if (data.preview && data.preview.length) {
        data.preview.forEach(row => {
            const isInsert = row.action === 'INSERTED';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><code style="font-size:.72rem;color:#93c5fd">${escHtml(row.order_id)}</code></td>
                <td><span style="${statusClass(row.status_pesanan)}">${escHtml(row.status_pesanan)}</span></td>
                <td style="max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${escHtml(row.produk_sample)}">${escHtml(row.produk_sample)}</td>
                <td style="text-align:center">${row.items}</td>
                <td>${fmtRp(row.total_amount)}</td>
                <td>
                    <span class="badge-sim ${isInsert ? 'badge-success' : 'badge-warning'}">
                        <i class="bi ${isInsert ? 'bi-plus-circle' : 'bi-arrow-clockwise'}"></i>
                        ${escHtml(row.action)}
                    </span>
                </td>
            `;
            previewTbody.appendChild(tr);
        });
        previewCard.style.display = 'block';
    } else {
        previewCard.style.display = 'none';
    }

    resultsSec.style.display = 'block';
    resultsSec.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Reload riwayat
    loadRiwayat();
}

// ---- Main Import click ----
btnImport.addEventListener('click', async () => {
    if (!selectedFile) return;

    btnImport.disabled    = true;
    btnIcon.className     = 'bi bi-hourglass-split';
    btnText.textContent   = 'Mengimport…';
    resultsSec.style.display = 'none';
    startFakeProgress();

    const formData = new FormData();
    formData.append('excel_file', selectedFile);

    try {
        const resp = await fetch(IMPORT_URL, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const data = await resp.json();

        if (data.success) {
            finishProgress();
            renderResults(data);
        } else {
            failProgress(data.error || 'Import gagal');
            errorBox.style.display = 'flex';
            errorBox.innerHTML = '<i class="bi bi-exclamation-triangle" style="flex-shrink:0"></i><div><strong>Error:</strong> '
                + escHtml(data.error || 'Terjadi kesalahan.') + '</div>';
            resultsSec.style.display = 'block';
            statChips.innerHTML = '';
            previewCard.style.display = 'none';
        }
    } catch (err) {
        failProgress('Request gagal: ' + err.message);
    } finally {
        btnImport.disabled  = false;
        btnIcon.className   = 'bi bi-database-up';
        btnText.textContent = 'Import ke Database';
    }
});

// ---- Riwayat Import (from DB) ----
async function loadRiwayat() {
    const tbody = document.getElementById('riwayat-tbody');
    try {
        const resp = await fetch('<?= base_url('/import/riwayat') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const data = await resp.json();
        riwayatData = data.rows || [];
        renderRiwayat(riwayatData);
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">Gagal memuat riwayat. (' + e.message + ')</td></tr>';
    }
}

function renderRiwayat(rows) {
    const tbody = document.getElementById('riwayat-tbody');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada riwayat import.</td></tr>';
        return;
    }
    tbody.innerHTML = rows.map((r, i) => `
        <tr>
            <td style="color:var(--text-muted)">${i+1}</td>
            <td style="color:#e2e8f0;font-weight:500"><i class="bi bi-file-earmark-spreadsheet" style="color:#4ade80;margin-right:5px"></i>${escHtml(r.filename)}</td>
            <td>${Number(r.total_rows||0).toLocaleString()}</td>
            <td style="color:#4ade80">${Number(r.inserted||0).toLocaleString()}</td>
            <td style="color:#fbbf24">${Number(r.updated||0).toLocaleString()}</td>
            <td style="color:var(--text-muted)">${escHtml(r.created_at || r.tanggal || '-')}</td>
            <td><span class="badge-sim badge-success">Selesai</span></td>
        </tr>
    `).join('');
}

function filterRiwayat(q) {
    const filtered = riwayatData.filter(r =>
        (r.filename || '').toLowerCase().includes(q.toLowerCase())
    );
    renderRiwayat(filtered);
}

// Load riwayat on page load
loadRiwayat();
</script>

<?= $this->endSection() ?>