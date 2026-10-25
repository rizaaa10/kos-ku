<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-9">
      
      <!-- Header Banner -->
      <div class="text-center mb-5">
        <span class="badge badge-coach badge-coach-teal px-3 py-2 text-uppercase mb-3">Tentang Platform</span>
        <h1 class="fw-800 text-dark display-5 tracking-tight mb-3">
          Cari Kos, Langsung dari Peta.
        </h1>
        <p class="text-muted fs-5 mx-auto" style="max-width: 650px;">
          <strong>kosKu</strong> hadir untuk menyelesaikan masalah pencarian kos yang tersebar, tidak terstandar, dan membingungkan dengan visualisasi peta interaktif yang akurat.
        </p>
      </div>

      <!-- Stats Cards -->
      <div class="row g-3 mb-5">
        <div class="col-12 col-md-4">
          <div class="card-kosku-dark p-4 text-center">
            <h2 class="display-6 fw-800 mb-0"><?= esc($totalKos) ?>+</h2>
            <div class="text-secondary small">Data Kos Terverifikasi</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card card-kosku p-4 text-center">
            <h2 class="display-6 fw-800 text-dark mb-0"><?= esc($totalWilayah) ?></h2>
            <div class="text-muted small">Kecamatan &amp; Wilayah</div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card card-kosku p-4 text-center">
            <h2 class="display-6 fw-800 text-dark mb-0">&lt; 30d</h2>
            <div class="text-muted small">Cepat Temukan Kos Idaman</div>
          </div>
        </div>
      </div>

      <!-- Problem and Solution -->
      <div class="card card-kosku p-4 p-md-5 mb-4">
        <h4 class="fw-bold text-dark mb-3">Latar Belakang &amp; Solusi</h4>
        <p class="text-secondary lh-lg mb-4">
          Selama bertahun-tahun, mahasiswa baru dan pekerja perantau harus berkeliling mencari spanduk kos atau bertanya-tanya di grup media sosial dengan informasi yang sering kali usang dan tanpa titik lokasi yang jelas. Sulit mengukur jarak nyata ke kampus atau kantor tanpa melihatnya di peta.
        </p>
        <div class="row g-4 pt-2">
          <div class="col-12 col-md-6">
            <div class="p-3 bg-light rounded-4 h-100">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Cara Lama</h6>
              </div>
              <ul class="text-muted small ps-3 mb-0">
                <li>Informasi tercecer di WhatsApp / Facebook</li>
                <li>Tidak tahu posisi pasti terhadap kampus</li>
                <li>Harga &amp; fasilitas tidak terstandar</li>
                <li>Menghabiskan tenaga survei fisik berulang kali</li>
              </ul>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="p-3 bg-dark text-white rounded-4 h-100">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <h6 class="fw-bold mb-0 text-white">Dengan kosKu</h6>
              </div>
              <ul class="text-secondary small ps-3 mb-0">
                <li>Peta interaktif berbasis OpenStreetMap</li>
                <li>Batas wilayah terpetakan dengan GeoJSON</li>
                <li>Filter harga, jenis kos, dan fasilitas</li>
                <li>Langsung chat WhatsApp pemilik dengan 1 klik</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="card-kosku-dark p-5 text-center mt-5">
        <h3 class="fw-bold text-white mb-2">Siap menemukan kos impian Anda?</h3>
        <p class="text-secondary small mb-4">Jelajahi sebaran kos sekarang juga secara visual dan gratis.</p>
        <div>
          <a href="<?= base_url('/') ?>" class="btn btn-light btn-pill px-4 py-2 fw-bold text-dark">
            <i class="bi bi-map-fill me-1"></i> Buka Peta Pencarian
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
