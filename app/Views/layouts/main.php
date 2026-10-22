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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.php" rel="stylesheet" onerror="this.onerror=null;this.href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css';">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <!-- kosKu Custom Design CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/kosku.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Public Header Navbar -->
  <header class="sticky-top kosku-glass border-bottom border-light-subtle" style="z-index: 1040;">
    <div class="container-fluid px-lg-4 py-2">
      <div class="d-flex justify-content-between align-items-center">
        <!-- Brand Logo -->
        <a href="<?= base_url('/') ?>" class="d-flex align-items-center gap-2 text-decoration-none">
          <div class="btn-icon-circle-dark d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 12px !important;">
            <i class="bi bi-geo-alt-fill text-white fs-5"></i>
          </div>
          <div>
            <span class="fs-4 fw-800 text-dark tracking-tight" style="font-weight: 800; letter-spacing: -0.5px;">kosKu</span>
            <span class="d-none d-md-inline ms-2 text-muted small fw-medium">| Cari kos, langsung dari peta</span>
          </div>
        </a>

        <!-- Middle Quick Nav (Desktop) -->
        <nav class="d-none d-lg-flex align-items-center gap-1 bg-white p-1 rounded-pill border">
          <a href="<?= base_url('/') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('/') ? 'btn-kosku-dark' : 'text-secondary hover-dark' ?>">
            <i class="bi bi-map"></i> Jelajah Peta
          </a>
          <a href="<?= base_url('favorit') ?>" class="btn btn-sm btn-pill position-relative <?= current_url() == base_url('favorit') ? 'btn-kosku-dark' : 'text-secondary hover-dark' ?>">
            <i class="bi bi-heart"></i> Favorit
            <span id="favCountBadge" class="badge rounded-pill bg-danger ms-1" style="display: none; font-size: 10px;">0</span>
          </a>
          <a href="<?= base_url('tentang') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('tentang') ? 'btn-kosku-dark' : 'text-secondary hover-dark' ?>">
            Tentang
          </a>
          <a href="<?= base_url('kontak') ?>" class="btn btn-sm btn-pill <?= current_url() == base_url('kontak') ? 'btn-kosku-dark' : 'text-secondary hover-dark' ?>">
            Bantuan
          </a>
        </nav>

        <!-- Right Action Buttons -->
        <div class="d-flex align-items-center gap-2">
          <a href="<?= base_url('favorit') ?>" class="btn btn-icon-circle d-lg-none position-relative" title="Favorit Saya">
            <i class="bi bi-heart"></i>
          </a>
          <a href="<?= base_url('admin/login') ?>" class="btn btn-kosku-dark btn-pill">
            <i class="bi bi-shield-lock"></i>
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
  <div id="compareFloatingBar" class="compare-floating-bar">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-columns-gap fs-5"></i>
      <span><strong id="compareCount">0</strong> kos terpilih untuk dibandingkan</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-sm btn-light btn-pill fw-bold" onclick="KosKu.showCompareModal()">
        Bandingkan Sekarang
      </button>
      <button class="btn btn-sm btn-outline-secondary btn-icon-circle text-white border-white p-0" style="width: 28px; height: 28px;" onclick="KosKu.clearCompare()" title="Batal">
        <i class="bi bi-x fs-6"></i>
      </button>
    </div>
  </div>

  <!-- Compare Modal -->
  <div class="modal fade" id="compareModal" tabindex="-1" aria-labelledby="compareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content rounded-kosku-xl border-0 shadow-lg">
        <div class="modal-header border-bottom px-4 py-3">
          <div class="d-flex align-items-center gap-2">
            <div class="btn-icon-circle-dark" style="width: 34px; height: 34px;">
              <i class="bi bi-columns-gap"></i>
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
    <div id="koskuToast" class="toast align-items-center text-bg-dark border-0 rounded-pill px-3 py-1" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-info-circle"></i>
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
