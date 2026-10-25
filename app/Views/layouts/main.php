<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'kosKu — Platform Pencarian Kos Berbasis Peta') ?></title>
  <meta name="description" content="kosKu adalah website pencarian kos berbasis peta interaktif. Temukan kos terdekat dengan visualisasi peta akurat, harga terstandar, dan kontak langsung pemilik.">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <!-- kosKu Custom Design CSS (CoachPro Pastel & Teal Aesthetic) -->
  <link rel="stylesheet" href="<?= base_url('assets/css/kosku.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Public Header Navbar (Glassmorphic) -->
  <header class="sticky-top" style="z-index: 1040; background: rgba(255, 255, 255, 0.78); backdrop-filter: blur(25px); border-bottom: 1.5px solid rgba(255, 255, 255, 0.85); box-shadow: 0 4px 20px -2px rgba(100, 116, 139, 0.06);">
    <div class="container-fluid px-lg-4 py-2">
      <div class="d-flex justify-content-between align-items-center">

        <!-- Brand Logo -->
        <a href="<?= base_url('/') ?>" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="brand-icon" style="width: 38px; height: 38px; border-radius: 12px;">
            <i class="bi bi-geo-alt-fill text-white fs-5"></i>
          </div>
          <div>
            <span class="fs-4 fw-800 text-dark tracking-tight" style="font-weight: 800; letter-spacing: -0.5px;">kosKu</span>
            <span class="brand-badge d-none d-sm-inline">Peta</span>
            <span class="d-none d-md-inline ms-2 text-muted small fw-medium">| Cari kos, langsung dari peta</span>
          </div>
        </a>

        <!-- Middle Quick Nav (Desktop) -->
        <nav class="d-none d-lg-flex align-items-center gap-1 p-1 rounded-pill" style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 232, 240, 0.8);">
          <a href="<?= base_url('/') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('/') ? 'btn-coach-teal' : 'text-muted hover-teal' ?>">
            <i class="bi bi-map-fill"></i> Jelajah Peta
          </a>
          <a href="<?= base_url('favorit') ?>" class="btn btn-sm btn-pill position-relative <?= current_url() == base_url('favorit') ? 'btn-coach-teal' : 'text-muted hover-teal' ?>">
            <i class="bi bi-heart-fill"></i> Favorit
            <span id="favCountBadge" class="badge rounded-pill bg-danger ms-1" style="display: none; font-size: 10px;">0</span>
          </a>
          <a href="<?= base_url('tentang') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('tentang') ? 'btn-coach-teal' : 'text-muted hover-teal' ?>">
            Tentang
          </a>
          <a href="<?= base_url('kontak') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('kontak') ? 'btn-coach-teal' : 'text-muted hover-teal' ?>">
            Bantuan
          </a>
        </nav>

        <!-- Right Action Buttons -->
        <div class="d-flex align-items-center gap-2">
          <a href="<?= base_url('favorit') ?>" class="topbar-btn d-lg-none" title="Favorit Saya">
            <i class="bi bi-heart"></i>
          </a>
          <a href="<?= base_url('admin/login') ?>" class="btn btn-coach-teal btn-pill">
            <i class="bi bi-shield-lock-fill"></i>
            <span class="d-none d-sm-inline">Panel Admin</span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Dynamic Content -->
  <main class="flex-grow-1">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- Floating Compare Bar -->
  <div id="compareFloatingBar" class="compare-floating-bar" style="background: var(--coach-gradient); border-radius: var(--radius-pill); box-shadow: 0 12px 32px rgba(13, 148, 136, 0.45);">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-columns-gap fs-5 text-white"></i>
      <span class="text-white"><strong id="compareCount">0</strong> kos terpilih untuk dibandingkan</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-sm btn-light btn-pill fw-bold text-teal" onclick="KosKu.showCompareModal()">
        Bandingkan Sekarang
      </button>
      <button class="topbar-btn text-white bg-transparent border-0 p-0" style="width: 28px; height: 28px;" onclick="KosKu.clearCompare()" title="Batal">
        <i class="bi bi-x fs-5"></i>
      </button>
    </div>
  </div>

  <!-- Compare Modal -->
  <div class="modal fade" id="compareModal" tabindex="-1" aria-labelledby="compareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content rounded-4 border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(25px);">
        <div class="modal-header border-bottom px-4 py-3">
          <div class="d-flex align-items-center gap-2">
            <div class="brand-icon" style="width: 34px; height: 34px; border-radius: 10px;">
              <i class="bi bi-columns-gap fs-6"></i>
            </div>
            <h5 class="modal-title fw-bold text-dark mb-0" id="compareModalLabel">Perbandingan Kos</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" id="compareModalBody">
          <!-- Injected via JavaScript -->
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="koskuToast" class="toast align-items-center border-0 rounded-pill px-3 py-1 text-white" style="background: var(--coach-gradient); box-shadow: 0 10px 25px rgba(13, 148, 136, 0.4);" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill fs-6"></i>
          <span id="koskuToastBody">Notifikasi</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5.3 Bundle JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- Custom KosKu JS -->
  <script src="<?= base_url('assets/js/kosku-map.js') ?>"></script>

  <?= $this->renderSection('scripts') ?>
</body>
</html>
