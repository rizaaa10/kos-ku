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
  <!-- Chart.js for Monochrome Analytics -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <!-- kosKu Custom Design CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/kosku.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light">

  <div class="container-fluid p-3 p-lg-4">
    <div class="d-flex gap-4">
      
      <!-- Rounded White Admin Sidebar -->
      <aside class="admin-sidebar d-none d-lg-flex flex-shrink-0">
        <!-- Logo -->
        <div class="d-flex align-items-center gap-3 px-2 mb-4 pb-3 border-bottom">
          <div class="btn-icon-circle-dark" style="width: 42px; height: 42px; border-radius: 14px !important;">
            <i class="bi bi-geo-alt-fill text-white fs-4"></i>
          </div>
          <div>
            <h5 class="fw-bold mb-0 text-dark">kosKu</h5>
            <span class="badge badge-kosku badge-kosku-dark" style="font-size: 10px;">Admin Panel</span>
          </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow-1">
          <div class="small text-muted fw-bold text-uppercase px-3 mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Menu Utama</div>
          <a href="<?= base_url('admin/dashboard') ?>" class="admin-nav-item <?= (current_url() == base_url('admin') || current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
          </a>
          <a href="<?= base_url('admin/kos') ?>" class="admin-nav-item <?= str_contains(current_url(), 'admin/kos') ? 'active' : '' ?>">
            <i class="bi bi-houses"></i>
            <span>Data Kos</span>
          </a>
          <a href="<?= base_url('admin/wilayah') ?>" class="admin-nav-item <?= str_contains(current_url(), 'admin/wilayah') ? 'active' : '' ?>">
            <i class="bi bi-pin-map"></i>
            <span>Wilayah (GIS)</span>
          </a>
          <a href="<?= base_url('admin/fasilitas') ?>" class="admin-nav-item <?= str_contains(current_url(), 'admin/fasilitas') ? 'active' : '' ?>">
            <i class="bi bi-check2-all"></i>
            <span>Master Fasilitas</span>
          </a>
          <a href="<?= base_url('admin/statistik') ?>" class="admin-nav-item <?= str_contains(current_url(), 'admin/statistik') ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Statistik & Trafik</span>
          </a>

          <div class="small text-muted fw-bold text-uppercase px-3 mt-4 mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Aksi Cepat</div>
          <a href="<?= base_url('admin/kos/create') ?>" class="admin-nav-item">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Kos Baru</span>
          </a>
          <a href="<?= base_url('/') ?>" target="_blank" class="admin-nav-item">
            <i class="bi bi-box-arrow-up-right"></i>
            <span>Lihat Website Publik</span>
          </a>
        </nav>

        <!-- User Profile Card -->
        <div class="pt-3 border-top mt-auto">
          <div class="d-flex align-items-center justify-content-between p-2 rounded-4 bg-light">
            <div class="d-flex align-items-center gap-2">
              <div class="btn-icon-circle-dark" style="width: 36px; height: 36px;">
                <i class="bi bi-person text-white"></i>
              </div>
              <div class="overflow-hidden" style="max-width: 120px;">
                <div class="fw-bold small text-truncate"><?= esc(session()->get('user_name') ?? 'Admin') ?></div>
                <div class="text-muted" style="font-size: 10px;"><?= esc(session()->get('user_email') ?? 'admin@kosku.id') ?></div>
              </div>
            </div>
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-sm btn-icon-circle border-0 text-danger" title="Logout">
              <i class="bi bi-box-arrow-right fs-5"></i>
            </a>
          </div>
        </div>
      </aside>

      <!-- Main Body Content -->
      <div class="flex-grow-1 overflow-hidden">
        
        <!-- Top Admin Header -->
        <header class="d-flex justify-content-between align-items-center mb-4">
          <!-- Left Mobile Toggle & Title -->
          <div class="d-flex align-items-center gap-3">
            <div class="dropdown d-lg-none">
              <button class="btn btn-icon-circle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-list fs-4"></i>
              </button>
              <ul class="dropdown-menu rounded-4 shadow border-0 p-2">
                <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a></li>
                <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/kos') ?>"><i class="bi bi-houses me-2"></i> Data Kos</a></li>
                <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/wilayah') ?>"><i class="bi bi-pin-map me-2"></i> Wilayah</a></li>
                <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/fasilitas') ?>"><i class="bi bi-check2-all me-2"></i> Fasilitas</a></li>
                <li><a class="dropdown-item rounded-pill py-2" href="<?= base_url('admin/statistik') ?>"><i class="bi bi-graph-up-arrow me-2"></i> Statistik</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item rounded-pill py-2 text-danger" href="<?= base_url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
              </ul>
            </div>

            <div>
              <h2 class="fw-800 text-dark tracking-tight mb-0" style="font-weight: 800;">
                Halo, <?= esc(session()->get('user_name') ?? 'Admin') ?> 👋
              </h2>
              <p class="text-muted small mb-0">Kelola informasi kos, pemetaan wilayah, dan foto dengan cepat.</p>
            </div>
          </div>

          <!-- Right Buttons -->
          <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('/') ?>" target="_blank" class="btn btn-icon-circle" title="Lihat Website Publik">
              <i class="bi bi-globe2"></i>
            </a>
            <a href="<?= base_url('admin/kos/create') ?>" class="btn btn-kosku-dark btn-pill">
              <i class="bi bi-plus-lg"></i>
              <span>Tambah Kos</span>
            </a>
          </div>
        </header>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-dark border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-white fs-5"></i>
            <div><?= session()->getFlashdata('success') ?></div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= session()->getFlashdata('error') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Terdapat kesalahan pengisian data:</div>
            <ul class="mb-0 ps-3">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Content Section -->
        <?= $this->renderSection('content') ?>

      </div>
    </div>
  </div>

  <!-- Bootstrap 5.3 Bundle JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <?= $this->renderSection('scripts') ?>
</body>
</html>
