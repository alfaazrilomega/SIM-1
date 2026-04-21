<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

.pr-wrap {
  --fh: 'Sora', sans-serif;
  --fb: 'DM Sans', sans-serif;
  --fm: 'JetBrains Mono', monospace;
  --bg:      #f2f5fb;
  --surface: #ffffff;
  --brd:     #e5eaf5;
  --brd2:    #cdd5e8;
  --txt:     #0f1623;
  --muted:   #7a849e;
  --accent:  #3b6ff5;
  --green:   #16a34a;
  --amber:   #d97706;
  --red:     #dc2626;
  --r:       16px;
  --r-sm:    10px;
  font-family: var(--fb);
  color: var(--txt);
}

/* ── Header ── */
.pr-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px; margin-bottom:28px; }
.pr-title  { font-family:var(--fh); font-size:clamp(1.7rem,3vw,2.3rem); font-weight:800; color:#0f1623; letter-spacing:-1px; line-height:1.1; margin:0; }
.pr-subtitle { font-size:.82rem; color:var(--muted); margin-top:6px; }
.pr-btns   { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }

/* ── Stats ── */
.pr-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px; margin-bottom:28px; }
.pr-stat  { background:var(--surface); border:1px solid var(--brd); border-radius:var(--r); padding:20px 22px; transition:box-shadow .2s,transform .2s; }
.pr-stat:hover { box-shadow:0 6px 20px rgba(59,111,245,.1); transform:translateY(-2px); }
.stat-ico { width:40px; height:40px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:17px; margin-bottom:14px; }
.stat-val { font-family:var(--fh); font-size:1.55rem; font-weight:700; }
.stat-lbl { font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); font-weight:600; margin-top:5px; }
.ic-b { background:#eef3ff; color:#3b6ff5; }
.ic-g { background:#dcfce7; color:#16a34a; }
.ic-a { background:#fef3c7; color:#d97706; }
.ic-r { background:#fee2e2; color:#dc2626; }

/* ── Card / Table ── */
.pr-card { background:var(--surface); border:1px solid var(--brd); border-radius:var(--r); overflow:hidden; margin-bottom:20px; box-shadow:0 2px 10px rgba(15,22,35,.04); }
.pr-card-head { padding:15px 22px; border-bottom:1px solid var(--brd); display:flex; align-items:center; justify-content:space-between; gap:12px; background:#fafbfe; flex-wrap:wrap; }
.pr-card-title { display:flex; align-items:center; gap:8px; font-family:var(--fh); font-size:.88rem; font-weight:700; }
.pr-count { font-size:.71rem; font-family:var(--fm); color:var(--muted); background:#f0f2f9; padding:3px 10px; border-radius:20px; }
.pr-tbl-wrap { overflow-x:auto; }
.pr-tbl { width:100%; border-collapse:collapse; font-size:.81rem; }
.pr-tbl thead th { padding:11px 18px; text-align:left; background:#fafbfe; font-size:.66rem; text-transform:uppercase; letter-spacing:.08em; color:var(--muted); font-weight:600; border-bottom:1px solid var(--brd); white-space:nowrap; }
.pr-tbl thead th.tr { text-align:right; }
.pr-tbl tbody td { padding:12px 18px; border-bottom:1px solid #f0f2f9; vertical-align:middle; }
.pr-tbl tbody tr:last-child td { border-bottom:none; }
.pr-tbl tbody tr:hover td { background:#f6f9ff; }
.tr { text-align:right; }

/* ── Detail bahan expand ── */
.bahan-detail { display:none; background:#fafbfe; padding:12px 18px 12px 40px; border-bottom:1px solid #f0f2f9; }
.bahan-detail.open { display:block; }
.bahan-chip { display:inline-flex; align-items:center; gap:6px; background:#eef3ff; color:#3b6ff5; border:1px solid #c7d7fc; border-radius:20px; padding:3px 10px; font-size:.72rem; font-weight:600; margin:3px 4px 3px 0; }

/* ── Badge ── */
.badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:.68rem; font-weight:600; }
.b-ok  { background:#dcfce7; color:#15803d; }
.b-red { background:#fee2e2; color:#b91c1c; }

/* ── Buttons ── */
button.btn-primary-pr {
  background:var(--accent) !important; color:#fff !important; border:none !important;
  border-radius:10px !important; padding:9px 20px !important;
  font-size:.82rem; font-weight:700; cursor:pointer;
  display:inline-flex; align-items:center; gap:7px;
  font-family:var(--fb); transition:all .2s; white-space:nowrap;
  box-shadow:none !important;
}
button.btn-primary-pr:hover { background:#2859e0 !important; }
button.btn-primary-pr:active { transform:scale(.96); }

button.btn-act {
  background:#fff !important; border:1px solid var(--brd2) !important;
  border-radius:20px !important; padding:4px 12px !important;
  font-size:.72rem; font-weight:600; cursor:pointer;
  display:inline-flex; align-items:center; gap:5px;
  font-family:var(--fb); transition:all .18s;
  box-shadow:none !important; white-space:nowrap;
}
button.btn-act.blue  { color:var(--accent) !important; border-color:#c7d7fc !important; background:#eef3ff !important; }
button.btn-act.blue:hover  { background:#dde8ff !important; }
button.btn-act.red   { color:var(--red) !important; border-color:#fca5a5 !important; background:#fff5f5 !important; }
button.btn-act.red:hover   { background:#fee2e2 !important; }
button.btn-act.gray  { color:#64748b !important; border-color:#cbd5e1 !important; background:#f8fafc !important; }
button.btn-act.gray:hover  { background:#f1f5f9 !important; }

/* ── Toast ── */
#pr-toast {
  position:fixed; bottom:24px; right:24px; z-index:9999;
  padding:14px 18px; border-radius:14px; font-size:.83rem; font-weight:600;
  display:flex; align-items:center; gap:10px;
  transform:translateY(80px) scale(.94); opacity:0;
  transition:all .34s cubic-bezier(.34,1.45,.64,1);
  max-width:340px; pointer-events:none;
  box-shadow:0 10px 30px rgba(0,0,0,.12); font-family:var(--fb);
}
#pr-toast.show    { transform:translateY(0) scale(1); opacity:1; }
#pr-toast.success { background:#fff; border:1.5px solid #86efac; color:#166534; }
#pr-toast.error   { background:#fff; border:1.5px solid #fca5a5; color:#991b1b; }
.t-ico { width:28px; height:28px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:14px; }
#pr-toast.success .t-ico { background:#dcfce7; }
#pr-toast.error   .t-ico { background:#fee2e2; }

/* mono helpers */
.mono { font-family:var(--fm); }
.tg   { color:var(--green); font-family:var(--fm); font-weight:500; }
.tb   { color:var(--accent); font-family:var(--fm); font-weight:500; }
.tm   { color:var(--muted);  font-family:var(--fm); }
.code-tag { color:#3b6ff5; background:#eef3ff; padding:1px 7px; border-radius:5px; font-family:var(--fm); font-size:.74rem; }
.bb-empty { text-align:center; padding:48px 24px; color:var(--muted); }
.bb-empty i { font-size:2.2rem; opacity:.2; display:block; margin-bottom:12px; }

/* ── Overlay / Modal ── */
.pr-overlay {
  display:none; position:fixed; inset:0; z-index:600;
  background:rgba(15,22,35,.45); backdrop-filter:blur(4px);
  align-items:flex-start; justify-content:center;
  opacity:0; transition:opacity .22s ease;
  overflow-y:auto; padding:24px 16px;
}
.pr-overlay.visible       { display:flex; }
.pr-overlay.visible.shown { opacity:1; }
.pr-overlay.closing       { opacity:0; transition:opacity .18s ease-in; }

.pr-modal {
  background:#fff; border:1px solid #e5eaf5; border-radius:20px;
  padding:28px 30px; max-width:640px; width:100%;
  box-shadow:0 20px 60px rgba(15,22,35,.14);
  transform:scale(.93) translateY(14px); opacity:0;
  transition:transform .28s cubic-bezier(.34,1.38,.64,1), opacity .22s ease;
  margin:auto;
}
.pr-overlay.shown   .pr-modal { transform:scale(1) translateY(0); opacity:1; }
.pr-overlay.closing .pr-modal { transform:scale(.95) translateY(8px); opacity:0; transition:transform .16s ease-in, opacity .15s ease-in; }

/* Modal header */
.m-head  { display:flex; align-items:center; gap:13px; margin-bottom:22px; }
.m-ico   { width:44px; height:44px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:20px; }
.m-title { font-family:var(--fh); font-size:1.05rem; font-weight:700; color:#0f1623; margin:0; }
.m-sub   { font-size:.77rem; color:var(--muted); margin-top:2px; }

/* Form */
.f-row  { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
.f-row.full { grid-template-columns:1fr; }
.f-row.tri  { grid-template-columns:1fr 1fr 1fr; gap:10px; }
.f-grp  { display:flex; flex-direction:column; gap:6px; }
.f-grp label { font-size:.69rem; font-weight:600; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); }
.f-grp input,
.f-grp select,
.f-grp textarea {
  padding:10px 13px; border:1.5px solid #e5eaf5; border-radius:var(--r-sm);
  background:#f8faff; color:#0f1623; font-family:var(--fb); font-size:.85rem;
  outline:none; transition:border-color .18s, background .18s, box-shadow .18s; width:100%;
}
.f-grp input:focus,.f-grp select:focus,.f-grp textarea:focus {
  border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px rgba(59,111,245,.1);
}
.f-grp input.invalid,.f-grp select.invalid {
  border-color:#dc2626 !important; background:#fff5f5 !important; box-shadow:0 0 0 3px rgba(220,38,38,.1) !important;
}

/* Bahan list */
.bahan-list-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.bahan-list-title  { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); }
.bahan-items       { display:flex; flex-direction:column; gap:8px; margin-bottom:10px; max-height:260px; overflow-y:auto; }
.bahan-item        { display:grid; grid-template-columns:1fr 140px 34px; gap:8px; align-items:center; background:#f8faff; border:1px solid #e5eaf5; border-radius:var(--r-sm); padding:8px 10px; }
.bahan-item select,
.bahan-item input  { padding:7px 10px; border:1.5px solid #e5eaf5; border-radius:8px; background:#fff; color:#0f1623; font-family:var(--fb); font-size:.82rem; outline:none; }
.bahan-item select:focus,
.bahan-item input:focus { border-color:var(--accent); }
.btn-remove-bahan { width:30px; height:30px; border:none; background:#fee2e2; color:#dc2626; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.9rem; transition:background .15s; flex-shrink:0; }
.btn-remove-bahan:hover { background:#fca5a5; }
button.btn-add-bahan {
  width:100% !important; padding:8px !important; border:1.5px dashed #c7d7fc !important;
  background:#f0f4ff !important; color:var(--accent) !important; border-radius:var(--r-sm) !important;
  font-size:.8rem; font-weight:600; cursor:pointer; font-family:var(--fb);
  display:flex !important; align-items:center; justify-content:center; gap:6px;
  transition:background .15s; box-shadow:none !important;
}
button.btn-add-bahan:hover { background:#dde8ff !important; }

/* Summary box */
.produksi-summary {
  background:#f0f4ff; border:1.5px solid #c7d7fc; border-radius:var(--r-sm);
  padding:12px 16px; margin:14px 0; display:grid; grid-template-columns:1fr 1fr; gap:8px;
}
.ps-item { display:flex; flex-direction:column; gap:3px; }
.ps-label { font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted); font-weight:600; }
.ps-val   { font-size:.9rem; font-weight:700; color:var(--accent); }

/* Modal footer */
.m-footer { display:flex; gap:10px; margin-top:22px; }
button.btn-mc {
  flex:1; padding:11px; border-radius:var(--r-sm) !important;
  border:1.5px solid var(--brd2) !important; background:#f2f5fb !important; color:#4b5675 !important;
  font-size:.84rem; font-weight:600; cursor:pointer; font-family:var(--fb); transition:all .18s;
  box-shadow:none !important;
}
button.btn-mc:hover { background:#e5eaf5 !important; color:var(--txt) !important; }
button.btn-ms-pr {
  flex:2; padding:11px; border-radius:var(--r-sm) !important; border:none !important;
  color:#fff !important; font-size:.84rem; font-weight:700; cursor:pointer;
  font-family:var(--fb); display:inline-flex; align-items:center; justify-content:center; gap:7px;
  position:relative; overflow:hidden; transition:background .2s, transform .15s;
  box-shadow:none !important;
}
button.btn-ms-pr.green { background:#16a34a !important; }
button.btn-ms-pr.green:hover { background:#138638 !important; }
button.btn-ms-pr:active { transform:scale(.96); }
button.btn-ms-pr:disabled { opacity:.6; cursor:not-allowed; }

@keyframes shake { 0%,100%{transform:translateX(0)} 20%{transform:translateX(-5px)} 40%{transform:translateX(5px)} 60%{transform:translateX(-3px)} 80%{transform:translateX(3px)} }
.shake { animation:shake .32s ease; }
</style>

<!-- Toast -->
<div id="pr-toast"><div class="t-ico" id="pr-t-ico"></div><span id="pr-t-msg"></span></div>

<!-- ════ MODAL: CATAT PRODUKSI ════ -->
<div class="pr-overlay" id="modal-produksi">
  <div class="pr-modal">
    <div class="m-head">
      <div class="m-ico ic-g">🏭</div>
      <div>
        <p class="m-title">Catat Produksi</p>
        <p class="m-sub">Isi detail produksi — bahan otomatis berkurang, stok produk bertambah</p>
      </div>
    </div>

    <!-- Info produksi -->
    <div class="f-row full">
      <div class="f-grp"><label>Nama Produksi</label>
        <input id="pr-nama" placeholder="contoh: Produksi Rendang 10 kg — Batch #8">
      </div>
    </div>
    <div class="f-row">
      <div class="f-grp"><label>Tanggal Produksi</label>
        <input id="pr-tanggal" type="date">
      </div>
      <div class="f-grp"><label>Produk yang Dihasilkan</label>
        <select id="pr-produk"><option value="">— Pilih produk —</option></select>
      </div>
    </div>
    <div class="f-row">
      <div class="f-grp"><label>Jumlah Hasil (kemasan)</label>
        <input id="pr-jumlah" type="number" min="1" placeholder="contoh: 20">
      </div>
      <div class="f-grp"><label>Catatan</label>
        <input id="pr-catatan" placeholder="Opsional…">
      </div>
    </div>

    <!-- Summary -->
    <div class="produksi-summary" id="pr-summary" style="display:none">
      <div class="ps-item">
        <div class="ps-label">Produk</div>
        <div class="ps-val" id="ps-produk-nama">—</div>
      </div>
      <div class="ps-item" style="text-align:right">
        <div class="ps-label">Stok Saat Ini</div>
        <div class="ps-val" id="ps-stok-kini">—</div>
      </div>
      <div class="ps-item">
        <div class="ps-label">Hasil Produksi</div>
        <div class="ps-val" id="ps-hasil" style="color:var(--green)">— kemasan</div>
      </div>
      <div class="ps-item" style="text-align:right">
        <div class="ps-label">Stok Setelah</div>
        <div class="ps-val" id="ps-stok-after" style="color:var(--green)">—</div>
      </div>
    </div>

    <!-- Daftar Bahan -->
    <div style="margin-bottom:14px">
      <div class="bahan-list-header">
        <div class="bahan-list-title"><i class="bi bi-basket2-fill" style="color:var(--accent)"></i> Bahan Baku yang Digunakan</div>
        <span id="bahan-stok-warn" style="font-size:.72rem;color:var(--amber);display:none"><i class="bi bi-exclamation-triangle-fill"></i> Periksa stok bahan</span>
      </div>
      <div class="bahan-items" id="bahan-items"></div>
      <button class="btn-add-bahan" id="btn-add-bahan"><i class="bi bi-plus-circle"></i> Tambah Bahan</button>
    </div>

    <div class="m-footer">
      <button class="btn-mc" id="btn-batal-produksi">Batal</button>
      <button class="btn-ms-pr green" id="btn-simpan-produksi">
        <i class="bi bi-check-circle-fill"></i> Jalankan Produksi
      </button>
    </div>
  </div>
</div>

<!-- ════ KONTEN ════ -->
<div class="pr-wrap">
  <div class="pr-header">
    <div>
      <h1 class="pr-title">🏭 Produksi</h1>
      <p class="pr-subtitle">Catat batch produksi — stok bahan otomatis berkurang, stok produk otomatis bertambah</p>
    </div>
    <div class="pr-btns">
      <button class="btn-primary-pr" id="btn-open-produksi"><i class="bi bi-plus-lg"></i> Catat Produksi</button>
    </div>
  </div>

  <!-- Stats -->
  <div class="pr-stats">
    <div class="pr-stat"><div class="stat-ico ic-b"><i class="bi bi-clipboard2-check-fill"></i></div><div class="stat-val tb" id="s-total">—</div><div class="stat-lbl">Total Produksi</div></div>
    <div class="pr-stat"><div class="stat-ico ic-g"><i class="bi bi-check-circle-fill"></i></div><div class="stat-val tg" id="s-selesai">—</div><div class="stat-lbl">Selesai</div></div>
    <div class="pr-stat"><div class="stat-ico ic-a"><i class="bi bi-calendar-check-fill"></i></div><div class="stat-val ta" id="s-bulan">—</div><div class="stat-lbl">Bulan Ini</div></div>
    <div class="pr-stat"><div class="stat-ico ic-r"><i class="bi bi-x-circle-fill"></i></div><div class="stat-val" style="color:var(--red)" id="s-batal">—</div><div class="stat-lbl">Dibatalkan</div></div>
  </div>

  <!-- Table -->
  <div class="pr-card">
    <div class="pr-card-head">
      <div class="pr-card-title"><i class="bi bi-clipboard2-data-fill" style="color:var(--accent)"></i> Riwayat Produksi</div>
      <span class="pr-count" id="pr-count-label">memuat…</span>
    </div>
    <div class="pr-tbl-wrap">
      <table class="pr-tbl">
        <thead><tr>
          <th>#</th>
          <th>Tanggal</th>
          <th>Nama Produksi</th>
          <th>Produk</th>
          <th class="tr">Hasil</th>
          <th>Bahan Digunakan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr></thead>
        <tbody id="tbody-produksi"></tbody>
      </table>
    </div>
  </div>
</div>

<script>
const PR_BASE = '<?= base_url() ?>';
let allBahan  = [];
let allProduk = [];
let allProduksi = [];
let prTT;

const esc  = s => String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
const fmtD = s => s ? new Date(s).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '—';
const fmtN = n => (parseFloat(n)||0).toLocaleString('id-ID');
const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

/* ── Toast ── */
function prToast(msg, type='success'){
  const el = document.getElementById('pr-toast');
  document.getElementById('pr-t-ico').textContent = type === 'success' ? '✓' : '✕';
  document.getElementById('pr-t-msg').textContent = msg;
  el.className = 'show ' + type;
  clearTimeout(prTT);
  prTT = setTimeout(() => { el.className = ''; }, 4000);
}

/* ── Modal open/close ── */
function openModal(id){
  const el = document.getElementById(id);
  el.classList.add('visible');
  requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('shown')));
}
function closeModal(id){
  const el = document.getElementById(id);
  el.classList.add('closing');
  el.classList.remove('shown');
  setTimeout(() => el.classList.remove('visible','closing'), 210);
}
document.querySelectorAll('.pr-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target === el) closeModal(el.id); });
});
document.getElementById('btn-batal-produksi').addEventListener('click', () => closeModal('modal-produksi'));

/* ── Bahan item row ── */
function buatBahanRow(idBahan = '', jumlah = '') {
  const div = document.createElement('div');
  div.className = 'bahan-item';
  div.innerHTML = `
    <select class="bahan-select">
      <option value="">— Pilih bahan —</option>
      ${allBahan.map(b => `<option value="${b.id}" data-stok="${b.stok}" data-satuan="${esc(b.satuan)}"
        ${b.id == idBahan ? 'selected' : ''}>${esc(b.nama)} (stok: ${fmtN(b.stok)} ${esc(b.satuan)})</option>`).join('')}
    </select>
    <input type="number" class="bahan-jumlah" step="0.001" min="0.001" placeholder="Jumlah" value="${jumlah}">
    <button class="btn-remove-bahan" title="Hapus bahan"><i class="bi bi-x"></i></button>
  `;
  div.querySelector('.btn-remove-bahan').addEventListener('click', () => {
    div.remove();
    updateSatuan();
    cekStokWarn();
  });
  div.querySelector('.bahan-jumlah').addEventListener('input', cekStokWarn);
  div.querySelector('.bahan-select').addEventListener('change', function(){
    updateSatuan();
    cekStokWarn();
  });
  return div;
}

function updateSatuan(){
  document.querySelectorAll('#bahan-items .bahan-item').forEach(row => {
    const sel     = row.querySelector('.bahan-select');
    const opt     = sel.options[sel.selectedIndex];
    const satuan  = opt?.dataset?.satuan ?? '';
    const inp     = row.querySelector('.bahan-jumlah');
    inp.placeholder = satuan ? `Jumlah (${satuan})` : 'Jumlah';
  });
}

function cekStokWarn(){
  let warn = false;
  document.querySelectorAll('#bahan-items .bahan-item').forEach(row => {
    const sel    = row.querySelector('.bahan-select');
    const opt    = sel.options[sel.selectedIndex];
    const stok   = parseFloat(opt?.dataset?.stok ?? 0);
    const jumlah = parseFloat(row.querySelector('.bahan-jumlah').value) || 0;
    if (jumlah > 0 && jumlah > stok) warn = true;
  });
  document.getElementById('bahan-stok-warn').style.display = warn ? '' : 'none';
}

function getBahanList(){
  return Array.from(document.querySelectorAll('#bahan-items .bahan-item')).map(row => ({
    id_bahan: row.querySelector('.bahan-select').value,
    jumlah:   row.querySelector('.bahan-jumlah').value,
  }));
}

/* ── Summary realtime ── */
function updateSummary(){
  const selProduk = document.getElementById('pr-produk');
  const idProduk  = selProduk.value;
  const jumlah    = parseInt(document.getElementById('pr-jumlah').value) || 0;
  const produk    = allProduk.find(p => p.id == idProduk);

  const summEl = document.getElementById('pr-summary');
  if (!produk || !jumlah) { summEl.style.display = 'none'; return; }

  const stokKini = parseInt(produk.stok) || 0;
  document.getElementById('ps-produk-nama').textContent = produk.nama;
  document.getElementById('ps-stok-kini').textContent   = fmtN(stokKini) + ' kemasan';
  document.getElementById('ps-hasil').textContent       = '+' + fmtN(jumlah) + ' kemasan';
  document.getElementById('ps-stok-after').textContent  = fmtN(stokKini + jumlah) + ' kemasan';
  summEl.style.display = 'grid';
}

/* ── Open modal produksi ── */
function openModalProduksi(){
  // Reset form
  document.getElementById('pr-nama').value    = '';
  document.getElementById('pr-tanggal').value = new Date().toISOString().split('T')[0];
  document.getElementById('pr-produk').value  = '';
  document.getElementById('pr-jumlah').value  = '';
  document.getElementById('pr-catatan').value = '';
  document.getElementById('pr-summary').style.display = 'none';
  document.getElementById('bahan-stok-warn').style.display = 'none';

  // Populate produk dropdown
  const sel = document.getElementById('pr-produk');
  sel.innerHTML = '<option value="">— Pilih produk hasil produksi —</option>' +
    allProduk.map(p => `<option value="${p.id}" data-stok="${p.stok}">${esc(p.nama)} (stok: ${fmtN(p.stok)} kem)</option>`).join('');

  // Reset bahan
  const container = document.getElementById('bahan-items');
  container.innerHTML = '';
  container.appendChild(buatBahanRow());

  openModal('modal-produksi');
  setTimeout(() => document.getElementById('pr-nama').focus(), 60);
}

// Event untuk update summary realtime
document.getElementById('pr-produk').addEventListener('change', updateSummary);
document.getElementById('pr-jumlah').addEventListener('input',  updateSummary);

document.getElementById('btn-open-produksi').addEventListener('click', openModalProduksi);
document.getElementById('btn-add-bahan').addEventListener('click', () => {
  document.getElementById('bahan-items').appendChild(buatBahanRow());
});

/* ── POST helper ── */
async function post(url, body){
  const r = await fetch(PR_BASE + url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrf(),
    },
    body: JSON.stringify(body),
  });
  return r.json();
}

/* ── Submit produksi ── */
document.getElementById('btn-simpan-produksi').addEventListener('click', async function(){
  const nama    = document.getElementById('pr-nama').value.trim();
  const tanggal = document.getElementById('pr-tanggal').value;
  const idProduk= document.getElementById('pr-produk').value;
  const jumlah  = document.getElementById('pr-jumlah').value;
  const catatan = document.getElementById('pr-catatan').value;
  const bahanList = getBahanList();

  // Validasi
  if (!nama)    { shake('pr-nama');    return prToast('Nama produksi wajib diisi.','error'); }
  if (!idProduk){ shake('pr-produk');  return prToast('Pilih produk yang dihasilkan.','error'); }
  if (!jumlah || parseInt(jumlah) < 1) { shake('pr-jumlah'); return prToast('Jumlah hasil harus > 0.','error'); }
  if (!bahanList.length || bahanList.some(b => !b.id_bahan)) return prToast('Lengkapi pilihan bahan baku.','error');
  if (bahanList.some(b => !b.jumlah || parseFloat(b.jumlah) <= 0)) return prToast('Jumlah setiap bahan harus > 0.','error');

  this.disabled = true;
  this.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses…';

  try {
    const res = await post('/produksi/simpan', { nama_produksi:nama, tanggal, id_produk:idProduk, jumlah_hasil:jumlah, catatan, bahan:bahanList });
    if (res.success) {
      closeModal('modal-produksi');
      prToast(res.message);
      loadData();
    } else {
      prToast(res.error, 'error');
    }
  } catch(e) {
    prToast('Koneksi gagal.', 'error');
  }

  this.disabled = false;
  this.innerHTML = '<i class="bi bi-check-circle-fill"></i> Jalankan Produksi';
});

function shake(id){
  const el = document.getElementById(id);
  el.classList.add('shake','invalid');
  setTimeout(() => el.classList.remove('shake','invalid'), 500);
}

/* ── Batalkan produksi ── */
async function batalkanProduksi(id, nama){
  if (!confirm(`Batalkan produksi "${nama}"?\nStok bahan akan dikembalikan dan stok produk dikurangi.`)) return;
  const res = await post('/produksi/batalkan', { id });
  if (res.success) { prToast(res.message); loadData(); }
  else prToast(res.error, 'error');
}

/* ── Toggle detail bahan ── */
function toggleDetail(id){
  const el = document.getElementById('detail-' + id);
  if (el) el.classList.toggle('open');
}

/* ── Render ── */
function renderProduksi(list){
  allProduksi = list;
  document.getElementById('pr-count-label').textContent = list.length + ' produksi';
  const tbody = document.getElementById('tbody-produksi');

  if (!list.length) {
    tbody.innerHTML = `<tr><td colspan="8"><div class="bb-empty"><i class="bi bi-clipboard2-x"></i><p>Belum ada catatan produksi.</p></div></td></tr>`;
    return;
  }

  tbody.innerHTML = list.map((r, i) => {
    const selesai = r.status === 'selesai';
    const bahan   = r.bahan || [];
    const bahanChips = bahan.map(b =>
      `<span class="bahan-chip"><i class="bi bi-dot"></i>${esc(b.nama_bahan)} <strong>${fmtN(b.jumlah)} ${esc(b.satuan)}</strong></span>`
    ).join('');

    return `
      <tr>
        <td class="tm" style="font-size:.74rem">${i + 1}</td>
        <td class="tm" style="font-size:.78rem">${fmtD(r.tanggal)}</td>
        <td style="font-weight:600;color:#0f1623;max-width:200px">
          ${esc(r.nama_produksi)}
          ${r.catatan ? `<div style="font-size:.72rem;color:var(--muted);margin-top:2px">${esc(r.catatan)}</div>` : ''}
        </td>
        <td>
          <span class="code-tag">${esc(r.kode_produk||'')}</span>
          <span style="font-size:.8rem;color:#0f1623;margin-left:4px">${esc(r.nama_produk||'—')}</span>
        </td>
        <td class="tr tg" style="font-weight:700">+${fmtN(r.jumlah_hasil)} kem</td>
        <td>
          ${bahan.length
            ? `<button class="btn-act blue" onclick="toggleDetail(${r.id})"><i class="bi bi-layers-fill"></i> ${bahan.length} bahan</button>`
            : '<span class="tm" style="font-size:.74rem">—</span>'
          }
        </td>
        <td><span class="badge ${selesai ? 'b-ok' : 'b-red'}">${selesai ? 'Selesai' : 'Dibatalkan'}</span></td>
        <td>
          ${selesai
            ? `<button class="btn-act red" onclick='batalkanProduksi(${r.id},"${esc(r.nama_produksi)}")'><i class="bi bi-x-circle"></i> Batal</button>`
            : '<span class="tm" style="font-size:.74rem">—</span>'
          }
        </td>
      </tr>
      ${bahan.length ? `
      <tr>
        <td colspan="8" style="padding:0">
          <div class="bahan-detail" id="detail-${r.id}">
            <strong style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em">Bahan yang digunakan:</strong>
            <div style="margin-top:6px">${bahanChips}</div>
          </div>
        </td>
      </tr>` : ''}
    `;
  }).join('');
}

/* ── Load data ── */
async function loadData(){
  try {
    const r = await fetch(PR_BASE + '/produksi/data', { headers:{'X-Requested-With':'XMLHttpRequest'} });
    const d = await r.json();
    if (!d.success) throw new Error(d.error || 'Gagal memuat data.');

    allBahan  = d.bahan  || [];
    allProduk = d.produk || [];

    // Stats
    document.getElementById('s-total').textContent   = d.summary.total;
    document.getElementById('s-selesai').textContent = d.summary.selesai;
    document.getElementById('s-bulan').textContent   = d.summary.bulan_ini;
    document.getElementById('s-batal').textContent   = d.summary.dibatalkan;

    renderProduksi(d.list || []);

  } catch(e) {
    prToast('Gagal memuat: ' + e.message, 'error');
  }
}

loadData();
</script>

<?= $this->endSection() ?>