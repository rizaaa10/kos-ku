<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-4 py-lg-5">
  
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-decoration-none text-muted">Beranda</a></li>
      <li class="breadcrumb-item"><a href="<?= base_url('/?wilayah_id=' . $kos['wilayah_id']) ?>" class="text-decoration-none text-muted"><?= esc($kos['nama_wilayah'] ?? 'Wilayah') ?></a></li>
      <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?= esc($kos['nama']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">
    
    <!-- Left Column: Photos Gallery, Description, Facilities, Rules -->
    <div class="col-12 col-lg-8">
      
      <!-- Photo Gallery Section -->
      <div class="card-kosku overflow-hidden mb-4 p-2 bg-white">
        <div class="position-relative mb-2">
          <?php if (!empty($kos['fotos'])): ?>
            <img id="mainGalleryImg" src="<?= base_url($kos['fotos'][0]['path_file']) ?>" class="img-fluid w-100 rounded-kosku" style="height: 420px; object-fit: cover;" alt="<?= esc($kos['nama']) ?>">
          <?php else: ?>
            <div class="w-100 rounded-kosku bg-light d-flex flex-column align-items-center justify-content-center text-muted" style="height: 420px;">
              <i class="bi bi-image fs-1 mb-2"></i>
              <span>Foto kos belum tersedia</span>
            </div>
          <?php endif; ?>
          
          <span class="position-absolute top-0 start-0 m-3 badge badge-kosku badge-kosku-dark text-capitalize fs-6 px-3 py-2">
            Kos <?= esc($kos['tipe']) ?>
          </span>

          <?php if ($kos['status_ketersediaan'] === 'tersedia' && (int)$kos['kamar_tersedia'] > 0): ?>
            <span class="position-absolute top-0 end-0 m-3 badge badge-available fs-6 px-3 py-2">
              <i class="bi bi-door-open me-1"></i> Tersedia (Sisa <?= $kos['kamar_tersedia'] ?> Kamar)
            </span>
          <?php else: ?>
            <span class="position-absolute top-0 end-0 m-3 badge badge-full fs-6 px-3 py-2">
              <i class="bi bi-door-closed me-1"></i> Kamar Penuh
            </span>
          <?php endif; ?>
        </div>

        <!-- Thumbnails Strip -->
        <?php if (!empty($kos['fotos']) && count($kos['fotos']) > 1): ?>
          <div class="d-flex gap-2 p-1 overflow-x-auto">
            <?php foreach ($kos['fotos'] as $idx => $foto): ?>
              <img src="<?= base_url($foto['path_file']) ?>" 
                   class="rounded-3 border cursor-pointer hover-opacity <?= ($idx === 0) ? 'border-dark border-2' : '' ?>" 
                   style="width: 84px; height: 60px; object-fit: cover; cursor: pointer;" 
                   onclick="document.getElementById('mainGalleryImg').src = this.src; document.querySelectorAll('.hover-opacity').forEach(e => e.classList.remove('border-dark', 'border-2')); this.classList.add('border-dark', 'border-2');" 
                   alt="Thumbnail <?= $idx ?>">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Title & Price Block -->
      <div class="card card-kosku p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
          <div>
            <span class="badge badge-kosku badge-kosku-light text-uppercase mb-2"><?= esc($kos['nama_wilayah'] ?? 'Wilayah') ?></span>
            <h2 class="fw-800 text-dark mb-1"><?= esc($kos['nama']) ?></h2>
            <p class="text-muted mb-0"><i class="bi bi-geo-alt text-dark me-1"></i> <?= esc($kos['alamat']) ?></p>
          </div>
          <div class="text-lg-end">
            <span class="text-muted small">Harga Sewa Bulanan</span>
            <div class="fs-2 fw-800 text-dark tracking-tight">
              Rp <?= number_format($kos['harga_min'], 0, ',', '.') ?>
              <span class="fs-6 fw-normal text-muted">/ bulan</span>
            </div>
            <?php if (!empty($kos['harga_max']) && $kos['harga_max'] > $kos['harga_min']): ?>
              <span class="badge badge-kosku badge-kosku-light">Hingga Rp <?= number_format($kos['harga_max'], 0, ',', '.') ?> (tipe VIP)</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Deskripsi Kos -->
      <div class="card card-kosku p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-text-paragraph me-2"></i> Deskripsi Kos</h5>
        <div class="text-secondary lh-lg" style="white-space: pre-line;">
          <?= esc($kos['deskripsi'] ?? 'Pemilik belum menambahkan deskripsi lengkap untuk kos ini.') ?>
        </div>
      </div>

      <!-- Fasilitas Kos -->
      <div class="card card-kosku p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-check2-all me-2"></i> Fasilitas Tersedia</h5>
        <?php if (!empty($kos['fasilitas'])): ?>
          <div class="row g-3">
            <?php foreach ($kos['fasilitas'] as $f): ?>
              <div class="col-6 col-md-4">
                <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-light">
                  <div class="btn-icon-circle-dark" style="width: 32px; height: 32px; font-size: 14px;">
                    <i class="bi <?= esc($f['ikon'] ?: 'bi-check') ?>"></i>
                  </div>
                  <div>
                    <div class="fw-bold small text-dark"><?= esc($f['nama']) ?></div>
                    <div class="text-muted" style="font-size: 10px;"><?= esc($f['kategori'] === 'kamar' ? 'Di dalam kamar' : 'Fasilitas umum') ?></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted mb-0">Belum ada daftar fasilitas yang tercatat.</p>
        <?php endif; ?>
      </div>

      <!-- Peraturan Kos -->
      <div class="card card-kosku p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check me-2"></i> Tata Tertib &amp; Peraturan</h5>
        <?php if (!empty($kos['peraturan'])): ?>
          <div class="p-3 bg-light rounded-4 text-secondary lh-lg" style="white-space: pre-line;">
            <?= esc($kos['peraturan']) ?>
          </div>
        <?php else: ?>
          <p class="text-muted mb-0">Tidak ada catatan aturan khusus. Mohon konfirmasi langsung ke pemilik kos.</p>
        <?php endif; ?>
      </div>

      <!-- Mini Map Lokasi Kos -->
      <div class="card card-kosku p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="bi bi-map me-2"></i> Titik Lokasi Peta</h5>
          <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $kos['latitude'] ?>,<?= $kos['longitude'] ?>" 
             target="_blank" 
             class="btn btn-sm btn-kosku-dark btn-pill" 
             onclick="KosKu.track(<?= $kos['id'] ?>, 'klik_maps')">
            <i class="bi bi-arrow-up-right-circle"></i> Buka di Google Maps
          </a>
        </div>
        <div id="miniDetailMap" class="rounded-kosku border" style="height: 320px; width: 100%;"></div>
        <div class="mt-2 text-muted small">
          <i class="bi bi-info-circle me-1"></i> Koordinat: <?= esc($kos['latitude']) ?>, <?= esc($kos['longitude']) ?>
        </div>
      </div>

    </div>

    <!-- Right Column: Sticky Contact Card & Actions -->
    <div class="col-12 col-lg-4">
      <div class="sticky-top" style="top: 86px;">
        
        <!-- Action Card -->
        <div class="card-kosku-dark p-4 mb-4">
          <span class="badge badge-kosku badge-kosku-light mb-2">Informasi Kontak Pemilik</span>
          <h4 class="fw-bold text-white mb-1"><?= esc($kos['kontak_nama']) ?></h4>
          <p class="text-secondary small mb-4">Pengelola resmi kos ini.</p>

          <!-- Pre-filled WA link -->
          <?php 
            $waMessage = rawurlencode("Halo Bapak/Ibu " . $kos['kontak_nama'] . ", saya tertarik dengan kamar di " . $kos['nama'] . " yang saya lihat di kosKu. Apakah saat ini masih ada kamar yang tersedia?");
            $waLink = "https://wa.me/" . esc($kos['kontak_wa']) . "?text=" . $waMessage;
          ?>

          <div class="d-grid gap-2">
            <a href="<?= $waLink ?>" 
               target="_blank" 
               class="btn btn-light btn-pill py-3 fw-bold fs-6 text-dark" 
               onclick="KosKu.track(<?= $kos['id'] ?>, 'klik_wa')">
              <i class="bi bi-whatsapp text-success me-2 fs-5"></i> Hubungi via WhatsApp
            </a>

            <?php if (!empty($kos['kontak_telepon'])): ?>
              <a href="tel:<?= esc($kos['kontak_telepon']) ?>" class="btn btn-outline-light btn-pill py-2">
                <i class="bi bi-telephone me-2"></i> Hubungi via Telepon
              </a>
            <?php endif; ?>

            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $kos['latitude'] ?>,<?= $kos['longitude'] ?>" 
               target="_blank" 
               class="btn btn-outline-light btn-pill py-2" 
               onclick="KosKu.track(<?= $kos['id'] ?>, 'klik_maps')">
              <i class="bi bi-compass me-2"></i> Petunjuk Arah (Navigasi)
            </a>
          </div>

          <hr class="border-secondary my-4">

          <!-- Extra Utility Buttons -->
          <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-sm btn-outline-light btn-pill" onclick="KosKu.toggleFavorite(<?= $kos['id'] ?>, this)">
              <i class="bi bi-heart me-1"></i> Simpan Favorit
            </button>
            <button type="button" class="btn btn-sm btn-outline-light btn-pill" onclick="KosKu.toggleCompare(<?= $kos['id'] ?>)">
              <i class="bi bi-columns-gap me-1"></i> Bandingkan
            </button>
          </div>
        </div>

        <!-- Quick Summary Card -->
        <div class="card card-kosku p-4">
          <h6 class="fw-bold text-dark mb-3">Ringkasan Kos</h6>
          <ul class="list-unstyled mb-0 small text-secondary">
            <li class="d-flex justify-content-between py-2 border-bottom">
              <span>Tipe Hunian</span>
              <span class="fw-bold text-dark text-capitalize">Kos <?= esc($kos['tipe']) ?></span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom">
              <span>Jumlah Kamar</span>
              <span class="fw-bold text-dark"><?= esc($kos['jumlah_kamar']) ?> Kamar</span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom">
              <span>Kamar Tersedia</span>
              <span class="fw-bold text-dark"><?= esc($kos['kamar_tersedia']) ?> Kamar</span>
            </li>
            <li class="d-flex justify-content-between py-2 border-bottom">
              <span>Status</span>
              <span class="badge <?= ($kos['status_ketersediaan'] === 'tersedia') ? 'badge-available' : 'badge-full' ?>">
                <?= esc($kos['status_ketersediaan']) ?>
              </span>
            </li>
            <li class="d-flex justify-content-between py-2">
              <span>Wilayah</span>
              <span class="fw-bold text-dark"><?= esc($kos['nama_wilayah'] ?? '-') ?></span>
            </li>
          </ul>
        </div>

      </div>
    </div>

  </div>

  <!-- Related Kos in Same Area -->
  <?php if (!empty($relatedKos)): ?>
    <div class="mt-5 pt-4 border-top">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fw-bold text-dark mb-1">Kos Lainnya di Wilayah <?= esc($kos['nama_wilayah'] ?? '') ?></h4>
          <p class="text-muted small mb-0">Rekomendasi alternatif di area sekitar.</p>
        </div>
        <a href="<?= base_url('/?wilayah_id=' . $kos['wilayah_id']) ?>" class="btn btn-sm btn-kosku-dark btn-pill">
          Lihat Semua di Peta <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-4">
        <?php foreach ($relatedKos as $rk): ?>
          <div class="col-12 col-md-4">
            <div class="card card-kosku h-100 overflow-hidden">
              <?php if (!empty($rk['foto_utama'])): ?>
                <img src="<?= base_url($rk['foto_utama']['path_file']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?= esc($rk['nama']) ?>">
              <?php else: ?>
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                  <i class="bi bi-image fs-1" aria-label="Foto kos belum tersedia"></i>
                </div>
              <?php endif; ?>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge badge-kosku badge-kosku-dark text-capitalize" style="font-size: 10px;">Kos <?= esc($rk['tipe']) ?></span>
                  <span class="fw-bold text-dark">Rp <?= number_format($rk['harga_min'], 0, ',', '.') ?>/bln</span>
                </div>
                <h6 class="card-title fw-bold text-dark text-truncate mb-2"><?= esc($rk['nama']) ?></h6>
                <a href="<?= base_url('kos/' . $rk['slug']) ?>" class="btn btn-sm btn-kosku-light btn-pill w-100">
                  Lihat Detail
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const lat = <?= (float) $kos['latitude'] ?>;
    const lng = <?= (float) $kos['longitude'] ?>;

    const miniMap = L.map('miniDetailMap', {
      center: [lat, lng],
      zoom: 16,
      zoomControl: true,
      attributionControl: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19
    }).addTo(miniMap);

    const marker = L.marker([lat, lng]).addTo(miniMap)
      .bindPopup('<strong><?= esc(addslashes($kos['nama'])) ?></strong><br><?= esc(addslashes($kos['alamat'])) ?>')
      .openPopup();
  });
</script>
<?= $this->endSection() ?>
