<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* ===== PAGE HEADER ===== */
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:var(--text-main); display:flex; align-items:center; gap:10px; }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

/* ===== TABS ===== */
.month-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; border-bottom:1px solid var(--border); padding-bottom:16px; }
.month-tab { background:transparent; border:1px solid var(--border); color:var(--text-muted); border-radius:8px; padding:8px 16px; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s; display:flex; align-items:center; gap:6px; }
.month-tab:hover { background:rgba(59,130,246,.05); border-color:var(--accent); color:var(--accent); }
.month-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); }

/* ===== CARDS & GRIDS ===== */
.grid-pos { display:grid; grid-template-columns:2fr 1fr; gap:24px; align-items:start; }
@media(max-width: 900px) { .grid-pos { grid-template-columns:1fr; } }

.table-card { background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,.05); }
.table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; background:#f8fafc; }
.table-card-title { font-size:.95rem; font-weight:700; color:var(--text-main); display:flex; align-items:center; gap:8px; }

/* ===== TABLE POS ===== */
.sim-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.sim-table thead th { padding:12px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody td { padding:12px 16px; border-bottom:1px solid var(--border); vertical-align:middle; }

/* ===== FORMS ===== */
.form-label { font-size:.8rem; color:var(--text-muted); font-weight:600; margin-bottom:6px; display:block; }
.sim-input, .sim-select { width:100%; background:#fff; border:1px solid var(--border); color:var(--text-main); border-radius:8px; padding:10px 14px; font-size:.85rem; outline:none; transition:border-color .15s; margin-bottom:16px; }
.sim-input:focus, .sim-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(59,130,246,.1); }

.btn-accent { background:linear-gradient(90deg, #3b82f6, #6366f1); border:none; color:#fff; border-radius:8px; padding:12px 20px; font-size:.9rem; font-weight:600; cursor:pointer; transition:opacity .15s, transform .15s; display:inline-flex; align-items:center; justify-content:center; gap:6px; width:100%; }
.btn-accent:hover { opacity:.9; transform:translateY(-1px); }

.btn-add-row { background:rgba(16,185,129,.1); color:#059669; border:1px dashed rgba(16,185,129,.4); padding:10px 16px; border-radius:8px; cursor:pointer; font-size:.85rem; font-weight:600; width:100%; transition:all .15s; margin-top:12px;}
.btn-add-row:hover { background:rgba(16,185,129,.15); border-color:#059669; }

.btn-del-row { background:rgba(239,68,68,.1); color:#dc2626; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-size:.8rem; transition:background .15s;}
.btn-del-row:hover { background:rgba(239,68,68,.2); }

.grand-total { font-size:1.8rem; font-weight:800; color:#059669; text-align:right; border-top:2px dashed var(--border); padding-top:16px; margin-top:8px; }

.alert-sim { padding:14px 20px; border-radius:10px; margin-bottom:24px; display:flex; align-items:center; gap:10px; font-size:.9rem; font-weight:500; }
.alert-sim-success { background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2); color:#059669; }
.alert-sim-danger  { background:rgba(239,68,68,.1);  border:1px solid rgba(239,68,68,.2);  color:#dc2626; }

.tab-content { display:none; }
.tab-content.active { display:block; }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-cart-check-fill" style="color:var(--accent)"></i> Pusat Pemasukan & Omnichannel</div>
        <div class="page-subtitle">Sistem Kasir (POS) Terpadu untuk Retail, Reseller, dan Maklon.</div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
  <div class="alert-sim alert-sim-success"><i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div class="alert-sim alert-sim-danger"><i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Tabs -->
<div class="month-tabs">
    <button class="month-tab active" onclick="activateTab('kasir')"><i class="bi bi-shop"></i> Kasir Reguler</button>
    <button class="month-tab" onclick="activateTab('reseller')"><i class="bi bi-people-fill"></i> Pesanan Reseller</button>
    <button class="month-tab" onclick="activateTab('maklon')"><i class="bi bi-box-fill"></i> Transaksi Maklon</button>
    <button class="month-tab" onclick="window.location.href='<?= base_url('/import') ?>'"><i class="bi bi-cloud-arrow-down-fill"></i> Import Marketplace</button>
</div>

<!-- ============================================== -->
<!-- TAB KASIR -->
<!-- ============================================== -->
<div id="tab-kasir" class="tab-content active">
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Kasir">
        <div class="grid-pos">
            <div class="table-card" style="margin-bottom:0">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="bi bi-basket" style="color:#f59e0b"></i> Keranjang Belanja</span>
                </div>
                <div style="padding:15px">
                    <table class="sim-table" id="table-pos-kasir">
                        <thead>
                            <tr>
                                <th style="width:45%">Pilih Produk</th>
                                <th style="width:15%">Qty</th>
                                <th style="width:30%">Harga Satuan (Rp)</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-kasir"></tbody>
                    </table>
                    <button type="button" class="btn-add-row" onclick="addRow('kasir')"><i class="bi bi-plus-circle"></i> Tambah Item Keranjang</button>
                </div>
            </div>
            
            <div class="table-card" style="margin-bottom:0; background:rgba(248,250,252,0.5);">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="bi bi-receipt" style="color:var(--accent)"></i> Ringkasan Pembayaran</span>
                </div>
                <div style="padding:20px">
                    <span class="form-label">Nama Pelanggan (Opsional)</span>
                    <input type="text" class="sim-input" name="buyer_username" placeholder="Guest / Pelanggan Umum">
                    
                    <div class="grand-total" id="total-kasir">Rp 0</div>
                    <button type="submit" class="btn-accent" style="margin-top:24px"><i class="bi bi-check2-circle" style="font-size:1.1rem"></i> Selesaikan Transaksi</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TAB RESELLER -->
<!-- ============================================== -->
<div id="tab-reseller" class="tab-content">
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Reseller">
        <div class="grid-pos">
            <div class="table-card" style="margin-bottom:0">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="bi bi-tags" style="color:#f59e0b"></i> Keranjang Reseller</span>
                </div>
                <div style="padding:15px">
                    <table class="sim-table" id="table-pos-reseller">
                        <thead>
                            <tr>
                                <th style="width:45%">Pilih Produk</th>
                                <th style="width:15%">Qty</th>
                                <th style="width:30%">Harga Spesial (Rp)</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-reseller"></tbody>
                    </table>
                    <button type="button" class="btn-add-row" onclick="addRow('reseller')"><i class="bi bi-plus-circle"></i> Tambah Item Keranjang</button>
                </div>
            </div>
            
            <div class="table-card" style="margin-bottom:0; background:rgba(248,250,252,0.5);">
                <div class="table-card-header d-flex justify-content-between align-items-center">
                    <span class="table-card-title"><i class="bi bi-person-check" style="color:var(--accent)"></i> Identitas Reseller</span>
                </div>
                <div style="padding:20px">
                    <span class="form-label">Pilih Reseller Terdaftar</span>
                    <select name="id_mitra" class="sim-select" required>
                        <option value="">-- Pilih Rekanan Reseller --</option>
                        <?php foreach($reseller as $r): ?>
                            <option value="<?= $r['id_mitra'] ?>"><?= $r['nama_mitra'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-add-row" style="background:transparent; color:var(--accent); border-color:var(--accent); padding:8px 12px; margin-top:-5px; margin-bottom:15px" onclick="document.getElementById('modal-mitra').style.display='flex'; document.getElementById('tipe_mitra').value='Reseller';">
                        <i class="bi bi-person-plus"></i> Tambah Reseller Baru
                    </button>
                    
                    <div class="grand-total" id="total-reseller">Rp 0</div>
                    <button type="submit" class="btn-accent" style="margin-top:24px"><i class="bi bi-check2-circle" style="font-size:1.1rem"></i> Catat Pesanan Reseller</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TAB MAKLON -->
<!-- ============================================== -->
<div id="tab-maklon" class="tab-content">
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Maklon">
        <div class="grid-pos">
            <div class="table-card" style="margin-bottom:0">
                <div class="table-card-header">
                    <span class="table-card-title"><i class="bi bi-box-seam" style="color:#f59e0b"></i> Detail Hasil Maklon</span>
                </div>
                <div style="padding:15px">
                    <table class="sim-table" id="table-pos-maklon">
                        <thead>
                            <tr>
                                <th style="width:45%">Pilih Produk (Hasil Maklon)</th>
                                <th style="width:15%">Qty</th>
                                <th style="width:30%">Biaya / Pendapatan (Rp)</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-maklon"></tbody>
                    </table>
                    <button type="button" class="btn-add-row" onclick="addRow('maklon')"><i class="bi bi-plus-circle"></i> Tambah Item Keranjang</button>
                </div>
            </div>
            
            <div class="table-card" style="margin-bottom:0; background:rgba(248,250,252,0.5);">
                <div class="table-card-header d-flex justify-content-between align-items-center">
                    <span class="table-card-title"><i class="bi bi-building" style="color:var(--accent)"></i> Entitas Mitra Maklon</span>
                </div>
                <div style="padding:20px">
                    <span class="form-label">Pilih Mitra Maklon Terdaftar</span>
                    <select name="id_mitra" class="sim-select" required>
                        <option value="">-- Pilih Perusahaan Maklon --</option>
                        <?php foreach($maklon as $m): ?>
                            <option value="<?= $m['id_mitra'] ?>"><?= $m['nama_mitra'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn-add-row" style="background:transparent; color:var(--accent); border-color:var(--accent); padding:8px 12px; margin-top:-5px; margin-bottom:15px" onclick="document.getElementById('modal-mitra').style.display='flex'; document.getElementById('tipe_mitra').value='Maklon';">
                        <i class="bi bi-plus-circle"></i> Tambah Mitra Maklon Baru
                    </button>

                    <div class="grand-total" id="total-maklon">Rp 0</div>
                    <button type="submit" class="btn-accent" style="margin-top:24px"><i class="bi bi-check2-circle" style="font-size:1.1rem"></i> Catat Transaksi Maklon</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- MODAL TAMBAH MITRA (RESELLER / MAKLON) -->
<!-- ============================================== -->
<div id="modal-mitra" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(3px); z-index:999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:100%; max-width:420px; padding:28px; border-radius:18px; position:relative; box-shadow:0 10px 25px rgba(0,0,0,0.15)">
        <button type="button" onclick="document.getElementById('modal-mitra').style.display='none'" style="position:absolute; right:20px; top:20px; background:transparent; border:none; font-size:24px; color:var(--text-muted); cursor:pointer;">&times;</button>
        <span class="table-card-title" style="font-size:1.2rem; margin-bottom:24px"><i class="bi bi-person-plus" style="color:var(--accent);margin-right:8px"></i>Tambah Rekanan Baru</span>
        
        <form action="<?= base_url('/pemasukan/store-mitra') ?>" method="post">
            <span class="form-label">Tipe Mitra / Rekanan</span>
            <select name="tipe_mitra" id="tipe_mitra" class="sim-select" required style="background:#f8fafc">
                <option value="Reseller">Reseller Individu / Grosir</option>
                <option value="Maklon">Perusahaan / Brand Maklon</option>
            </select>
            
            <span class="form-label">Nama Mitra / Perusahaan / Toko</span>
            <input type="text" name="nama_mitra" class="sim-input" placeholder="Masukkan nama lengkap..." required>
            
            <span class="form-label">No. WhatsApp / Kontak (Opsional)</span>
            <input type="text" name="no_hp" class="sim-input" placeholder="08xxxxxxxxxx">
            
            <span class="form-label">Alamat Pengiriman (Opsional)</span>
            <textarea name="alamat" class="sim-input" rows="3" placeholder="Alamat lengkap..."></textarea>
            
            <button type="submit" class="btn-accent" style="margin-top:10px">Simpan Profil Mitra</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const masterProducts = <?= json_encode($products) ?>;
    
    function activateTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.month-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }
    
    function addRow(type) {
        const tbody = document.getElementById('tbody-' + type);
        const tr = document.createElement('tr');
        
        let selectOptions = `<option value="">-- Pilih Produk --</option>`;
        masterProducts.forEach(p => {
            selectOptions += `<option value="${p.id}">${p.kombinasi_label} (${p.variasi_raw})</option>`;
        });
        
        tr.innerHTML = `
            <td>
                <select name="produk[]" class="sim-select product-select" style="margin-bottom:0" required>
                    ${selectOptions}
                </select>
            </td>
            <td>
                <input type="number" name="qty[]" class="sim-input row-qty" style="margin-bottom:0" value="1" min="1" required oninput="calcTotal('${type}')">
            </td>
            <td>
                <input type="number" name="price[]" class="sim-input row-price" style="margin-bottom:0" placeholder="Harga Jual" required oninput="calcTotal('${type}')">
            </td>
            <td style="text-align:right">
                <button type="button" class="btn-del-row" onclick="this.closest('tr').remove(); calcTotal('${type}')"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    }
    
    function calcTotal(type) {
        const tbody = document.getElementById('tbody-' + type);
        let grandTotal = 0;
        
        const qtys = tbody.querySelectorAll('.row-qty');
        const prices = tbody.querySelectorAll('.row-price');
        
        for (let i = 0; i < qtys.length; i++) {
            const q = parseInt(qtys[i].value) || 0;
            const p = parseFloat(prices[i].value) || 0;
            grandTotal += (q * p);
        }
        
        document.getElementById('total-' + type).innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Initialize with 1 row each
    addRow('kasir');
    addRow('reseller');
    addRow('maklon');
</script>
<?= $this->endSection() ?>
