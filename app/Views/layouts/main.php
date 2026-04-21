<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'SIM System' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <?= $this->renderSection('css') ?>

    <style>
        :root {
            --sidebar-w: 240px;
            --bg-page: #f8fafc;
            --bg-side: #ffffff;
            --border: rgba(0, 0, 0, .08);
            --accent: #3b82f6;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-page);
            color: var(--text-main);
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--bg-side);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand .brand-logo {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4f8ef7, #7c5cfc);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .sidebar-brand h5 {
            color: var(--accent);
            font-size: .9rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .sidebar-brand small {
            color: var(--text-muted);
            font-size: .72rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 12px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #334155;
            padding: 10px 8px 4px;
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 2px;
            font-size: .875rem;
            transition: background .15s, color .15s;
            position: relative;
        }

        .sidebar-nav a .nav-icon {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: var(--accent);
        }

        .sidebar-nav a.active {
            background: rgba(59, 130, 246, .08);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, .15);
            font-weight: 600;
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: linear-gradient(#4f8ef7, #7c5cfc);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            font-size: .72rem;
            color: var(--text-muted);
        }

        /* ── TOPBAR (mobile) ── */
        .topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: var(--bg-side);
            border-bottom: 1px solid var(--border);
            align-items: center;
            padding: 0 16px;
            gap: 12px;
            z-index: 200;
        }

        .topbar-brand {
            font-weight: 700;
            font-size: .95rem;
            color: var(--accent);
            flex: 1;
        }

        .btn-menu {
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.4rem;
            cursor: pointer;
            padding: 4px;
        }

        /* ── OVERLAY (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            z-index: 99;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 28px 24px;
            min-height: 100vh;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .topbar {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                top: 0;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 72px 16px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Topbar -->
    <div class="topbar">
        <button class="btn-menu" id="btnMenu"><i class="bi bi-list"></i></button>
        <span class="topbar-brand">SIM Dashboard</span>
    </div>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="overlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-logo">📊</div>
            <h5>SIM Dashboard</h5>
            <small>Sales Information Management</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>

            <a href="<?= base_url('/analytics') ?>"
                class="<?= str_contains(uri_string(), 'analytics') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-bar-chart-fill"></i></span>
                Analytics
            </a>

            <a href="<?= base_url('/bahan-baku') ?>"
                class="<?= str_contains(uri_string(), 'bahan-baku') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-basket2-fill"></i></span>
                Bahan Baku
            </a>

            <a href="<?= base_url('/import') ?>"
                class="<?= str_contains(uri_string(), 'import') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-cloud-arrow-down-fill"></i></span>
                Import
            </a>

            <div class="nav-label">Produk</div>

            <a href="<?= base_url('/produk-bumbu') ?>"
                class="<?= str_contains(uri_string(), 'produk-bumbu') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-jar-fill"></i></span>
                Produk Bumbu
            </a>

            <a href="<?= base_url('/produksi') ?>" class="...">
                <span class="nav-icon">🏭</span> Produksi
            </a>

            <a href="<?= base_url('/rekap-produk') ?>"
                class="<?= str_contains(uri_string(), 'rekap-produk') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-box-seam-fill"></i></span>
                Rekap Produk
            </a>

            <div class="nav-label">Keuangan</div>

            <a href="<?= base_url('/withdrawal') ?>"
                class="<?= str_contains(uri_string(), 'withdrawal') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-cash-coin"></i></span>
                Pencairan
            </a>

            <div class="nav-label">Finance & HRD</div>

            <a href="<?= base_url('/pemasukan') ?>"
                class="<?= str_contains(uri_string(), 'pemasukan') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-shop"></i></span>
                Pemasukan
            </a>

            <a href="<?= base_url('/finance/pengeluaran') ?>"
                class="<?= str_contains(uri_string(), 'finance/pengeluaran') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-wallet2"></i></span>
                Keuangan
            </a>

            <a href="<?= base_url('/hrd/karyawan') ?>"
                class="<?= str_contains(uri_string(), 'hrd') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
                HRD
            </a>

        </nav>

        <div class="sidebar-footer">
            SIM System &copy; <?= date('Y') ?>
        </div>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const btnMenu = document.getElementById('btnMenu');

        btnMenu.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    </script>

    <?= $this->renderSection('js') ?>

</body>

</html>