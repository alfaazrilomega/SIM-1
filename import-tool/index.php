<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIM — Excel Import Tool</title>
  <meta name="description" content="Import file Excel TikTok OrderSKUList ke database MySQL secara otomatis">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    /* ===== ROOT VARIABLES ===== */
    :root {
      --bg-base:    #070c18;
      --bg-card:    #0d1526;
      --bg-card2:   #111d35;
      --border:     rgba(99,179,237,0.12);
      --accent:     #4f8ef7;
      --accent2:    #7c5cfc;
      --success:    #22c55e;
      --warning:    #f59e0b;
      --danger:     #ef4444;
      --text:       #e2e8f0;
      --muted:      #64748b;
      --radius:     14px;
    }

    /* ===== RESET & BASE ===== */
    *, *::before, *::after { box-sizing: border-box; }
    html, body {
      height: 100%;
      background: var(--bg-base);
      color: var(--text);
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      line-height: 1.6;
    }

    /* ===== BACKGROUND GRID ===== */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(79,142,247,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(79,142,247,.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 0;
    }

    /* ===== LAYOUT ===== */
    .wrapper {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== HEADER ===== */
    .site-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.25rem 2rem;
      border-bottom: 1px solid var(--border);
      background: rgba(7,12,24,.85);
      backdrop-filter: blur(16px);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .site-logo {
      display: flex;
      align-items: center;
      gap: .75rem;
      text-decoration: none;
      color: var(--text);
    }
    .logo-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
    }
    .logo-text { font-weight: 700; font-size: 1.1rem; letter-spacing: -.3px; }
    .logo-sub  { font-size: .7rem; color: var(--muted); }
    .header-badge {
      padding: .35rem .8rem;
      background: rgba(79,142,247,.12);
      border: 1px solid rgba(79,142,247,.25);
      border-radius: 20px;
      font-size: .75rem;
      color: var(--accent);
    }

    /* ===== MAIN ===== */
    .main-content {
      flex: 1;
      padding: 2.5rem 1.5rem;
      max-width: 940px;
      margin: 0 auto;
      width: 100%;
    }

    /* ===== PAGE TITLE ===== */
    .page-title { font-size: 1.75rem; font-weight: 700; letter-spacing: -.5px; margin-bottom: .35rem; }
    .page-sub   { color: var(--muted); font-size: .9rem; }

    /* ===== CARDS ===== */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.75rem;
      transition: border-color .25s;
    }
    .card:hover { border-color: rgba(79,142,247,.25); }
    .card-title {
      font-size: .8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--muted);
      margin-bottom: 1rem;
    }

    /* ===== DROP ZONE ===== */
    #drop-zone {
      border: 2px dashed rgba(79,142,247,.3);
      border-radius: var(--radius);
      padding: 3rem 2rem;
      text-align: center;
      cursor: pointer;
      transition: all .25s;
      background: rgba(79,142,247,.03);
      position: relative;
    }
    #drop-zone.drag-over {
      border-color: var(--accent);
      background: rgba(79,142,247,.08);
      transform: scale(1.01);
    }
    #drop-zone.file-selected {
      border-color: var(--success);
      background: rgba(34,197,94,.05);
    }
    #drop-zone .dz-icon {
      font-size: 3rem;
      color: var(--accent);
      margin-bottom: .75rem;
      display: block;
      transition: transform .3s;
    }
    #drop-zone:hover .dz-icon, #drop-zone.drag-over .dz-icon {
      transform: translateY(-4px) scale(1.05);
    }
    #drop-zone .dz-main { font-size: 1.1rem; font-weight: 600; margin-bottom: .4rem; }
    #drop-zone .dz-sub  { color: var(--muted); font-size: .85rem; }
    #excel-input { display: none; }
    #file-info {
      margin-top: 1rem;
      padding: .75rem 1rem;
      background: rgba(34,197,94,.08);
      border: 1px solid rgba(34,197,94,.2);
      border-radius: 10px;
      display: none;
      align-items: center;
      gap: .75rem;
    }
    #file-info .fi-icon { font-size: 1.4rem; color: var(--success); }
    #file-info .fi-name { font-weight: 600; font-size: .9rem; }
    #file-info .fi-size { font-size: .78rem; color: var(--muted); }

    /* ===== IMPORT BUTTON ===== */
    .btn-import {
      width: 100%;
      padding: .9rem 1.5rem;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all .25s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .6rem;
      margin-top: 1.5rem;
    }
    .btn-import:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(79,142,247,.35);
    }
    .btn-import:disabled { opacity: .45; cursor: not-allowed; transform: none; }

    /* ===== PROGRESS ===== */
    #progress-section { display: none; margin-top: 1.5rem; }
    .progress-bar-wrap {
      height: 6px;
      background: rgba(255,255,255,.08);
      border-radius: 99px;
      overflow: hidden;
      margin: .75rem 0;
    }
    .progress-bar-inner {
      height: 100%;
      border-radius: 99px;
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      transition: width .4s ease;
    }
    .progress-label { font-size: .85rem; color: var(--muted); }
    @keyframes loadingPulse {
      0%, 100% { opacity: .5; }
      50% { opacity: 1; }
    }
    .loading-pulse { animation: loadingPulse 1.4s ease infinite; }

    /* ===== RESULTS ===== */
    #results-section { display: none; }

    /* Stat chips */
    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    .stat-chip {
      background: var(--bg-card2);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      text-align: center;
    }
    .stat-chip .sv { font-size: 1.8rem; font-weight: 700; }
    .stat-chip .sl { font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .8px; margin-top: .2rem; }
    .stat-chip.s-total   .sv { color: var(--accent); }
    .stat-chip.s-inserted .sv { color: var(--success); }
    .stat-chip.s-updated  .sv { color: var(--warning); }
    .stat-chip.s-skipped  .sv { color: var(--danger); }

    /* Preview table */
    .table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid var(--border); }
    table.preview-table {
      width: 100%; border-collapse: collapse;
      font-size: .82rem;
    }
    .preview-table thead th {
      background: var(--bg-card2);
      padding: .65rem 1rem;
      text-align: left;
      color: var(--muted);
      font-weight: 500;
      white-space: nowrap;
      border-bottom: 1px solid var(--border);
    }
    .preview-table tbody td {
      padding: .65rem 1rem;
      border-bottom: 1px solid rgba(255,255,255,.04);
      white-space: nowrap;
    }
    .preview-table tbody tr:last-child td { border-bottom: none; }
    .preview-table tbody tr:hover td { background: rgba(79,142,247,.04); }

    /* Badges */
    .badge-action {
      display: inline-flex; align-items: center; gap: .3rem;
      padding: .25rem .65rem; border-radius: 20px; font-size: .72rem; font-weight: 600;
    }
    .badge-action.inserted { background: rgba(34,197,94,.12); color: #4ade80; }
    .badge-action.updated  { background: rgba(245,158,11,.12); color: #fbbf24; }

    /* Error box */
    .error-box {
      background: rgba(239,68,68,.06);
      border: 1px solid rgba(239,68,68,.25);
      border-radius: 10px;
      padding: 1rem 1.25rem;
      color: #fca5a5;
      font-size: .87rem;
    }

    /* Highlight "Selesai" */
    .status-selesai  { color: #4ade80; }
    .status-dikirim  { color: #60a5fa; }
    .status-batal    { color: #f87171; }
    .status-other    { color: var(--muted); }

    /* ===== INFO PANEL ===== */
    .info-list { list-style: none; padding: 0; margin: 0; }
    .info-list li {
      display: flex; align-items: flex-start; gap: .65rem;
      padding: .55rem 0;
      border-bottom: 1px solid var(--border);
      font-size: .85rem;
    }
    .info-list li:last-child { border-bottom: none; }
    .info-list .ii { font-size: 1rem; flex-shrink: 0; margin-top: .1rem; }
    .info-list strong { color: var(--text); display: block; font-size: .82rem; }
    .info-list span { color: var(--muted); font-size: .78rem; }

    /* ===== FOOTER ===== */
    .site-footer {
      text-align: center;
      padding: 1.25rem;
      border-top: 1px solid var(--border);
      color: var(--muted);
      font-size: .78rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 576px) {
      .main-content { padding: 1.5rem 1rem; }
      .page-title { font-size: 1.4rem; }
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <header class="site-header">
    <a href="index.php" class="site-logo">
      <div class="logo-icon">📊</div>
      <div>
        <div class="logo-text">SIM Import Tool</div>
        <div class="logo-sub">Sales Information Management</div>
      </div>
    </a>
    <div class="d-flex align-items-center gap-3">
      <span class="header-badge"><i class="bi bi-database me-1"></i>sim_orders</span>
      <a href="setup.php" class="header-badge" style="text-decoration:none">
        <i class="bi bi-gear me-1"></i>Setup DB
      </a>
    </div>
  </header>

  <!-- MAIN -->
  <main class="main-content">
    <h1 class="page-title">Import Excel Pesanan</h1>
    <p class="page-sub mb-4">Upload file <strong>.xlsx</strong> export TikTok OrderSKUList. Data akan di-<em>upsert</em> otomatis ke database.</p>

    <div class="row g-4">

      <!-- LEFT: Upload Form -->
      <div class="col-lg-7">
        <div class="card">
          <p class="card-title"><i class="bi bi-cloud-upload me-1"></i>Upload File Excel</p>

          <!-- Drop Zone -->
          <div id="drop-zone" tabindex="0" role="button"
               aria-label="Klik atau drag file Excel ke sini">
            <i class="bi bi-file-earmark-spreadsheet dz-icon"></i>
            <div class="dz-main">Drag &amp; Drop file di sini</div>
            <div class="dz-sub">atau <u style="color:var(--accent)">klik untuk pilih file</u></div>
            <div class="dz-sub mt-2" style="font-size:.75rem; color:var(--muted)">
              Format: .xlsx &nbsp;|&nbsp; Max: 50 MB
            </div>
          </div>
          <input type="file" id="excel-input" accept=".xlsx">

          <!-- File Info -->
          <div id="file-info">
            <i class="bi bi-file-earmark-excel-fill fi-icon"></i>
            <div>
              <div id="fi-name" class="fi-name"></div>
              <div id="fi-size" class="fi-size"></div>
            </div>
            <button id="btn-clear" style="margin-left:auto; background:none; border:none;
              color:var(--muted); cursor:pointer; font-size:1.1rem;"
              aria-label="Hapus file">&times;</button>
          </div>

          <!-- Import Button -->
          <button id="btn-import" class="btn-import" disabled>
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
        </div>

        <!-- Results -->
        <div id="results-section" class="mt-4">
          <!-- Stats -->
          <div class="stat-grid" id="stat-grid"></div>

          <!-- Error box -->
          <div id="error-box" class="error-box mb-3" style="display:none"></div>

          <!-- Preview table -->
          <div class="card" id="preview-card" style="padding:0; overflow:hidden;">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border);">
              <span class="card-title" style="margin:0"><i class="bi bi-table me-1"></i>Preview (10 Pesanan Pertama)</span>
            </div>
            <div class="table-wrap">
              <table class="preview-table">
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
      </div>

      <!-- RIGHT: Info Panel -->
      <div class="col-lg-5">
        <div class="card mb-4">
          <p class="card-title"><i class="bi bi-info-circle me-1"></i>Business Rules Aktif</p>
          <ul class="info-list">
            <li>
              <i class="bi bi-arrow-repeat text-warning ii"></i>
              <div>
                <strong>Upsert by Order ID</strong>
                <span>Jika Order ID sudah ada → UPDATE. Baru → INSERT. Tidak ada duplikat.</span>
              </div>
            </li>
            <li>
              <i class="bi bi-shield-check text-success ii"></i>
              <div>
                <strong>Status Penarikan Aman</strong>
                <span>Field <code>status_penarikan</code> (CEO) TIDAK di-reset saat re-import.</span>
              </div>
            </li>
            <li>
              <i class="bi bi-funnel text-info ii"></i>
              <div>
                <strong>Laporan Filter "Selesai"</strong>
                <span>View laporan hanya menampilkan pesanan dengan status <em>Selesai</em>.</span>
              </div>
            </li>
            <li>
              <i class="bi bi-tags text-primary ii"></i>
              <div>
                <strong>Kombinasi Produk + Variasi</strong>
                <span>Variasi "Default" di-mapping ke nama produk. Non-default = nama + variasi.</span>
              </div>
            </li>
            <li>
              <i class="bi bi-recycle text-warning ii"></i>
              <div>
                <strong>Detail Rebuild Otomatis</strong>
                <span>Setiap re-import, detail item lama dihapus dan diganti dengan versi terbaru.</span>
              </div>
            </li>
          </ul>
        </div>

        <div class="card mb-4">
          <p class="card-title"><i class="bi bi-columns me-1"></i>Kolom yang Dibaca dari Excel</p>
          <ul class="info-list">
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Order ID</strong><span>Primary Key pesanan (18-digit TikTok ID)</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Order Status</strong><span>Selesai / Dikirim / Dibatalkan</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Product Name</strong><span>Nama produk verbatim dari TikTok</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Variation</strong><span>Variasi SKU (incl. "Default")</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Quantity</strong><span>Jumlah unit tiap SKU</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>SKU Settlement Amount</strong><span>Jumlah yang diterima seller</span></div></li>
            <li><i class="bi bi-check-circle-fill text-success ii"></i>
              <div><strong>Create Time &amp; Paid Time</strong><span>Waktu order &amp; pembayaran</span></div></li>
          </ul>
        </div>

        <div class="card">
          <p class="card-title"><i class="bi bi-terminal me-1"></i>Setup Pertama Kali</p>
          <div style="font-size:.8rem; color:var(--muted); line-height:1.8;">
            <div class="mb-2">1️⃣ Buka <a href="setup.php" style="color:var(--accent)">setup.php</a> untuk membuat database</div>
            <div class="mb-2">2️⃣ Jalankan di terminal:</div>
            <pre style="background:rgba(0,0,0,.4);border:1px solid var(--border);border-radius:8px;
              padding:.75rem;font-size:.75rem;color:#93c5fd;margin-bottom:.75rem;overflow-x:auto;">cd d:\SIM\import-tool
composer install</pre>
            <div>3️⃣ Tempatkan folder <code>import-tool/</code> di <code>C:\xampp\htdocs\</code></div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </main>

  <!-- FOOTER -->
  <footer class="site-footer">
    SIM Import Tool &nbsp;|&nbsp; Platform: TikTok OrderSKUList &nbsp;|&nbsp;
    Database: <code>sim_orders</code> (XAMPP MySQL)
  </footer>

</div><!-- /.wrapper -->

<script>
// =====================================================
// SIM Import Tool — Frontend Logic
// =====================================================
const dropZone   = document.getElementById('drop-zone');
const fileInput  = document.getElementById('excel-input');
const fileInfo   = document.getElementById('file-info');
const fiName     = document.getElementById('fi-name');
const fiSize     = document.getElementById('fi-size');
const btnClear   = document.getElementById('btn-clear');
const btnImport  = document.getElementById('btn-import');
const btnText    = document.getElementById('btn-text');
const btnIcon    = document.getElementById('btn-icon');
const progSection = document.getElementById('progress-section');
const progLabel  = document.getElementById('progress-label');
const progBar    = document.getElementById('progress-bar');
const resultsSec = document.getElementById('results-section');
const statGrid   = document.getElementById('stat-grid');
const previewCard = document.getElementById('preview-card');
const previewTbody = document.getElementById('preview-tbody');
const errorBox   = document.getElementById('error-box');

let selectedFile = null;

// ---- Format file size ----
function fmtSize(bytes) {
  if (bytes < 1024)       return bytes + ' B';
  if (bytes < 1024*1024)  return (bytes/1024).toFixed(1) + ' KB';
  return (bytes/1024/1024).toFixed(2) + ' MB';
}

// ---- Format currency ----
function fmtRp(n) {
  return 'Rp ' + Number(n).toLocaleString('id-ID');
}

// ---- Set file ----
function setFile(file) {
  if (!file) return;
  if (!file.name.toLowerCase().endsWith('.xlsx')) {
    alert('❌ Hanya file .xlsx yang diizinkan.\n\nFile yang kamu pilih: ' + file.name);
    return;
  }
  selectedFile = file;
  fiName.textContent = file.name;
  fiSize.textContent = fmtSize(file.size);
  fileInfo.style.display = 'flex';
  dropZone.classList.add('file-selected');
  btnImport.disabled = false;
  btnText.textContent = 'Import ke Database';
}

// ---- Clear file ----
function clearFile() {
  selectedFile = null;
  fileInput.value = '';
  fileInfo.style.display = 'none';
  dropZone.classList.remove('file-selected');
  btnImport.disabled = true;
  btnText.textContent = 'Pilih file terlebih dahulu';
  resultsSec.style.display = 'none';
  progSection.style.display = 'none';
}

// ---- Drop Zone events ----
dropZone.addEventListener('click',    () => fileInput.click());
dropZone.addEventListener('keydown',  e => { if (e.key === 'Enter' || e.key === ' ') fileInput.click(); });
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave',() => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
  e.preventDefault();
  dropZone.classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file) setFile(file);
});
fileInput.addEventListener('change', () => { if (fileInput.files[0]) setFile(fileInput.files[0]); });
btnClear.addEventListener('click', e => { e.stopPropagation(); clearFile(); });

// ---- Animate progress bar ----
let progInterval = null;
function startFakeProgress() {
  let pct = 0;
  progBar.style.width = '0%';
  progSection.style.display = 'block';
  progInterval = setInterval(() => {
    pct = Math.min(pct + Math.random() * 8, 85);
    progBar.style.width = pct + '%';
    if (pct < 30)      progLabel.textContent = 'Membaca file Excel…';
    else if (pct < 60) progLabel.textContent = 'Memproses baris data…';
    else               progLabel.textContent = 'Mengupdate database…';
  }, 300);
}
function finishProgress() {
  clearInterval(progInterval);
  progBar.style.width = '100%';
  progLabel.classList.remove('loading-pulse');
  progLabel.textContent = '✅ Selesai!';
  setTimeout(() => { progSection.style.display = 'none'; progLabel.classList.add('loading-pulse'); }, 1800);
}
function failProgress(msg) {
  clearInterval(progInterval);
  progBar.style.background = 'var(--danger)';
  progBar.style.width = '100%';
  progLabel.classList.remove('loading-pulse');
  progLabel.textContent = '❌ ' + msg;
  setTimeout(() => {
    progBar.style.background = '';
    progLabel.classList.add('loading-pulse');
    progSection.style.display = 'none';
  }, 3000);
}

// ---- Status badge ----
function statusClass(s) {
  const l = (s||'').toLowerCase();
  if (l.includes('selesai'))    return 'status-selesai';
  if (l.includes('kirim'))      return 'status-dikirim';
  if (l.includes('batal') || l.includes('cancel')) return 'status-batal';
  return 'status-other';
}

// ---- Render results ----
function renderResults(data) {
  // Stats
  statGrid.innerHTML = `
    <div class="stat-chip s-total">
      <div class="sv">${data.total_orders.toLocaleString()}</div>
      <div class="sl">Total Order</div>
    </div>
    <div class="stat-chip s-inserted">
      <div class="sv">${data.inserted.toLocaleString()}</div>
      <div class="sl">Baru (INSERT)</div>
    </div>
    <div class="stat-chip s-updated">
      <div class="sv">${data.updated.toLocaleString()}</div>
      <div class="sl">Diperbarui (UPDATE)</div>
    </div>
    <div class="stat-chip s-skipped">
      <div class="sv">${data.total_rows.toLocaleString()}</div>
      <div class="sl">Baris Diproses</div>
    </div>
  `;

  // Errors
  if (data.errors && data.errors.length) {
    errorBox.style.display = 'block';
    errorBox.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i><strong>Error:</strong> ' +
      data.errors.map(e => '<div>' + escHtml(e) + '</div>').join('');
  } else {
    errorBox.style.display = 'none';
  }

  // Preview table
  previewTbody.innerHTML = '';
  if (data.preview && data.preview.length) {
    data.preview.forEach(row => {
      const tr = document.createElement('tr');
      const actionClass = row.action === 'INSERTED' ? 'inserted' : 'updated';
      const actionIcon  = row.action === 'INSERTED' ? 'bi-plus-circle' : 'bi-arrow-clockwise';
      tr.innerHTML = `
        <td><code style="font-size:.75rem;color:#93c5fd">${escHtml(row.order_id)}</code></td>
        <td><span class="${statusClass(row.status_pesanan)}">${escHtml(row.status_pesanan)}</span></td>
        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
            title="${escHtml(row.produk_sample)}">${escHtml(row.produk_sample)}</td>
        <td style="text-align:center">${row.items}</td>
        <td>${fmtRp(row.total_amount)}</td>
        <td><span class="badge-action ${actionClass}">
              <i class="bi ${actionIcon}"></i>${row.action}
            </span></td>
      `;
      previewTbody.appendChild(tr);
    });
    previewCard.style.display = 'block';
  } else {
    previewCard.style.display = 'none';
  }

  resultsSec.style.display = 'block';
  resultsSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function escHtml(str) {
  return String(str||'')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ---- Main: Import Click ----
btnImport.addEventListener('click', async () => {
  if (!selectedFile) return;

  // UI: loading state
  btnImport.disabled = true;
  btnIcon.className  = 'bi bi-hourglass-split';
  btnText.textContent = 'Mengimport…';
  resultsSec.style.display = 'none';
  startFakeProgress();

  const formData = new FormData();
  formData.append('excel_file', selectedFile);

  try {
    const resp = await fetch('process.php', {
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
      errorBox.style.display = 'block';
      errorBox.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>'
        + '<strong>Error:</strong> ' + escHtml(data.error || 'Terjadi kesalahan.')
        + (data.hint ? '<br><small>' + escHtml(data.hint) + '</small>' : '');
      resultsSec.style.display = 'block';
      statGrid.innerHTML = '';
      previewCard.style.display = 'none';
    }
  } catch (err) {
    failProgress('Request gagal: ' + err.message);
  } finally {
    btnImport.disabled = false;
    btnIcon.className  = 'bi bi-database-up';
    btnText.textContent = 'Import ke Database';
  }
});
</script>
</body>
</html>
