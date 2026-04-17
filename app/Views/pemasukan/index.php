<?= $this->extend('layout/template') ?>

<?= $this->section('css') ?>
<style>
    .tabs { display: flex; gap: 10px; margin-bottom: 2rem; border-bottom: 2px solid var(--border); padding-bottom: 10px; }
    .tab-btn {
        background: transparent; border: none; padding: 10px 20px; font-weight: 600; font-size: 14px;
        color: var(--muted); cursor: pointer; border-radius: 8px; transition: all .2s;
    }
    .tab-btn:hover { background: rgba(59,130,246,.05); color: var(--accent); }
    .tab-btn.active { background: rgba(59,130,246,.1); color: var(--accent); }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .grid-pos { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    @media(max-width: 900px) { .grid-pos { grid-template-columns: 1fr; } }
    
    .table-pos th { background: rgba(0,0,0,.03); padding: 10px; text-transform: uppercase; font-size: 11px; }
    .table-pos td { vertical-align: middle; padding: 10px; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 5px; font-weight: 600; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid var(--border); border-radius: 8px; background: #fff; font-family: inherit; }
    
    .btn-action { background: var(--accent); color: #fff; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: all .2s; }
    .btn-action:hover { background: #2563eb; transform: translateY(-2px); }
    
    .btn-add-row { background: rgba(16,185,129,.1); color: var(--success); border: 1px dashed var(--success); padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; width: 100%; }
    .btn-add-row:hover { background: rgba(16,185,129,.2); }
    
    .btn-del-row { background: rgba(239,68,68,.1); color: var(--danger); border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;}
    
    .grand-total { font-size: 24px; font-weight: 800; color: var(--success); text-align: right; border-top: 2px dashed var(--border); padding-top: 15px; margin-top: 15px; }
    
    select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2364748b'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1>🛒 Pusat Pemasukan & Omnichannel</h1>

<?php if(session()->getFlashdata('success')): ?>
  <div style="background: rgba(16,185,129,.1); border: 1px solid var(--success); color: var(--success); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
  <div style="background: rgba(239,68,68,.1); border: 1px solid var(--danger); color: var(--danger); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<div class="tabs">
    <button class="tab-btn active" onclick="activateTab('kasir')"><i class="bi bi-shop"></i> Transaksi Biasa (Kasir)</button>
    <button class="tab-btn" onclick="activateTab('reseller')"><i class="bi bi-people"></i> Transaksi Reseller</button>
    <button class="tab-btn" onclick="activateTab('maklon')"><i class="bi bi-box"></i> Transaksi Maklon</button>
    <button class="tab-btn" onclick="window.location.href='<?= base_url('/import') ?>'"><i class="bi bi-phone"></i> Marketplace (Import)</button>
</div>

<!-- ============================================== -->
<!-- TAB KASIR -->
<!-- ============================================== -->
<div id="tab-kasir" class="tab-content active">
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Kasir">
        <div class="grid-pos">
            <div class="card" style="margin-bottom:0">
                <h3 style="margin-bottom:20px; font-size:16px;">Keranjang Belanja (POS)</h3>
                <table class="table-pos" id="table-pos-kasir">
                    <thead>
                        <tr>
                            <th style="width:45%">Pilih Produk</th>
                            <th style="width:15%">Qty</th>
                            <th style="width:25%">Harga Satuan (Rp)</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-kasir">
                        <!-- Dimasukkan lewat JS -->
                    </tbody>
                </table>
                <button type="button" class="btn-add-row" onclick="addRow('kasir')" style="margin-top:15px"><i class="bi bi-plus-circle"></i> Tambah Item Produk</button>
            </div>
            
            <div class="card" style="margin-bottom:0; background: var(--bg-card2)">
                <h3 style="margin-bottom:20px; font-size:16px;">Ringkasan Pembayaran</h3>
                <div class="form-group">
                    <label>Nama Pelanggan (Opsional)</label>
                    <input type="text" class="form-control" name="buyer_username" placeholder="Guest">
                </div>
                <div class="grand-total" id="total-kasir">Rp 0</div>
                <button type="submit" class="btn-action" style="margin-top:20px"><i class="bi bi-check-circle"></i> Selesaikan Transaksi (Kasir)</button>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TAB RESELLER -->
<!-- ============================================== -->
<div id="tab-reseller" class="tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px">
        <h3 style="margin:0">Transaksi Reseller</h3>
        <button onclick="document.getElementById('modal-mitra').style.display='flex'; document.getElementById('tipe_mitra').value='Reseller';" style="background:var(--accent); color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer; font-size:12px;">+ Tambah Reseller Baru</button>
    </div>
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Reseller">
        <div class="grid-pos">
            <div class="card" style="margin-bottom:0">
                <table class="table-pos" id="table-pos-reseller">
                    <thead>
                        <tr>
                            <th style="width:45%">Pilih Produk</th>
                            <th style="width:15%">Qty</th>
                            <th style="width:25%">Harga (Reseller)</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-reseller">
                    </tbody>
                </table>
                <button type="button" class="btn-add-row" onclick="addRow('reseller')" style="margin-top:15px"><i class="bi bi-plus-circle"></i> Tambah Item Produk</button>
            </div>
            
            <div class="card" style="margin-bottom:0; background: var(--bg-card2)">
                <div class="form-group">
                    <label>Pilih Reseller Terdaftar</label>
                    <select name="id_mitra" class="form-control" required>
                        <option value="">-- Pilih Reseller --</option>
                        <?php foreach($reseller as $r): ?>
                            <option value="<?= $r['id_mitra'] ?>"><?= $r['nama_mitra'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grand-total" id="total-reseller">Rp 0</div>
                <button type="submit" class="btn-action" style="margin-top:20px"><i class="bi bi-check-circle"></i> Catat Transaksi Reseller</button>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TAB MAKLON -->
<!-- ============================================== -->
<div id="tab-maklon" class="tab-content">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px">
        <h3 style="margin:0">Transaksi Mitra Maklon</h3>
        <button onclick="document.getElementById('modal-mitra').style.display='flex'; document.getElementById('tipe_mitra').value='Maklon';" style="background:var(--accent); color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer; font-size:12px;">+ Tambah Mitra Maklon Baru</button>
    </div>
    <form action="<?= base_url('/pemasukan/store-manual') ?>" method="post">
        <input type="hidden" name="platform" value="Maklon">
        <div class="grid-pos">
            <div class="card" style="margin-bottom:0">
                <table class="table-pos" id="table-pos-maklon">
                    <thead>
                        <tr>
                            <th style="width:45%">Pilih Produk (Hasil Maklon)</th>
                            <th style="width:15%">Qty</th>
                            <th style="width:25%">Biaya Jual/Maklon (Rp)</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody-maklon">
                    </tbody>
                </table>
                <button type="button" class="btn-add-row" onclick="addRow('maklon')" style="margin-top:15px"><i class="bi bi-plus-circle"></i> Tambah Item Produk</button>
            </div>
            
            <div class="card" style="margin-bottom:0; background: var(--bg-card2)">
                <div class="form-group">
                    <label>Pilih Mitra Maklon Terdaftar</label>
                    <select name="id_mitra" class="form-control" required>
                        <option value="">-- Pilih Mitra Maklon --</option>
                        <?php foreach($maklon as $m): ?>
                            <option value="<?= $m['id_mitra'] ?>"><?= $m['nama_mitra'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grand-total" id="total-maklon">Rp 0</div>
                <button type="submit" class="btn-action" style="margin-top:20px"><i class="bi bi-check-circle"></i> Catat Transaksi Maklon</button>
            </div>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- MODAL TAMBAH MITRA (RESELLER / MAKLON) -->
<!-- ============================================== -->
<div id="modal-mitra" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); width:400px; padding:25px; border-radius:12px; position:relative;">
        <button type="button" onclick="document.getElementById('modal-mitra').style.display='none'" style="position:absolute; right:15px; top:15px; background:transparent; border:none; font-size:20px; cursor:pointer;">&times;</button>
        <h3 style="margin-top:0">Tambah Mitra Baru</h3>
        <form action="<?= base_url('/pemasukan/store-mitra') ?>" method="post">
            <div class="form-group">
                <label>Tipe Mitra</label>
                <select name="tipe_mitra" id="tipe_mitra" class="form-control" required>
                    <option value="Reseller">Reseller</option>
                    <option value="Maklon">Maklon</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nama Mitra / Perusahaan / Toko</label>
                <input type="text" name="nama_mitra" class="form-control" required>
            </div>
            <div class="form-group">
                <label>No. WhatsApp (Opsional)</label>
                <input type="text" name="no_hp" class="form-control">
            </div>
            <div class="form-group">
                <label>Alamat Pengiriman (Opsional)</label>
                <textarea name="alamat" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn-action">Simpan Profil Mitra</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Data List Product Mapping for dynamically populating POS select options
    const masterProducts = <?= json_encode($products) ?>;
    
    function activateTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }
    
    function addRow(type) {
        const tbody = document.getElementById('tbody-' + type);
        const tr = document.createElement('tr');
        
        let selectOptions = `<option value="">Pilih Produk Sesuai Sistem</option>`;
        masterProducts.forEach(p => {
            selectOptions += `<option value="${p.id}">${p.kombinasi_label} (${p.variasi_raw})</option>`;
        });
        
        tr.innerHTML = `
            <td>
                <select name="produk[]" class="form-control product-select" required>
                    ${selectOptions}
                </select>
            </td>
            <td>
                <input type="number" name="qty[]" class="form-control row-qty" value="1" min="1" required oninput="calcTotal('${type}')">
            </td>
            <td>
                <input type="number" name="price[]" class="form-control row-price" placeholder="Harga Jual" required oninput="calcTotal('${type}')">
            </td>
            <td>
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
