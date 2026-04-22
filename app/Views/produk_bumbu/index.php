<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

  :root {
    --ff-body: 'Plus Jakarta Sans', system-ui, sans-serif;
    --ff-mono: 'JetBrains Mono', monospace;

    --bg-page: #f0f4f8;
    --bg-card: #ffffff;
    --border: rgba(0, 0, 0, .07);
    --border-md: rgba(0, 0, 0, .12);

    --accent: #2563eb;
    --accent-2: #7c3aed;
    --green: #059669;
    --red: #dc2626;
    --amber: #d97706;

    --text-1: #0f172a;
    --text-2: #475569;
    --text-3: #94a3b8;

    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;

    --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, .08), 0 2px 4px rgba(0, 0, 0, .04);
    --shadow-lg: 0 20px 48px rgba(0, 0, 0, .14), 0 8px 16px rgba(0, 0, 0, .06);
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: var(--ff-body);
  }

  /* ── PAGE HEADER ─────────────────────────────── */
  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }

  .page-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -.03em;
  }

  .page-title-icon {
    width: 38px;
    height: 38px;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, .35);
  }

  .page-subtitle {
    font-size: .8rem;
    color: var(--text-2);
    margin-top: 3px;
  }

  .header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
  }

  /* ── STAT CARDS ─────────────────────────────── */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }

  @media (max-width: 900px) {
    .stat-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 500px) {
    .stat-grid {
      grid-template-columns: 1fr;
    }
  }

  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    gap: 14px;
    align-items: flex-start;
    transition: transform .18s, box-shadow .18s;
    position: relative;
    overflow: hidden;
  }

  .stat-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    opacity: .04;
    transform: translate(20px, -20px);
  }

  .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
  }

  .stat-card.blue::after {
    background: #2563eb;
  }

  .stat-card.green::after {
    background: #059669;
  }

  .stat-card.amber::after {
    background: #d97706;
  }

  .stat-card.red::after {
    background: #dc2626;
  }

  .stat-icon {
    width: 42px;
    height: 42px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
  }

  .ic-blue {
    background: rgba(37, 99, 235, .1);
    color: #2563eb;
  }

  .ic-green {
    background: rgba(5, 150, 105, .1);
    color: #059669;
  }

  .ic-amber {
    background: rgba(217, 119, 6, .1);
    color: #d97706;
  }

  .ic-red {
    background: rgba(220, 38, 38, .1);
    color: #dc2626;
  }

  .stat-body {}

  .stat-val {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -.03em;
    line-height: 1;
  }

  .stat-label {
    font-size: .72rem;
    color: var(--text-2);
    margin-top: 3px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .05em;
  }

  /* ── TOOLBAR ─────────────────────────────────── */
  .toolbar {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 12px 16px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-sm);
  }

  .search-wrap {
    flex: 1;
    min-width: 180px;
    position: relative;
  }

  .search-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-3);
    font-size: .85rem;
    pointer-events: none;
  }

  .sim-input,
  .sim-select {
    font-family: var(--ff-body);
    background: #f8fafc;
    border: 1px solid var(--border-md);
    color: var(--text-1);
    border-radius: var(--radius-sm);
    padding: 7px 12px;
    font-size: .82rem;
    outline: none;
    transition: border-color .15s;
    width: 100%;
  }

  .search-wrap .sim-input {
    padding-left: 32px;
  }

  .sim-input:focus,
  .sim-select:focus {
    border-color: var(--accent);
    background: #fff;
  }

  .sim-select {
    width: auto;
    cursor: pointer;
  }

  .toolbar-divider {
    width: 1px;
    height: 26px;
    background: var(--border-md);
    flex-shrink: 0;
  }

  /* ── TABS ─────────────────────────────────────── */
  .tabs {
    display: flex;
    gap: 2px;
    background: #f1f5f9;
    border-radius: var(--radius-md);
    padding: 3px;
    width: fit-content;
  }

  .tab-btn {
    padding: 6px 16px;
    border: none;
    background: none;
    color: var(--text-2);
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: var(--radius-sm);
    transition: all .15s;
    white-space: nowrap;
    font-family: var(--ff-body);
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .tab-btn.active {
    background: #fff;
    color: var(--accent);
    box-shadow: var(--shadow-sm);
  }

  /* ── CONTENT HEADER ──────────────────────────── */
  .content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 8px;
  }

  .content-label {
    font-size: .82rem;
    color: var(--text-2);
    font-weight: 500;
  }

  /* ── PRODUCT GRID ────────────────────────────── */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 14px;
  }

  .product-grid.list-view {
    grid-template-columns: 1fr;
  }

  .product-grid.list-view .product-card {
    flex-direction: row;
    align-items: center;
    padding: 14px 18px;
  }

  .product-grid.list-view .product-card-top {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
  }

  .product-grid.list-view .product-card-emoji {
    margin-bottom: 0;
    flex-shrink: 0;
  }

  .product-grid.list-view .product-card-stok-block {
    text-align: right;
    flex-shrink: 0;
    min-width: 80px;
  }

  .product-grid.list-view .product-card-divider,
  .product-grid.list-view .product-card-info {
    display: none;
  }

  .product-grid.list-view .product-card-actions {
    margin-top: 0;
    margin-left: 12px;
    flex-shrink: 0;
  }

  .product-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px;
    box-shadow: var(--shadow-sm);
    transition: transform .18s, border-color .18s, box-shadow .18s;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
  }

  .product-card:hover {
    transform: translateY(-3px);
    border-color: rgba(37, 99, 235, .2);
    box-shadow: var(--shadow-md);
  }

  .product-card.stok-habis {
    border-color: rgba(220, 38, 38, .2);
    background: #fff8f8;
  }

  .product-card.stok-nipis {
    border-color: rgba(217, 119, 6, .2);
    background: #fffdf5;
  }

  .product-card-emoji {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, rgba(37, 99, 235, .07), rgba(124, 58, 237, .07));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.45rem;
    margin-bottom: 12px;
  }

  .product-card-sku {
    font-family: var(--ff-mono);
    font-size: .68rem;
    color: var(--text-3);
    margin-bottom: 3px;
    font-weight: 600;
  }

  .product-card-name {
    font-size: .9rem;
    font-weight: 700;
    color: var(--text-1);
    line-height: 1.35;
    margin-bottom: 12px;
  }

  .product-card-stok-block {
    margin-bottom: 2px;
  }

  .product-card-stok {
    font-size: 2.1rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -2px;
  }

  .product-card-stok-sub {
    font-size: .68rem;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .product-card-divider {
    height: 1px;
    background: var(--border);
    margin: 12px 0;
  }

  .product-card-info {
    display: flex;
    justify-content: space-between;
  }

  .pc-info-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .pc-info-label {
    font-size: .63rem;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .7px;
    font-weight: 600;
  }

  .pc-info-val {
    font-size: .82rem;
    font-weight: 700;
  }

  .product-card-actions {
    display: flex;
    gap: 6px;
    margin-top: 14px;
  }

  .btn-card {
    flex: 1;
    padding: 7px 4px;
    border: none;
    border-radius: var(--radius-sm);
    font-size: .72rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all .15s;
    font-family: var(--ff-body);
  }

  .btn-card-blue {
    background: rgba(37, 99, 235, .08);
    color: #2563eb;
    border: 1px solid rgba(37, 99, 235, .18);
  }

  .btn-card-blue:hover {
    background: rgba(37, 99, 235, .14);
  }

  .btn-card-gray {
    background: rgba(100, 116, 139, .08);
    color: #64748b;
    border: 1px solid rgba(100, 116, 139, .18);
  }

  .btn-card-gray:hover {
    background: rgba(100, 116, 139, .15);
  }

  .btn-card-purple {
    background: rgba(124, 58, 237, .08);
    color: #7c3aed;
    border: 1px solid rgba(124, 58, 237, .18);
  }

  .btn-card-purple:hover {
    background: rgba(124, 58, 237, .14);
  }

  /* ── TABLE ───────────────────────────────────── */
  .table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }

  .table-responsive {
    overflow-x: auto;
  }

  .sim-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .83rem;
  }

  .sim-table thead tr {
    background: #f8fafc;
  }

  .sim-table thead th {
    padding: 11px 16px;
    text-align: left;
    color: var(--text-2);
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }

  .sim-table thead th.tr {
    text-align: right;
  }

  .sim-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .12s;
  }

  .sim-table tbody tr:last-child {
    border-bottom: none;
  }

  .sim-table tbody tr:hover {
    background: #f8fafc;
  }

  .sim-table tbody td {
    padding: 11px 16px;
    color: var(--text-1);
    vertical-align: middle;
    white-space: nowrap;
  }

  .sim-table tbody td.tr {
    text-align: right;
  }

  /* ── BADGES ──────────────────────────────────── */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: .67rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .badge-success {
    background: rgba(5, 150, 105, .1);
    color: #047857;
    border: 1px solid rgba(5, 150, 105, .2);
  }

  .badge-warning {
    background: rgba(217, 119, 6, .1);
    color: #b45309;
    border: 1px solid rgba(217, 119, 6, .2);
  }

  .badge-danger {
    background: rgba(220, 38, 38, .1);
    color: #b91c1c;
    border: 1px solid rgba(220, 38, 38, .2);
  }

  .badge-in {
    background: rgba(5, 150, 105, .1);
    color: #047857;
    border: 1px solid rgba(5, 150, 105, .2);
  }

  .badge-out {
    background: rgba(220, 38, 38, .1);
    color: #b91c1c;
    border: 1px solid rgba(220, 38, 38, .2);
  }

  /* ── BUTTONS ─────────────────────────────────── */
  .btn-primary {
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    border: none;
    color: #fff;
    border-radius: var(--radius-sm);
    padding: 8px 18px;
    font-size: .82rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity .15s, transform .15s;
    font-family: var(--ff-body);
    box-shadow: 0 3px 10px rgba(37, 99, 235, .3);
  }

  .btn-primary:hover {
    opacity: .9;
    transform: translateY(-1px);
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .btn-ghost {
    background: transparent;
    border: 1px solid var(--border-md);
    color: var(--text-2);
    border-radius: var(--radius-sm);
    padding: 6px 10px;
    font-size: .78rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all .15s;
    font-family: var(--ff-body);
  }

  .btn-ghost:hover {
    border-color: rgba(37, 99, 235, .4);
    color: var(--accent);
    background: rgba(37, 99, 235, .04);
  }

  .btn-ghost.purple:hover {
    border-color: rgba(124, 58, 237, .4);
    color: #7c3aed;
    background: rgba(124, 58, 237, .04);
  }

  .btn-ghost.red:hover {
    border-color: rgba(220, 38, 38, .4);
    color: #dc2626;
    background: rgba(220, 38, 38, .04);
  }

  .view-toggle {
    display: flex;
    border: 1px solid var(--border-md);
    border-radius: var(--radius-sm);
    overflow: hidden;
  }

  .view-toggle button {
    background: none;
    border: none;
    padding: 6px 10px;
    color: var(--text-3);
    cursor: pointer;
    font-size: .85rem;
    transition: all .15s;
  }

  .view-toggle button.active {
    background: var(--accent);
    color: #fff;
  }

  /* ── MODAL ───────────────────────────────────── */
  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(15, 23, 42, .5);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .modal-overlay.visible {
    display: flex;
  }

  .modal-box {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 28px;
    width: 100%;
    max-width: 460px;
    box-shadow: var(--shadow-lg);
    animation: modalPop .22s cubic-bezier(.34, 1.56, .64, 1);
  }

  @keyframes modalPop {
    from {
      transform: scale(.9);
      opacity: 0;
    }

    to {
      transform: scale(1);
      opacity: 1;
    }
  }

  .modal-header {
    margin-bottom: 20px;
  }

  .modal-title {
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-1);
    letter-spacing: -.02em;
  }

  .modal-subtitle {
    font-size: .78rem;
    color: var(--text-2);
    margin-top: 3px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
  }

  .form-row.full {
    grid-template-columns: 1fr;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  .form-label {
    font-size: .65rem;
    text-transform: uppercase;
    letter-spacing: .09em;
    color: var(--text-2);
    font-weight: 700;
  }

  .form-control {
    font-family: var(--ff-body);
    padding: 9px 12px;
    border: 1px solid var(--border-md);
    border-radius: var(--radius-sm);
    background: #f8fafc;
    color: var(--text-1);
    font-size: .85rem;
    outline: none;
    transition: border-color .15s, background .15s;
  }

  .form-control:focus {
    border-color: var(--accent);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
  }

  .form-control[readonly] {
    cursor: default;
    color: var(--text-2);
  }

  .form-hint {
    font-size: .7rem;
    color: var(--text-3);
    margin-top: 2px;
  }

  .modal-footer {
    display: flex;
    gap: 8px;
    margin-top: 20px;
  }

  .btn-cancel {
    flex: 1;
    padding: 10px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-md);
    background: none;
    color: var(--text-2);
    font-size: .82rem;
    cursor: pointer;
    font-weight: 600;
    transition: all .15s;
    font-family: var(--ff-body);
  }

  .btn-cancel:hover {
    background: #f1f5f9;
    color: var(--text-1);
  }

  .btn-submit {
    flex: 2;
    padding: 10px;
    border-radius: var(--radius-sm);
    border: none;
    color: #fff;
    font-size: .82rem;
    cursor: pointer;
    font-weight: 700;
    transition: opacity .15s;
    font-family: var(--ff-body);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
  }

  .btn-submit:hover {
    opacity: .88;
  }

  .btn-submit.grad-blue {
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    box-shadow: 0 3px 10px rgba(37, 99, 235, .3);
  }

  .btn-submit.grad-red {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    box-shadow: 0 3px 10px rgba(220, 38, 38, .3);
  }

  /* ── RIWAYAT MODAL SCROLLABLE TABLE ─────────── */
  .riwayat-table-wrap {
    max-height: 380px;
    overflow-y: auto;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
  }

  .riwayat-table-wrap .sim-table tbody td {
    white-space: normal;
  }

  /* stok display di modal */
  .stok-display {
    background: #f8fafc;
    border: 1px solid var(--border-md);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }

  .stok-display-label {
    font-size: .72rem;
    color: var(--text-2);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
  }

  .stok-display-val {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -.02em;
  }

  /* ── EMPTY STATE ─────────────────────────────── */
  .empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-3);
  }

  .empty-state i {
    font-size: 2.5rem;
    display: block;
    margin-bottom: 10px;
    opacity: .3;
  }

  .empty-state p {
    font-size: .85rem;
  }

  /* ── TOAST ───────────────────────────────────── */
  #toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    padding: 12px 18px;
    border-radius: var(--radius-md);
    font-size: .82rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    pointer-events: none;
    max-width: 340px;
    transform: translateY(80px) scale(.95);
    opacity: 0;
    transition: all .3s cubic-bezier(.34, 1.56, .64, 1);
    box-shadow: var(--shadow-lg);
  }

  #toast.show {
    transform: translateY(0) scale(1);
    opacity: 1;
  }

  #toast.success {
    background: #fff;
    border: 1px solid rgba(5, 150, 105, .3);
    color: #047857;
  }

  #toast.error {
    background: #fff;
    border: 1px solid rgba(220, 38, 38, .3);
    color: #dc2626;
  }

  /* ── LOADING ─────────────────────────────────── */
  #loading {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 2000;
    background: rgba(255, 255, 255, .45);
  }

  #loading.on {
    display: block;
  }

  /* ── STOK NIPIS PULSE ────────────────────────── */
  @keyframes pulse-red {

    0%,
    100% {
      opacity: 1
    }

    50% {
      opacity: .5
    }
  }

  .pulse-red {
    animation: pulse-red 1.8s infinite;
  }

  @keyframes pulse-amber {

    0%,
    100% {
      opacity: 1
    }

    50% {
      opacity: .6
    }
  }

  .pulse-amber {
    animation: pulse-amber 2.2s infinite;
  }

  /* code tag */
  code {
    font-family: var(--ff-mono);
    font-size: .72rem;
    background: #f1f5f9;
    padding: 2px 7px;
    border-radius: 5px;
    color: var(--accent);
    font-weight: 600;
  }

  /* ── RESPONSIVE ──────────────────────────────── */
  @media (max-width: 640px) {
    .product-grid {
      grid-template-columns: 1fr 1fr;
    }

    .toolbar {
      flex-direction: column;
      align-items: stretch;
    }

    .toolbar .sim-select {
      width: 100%;
    }

    .toolbar-divider {
      display: none;
    }
  }

  @media (max-width: 380px) {
    .product-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<!-- ── LOADING ──────────────────────────────────────────────────────────── -->
<div id="loading"></div>

<!-- ── TOAST ────────────────────────────────────────────────────────────── -->
<div id="toast"></div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL — TAMBAH / EDIT PRODUK
══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-produk">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title" id="modal-produk-title">Tambah Produk Bumbu</div>
      <div class="modal-subtitle">Isi data produk dengan lengkap</div>
    </div>
    <input type="hidden" id="produk-id">
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Kode Produk *</label>
        <input class="form-control" id="produk-kode" placeholder="BM-001" maxlength="20">
      </div>
      <div class="form-group">
        <label class="form-label">Berat (gram) *</label>
        <input class="form-control" id="produk-berat" type="number" placeholder="250" min="1">
      </div>
    </div>
    <div class="form-row full">
      <div class="form-group">
        <label class="form-label">Nama Produk *</label>
        <input class="form-control" id="produk-nama" placeholder="mis: Bumbu Soto Sitti Nurbaya">
      </div>
    </div>
    <div class="form-row full">
      <div class="form-group">
        <label class="form-label">Harga Jual (Rp) *</label>
        <input class="form-control" id="produk-harga" type="number" placeholder="35000" min="0">
        <div class="form-hint">Harga per kemasan untuk perhitungan nilai stok</div>
      </div>
    </div>
    <div class="form-row full">
      <div class="form-group">
        <label class="form-label">Keterangan</label>
        <input class="form-control" id="produk-ket" placeholder="Opsional — catatan tambahan">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal('modal-produk')">Batal</button>
      <button class="btn-submit grad-blue" onclick="simpanProduk()">
        <i class="bi bi-check-lg"></i> Simpan Produk
      </button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL — HAPUS KONFIRMASI
══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-hapus">
  <div class="modal-box" style="max-width:380px">
    <div class="modal-header">
      <div class="modal-title" style="color:var(--red)">🗑️ Hapus Produk?</div>
      <div class="modal-subtitle">Tindakan ini tidak bisa dibatalkan</div>
    </div>
    <input type="hidden" id="hapus-id">
    <div
      style="background:#fff5f5;border:1px solid rgba(220,38,38,.2);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:4px">
      <div style="font-size:.72rem;color:var(--text-2);text-transform:uppercase;letter-spacing:.06em;font-weight:700">
        Produk yang akan dihapus:</div>
      <div style="font-size:.95rem;font-weight:800;color:var(--red);margin-top:6px" id="hapus-nama">—</div>
      <div style="font-size:.75rem;color:var(--text-2);margin-top:3px" id="hapus-info">—</div>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal('modal-hapus')">Batal</button>
      <button class="btn-submit grad-red" onclick="konfirmasiHapus()">
        <i class="bi bi-trash3"></i> Ya, Hapus
      </button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     MODAL — RIWAYAT STOK PER PRODUK
══════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-riwayat">
  <div class="modal-box" style="max-width:580px">
    <div class="modal-header">
      <div class="modal-title">📋 Riwayat Stok</div>
      <div class="modal-subtitle" id="riwayat-subtitle">—</div>
    </div>

    <!-- Filter tipe di dalam modal -->
    <div style="display:flex;gap:8px;margin-bottom:14px;align-items:center;flex-wrap:wrap">
      <select class="sim-select" id="riwayat-filter-tipe" style="width:auto">
        <option value="">Semua Tipe</option>
        <option value="masuk">Masuk</option>
        <option value="keluar">Keluar</option>
      </select>
      <span id="riwayat-count" style="font-size:.75rem;color:var(--text-3);margin-left:auto">—</span>
    </div>

    <div class="riwayat-table-wrap">
      <table class="sim-table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th class="tr">Jumlah</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody id="tbody-riwayat"></tbody>
      </table>
    </div>

    <div class="modal-footer" style="margin-top:16px">
      <button class="btn-cancel" onclick="closeModal('modal-riwayat')"
        style="flex:none;padding:10px 28px">Tutup</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════════ -->
<div class="page-header">
  <div>
    <div class="page-title">
      <div class="page-title-icon"><i class="bi bi-bag-fill" style="color:#ffffff"></i></div>
      <div>
        Produk Bumbu
        <div class="page-subtitle">Manajemen stok bumbu siap jual — produksi &amp; penjualan</div>
      </div>
    </div>
  </div>
  <button class="btn-primary" onclick="openModalProduk()">
    <i class="bi bi-plus-lg"></i> Tambah Produk
  </button>
</div>

<!-- ══════════════════════════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════════════════════════ -->
<div class="stat-grid">
  <div class="stat-card blue">
    <div class="stat-icon ic-blue"><i class="bi bi-bag-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val" id="s-produk">—</div>
      <div class="stat-label">Total Produk</div>
    </div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon ic-green"><i class="bi bi-box-seam-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val" id="s-stok">—</div>
      <div class="stat-label">Total Kemasan</div>
    </div>
  </div>
  <div class="stat-card amber">
    <div class="stat-icon ic-amber"><i class="bi bi-currency-dollar"></i></div>
    <div class="stat-body">
      <div class="stat-val" id="s-nilai">—</div>
      <div class="stat-label">Nilai Stok</div>
    </div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon ic-red"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div class="stat-body">
      <div class="stat-val" id="s-nipis">—</div>
      <div class="stat-label">Stok Nipis (≤5)</div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     TOOLBAR
══════════════════════════════════════════════════════════════ -->
<div class="toolbar">
  <div class="tabs">
    <button class="tab-btn active" onclick="switchTab('cards',this)">
      <i class="bi bi-grid-3x3-gap-fill"></i> Kartu
    </button>
    <button class="tab-btn" onclick="switchTab('tabel',this)">
      <i class="bi bi-table"></i> Tabel
    </button>
    <button class="tab-btn" onclick="switchTab('log',this)">
      <i class="bi bi-clock-history"></i> Log Mutasi
    </button>
  </div>

  <div class="toolbar-divider"></div>

  <div class="search-wrap">
    <i class="bi bi-search"></i>
    <input class="sim-input" type="text" id="search-input" placeholder="Cari produk…" autocomplete="off">
  </div>

  <select class="sim-select" id="filter-status">
    <option value="">Semua Status</option>
    <option value="ready">Tersedia</option>
    <option value="nipis">Stok Tipis</option>
    <option value="habis">Habis</option>
  </select>

  <div class="toolbar-divider" id="sort-divider"></div>

  <select class="sim-select" id="sort-by" style="display:none">
    <option value="nama">Urutkan: Nama</option>
    <option value="stok_asc">Stok ↑</option>
    <option value="stok_desc">Stok ↓</option>
    <option value="nilai_desc">Nilai ↓</option>
    <option value="harga_desc">Harga ↓</option>
  </select>

  <div class="view-toggle" id="viewToggle">
    <button class="active" onclick="setView('grid',this)" title="Grid"><i class="bi bi-grid-3x3-gap-fill"></i></button>
    <button onclick="setView('list',this)" title="List"><i class="bi bi-list-ul"></i></button>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     TAB: KARTU
══════════════════════════════════════════════════════════════ -->
<div id="tab-cards">
  <div class="content-header">
    <div class="content-label" id="cards-label">—</div>
    <div style="font-size:.75rem;color:var(--text-3)" id="cards-nilai">—</div>
  </div>
  <div class="product-grid" id="produk-grid"></div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     TAB: TABEL
══════════════════════════════════════════════════════════════ -->
<div id="tab-tabel" style="display:none">
  <div class="content-header">
    <div class="content-label" id="tabel-label">—</div>
  </div>
  <div class="table-card">
    <div class="table-responsive">
      <table class="sim-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th class="tr">Berat</th>
            <th class="tr">Harga Jual</th>
            <th class="tr">Stok</th>
            <th class="tr">Nilai Stok</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tbody-produk"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     TAB: LOG MUTASI
══════════════════════════════════════════════════════════════ -->
<div id="tab-log" style="display:none">
  <div class="content-header">
    <div class="content-label">Log mutasi stok — 50 transaksi terakhir</div>
    <div style="display:flex;gap:8px;align-items:center">
      <select class="sim-select" id="log-filter-produk">
        <option value="">Semua Produk</option>
      </select>
      <select class="sim-select" id="log-filter-tipe">
        <option value="">Semua Tipe</option>
        <option value="masuk">Masuk</option>
        <option value="keluar">Keluar</option>
      </select>
    </div>
  </div>
  <div class="table-card">
    <div class="table-responsive">
      <table class="sim-table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Tipe</th>
            <th class="tr">Jumlah</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody id="tbody-log"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════ -->
<script>
  const BASE = '<?= base_url() ?>';
  let CSRF_NAME = '<?= csrf_token() ?>';
  let csrfHash = '<?= csrf_hash() ?>';

  /* ── utils ─────────────────────────────────────────────────── */
  const fmt = n => 'Rp ' + (parseFloat(n) || 0).toLocaleString('id-ID');
  const fmtK = n => {
    const v = parseFloat(n) || 0;
    if (v >= 1e9) return 'Rp ' + (v / 1e9).toFixed(2) + ' M';
    if (v >= 1e6) return 'Rp ' + (v / 1e6).toFixed(1) + ' Jt';
    if (v >= 1e3) return 'Rp ' + (v / 1e3).toFixed(0) + ' Rb';
    return 'Rp ' + v.toLocaleString('id-ID');
  };
  const esc = s => String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  const fmtD = s => {
    if (!s) return '—';
    const d = new Date(s);
    if (isNaN(d)) return s;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
  };
  const fmtNum = n => (parseInt(n) || 0).toLocaleString('id-ID');

  /* ── state ─────────────────────────────────────────────────── */
  let allData = [];
  let allLog = [];
  let activeTab = 'cards';
  let currentView = 'grid';
  let toastTimer;
  let riwayatIdProduk = 0; // id produk yang sedang dibuka riwayatnya

  /* ── toast ─────────────────────────────────────────────────── */
  function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.className = 'show ' + type;
    t.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'x-circle-fill'}"></i><span>${esc(msg)}</span>`;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.className = ''; }, 3800);
  }

  /* ── loading ────────────────────────────────────────────────── */
  function setLoad(v) { document.getElementById('loading').classList.toggle('on', v); }

  /* ── tabs ──────────────────────────────────────────────────── */
  function switchTab(name, btn) {
    activeTab = name;
    ['cards', 'tabel', 'log'].forEach(t => {
      document.getElementById('tab-' + t).style.display = t === name ? '' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const showExtras = name !== 'log';
    document.getElementById('viewToggle').style.display = name === 'cards' ? '' : 'none';
    document.getElementById('sort-by').style.display = showExtras ? '' : 'none';
    document.getElementById('sort-divider').style.display = showExtras ? '' : 'none';

    if (name === 'log') renderLog(getFilteredLog());
    else rerender();
  }

  /* ── view toggle ────────────────────────────────────────────── */
  function setView(v, btn) {
    currentView = v;
    const grid = document.getElementById('produk-grid');
    grid.classList.toggle('list-view', v === 'list');
    document.querySelectorAll('#viewToggle button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  }

  /* ── modal ─────────────────────────────────────────────────── */
  function openModal(id) { document.getElementById(id).classList.add('visible'); }
  function closeModal(id) { document.getElementById(id).classList.remove('visible'); }
  document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function (e) { if (e.target === this) this.classList.remove('visible'); });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.visible').forEach(m => m.classList.remove('visible'));
  });

  /* ── modal produk ───────────────────────────────────────────── */
  function openModalProduk(row = null) {
    document.getElementById('produk-id').value = row?.id ?? '';
    document.getElementById('produk-kode').value = row?.kode ?? '';
    document.getElementById('produk-nama').value = row?.nama ?? '';
    document.getElementById('produk-berat').value = row?.berat_gram ?? 250;
    document.getElementById('produk-harga').value = row?.harga_jual ?? '';
    document.getElementById('produk-ket').value = row?.keterangan ?? '';
    document.getElementById('modal-produk-title').textContent = row ? '✏️ Edit Produk' : '✨ Tambah Produk Bumbu';
    openModal('modal-produk');
    setTimeout(() => document.getElementById(row ? 'produk-nama' : 'produk-kode').focus(), 50);
  }
  function openModalProdukById(id) {
    const row = allData.find(r => +r.id === +id);
    if (row) openModalProduk(row);
  }

  /* ── modal hapus ────────────────────────────────────────────── */
  function openModalHapus(id, nama, stok) {
    document.getElementById('hapus-id').value = id;
    document.getElementById('hapus-nama').textContent = nama;
    document.getElementById('hapus-info').textContent = `Sisa stok: ${fmtNum(stok)} kemasan`;
    openModal('modal-hapus');
  }

  /* ── modal riwayat stok per produk ─────────────────────────── */
  function openRiwayat(idProduk, nama) {
    riwayatIdProduk = +idProduk;
    document.getElementById('riwayat-subtitle').textContent = nama;
    document.getElementById('riwayat-filter-tipe').value = '';
    renderRiwayat();
    openModal('modal-riwayat');
  }

  function renderRiwayat() {
    const tipe = document.getElementById('riwayat-filter-tipe').value;
    const rows = allLog.filter(r => {
      const matchId = +r.id_produk === riwayatIdProduk;
      const matchTipe = !tipe || r.tipe === tipe;
      return matchId && matchTipe;
    });

    document.getElementById('riwayat-count').textContent = rows.length + ' transaksi';

    const tbody = document.getElementById('tbody-riwayat');
    if (!rows.length) {
      tbody.innerHTML = `<tr><td colspan="4"><div class="empty-state">
      <i class="bi bi-clock"></i><p>Belum ada riwayat stok untuk produk ini.</p>
    </div></td></tr>`;
      return;
    }

    tbody.innerHTML = rows.map(r => {
      const isMasuk = r.tipe === 'masuk';
      return `<tr>
      <td style="font-size:.78rem;color:var(--text-2);white-space:nowrap">${fmtD(r.tanggal)}</td>
      <td><span class="badge ${isMasuk ? 'badge-in' : 'badge-out'}">${isMasuk ? '↑ Masuk' : '↓ Keluar'}</span></td>
      <td class="tr" style="font-weight:800;color:${isMasuk ? 'var(--green)' : 'var(--red)'}">${fmtNum(r.jumlah)}</td>
      <td style="font-size:.78rem;color:var(--text-2)">${esc(r.keterangan || '—')}</td>
    </tr>`;
    }).join('');
  }

  // filter tipe di dalam modal riwayat
  document.getElementById('riwayat-filter-tipe').addEventListener('change', renderRiwayat);

  /* ── POST helper ────────────────────────────────────────────── */
  async function post(url, body) {
    body[CSRF_NAME] = csrfHash;
    const r = await fetch(BASE + url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfHash,
      },
      body: JSON.stringify(body)
    });
    const data = await r.json();
    if (data.csrf_hash) csrfHash = data.csrf_hash;
    return data;
  }

  /* ── actions ────────────────────────────────────────────────── */
  async function simpanProduk() {
    const kode = document.getElementById('produk-kode').value.trim();
    const nama = document.getElementById('produk-nama').value.trim();
    const harga = document.getElementById('produk-harga').value;
    if (!kode) { showToast('Kode produk wajib diisi.', 'error'); document.getElementById('produk-kode').focus(); return; }
    if (!nama) { showToast('Nama produk wajib diisi.', 'error'); document.getElementById('produk-nama').focus(); return; }
    if (!harga) { showToast('Harga jual wajib diisi.', 'error'); document.getElementById('produk-harga').focus(); return; }

    setLoad(true);
    const res = await post('/produk-bumbu/simpan', {
      id: document.getElementById('produk-id').value,
      kode, nama,
      berat_gram: document.getElementById('produk-berat').value || 250,
      harga_jual: harga,
      keterangan: document.getElementById('produk-ket').value,
    });
    setLoad(false);
    if (res.success) { showToast(res.message); closeModal('modal-produk'); loadData(); }
    else showToast(res.error || 'Gagal menyimpan.', 'error');
  }

  async function konfirmasiHapus() {
    const id = document.getElementById('hapus-id').value;
    if (!id) return;
    setLoad(true);
    const res = await post('/produk-bumbu/hapus', { id });
    setLoad(false);
    if (res.success) { showToast(res.message); closeModal('modal-hapus'); loadData(); }
    else showToast(res.error || 'Gagal menghapus.', 'error');
  }

  /* ── filter & sort ──────────────────────────────────────────── */
  function statusInfo(stok) {
    stok = parseInt(stok) || 0;
    if (stok <= 0) return { cls: 'danger', label: 'Habis', color: 'var(--red)', cardClass: 'stok-habis' };
    if (stok <= 5) return { cls: 'warning', label: 'Stok Tipis', color: 'var(--amber)', cardClass: 'stok-nipis' };
    return { cls: 'success', label: 'Tersedia', color: 'var(--green)', cardClass: '' };
  }

  function getFiltered() {
    const q = document.getElementById('search-input').value.toLowerCase().trim();
    const status = document.getElementById('filter-status').value;
    const sort = document.getElementById('sort-by').value;

    let list = allData.filter(r => {
      const stok = parseInt(r.stok) || 0;
      const matchQ = !q || r.nama.toLowerCase().includes(q) || r.kode.toLowerCase().includes(q) || (r.keterangan || '').toLowerCase().includes(q);
      let matchS = true;
      if (status === 'ready') matchS = stok > 5;
      if (status === 'nipis') matchS = stok > 0 && stok <= 5;
      if (status === 'habis') matchS = stok <= 0;
      return matchQ && matchS;
    });

    list.sort((a, b) => {
      if (sort === 'stok_asc') return (parseInt(a.stok) || 0) - (parseInt(b.stok) || 0);
      if (sort === 'stok_desc') return (parseInt(b.stok) || 0) - (parseInt(a.stok) || 0);
      if (sort === 'nilai_desc') return (parseInt(b.stok) * parseFloat(b.harga_jual)) - (parseInt(a.stok) * parseFloat(a.harga_jual));
      if (sort === 'harga_desc') return parseFloat(b.harga_jual) - parseFloat(a.harga_jual);
      return a.nama.localeCompare(b.nama, 'id');
    });

    return list;
  }

  function getFilteredLog() {
    const produkFilter = document.getElementById('log-filter-produk').value;
    const tipeFilter = document.getElementById('log-filter-tipe').value;
    return allLog.filter(r => {
      const matchP = !produkFilter || String(r.id_produk) === produkFilter;
      const matchT = !tipeFilter || r.tipe === tipeFilter;
      return matchP && matchT;
    });
  }

  /* ── render kartu ───────────────────────────────────────────── */
  function renderCards(list) {
    const grid = document.getElementById('produk-grid');
    const totalNilai = list.reduce((s, r) => s + (parseInt(r.stok) || 0) * parseFloat(r.harga_jual || 0), 0);

    document.getElementById('cards-label').textContent = `${list.length} produk ditampilkan`;
    document.getElementById('cards-nilai').textContent = `Total nilai: ${fmtK(totalNilai)}`;

    if (!list.length) {
      grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="bi bi-bag"></i><p>Tidak ada produk yang cocok dengan filter.</p></div>`;
      return;
    }

    grid.innerHTML = list.map(r => {
      const stok = parseInt(r.stok) || 0;
      const s = statusInfo(stok);
      const nilai = stok * parseFloat(r.harga_jual || 0);
      const pulseClass = stok <= 0 ? 'pulse-red' : stok <= 5 ? 'pulse-amber' : '';

      return `<div class="product-card ${s.cardClass}">
      <div class="product-card-top">
        <div class="product-card-emoji"><i class="bi bi-bag-fill" style="color:var(--accent)"></i></div>
        <div style="flex:1;min-width:0">
          <div class="product-card-sku">${esc(r.kode)} &middot; ${parseInt(r.berat_gram) || 0}g</div>
          <div class="product-card-name">${esc(r.nama)}</div>
        </div>
      </div>

      <div class="product-card-stok-block">
        <div class="product-card-stok ${pulseClass}" style="color:${s.color}">${fmtNum(stok)}</div>
        <div class="product-card-stok-sub">
          kemasan &nbsp;<span class="badge badge-${s.cls}">${s.label}</span>
        </div>
      </div>

      <div class="product-card-divider"></div>
      <div class="product-card-info">
        <div class="pc-info-item">
          <div class="pc-info-label">Harga Jual</div>
          <div class="pc-info-val" style="color:var(--green)">${fmt(r.harga_jual)}</div>
        </div>
        <div class="pc-info-item" style="text-align:right">
          <div class="pc-info-label">Nilai Stok</div>
          <div class="pc-info-val" style="color:var(--accent)">${fmtK(nilai)}</div>
        </div>
      </div>

      <div class="product-card-actions">
        <button class="btn-card btn-card-purple" onclick='openRiwayat(${r.id},"${esc(r.nama)}")' title="Riwayat Stok">
          <i class="bi bi-clock-history"></i> Riwayat
        </button>
        <button class="btn-card btn-card-blue" onclick='openModalProdukById(${r.id})' title="Edit Produk">
          <i class="bi bi-pencil"></i> Edit
        </button>
        <button class="btn-card btn-card-gray" onclick='openModalHapus(${r.id},"${esc(r.nama)}",${stok})' title="Hapus Produk">
          <i class="bi bi-trash3"></i>
        </button>
      </div>
    </div>`;
    }).join('');
  }

  /* ── render tabel ───────────────────────────────────────────── */
  function renderTabel(list) {
    const tbody = document.getElementById('tbody-produk');
    document.getElementById('tabel-label').textContent = `${list.length} produk`;

    if (!list.length) {
      tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><i class="bi bi-bag"></i><p>Tidak ada produk yang cocok.</p></div></td></tr>`;
      return;
    }

    tbody.innerHTML = list.map((r, i) => {
      const stok = parseInt(r.stok) || 0;
      const s = statusInfo(stok);
      const nilai = stok * parseFloat(r.harga_jual || 0);
      return `<tr>
      <td style="color:var(--text-3);font-size:.78rem">${i + 1}</td>
      <td><code>${esc(r.kode)}</code></td>
      <td style="font-weight:600;max-width:220px;overflow:hidden;text-overflow:ellipsis">${esc(r.nama)}</td>
      <td class="tr" style="color:var(--text-2)">${parseInt(r.berat_gram) || 0} g</td>
      <td class="tr" style="color:var(--green);font-weight:700">${fmt(r.harga_jual)}</td>
      <td class="tr" style="font-weight:800;color:${s.color}">${fmtNum(stok)}</td>
      <td class="tr" style="color:var(--accent);font-weight:700">${fmtK(nilai)}</td>
      <td><span class="badge badge-${s.cls}">${s.label}</span></td>
      <td>
        <div style="display:flex;gap:4px">
          <button class="btn-ghost purple" onclick='openRiwayat(${r.id},"${esc(r.nama)}")' title="Riwayat Stok">
            <i class="bi bi-clock-history"></i>
          </button>
          <button class="btn-ghost" onclick='openModalProdukById(${r.id})' title="Edit">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn-ghost red" onclick='openModalHapus(${r.id},"${esc(r.nama)}",${stok})' title="Hapus">
            <i class="bi bi-trash3"></i>
          </button>
        </div>
      </td>
    </tr>`;
    }).join('');
  }

  /* ── render log ─────────────────────────────────────────────── */
  function renderLog(rows) {
    const tbody = document.getElementById('tbody-log');
    if (!rows.length) {
      tbody.innerHTML = `<tr><td colspan="5"><div class="empty-state"><i class="bi bi-clock"></i><p>Tidak ada log mutasi.</p></div></td></tr>`;
      return;
    }
    tbody.innerHTML = rows.map(r => {
      const isMasuk = r.tipe === 'masuk';
      return `<tr>
      <td style="color:var(--text-2);font-size:.78rem;white-space:nowrap">${fmtD(r.tanggal)}</td>
      <td style="font-weight:600">${esc(r.nama_produk)}</td>
      <td><span class="badge ${isMasuk ? 'badge-in' : 'badge-out'}">${isMasuk ? '↑ Masuk' : '↓ Keluar'}</span></td>
      <td class="tr" style="font-weight:800;color:${isMasuk ? 'var(--green)' : 'var(--red)'}">${fmtNum(r.jumlah)}</td>
      <td style="font-size:.78rem;color:var(--text-2)">${esc(r.keterangan || '—')}</td>
    </tr>`;
    }).join('');
  }

  /* ── populate log filter ────────────────────────────────────── */
  function populateLogFilter() {
    const sel = document.getElementById('log-filter-produk');
    const cur = sel.value;
    sel.innerHTML = '<option value="">Semua Produk</option>';
    allData.forEach(r => {
      const opt = document.createElement('option');
      opt.value = r.id;
      opt.textContent = r.nama;
      if (String(r.id) === cur) opt.selected = true;
      sel.appendChild(opt);
    });
  }

  /* ── rerender ────────────────────────────────────────────────── */
  function rerender() {
    const filtered = getFiltered();
    renderCards(filtered);
    renderTabel(filtered);
  }

  /* ── load data ───────────────────────────────────────────────── */
  async function loadData() {
    setLoad(true);
    try {
      const r = await fetch(BASE + '/produk-bumbu/data', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const d = await r.json();
      if (!d.success) throw new Error(d.error || 'Gagal memuat data.');
      if (d.csrf_hash) csrfHash = d.csrf_hash;

      allData = d.list || [];
      allLog = d.log || [];

      // stat cards
      document.getElementById('s-produk').textContent = fmtNum(d.summary.total_produk);
      document.getElementById('s-stok').textContent = fmtNum(d.summary.total_stok) + ' kem';
      document.getElementById('s-nilai').textContent = fmtK(d.summary.total_nilai);
      document.getElementById('s-nipis').textContent = fmtNum(d.summary.stok_nipis) + ' produk';

      populateLogFilter();
      rerender();
      if (activeTab === 'log') renderLog(getFilteredLog());

    } catch (e) {
      showToast('Gagal memuat data: ' + e.message, 'error');
    } finally {
      setLoad(false);
    }
  }

  /* ── event listeners ─────────────────────────────────────────── */
  document.getElementById('search-input').addEventListener('input', rerender);
  document.getElementById('filter-status').addEventListener('change', rerender);
  document.getElementById('sort-by').addEventListener('change', rerender);
  document.getElementById('log-filter-produk').addEventListener('change', () => renderLog(getFilteredLog()));
  document.getElementById('log-filter-tipe').addEventListener('change', () => renderLog(getFilteredLog()));

  // Enter shortcut di form input
  ['produk-kode', 'produk-nama', 'produk-berat', 'produk-harga', 'produk-ket'].forEach((id, i, arr) => {
    document.getElementById(id).addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        if (i < arr.length - 1) document.getElementById(arr[i + 1]).focus();
        else simpanProduk();
      }
    });
  });

  /* ── init ─────────────────────────────────────────────────────── */
  document.getElementById('sort-by').style.display = '';
  document.getElementById('sort-divider').style.display = '';

  loadData();
</script>

<?= $this->endSection() ?>