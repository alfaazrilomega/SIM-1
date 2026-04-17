<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIM — Enterprise Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --bg:        #f8fafc;
      --bg-card:   #ffffff;
      --bg-card2:  #f1f5f9;
      --border:    rgba(0,0,0,.08);
      --accent:    #3b82f6;
      --accent2:   #6366f1;
      --success:   #10b981;
      --warning:   #f59e0b;
      --danger:    #ef4444;
      --gold:      #d97706;
      --teal:      #0d9488;
      --text:      #0f172a;
      --muted:     #64748b;
    }

    body { 
      font-family: 'Inter', sans-serif; 
      background: var(--bg); 
      color: var(--text); 
      padding: 0;
      margin: 0;
      font-size: 14px; 
    }

    .wrapper {
      padding: 2rem;
      max-width: 1440px;
      margin: 0 auto;
    }

    /* ===== HEADER GLOBALS ===== */
    header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 1.1rem 2rem;
      background: rgba(255,255,255,.95);
      border-bottom: 1px solid var(--border);
      position: sticky; top: 0; z-index: 100;
    }
    .logo { display: flex; align-items: center; gap: .75rem; text-decoration: none; color: var(--text); }
    .logo-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      font-size: 19px; box-shadow: 0 4px 16px rgba(59,130,246,.25);
    }
    .logo-name { font-weight: 800; font-size: 1.05rem; letter-spacing: -.3px; }
    .logo-sub  { font-size: .68rem; color: var(--muted); }
    .header-nav { display: flex; gap: .5rem; align-items: center; }
    .btn-nav {
      padding: .35rem .85rem; border: 1px solid var(--border);
      border-radius: 20px; font-size: .75rem; color: var(--accent);
      background: rgba(59,130,246,.08); text-decoration: none;
      transition: all .2s; display: inline-flex; align-items: center; gap: .3rem;
    }
    .btn-nav:hover { background: rgba(59,130,246,.2); }
    .btn-nav.active { background: rgba(99,102,241,.15); color: #4f46e5; border-color: rgba(99,102,241,.3); }

    /* Base UI Components (Cards, Buttons, Tables) */
    .card { 
      background: var(--bg-card); 
      border: 1px solid var(--border); 
      padding: 1.5rem; 
      border-radius: 12px; 
      margin-bottom: 2rem; 
    }

    h1, h2, h3, h4 { color: var(--text); margin-top: 0; }
    
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { padding: 12px; border-bottom: 1px solid var(--border); text-align: left; }
    th { background: var(--bg-card2); color: var(--text); font-weight: 600; }
    tr:hover { background: rgba(255, 255, 255, 0.02); }

    /* Input & Forms */
    input, select, textarea, button { 
      padding: 10px 14px; 
      border-radius: 6px; 
      border: 1px solid var(--border); 
      background: var(--bg-card2); 
      color: var(--text); 
      margin-bottom: 15px; 
      width: 100%;
      font-family: 'Inter', sans-serif; 
      box-sizing: border-box;
    }
    
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(79, 142, 247, 0.15);
    }
    
    input:disabled, select:disabled, input[readonly] {
      background: rgba(17, 26, 62, 0.4);
      color: var(--muted);
      cursor: not-allowed;
    }

    button { 
      background: var(--accent); 
      cursor: pointer; 
      font-weight: 600; 
      border: none;
      transition: all 0.2s ease; 
    }
    button:hover { background: #3b76e1; transform: translateY(-1px); }

    /* Alerts */
    .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: rgba(34,197,94,.1); border: 1px solid var(--success); color: var(--success); }
    .alert-danger { background: rgba(239,68,68,.1); border: 1px solid var(--danger); color: var(--danger); }
    .alert-warning { background: rgba(245,158,11,.1); border: 1px solid var(--warning); color: var(--warning); }

    /* Grids */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media (max-width: 768px) {
      .grid-2 { grid-template-columns: 1fr; }
    }
  </style>
  
  <!-- CSS Section untuk kustomisasi halaman spesifik -->
  <?= $this->renderSection('css') ?>
</head>
<body>

  <header>
    <a href="<?= base_url('/') ?>" class="logo">
      <div class="logo-icon">🏢</div>
      <div>
        <div class="logo-name">SIM Internal</div>
        <div class="logo-sub">HRD & Finance Modules</div>
      </div>
    </a>
    <div class="header-nav">
      <a href="<?= base_url('/') ?>"                  class="btn-nav"><i class="bi bi-house"></i> Home</a>
      <a href="<?= base_url('/analytics') ?>"         class="btn-nav"><i class="bi bi-bar-chart-fill"></i> Analitik</a>
      <a href="<?= base_url('/rekap-produk') ?>"      class="btn-nav"><i class="bi bi-box-seam"></i> Rekap</a>
      <a href="<?= base_url('/withdrawal') ?>"        class="btn-nav"><i class="bi bi-cash-stack"></i> Pencairan</a>
      <a href="<?= base_url('/finance/pengeluaran')?>"class="btn-nav"><i class="bi bi-wallet2"></i> Keuangan</a>
      <a href="<?= base_url('/hrd/karyawan') ?>"      class="btn-nav"><i class="bi bi-people-fill"></i> HRD</a>
      <a href="<?= base_url('/pemasukan') ?>"         class="btn-nav"><i class="bi bi-shop"></i> Pemasukan</a>
    </div>
  </header>

  <div class="wrapper">
    <!-- Navbar Rekan Tim -->
    <?= $this->include('partials/navbar') ?>
    <hr style="border-color: rgba(255,255,255,0.05); margin-bottom: 2rem; margin-top: 1rem;">
    <!-- Konten Utama Halaman -->
    <?= $this->renderSection('content') ?>
  </div>

  <!-- JS Section untuk interaktivitas masing-masing DOM -->
  <?= $this->renderSection('js') ?>

</body>
</html>
