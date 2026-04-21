<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.page-title   { font-size:1.4rem; font-weight:700; color:var(--text-main); display:flex; align-items:center; gap:10px; }
.page-title i { color:var(--accent); }
.page-subtitle{ font-size:.8rem; color:var(--text-muted); margin-top:2px; }

.table-card { background:#ffffff; border:1px solid var(--border); border-radius:14px; overflow:hidden; margin-bottom:20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
.table-card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.table-card-title  { font-size:.9rem; font-weight:600; color:var(--text-main); }

.sim-table { width:100%; border-collapse:collapse; font-size:.83rem; }
.sim-table thead tr { background:#f8fafc; }
.sim-table thead th { padding:11px 16px; text-align:left; color:var(--text-muted); font-weight:600; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border); white-space:nowrap; }
.sim-table tbody td { padding:11px 16px; color:#334155; vertical-align:middle; border-bottom: 1px solid var(--border); }

.badge-sim { display:inline-flex; align-items:center; gap:4px; font-size:.7rem; font-weight:600; padding:3px 9px; border-radius:20px; }
.badge-success { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.badge-warning { background:rgba(250,204,21,.12); color:#facc15; border:1px solid rgba(250,204,21,.2); }
.badge-info    { background:rgba(79,142,247,.12); color:#4f8ef7; border:1px solid rgba(79,142,247,.2); }

.upload-zone {
    border: 2px dashed var(--border);
    border-radius: 14px;
    padding: 40px 24px;
    text-align: center;
    margin-bottom: 28px;
    transition: border-color .2s, background .2s;
    cursor: pointer;
    position: relative;
    background: #ffffff;
}
.upload-zone:hover {
    border-color: rgba(79,142,247,.5);
    background: rgba(79,142,247,.03);
}
.upload-zone input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.upload-icon {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, rgba(79,142,247,.15), rgba(124,92,252,.15));
    border-radius: 16px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: #4f8ef7;
    margin-bottom: 14px;
}
.upload-title { font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px; }
.upload-sub   { font-size: .8rem; color: var(--text-muted); margin-bottom: 16px; }

.info-list { list-style: none; padding: 0; }
.info-list li { display: flex; gap: .65rem; padding: .6rem 0; border-bottom: 1px solid var(--border); font-size: .82rem; }
.info-list li:last-child { border-bottom: none; }
</style>

<div class="page-header">
    <div>
        <div class="page-title"><i class="bi bi-cloud-arrow-down-fill"></i> Import Marketplace Data</div>
        <div class="page-subtitle">Upload & sinkronisasi pesanan TikTok (OrderSKUList)</div>
    </div>
    <div class="filter-bar d-flex gap-2">
        <a href="<?= base_url('/withdrawal') ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-shield-lock-fill"></i> Pencairan (CEO)</a>
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-arrow-down"></i> Unduh Template</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card bg-side border-sim p-4 mb-4">
            <p class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-2"></i>Upload File Excel</p>
            
            <form action="<?= base_url('/import/process') ?>" method="POST" enctype="multipart/form-data" id="importForm">
                <div class="upload-zone" id="drop-zone">
                    <input type="file" name="excel_file" id="file-picker" accept=".xlsx,.xls,.csv" required>
                    <div class="upload-icon"><i class="bi bi-file-earmark-spreadsheet"></i></div>
                    <div class="upload-title">Seret & Lepas file di sini</div>
                    <div class="upload-sub">atau <u class="text-primary">klik untuk memilih file</u></div>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-dark border border-secondary text-muted">.xlsx</span>
                        <span class="badge bg-dark border border-secondary text-muted">.csv</span>
                        <span class="badge bg-dark border border-secondary text-muted">Maks 50MB</span>
                    </div>
                </div>

                <div id="file-info" class="alert alert-info d-none mb-3 py-2 small">
                    <i class="bi bi-check-circle-fill me-2"></i><span id="filename"></span>
                </div>

                <button type="submit" id="btn-import" class="btn btn-primary w-100 py-2 fw-bold" disabled>
                    <i class="bi bi-database-up me-2"></i>Mulai Sinkronisasi Data
                </button>
            </form>
        </div>

        <!-- Riwayat Import -->
        <div class="table-card">
            <div class="table-card-header">
                <span class="table-card-title"><i class="bi bi-clock-history me-2 text-info"></i>Riwayat Import</span>
            </div>
            <div class="table-responsive">
                <table class="sim-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama File</th>
                            <th>Total</th>
                            <th>Berhasil</th>
                            <th>Gagal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mock data for UI demo
                        $history = [
                            ['tiktok_orders_apr_15.xlsx', '432', '432', '0', '15 Apr 2025', 'Selesai', 'success'],
                            ['tiktok_orders_apr_10.xlsx', '210', '208', '2', '10 Apr 2025', 'Selesai', 'warning'],
                        ];
                        foreach ($history as $i => $h): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><i class="bi bi-file-earmark-excel me-2 text-success"></i><?= $h[0] ?></td>
                            <td><?= $h[1] ?></td>
                            <td class="text-success"><?= $h[2] ?></td>
                            <td class="<?= $h[3] > 0 ? 'text-danger' : 'text-muted' ?>"><?= $h[3] ?></td>
                            <td class="text-muted small"><?= $h[4] ?></td>
                            <td><span class="badge-sim badge-<?= $h[6] ?>"><?= $h[5] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-side border-sim p-4 mb-4">
            <p class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Aturan Bisnis</p>
            <ul class="info-list">
                <li>
                    <i class="bi bi-arrow-repeat text-warning"></i>
                    <div>
                        <strong class="d-block text-dark small">Upsert by Order ID</strong>
                        <span class="text-muted extra-small">Sudah ada → UPDATE. Baru → INSERT. Tanpa duplikat data.</span>
                    </div>
                </li>
                <li>
                    <i class="bi bi-lock-fill text-success"></i>
                    <div>
                        <strong class="d-block text-dark small">Keamanan Pencairan</strong>
                        <span class="text-muted extra-small">Status <code>sudah ditarik</code> tidak akan terpengaruh re-import.</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const filePicker = document.getElementById('file-picker');
    const btnImport = document.getElementById('btn-import');
    const fileInfo = document.getElementById('file-info');
    const filename = document.getElementById('filename');

    filePicker.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            filename.textContent = this.files[0].name;
            fileInfo.classList.remove('d-none');
            btnImport.disabled = false;
        } else {
            fileInfo.classList.add('d-none');
            btnImport.disabled = true;
        }
    });
</script>
<?= $this->endSection() ?>