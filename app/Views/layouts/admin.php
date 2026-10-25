<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Panel Admin — kosKu') ?></title>

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <!-- Chart.js for Analytics -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <!-- kosKu Custom Design CSS (CoachPro Aesthetic) -->
  <link rel="stylesheet" href="<?= base_url('assets/css/kosku.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body>

  <!-- Outer Glass Canvas Window -->
  <div class="app-window">

    <!-- Left Integrated Sidebar -->
    <aside class="app-sidebar d-none d-lg-flex">

      <!-- Brand Logo -->
      <a href="<?= base_url('admin/dashboard') ?>" class="app-brand">
        <div class="brand-icon">
          <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div>
          <span class="brand-name">kosKu</span>
          <span class="brand-badge">Pro</span>
        </div>
      </a>

      <!-- Navigation Links -->
      <nav class="app-nav">
        <div class="nav-section-title">Menu Utama</div>

        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link-coach <?= (current_url() == base_url('admin') || current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>">
          <i class="bi bi-grid-fill"></i>
          <span>Dashboard</span>
        </a>

        <a href="<?= base_url('admin/kos') ?>" class="nav-link-coach <?= str_contains(current_url(), 'admin/kos') ? 'active' : '' ?>">
          <i class="bi bi-houses-fill"></i>
          <span>Data Kos</span>
        </a>

        <a href="<?= base_url('admin/wilayah') ?>" class="nav-link-coach <?= str_contains(current_url(), 'admin/wilayah') ? 'active' : '' ?>">
          <i class="bi bi-pin-map-fill"></i>
          <span>Wilayah GIS</span>
        </a>

        <a href="<?= base_url('admin/fasilitas') ?>" class="nav-link-coach <?= str_contains(current_url(), 'admin/fasilitas') ? 'active' : '' ?>">
          <i class="bi bi-check2-circle"></i>
          <span>Fasilitas</span>
        </a>

        <a href="<?= base_url('admin/statistik') ?>" class="nav-link-coach <?= str_contains(current_url(), 'admin/statistik') ? 'active' : '' ?>">
          <i class="bi bi-graph-up-arrow"></i>
          <span>Statistik</span>
        </a>

        <div class="nav-section-title">Aksi &amp; Publik</div>

        <a href="<?= base_url('admin/kos/create') ?>" class="nav-link-coach">
          <i class="bi bi-plus-circle-fill"></i>
          <span>Tambah Kos</span>
        </a>

        <a href="<?= base_url('/') ?>" target="_blank" class="nav-link-coach">
          <i class="bi bi-compass-fill"></i>
          <span>Peta Publik</span>
        </a>
      </nav>

      <!-- Bottom User Profile Item -->
      <div class="pt-3 border-top mt-auto" style="border-color: rgba(226, 232, 240, 0.6) !important;">
        <div class="d-flex align-items-center justify-content-between p-2 rounded-4" style="background: rgba(255, 255, 255, 0.7);">
          <div class="d-flex align-items-center gap-2 overflow-hidden">
            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
              <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
            </div>
            <div class="text-truncate" style="max-width: 110px;">
              <div class="fw-bold small text-dark text-truncate"><?= esc(session()->get('user_name') ?? 'Admin') ?></div>
              <div class="text-muted" style="font-size: 10px;">Administrator</div>
            </div>
          </div>
          <a href="<?= base_url('admin/logout') ?>" class="topbar-btn text-danger" style="width: 32px; height: 32px;" title="Keluar">
            <i class="bi bi-box-arrow-right fs-6"></i>
          </a>
        </div>
      </div>

    </aside>

    <!-- Main Content Area -->
    <main class="app-main">

      <!-- Topbar Header -->
      <header class="app-topbar">

        <!-- Left: Mobile Menu & Titles -->
        <div class="d-flex align-items-center gap-3">
          <div class="dropdown d-lg-none">
            <button class="topbar-btn" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-list fs-5"></i>
            </button>
            <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
              <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-grid-fill me-2 text-teal"></i> Dashboard</a></li>
              <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/kos') ?>"><i class="bi bi-houses-fill me-2"></i> Data Kos</a></li>
              <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/wilayah') ?>"><i class="bi bi-pin-map-fill me-2"></i> Wilayah GIS</a></li>
              <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/fasilitas') ?>"><i class="bi bi-check2-circle me-2"></i> Fasilitas</a></li>
              <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/statistik') ?>"><i class="bi bi-graph-up-arrow me-2"></i> Statistik</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item rounded-pill py-2 text-danger" href="<?= base_url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </div>

          <div>
            <div class="topbar-welcome">Welcome back, <?= esc(session()->get('user_name') ?? 'Admin') ?> ☀️</div>
            <h1 class="topbar-title"><?= esc($headerTitle ?? 'Dashboard') ?></h1>
          </div>
        </div>

        <!-- Right: Actions & Profile -->
        <div class="topbar-actions">
          <a href="<?= base_url('/') ?>" target="_blank" class="topbar-btn" title="Cari di Peta Publik">
            <i class="bi bi-search"></i>
          </a>

          <div class="topbar-btn" title="Notifikasi Sistem">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
          </div>

          <div class="topbar-user d-none d-sm-flex">
            <div class="user-avatar">
              <?= strtoupper(substr(session()->get('user_name') ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-name">
              <?= esc(session()->get('user_name') ?? 'Admin') ?>
            </div>
          </div>
        </div>

      </header>

      <!-- Alert Flash Messages -->
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" style="background: #ccfbf1; color: #0f766e;" role="alert">
          <i class="bi bi-check-circle-fill fs-5"></i>
          <div class="fw-semibold small"><?= esc(session()->getFlashdata('success')) ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" style="background: #fee2e2; color: #b91c1c;" role="alert">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <div class="fw-semibold small"><?= esc(session()->getFlashdata('error')) ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert border-0 rounded-4 shadow-sm mb-4" style="background: #fee2e2; color: #b91c1c;" role="alert">
          <div class="fw-bold mb-1 small"><i class="bi bi-exclamation-octagon-fill me-1"></i> Terjadi kesalahan validasi:</div>
          <ul class="mb-0 ps-3 small">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
              <li><?= esc($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Dynamic Page Content -->
      <?= $this->renderSection('content') ?>

    </main>

  </div>

  <!-- Bootstrap 5.3 Bundle JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <?= $this->renderSection('scripts') ?>
</body>
</html>
