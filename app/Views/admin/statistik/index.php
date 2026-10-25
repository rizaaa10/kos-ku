<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 mb-4">
  
  <!-- Stat Card: Total Views (Amber) -->
  <div class="col-12 col-md-4">
    <div class="card-coach h-100">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="stat-label-tiny">Total Kunjungan (Views)</span>
        <div class="stat-icon-circle icon-amber">
          <i class="bi bi-eye-fill"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-dark mb-0"><?= number_format($summary['total_views']) ?></h2>
      <div class="text-muted small mt-1">Dilihat oleh pencari kos di peta &amp; detail</div>
    </div>
  </div>

  <!-- Stat Card: WhatsApp Clicks (Teal) -->
  <div class="col-12 col-md-4">
    <div class="card-coach h-100">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="stat-label-tiny">Klik Kontak WhatsApp</span>
        <div class="stat-icon-circle icon-teal">
          <i class="bi bi-whatsapp"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-teal mb-0"><?= number_format($summary['total_wa']) ?></h2>
      <div class="text-muted small mt-1">Pencari kos yang membuka chat pemilik</div>
    </div>
  </div>

  <!-- Stat Card: Google Maps Clicks (Blue) -->
  <div class="col-12 col-md-4">
    <div class="card-coach h-100">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="stat-label-tiny">Klik Navigasi Google Maps</span>
        <div class="stat-icon-circle icon-blue">
          <i class="bi bi-compass-fill"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-primary mb-0"><?= number_format($summary['total_maps']) ?></h2>
      <div class="text-muted small mt-1">Pencari kos yang membuka rute jalan</div>
    </div>
  </div>

</div>

<!-- Breakdown Table per Kos -->
<div class="card-coach">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold text-dark mb-0">Statistik Performa &amp; Konversi per Kos</h5>
      <p class="text-muted small mb-0">Rasio klik kontak WhatsApp dan penayangan halaman</p>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table-coach">
      <thead>
        <tr>
          <th>Nama Kos</th>
          <th>Wilayah</th>
          <th class="text-center">Tipe</th>
          <th class="text-center">Views</th>
          <th class="text-center">Klik WA</th>
          <th class="text-center">Klik Maps</th>
          <th class="text-center">Conversion Rate</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($statsPerKos)): ?>
          <?php foreach ($statsPerKos as $s): ?>
            <?php 
              $views = (int)$s['view_count'];
              $wa = (int)$s['wa_count'];
              $ctr = $views > 0 ? round(($wa / $views) * 100, 1) : 0;
            ?>
            <tr>
              <td>
                <div class="fw-bold text-dark"><?= esc($s['nama']) ?></div>
              </td>
              <td class="text-muted small"><?= esc($s['nama_wilayah'] ?? '-') ?></td>
              <td class="text-center">
                <span class="badge badge-coach badge-coach-teal text-capitalize"><?= esc($s['tipe']) ?></span>
              </td>
              <td class="text-center fw-bold text-dark"><?= $views ?></td>
              <td class="text-center fw-bold text-teal"><?= $wa ?></td>
              <td class="text-center fw-bold text-primary"><?= (int)$s['maps_count'] ?></td>
              <td class="text-center">
                <span class="badge badge-coach <?= ($ctr >= 15) ? 'badge-coach-available' : 'badge-coach' ?>" style="<?= ($ctr < 15) ? 'background: #f1f5f9; color: #64748b;' : '' ?>">
                  <?= $ctr ?>%
                </span>
              </td>
              <td class="text-end">
                <a href="<?= base_url('kos/' . $s['slug']) ?>" target="_blank" class="topbar-btn d-inline-flex" style="width: 32px; height: 32px;" title="Lihat">
                  <i class="bi bi-box-arrow-up-right"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">Belum ada data kunjungan yang tercatat.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
