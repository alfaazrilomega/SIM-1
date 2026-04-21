<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');


.bb-wrap {
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
  --teal:    #0891b2;
  --r:       16px;
  --r-sm:    10px;
  font-family: var(--fb);
  color: var(--txt);
}

.bb-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  flex-wrap: wrap; gap: 16px; margin-bottom: 28px;
}
.bb-title {
  font-family: var(--fh); font-size: clamp(1.7rem,3vw,2.3rem);
  font-weight: 800; color: #0f1623; letter-spacing: -1px; line-height: 1.1; margin: 0;
}
.bb-subtitle { font-size: .82rem; color: var(--muted); margin-top: 6px; }
.bb-btns     { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

.bb-stats {
  display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
  gap: 14px; margin-bottom: 28px;
}
.bb-stat {
  background: var(--surface); border: 1px solid var(--brd);
  border-radius: var(--r); padding: 20px 22px;
  transition: box-shadow .2s, transform .2s;
}
.bb-stat:hover { box-shadow: 0 6px 20px rgba(59,111,245,.1); transform: translateY(-2px); }
.stat-ico {
  width: 40px; height: 40px; border-radius: 11px;
  display: flex; align-items: center; justify-content: center;
  font-size: 17px; margin-bottom: 14px;
}
.stat-val   { font-family: var(--fh); font-size: 1.55rem; font-weight: 700; color: var(--txt); }
.stat-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 600; margin-top: 5px; }
.ic-b { background: #eef3ff; color: #3b6ff5; }
.ic-g { background: #dcfce7; color: #16a34a; }
.ic-a { background: #fef3c7; color: #d97706; }
.ic-r { background: #fee2e2; color: #dc2626; }

.bb-tabs { display: flex; border-bottom: 2px solid var(--brd); margin-bottom: 20px; gap: 2px; }
.bb-tab  {
  padding: 10px 20px; background: none; border: none;
  font-family: var(--fb); font-size: .84rem; font-weight: 600;
  color: var(--muted); cursor: pointer;
  border-bottom: 2px solid transparent; margin-bottom: -2px;
  transition: color .18s, border-color .18s;
  display: inline-flex; align-items: center; gap: 6px;
}
.bb-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
.bb-tab:hover:not(.active) { color: var(--txt); }

.bb-card {
  background: var(--surface); border: 1px solid var(--brd);
  border-radius: var(--r); overflow: hidden; margin-bottom: 20px;
  box-shadow: 0 2px 10px rgba(15,22,35,.04);
}
.bb-card-head {
  padding: 15px 22px; border-bottom: 1px solid var(--brd);
  display: flex; align-items: center; justify-content: space-between; gap: 12px;
  background: #fafbfe;
}
.bb-card-title {
  display: flex; align-items: center; gap: 8px;
  font-family: var(--fh); font-size: .88rem; font-weight: 700; color: var(--txt);
}
.bb-count {
  font-size: .71rem; font-family: var(--fm); color: var(--muted);
  background: #f0f2f9; padding: 3px 10px; border-radius: 20px;
}
.bb-tbl-wrap { overflow-x: auto; }
.bb-tbl { width: 100%; border-collapse: collapse; font-size: .81rem; }
.bb-tbl thead th {
  padding: 11px 18px; text-align: left; background: #fafbfe;
  font-size: .66rem; text-transform: uppercase; letter-spacing: .08em;
  color: var(--muted); font-weight: 600; white-space: nowrap;
  border-bottom: 1px solid var(--brd);
}
.bb-tbl thead th.tr { text-align: right; }
.bb-tbl tbody td {
  padding: 13px 18px; border-bottom: 1px solid #f0f2f9;
  white-space: nowrap; vertical-align: middle;
}
.bb-tbl tbody tr:last-child td { border-bottom: none; }
.bb-tbl tbody tr:hover td { background: #f6f9ff; }
.tr { text-align: right; }

.badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: .68rem; font-weight: 600; }
.b-ok  { background: #dcfce7; color: #15803d; }
.b-w   { background: #fef3c7; color: #b45309; }
.b-r   { background: #fee2e2; color: #b91c1c; }

.stok-bar { display: flex; align-items: center; gap: 8px; justify-content: flex-end; }
.stok-tr  { width: 52px; height: 5px; background: #e8ecf4; border-radius: 99px; }
.stok-f   { height: 100%; border-radius: 99px; }

button.btn-p, button.btn-g,
button.btn-mc, button.btn-ms,
button.btn-o {
  border-radius: 10px !important;
}
button.btn-o  { border-radius: 20px !important; }
button.btn-ms { border-radius: 10px !important; }

.btn-p {
  background: var(--accent); color: #ffffff; border: none;
  border-radius: var(--r-sm); padding: 9px 18px;
  font-size: .81rem; font-weight: 600; cursor: pointer;
  display: inline-flex; align-items: center; gap: 7px;
  font-family: var(--fb); transition: all .2s; white-space: nowrap;
}
.btn-p:hover  { background: #2859e0; box-shadow: 0 4px 14px rgba(59,111,245,.28); }
.btn-p:active { transform: scale(.96); }
.btn-g {
  background: var(--green); color: #ffffff; border: none;
  border-radius: var(--r-sm); padding: 9px 18px;
  font-size: .81rem; font-weight: 600; cursor: pointer;
  display: inline-flex; align-items: center; gap: 7px;
  font-family: var(--fb); transition: all .2s; white-space: nowrap;
}
.btn-g:hover  { background: #138638; }
.btn-g:active { transform: scale(.96); }

.bb-wrap button.btn-o,
button.btn-o,
.bb-wrap .btn-o {
  border-radius: 20px !important;
}
.btn-o {
  background: #fff !important; color: var(--txt); border: 1px solid var(--brd2) !important;
  border-radius: 20px !important; padding: 5px 13px !important;
  font-size: .73rem; font-weight: 600; cursor: pointer;
  display: inline-flex !important; align-items: center; gap: 5px;
  font-family: var(--fb); transition: all .18s;
  white-space: nowrap; line-height: 1.4;
  box-shadow: none !important;
  text-transform: none !important;
  letter-spacing: 0 !important;
}
.btn-o:hover        { border-color: var(--accent) !important; color: var(--accent) !important; background: #f0f4ff !important; }
.btn-o:active       { transform: scale(.94); }
.btn-o.ob           { color: var(--accent) !important; border-color: #c7d7fc !important; background: #eef3ff !important; }
.btn-o.ob:hover     { background: #dde8ff !important; border-color: var(--accent) !important; }
.btn-o.or           { color: var(--red) !important; border-color: #fca5a5 !important; background: #fff5f5 !important; }
.btn-o.or:hover     { background: #fee2e2 !important; border-color: var(--red) !important; }
.btn-o.oe           { color: #64748b !important; border-color: #cbd5e1 !important; background: #f8fafc !important; }
.btn-o.oe:hover     { background: #f1f5f9 !important; border-color: #94a3b8 !important; color: #334155 !important; }

.mono { font-family: var(--fm); }
.tg   { color: var(--green); font-family: var(--fm); font-weight: 500; }
.tb   { color: var(--accent); font-family: var(--fm); font-weight: 500; }
.ta   { color: var(--amber);  font-family: var(--fm); font-weight: 500; }
.tr2  { color: var(--red);    font-family: var(--fm); font-weight: 500; }
.tm   { color: var(--muted);  font-family: var(--fm); }
.code { color: #3b6ff5; background: #eef3ff; padding: 1px 7px; border-radius: 5px; font-family: var(--fm); font-size: .74rem; }

.bb-overlay {
  display: none; position: fixed; inset: 0; z-index: 600;
  background: rgba(15,22,35,.42); backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
  opacity: 0;
  transition: opacity .22s ease;
}
.bb-overlay.visible         { display: flex; }
.bb-overlay.visible.shown   { opacity: 1; }
.bb-overlay.closing         { opacity: 0; transition: opacity .18s ease-in; }

.bb-modal {
  background: #ffffff; border: 1px solid #e5eaf5;
  border-radius: 20px; padding: 28px 30px;
  max-width: 460px; width: 94%;
  box-shadow: 0 20px 60px rgba(15,22,35,.14);
  transform: scale(.93) translateY(14px);
  opacity: 0;
  transition: transform .28s cubic-bezier(.34,1.38,.64,1), opacity .22s ease;
}
.bb-overlay.shown   .bb-modal { transform: scale(1) translateY(0); opacity: 1; }
.bb-overlay.closing .bb-modal {
  transform: scale(.95) translateY(8px);
  opacity: 0;
  transition: transform .16s ease-in, opacity .15s ease-in;
}

.m-head { display: flex; align-items: center; gap: 13px; margin-bottom: 22px; }
.m-ico  {
  width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 18px;
}
.m-title { font-family: var(--fh); font-size: 1.05rem; font-weight: 700; color: #0f1623; margin: 0; }
.m-sub   { font-size: .77rem; color: var(--muted); margin-top: 2px; }

.f-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
.f-row.full { grid-template-columns: 1fr; }
.f-grp  { display: flex; flex-direction: column; gap: 6px; }
.f-grp label {
  font-size: .69rem; font-weight: 600; text-transform: uppercase;
  letter-spacing: .07em; color: var(--muted);
}
.f-grp input,
.f-grp select,
.f-grp textarea {
  padding: 10px 13px; border: 1.5px solid #e5eaf5;
  border-radius: var(--r-sm); background: #f8faff;
  color: #0f1623; font-family: var(--fb); font-size: .85rem;
  outline: none; transition: border-color .18s, background .18s, box-shadow .18s;
}
.f-grp input:focus,
.f-grp select:focus,
.f-grp textarea:focus {
  border-color: var(--accent); background: #fff;
  box-shadow: 0 0 0 3px rgba(59,111,245,.1);
}
.f-grp input[readonly] {
  background: #eef3ff; color: var(--accent); font-weight: 600;
  cursor: default; border-color: #c7d7fc;
}
.f-grp textarea { resize: vertical; min-height: 60px; }
.f-grp input.invalid,
.f-grp select.invalid {
  border-color: #dc2626 !important;
  background: #fff5f5 !important;
  box-shadow: 0 0 0 3px rgba(220,38,38,.1) !important;
}

@keyframes shake {
  0%,100% { transform: translateX(0); }
  20%      { transform: translateX(-5px); }
  40%      { transform: translateX(5px); }
  60%      { transform: translateX(-3px); }
  80%      { transform: translateX(3px); }
}
.shake { animation: shake .32s ease; }

.m-footer { display: flex; gap: 10px; margin-top: 22px; }
.btn-mc {
  flex: 1; padding: 11px; border-radius: var(--r-sm);
  border: 1.5px solid var(--brd2); background: #f2f5fb !important; color: #4b5675 !important;
  font-size: .84rem; font-weight: 600; cursor: pointer; font-family: var(--fb);
  transition: all .18s;
}
.btn-mc:hover  { background: #e5eaf5 !important; color: var(--txt) !important; border-color: #aab4cc; }
.btn-mc:active { transform: scale(.96); }

.btn-ms {
  flex: 2; padding: 11px; border-radius: var(--r-sm); border: none;
  color: #ffffff !important; font-size: .84rem; font-weight: 700; cursor: pointer;
  font-family: var(--fb);
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  position: relative; overflow: hidden;
  transition: background .2s, box-shadow .2s, transform .15s, opacity .2s;
}
.btn-ms:active:not(:disabled)  { transform: scale(.96); }
.btn-ms:disabled               { cursor: not-allowed; opacity: .72; }

.btn-ms .btn-label {
  display: inline-flex; align-items: center; gap: 7px;
  color: #ffffff !important;
  transition: opacity .15s, transform .15s;
}
.btn-ms .btn-spin {
  display: none; position: absolute;
  width: 19px; height: 19px; border-radius: 50%;
  border: 2.5px solid rgba(255,255,255,.3);
  border-top-color: #fff;
  animation: bspin .5s linear infinite;
}

.btn-ms.loading .btn-label { opacity: 0; transform: scale(.8); }
.btn-ms.loading .btn-spin  { display: block; }

.btn-ms.done .btn-label { opacity: 0; transform: scale(.8); }
.btn-ms.done::after {
  content: '✓';
  position: absolute;
  font-size: 1.15rem; font-weight: 700; color: #ffffff;
  animation: tickPop .28s cubic-bezier(.34,1.6,.64,1) forwards;
}
@keyframes tickPop {
  from { transform: scale(.3) rotate(-20deg); opacity: 0; }
  to   { transform: scale(1)  rotate(0deg);   opacity: 1; }
}

.ripple {
  position: absolute; border-radius: 50%;
  background: rgba(255,255,255,.28);
  transform: scale(0);
  animation: rippleAnim .55s ease-out forwards;
  pointer-events: none;
}
@keyframes rippleAnim { to { transform: scale(4.5); opacity: 0; } }

.btn-ms.blue  { background: #3b6ff5 !important; }
.btn-ms.blue:not(:disabled):hover  { background: #2859e0 !important; box-shadow: 0 4px 14px rgba(59,111,245,.3); }
.btn-ms.green { background: #16a34a !important; }
.btn-ms.green:not(:disabled):hover { background: #138638 !important; box-shadow: 0 4px 14px rgba(22,163,74,.28); }

#bb-toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  padding: 14px 18px; border-radius: 14px; font-size: .83rem; font-weight: 600;
  display: flex; align-items: center; gap: 10px;
  transform: translateY(80px) scale(.94); opacity: 0;
  transition: all .34s cubic-bezier(.34,1.45,.64,1);
  max-width: 320px; pointer-events: none;
  box-shadow: 0 10px 30px rgba(0,0,0,.12);
  font-family: var(--fb);
}
#bb-toast.show    { transform: translateY(0) scale(1); opacity: 1; }
#bb-toast.success { background: #fff; border: 1.5px solid #86efac; color: #166534; }
#bb-toast.error   { background: #fff; border: 1.5px solid #fca5a5; color: #991b1b; }
.t-ico {
  width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 14px;
}
#bb-toast.success .t-ico { background: #dcfce7; }
#bb-toast.error   .t-ico { background: #fee2e2; }

.bb-empty { text-align: center; padding: 48px 24px; color: var(--muted); }
.bb-empty i { font-size: 2.2rem; opacity: .2; display: block; margin-bottom: 12px; }
</style>

<!-- Toast -->
<div id="bb-toast"><div class="t-ico" id="t-ico"></div><span id="t-msg"></span></div>

<!-- ════ MODAL: TAMBAH / EDIT BAHAN ════ -->
<div class="bb-overlay" id="modal-bahan">
  <div class="bb-modal">
    <div class="m-head">
      <div class="m-ico ic-b" id="m-bahan-ico" style="font-size:20px">✦</div>
      <div>
        <p class="m-title" id="m-bahan-title">Tambah Bahan Baku</p>
        <p class="m-sub">Isi data master bahan baku</p>
      </div>
    </div>
    <input type="hidden" id="bahan-id">
    <div class="f-row">
      <div class="f-grp"><label>Kode Bahan</label><input id="bahan-kode" placeholder="BB-011"></div>
      <div class="f-grp">
        <label>Satuan</label>
        <select id="bahan-satuan">
          <option>kg</option><option>gram</option><option>liter</option>
          <option>ml</option><option>buah</option><option>ikat</option>
        </select>
      </div>
    </div>
    <div class="f-row full"><div class="f-grp"><label>Nama Bahan</label><input id="bahan-nama" placeholder="Bawang Merah"></div></div>
    <div class="f-row full"><div class="f-grp"><label>Harga Beli / Satuan (Rp)</label><input id="bahan-harga" type="number" placeholder="28000"></div></div>
    <div class="f-row full"><div class="f-grp"><label>Keterangan</label><textarea id="bahan-ket" placeholder="Opsional…"></textarea></div></div>
    <div class="m-footer">
      <button class="btn-mc" id="btn-batal-bahan">Batal</button>
      <button class="btn-ms blue" id="btn-simpan-bahan">
        <span class="btn-label"><i class="bi bi-check-lg"></i> Simpan Bahan</span>
        <span class="btn-spin"></span>
      </button>
    </div>
  </div>
</div>

<!-- ════ MODAL: CATAT BELI ════ -->
<div class="bb-overlay" id="modal-beli">
  <div class="bb-modal">
    <div class="m-head">
      <div class="m-ico ic-g"><i class="bi bi-cart-plus-fill" style="color:#16a34a;font-size:18px"></i></div>
      <div>
        <p class="m-title">Catat Pembelian</p>
        <p class="m-sub">Tambah stok dari pembelian baru</p>
      </div>
    </div>
    <div class="f-row full"><div class="f-grp"><label>Bahan</label><select id="beli-bahan-select"></select></div></div>
    <div class="f-row">
      <div class="f-grp"><label>Tanggal</label><input id="beli-tanggal" type="date"></div>
      <div class="f-grp"><label>Supplier</label><input id="beli-supplier" placeholder="Nama supplier"></div>
    </div>
    <div class="f-row">
      <div class="f-grp"><label>Jumlah</label><input id="beli-jumlah" type="number" step="0.001" placeholder="5"></div>
      <div class="f-grp"><label>Harga / Satuan (Rp)</label><input id="beli-harga" type="number" placeholder="28000"></div>
    </div>
    <div class="f-row full"><div class="f-grp"><label>Total Harga</label><input id="beli-total" readonly></div></div>
    <div class="f-row full"><div class="f-grp"><label>Catatan</label><textarea id="beli-catatan" placeholder="Opsional…"></textarea></div></div>
    <div class="m-footer">
      <button class="btn-mc" id="btn-batal-beli">Batal</button>
      <button class="btn-ms green" id="btn-submit-beli">
        <span class="btn-label"><i class="bi bi-cart-check-fill"></i> Catat Pembelian</span>
        <span class="btn-spin"></span>
      </button>
    </div>
  </div>
</div>

<!-- ════════════════ KONTEN ════════════════ -->
<div class="bb-wrap">

  <div class="bb-header">
    <div>
      <h1 class="bb-title">Manajemen Bahan Baku</h1>
      <p class="bb-subtitle">Pembelian, penggunaan, dan stok seluruh bahan baku produksi bumbu</p>
    </div>
    <div class="bb-btns">
      <button class="btn-p" id="btn-open-bahan"><i class="bi bi-plus-lg"></i> Tambah Bahan</button>
      <button class="btn-g" id="btn-open-beli"><i class="bi bi-cart-plus"></i> Catat Beli</button>
    </div>
  </div>

  <!-- Stats -->
  <div class="bb-stats">
    <div class="bb-stat">
      <div class="stat-ico ic-b"><i class="bi bi-basket2-fill"></i></div>
      <div class="stat-val" id="s-jenis">—</div>
      <div class="stat-label">Jenis Bahan</div>
    </div>
    <div class="bb-stat">
      <div class="stat-ico ic-g"><i class="bi bi-currency-dollar"></i></div>
      <div class="stat-val" id="s-nilai">—</div>
      <div class="stat-label">Nilai Stok</div>
    </div>
    <div class="bb-stat">
      <div class="stat-ico ic-a"><i class="bi bi-bag-check-fill"></i></div>
      <div class="stat-val" id="s-beli">—</div>
      <div class="stat-label">Total Pembelian</div>
    </div>
    <div class="bb-stat">
      <div class="stat-ico ic-r"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <div class="stat-val" id="s-nipis">—</div>
      <div class="stat-label">Stok Sedikit (≤5)</div>
    </div>
  </div>

  <!-- Tabs -->
  <div class="bb-tabs">
    <button class="bb-tab active" data-tab="stok"><i class="bi bi-list-ul"></i> Stok Bahan</button>
    <button class="bb-tab"        data-tab="beli"><i class="bi bi-cart-check"></i> Riwayat Pembelian</button>
    <button class="bb-tab"        data-tab="pakai"><i class="bi bi-fire"></i> Riwayat Penggunaan</button>
  </div>

  <!-- Tab: Stok -->
  <div id="tab-stok">
    <div class="bb-card">
      <div class="bb-card-head">
        <div class="bb-card-title"><i class="bi bi-basket3-fill" style="color:#3b6ff5"></i>Daftar Bahan Baku</div>
        <span class="bb-count" id="stok-label">memuat…</span>
      </div>
      <div class="bb-tbl-wrap">
        <table class="bb-tbl">
          <thead><tr>
            <th>#</th><th>Kode</th><th>Nama Bahan</th><th>Satuan</th>
            <th class="tr">Stok</th><th class="tr">Harga/Sat.</th><th class="tr">Nilai Stok</th>
            <th>Status</th><th>Aksi</th>
          </tr></thead>
          <tbody id="tbody-stok"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Tab: Beli -->
  <div id="tab-beli" style="display:none">
    <div class="bb-card">
      <div class="bb-card-head">
        <div class="bb-card-title"><i class="bi bi-cart-check-fill" style="color:#16a34a"></i>Riwayat Pembelian</div>
        <span class="bb-count" id="beli-label">—</span>
      </div>
      <div class="bb-tbl-wrap">
        <table class="bb-tbl">
          <thead><tr>
            <th>Tanggal</th><th>Bahan</th>
            <th class="tr">Jumlah</th><th class="tr">Harga/Sat.</th><th class="tr">Total</th>
            <th>Supplier</th><th>Catatan</th>
          </tr></thead>
          <tbody id="tbody-beli"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Tab: Pakai -->
  <div id="tab-pakai" style="display:none">
    <div class="bb-card">
      <div class="bb-card-head">
        <div class="bb-card-title"><i class="bi bi-fire-fill" style="color:#dc2626"></i>Riwayat Penggunaan</div>
        <span class="bb-count" id="pakai-label">—</span>
      </div>
      <div class="bb-tbl-wrap">
        <table class="bb-tbl">
          <thead><tr>
            <th>Tanggal</th><th>Bahan</th>
            <th class="tr">Jumlah</th><th>Keperluan</th><th>Catatan</th>
          </tr></thead>
          <tbody id="tbody-pakai"></tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
const BB_BASE = '<?= base_url() ?>';
let allBahan = [];
let bbTT;

const fmt  = n => 'Rp '+(parseFloat(n)||0).toLocaleString('id-ID');
const fmtK = n => {
  const v=parseFloat(n)||0;
  if(v>=1e9) return 'Rp '+(v/1e9).toFixed(2)+' M';
  if(v>=1e6) return 'Rp '+(v/1e6).toFixed(1)+' Jt';
  if(v>=1e3) return 'Rp '+(v/1e3).toFixed(0)+' Rb';
  return 'Rp '+v.toLocaleString('id-ID');
};
const fmtN = (n,s='') => (parseFloat(n)||0).toLocaleString('id-ID')+(s?' '+s:'');
const esc  = s => String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
const fmtD = s => s?new Date(s).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}):'—';

function getCsrf(){
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function showToast(msg, type='success'){
  const el = document.getElementById('bb-toast');
  document.getElementById('t-ico').textContent = type==='success' ? '✓' : '✕';
  document.getElementById('t-msg').textContent = msg;
  el.className = 'show '+type;
  clearTimeout(bbTT);
  bbTT = setTimeout(()=>{ el.className=''; }, 3600);
}

document.querySelectorAll('.bb-tab').forEach(btn => {
  btn.addEventListener('click', function() {
    const name = this.dataset.tab;
    ['stok','beli','pakai'].forEach(t => {
      document.getElementById('tab-'+t).style.display = t === name ? '' : 'none';
    });
    document.querySelectorAll('.bb-tab').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
  });
});

function openModal(id){
  const el = document.getElementById(id);
  el.classList.add('visible');
  requestAnimationFrame(()=> requestAnimationFrame(()=> el.classList.add('shown')));
}

function closeModal(id){
  const el = document.getElementById(id);
  el.classList.add('closing');
  el.classList.remove('shown');
  setTimeout(()=>{ el.classList.remove('visible','closing'); }, 210);
}

document.querySelectorAll('.bb-overlay').forEach(el=>{
  el.addEventListener('click', e=>{ if(e.target===el) closeModal(el.id); });
});

document.getElementById('btn-batal-bahan').addEventListener('click', () => closeModal('modal-bahan'));
document.getElementById('btn-batal-beli').addEventListener('click',  () => closeModal('modal-beli'));

document.addEventListener('click', e=>{
  const btn = e.target.closest('.btn-ms');
  if(!btn || btn.disabled) return;
  const r    = document.createElement('span');
  r.className = 'ripple';
  const rect  = btn.getBoundingClientRect();
  const size  = Math.max(rect.width, rect.height);
  r.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px`;
  btn.appendChild(r);
  setTimeout(()=> r.remove(), 650);
});

function btnLoading(btn){ btn.disabled = true; btn.classList.add('loading'); }
function btnSuccess(btn){
  btn.classList.remove('loading'); btn.classList.add('done');
  setTimeout(()=>{ btn.classList.remove('done'); btn.disabled = false; }, 950);
}
function btnError(btn){
  btn.classList.remove('loading'); btn.disabled = false;
  btn.classList.add('shake');
  setTimeout(()=> btn.classList.remove('shake'), 380);
}

function validateFields(ids){
  let firstBad = null;
  ids.forEach(id=>{
    const el  = document.getElementById(id);
    const bad = !el.value.trim() || (el.type==='number' && parseFloat(el.value)<=0);
    if(bad){
      el.classList.add('invalid','shake');
      setTimeout(()=> el.classList.remove('shake'), 380);
      if(!firstBad){ firstBad=el; el.focus(); }
    } else {
      el.classList.remove('invalid');
    }
  });
  return !firstBad;
}

document.addEventListener('input', e=>{
  if(e.target.classList.contains('invalid') && e.target.value.trim())
    e.target.classList.remove('invalid');
});

function populateBahanSelect(selId, defId=0){
  document.getElementById(selId).innerHTML = allBahan.map(b=>
    `<option value="${b.id}" ${b.id==defId?'selected':''}>${esc(b.nama)} — stok ${fmtN(b.stok)} ${esc(b.satuan)}</option>`
  ).join('');
}

function openModalBahan(row=null){
  document.getElementById('bahan-id').value     = row?row.id:'';
  document.getElementById('bahan-kode').value   = row?row.kode:'';
  document.getElementById('bahan-nama').value   = row?row.nama:'';
  document.getElementById('bahan-satuan').value = row?row.satuan:'kg';
  document.getElementById('bahan-harga').value  = row?row.harga_beli:'';
  document.getElementById('bahan-ket').value    = row?row.keterangan:'';
  document.getElementById('m-bahan-title').textContent = row?'Edit Bahan Baku':'Tambah Bahan Baku';
  document.getElementById('m-bahan-ico').textContent   = row?'✎':'✦';
  ['bahan-kode','bahan-nama'].forEach(id=> document.getElementById(id).classList.remove('invalid'));
  openModal('modal-bahan');
}
function openModalBahanFromId(id){
  const row = allBahan.find(b=>b.id==id);
  if(row) openModalBahan(row);
}
function openModalBeli(idBahan=0){
  populateBahanSelect('beli-bahan-select', idBahan);
  document.getElementById('beli-tanggal').value = new Date().toISOString().split('T')[0];
  ['beli-supplier','beli-jumlah','beli-harga','beli-total','beli-catatan']
    .forEach(i=>{ document.getElementById(i).value=''; document.getElementById(i).classList.remove('invalid'); });
  openModal('modal-beli');
}

document.getElementById('btn-open-bahan').addEventListener('click', () => openModalBahan());
document.getElementById('btn-open-beli').addEventListener('click',  () => openModalBeli());

['beli-jumlah','beli-harga'].forEach(id=>
  document.getElementById(id).addEventListener('input',()=>{
    const j = parseFloat(document.getElementById('beli-jumlah').value)||0;
    const h = parseFloat(document.getElementById('beli-harga').value)||0;
    document.getElementById('beli-total').value = (j&&h) ? fmt(j*h) : '';
  })
);

async function post(url, body){
  const r = await fetch(BB_BASE+url,{
    method:'POST',
    headers:{
      'Content-Type':'application/json',
      'X-Requested-With':'XMLHttpRequest',
      'X-CSRF-TOKEN': getCsrf()
    },
    body:JSON.stringify(body)
  });
  return r.json();
}

async function simpanBahan(btn){
  if(!validateFields(['bahan-kode','bahan-nama'])) return;
  btnLoading(btn);
  try{
    const res = await post('/bahan-baku/simpan',{
      id:         document.getElementById('bahan-id').value,
      kode:       document.getElementById('bahan-kode').value,
      nama:       document.getElementById('bahan-nama').value,
      satuan:     document.getElementById('bahan-satuan').value,
      harga_beli: document.getElementById('bahan-harga').value,
      keterangan: document.getElementById('bahan-ket').value,
    });
    if(res.success){
      btnSuccess(btn);
      setTimeout(()=> closeModal('modal-bahan'), 280);
      setTimeout(()=>{ showToast(res.message); loadData(); }, 520);
    } else { btnError(btn); showToast(res.error,'error'); }
  } catch(e){ btnError(btn); showToast('Koneksi gagal, coba lagi.','error'); }
}

async function submitBeli(btn){
  if(!validateFields(['beli-jumlah','beli-harga'])) return;
  btnLoading(btn);
  try{
    const res = await post('/bahan-baku/beli',{
      id_bahan:     document.getElementById('beli-bahan-select').value,
      tanggal:      document.getElementById('beli-tanggal').value,
      jumlah:       document.getElementById('beli-jumlah').value,
      harga_satuan: document.getElementById('beli-harga').value,
      supplier:     document.getElementById('beli-supplier').value,
      catatan:      document.getElementById('beli-catatan').value,
    });
    if(res.success){
      btnSuccess(btn);
      setTimeout(()=> closeModal('modal-beli'), 280);
      setTimeout(()=>{ showToast(res.message); loadData(); }, 520);
    } else { btnError(btn); showToast(res.error,'error'); }
  } catch(e){ btnError(btn); showToast('Koneksi gagal, coba lagi.','error'); }
}

document.getElementById('btn-simpan-bahan').addEventListener('click', function(){ simpanBahan(this); });
document.getElementById('btn-submit-beli').addEventListener('click',  function(){ submitBeli(this); });

async function hapusBahan(id,nama){
  if(!confirm(`Hapus bahan "${nama}"?\nPastikan tidak ada transaksi terkait.`)) return;
  const res = await post('/bahan-baku/hapus',{id});
  if(res.success){ showToast(res.message); loadData(); }
  else showToast(res.error,'error');
}

function renderStok(list){
  allBahan = list;
  document.getElementById('stok-label').textContent = list.length+' bahan';
  const tbody = document.getElementById('tbody-stok');
  if(!list.length){
    tbody.innerHTML=`<tr><td colspan="9"><div class="bb-empty"><i class="bi bi-basket3"></i><p>Belum ada bahan baku.</p></div></td></tr>`;
    return;
  }
  const mx = Math.max(...list.map(r=>parseFloat(r.stok)||0),1);
  tbody.innerHTML = list.map((r,i)=>{
    const stok     = parseFloat(r.stok)||0;
    const pct      = Math.min(stok/mx*100,100).toFixed(1);
    const col      = stok<=0?'#dc2626':stok<=5?'#d97706':'#16a34a';
    const badge    = stok<=0?'b-r':stok<=5?'b-w':'b-ok';
    const badgeTxt = stok<=0?'Habis':stok<=5?'Sedikit':'Tersedia';
    return `<tr>
      <td class="tm" style="font-size:.74rem">${i+1}</td>
      <td><span class="code">${esc(r.kode)}</span></td>
      <td style="font-weight:600;color:#0f1623">${esc(r.nama)}</td>
      <td class="tm">${esc(r.satuan)}</td>
      <td class="tr">
        <div class="stok-bar">
          <span style="color:${col};font-weight:600;font-family:'JetBrains Mono',monospace;font-size:.8rem">${fmtN(stok)}</span>
          <div class="stok-tr"><div class="stok-f" style="width:${pct}%;background:${col}"></div></div>
        </div>
      </td>
      <td class="tr tm">${fmt(r.harga_beli)}</td>
      <td class="tr tg">${fmtK(stok*parseFloat(r.harga_beli))}</td>
      <td><span class="badge ${badge}">${badgeTxt}</span></td>
      <td>
        <div style="display:flex;gap:4px;flex-wrap:wrap">
          <button class="btn-o ob" data-action="beli" data-id="${r.id}"><i class="bi bi-cart-plus"></i> Beli</button>
          <button class="btn-o oe" data-action="edit" data-id="${r.id}"><i class="bi bi-pencil"></i></button>
          <button class="btn-o or" data-action="hapus" data-id="${r.id}" data-nama="${esc(r.nama)}"><i class="bi bi-trash3"></i></button>
        </div>
      </td>
    </tr>`;
  }).join('');
}

document.getElementById('tbody-stok').addEventListener('click', function(e){
  const btn = e.target.closest('[data-action]');
  if(!btn) return;
  const action = btn.dataset.action;
  const id     = btn.dataset.id;
  if(action === 'beli')  openModalBeli(id);
  if(action === 'edit')  openModalBahanFromId(id);
  if(action === 'hapus') hapusBahan(id, btn.dataset.nama);
});

function renderBeli(rows){
  document.getElementById('beli-label').textContent = rows.length+' transaksi';
  const tbody = document.getElementById('tbody-beli');
  if(!rows.length){
    tbody.innerHTML=`<tr><td colspan="7"><div class="bb-empty"><i class="bi bi-cart"></i><p>Belum ada pembelian.</p></div></td></tr>`;
    return;
  }
  tbody.innerHTML = rows.map(r=>`<tr>
    <td class="tm" style="font-size:.78rem">${fmtD(r.tanggal)}</td>
    <td style="font-weight:600;color:#0f1623">${esc(r.nama_bahan)}</td>
    <td class="tr tb">${fmtN(r.jumlah)} ${esc(r.satuan)}</td>
    <td class="tr tm">${fmt(r.harga_satuan)}</td>
    <td class="tr tg">${fmt(r.total_harga)}</td>
    <td style="font-size:.79rem;color:#7a849e">${esc(r.supplier||'—')}</td>
    <td style="font-size:.75rem;color:#7a849e;max-width:160px;white-space:normal">${esc(r.catatan||'—')}</td>
  </tr>`).join('');
}

function renderPakai(rows){
  document.getElementById('pakai-label').textContent = rows.length+' transaksi';
  const tbody = document.getElementById('tbody-pakai');
  if(!rows.length){
    tbody.innerHTML=`<tr><td colspan="5"><div class="bb-empty"><i class="bi bi-fire"></i><p>Belum ada penggunaan.</p></div></td></tr>`;
    return;
  }
  tbody.innerHTML = rows.map(r=>`<tr>
    <td class="tm" style="font-size:.78rem">${fmtD(r.tanggal)}</td>
    <td style="font-weight:600;color:#0f1623">${esc(r.nama_bahan)}</td>
    <td class="tr tr2">${fmtN(r.jumlah)} ${esc(r.satuan)}</td>
    <td style="font-size:.79rem;color:#7a849e">${esc(r.keperluan||'—')}</td>
    <td style="font-size:.75rem;color:#7a849e">${esc(r.catatan||'—')}</td>
  </tr>`).join('');
}

async function loadData(){
  try{
    const r = await fetch(BB_BASE+'/bahan-baku/data',{headers:{'X-Requested-With':'XMLHttpRequest'}});
    const d = await r.json();
    if(!d.success) throw new Error(d.error||'Gagal mengambil data.');
    document.getElementById('s-jenis').textContent = d.summary.total_jenis;
    document.getElementById('s-nilai').textContent = fmtK(d.summary.total_nilai);
    document.getElementById('s-beli').textContent  = fmtK(d.summary.total_beli);
    document.getElementById('s-nipis').textContent = d.summary.stok_nipis+' bahan';
    renderStok(d.list);
    renderBeli(d.pembelian);
    renderPakai(d.penggunaan);
  } catch(e){
    showToast('Gagal memuat: '+e.message,'error');
  }
}

loadData();
</script>

<?= $this->endSection() ?>