<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4 mb-4">
  
  <!-- Stat Card: Total Views -->
  <div class="col-12 col-md-4">
    <div class="card card-kosku p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-muted small text-uppercase fw-bold">Total Kunjungan (Views)</span>
        <div class="btn-icon-circle-dark" style="width: 38px; height: 38px;">
          <i class="bi bi-eye"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-dark mb-0"><?= number_format($summary['total_views']) ?></h2>
      <div class="text-muted small mt-1">Dilihat oleh pencari kos di peta &amp; detail</div>
    </div>
  </div>

  <!-- Stat Card: WhatsApp Clicks -->
  <div class="col-12 col-md-4">
    <div class="card-kosku-dark p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small text-uppercase fw-bold">Klik Hubungi WhatsApp</span>
        <div class="btn-icon-circle bg-white text-dark" style="width: 38px; height: 38px;">
          <i class="bi bi-whatsapp text-success"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-white mb-0"><?= number_format($summary['total_wa']) ?></h2>
      <div class="text-secondary small mt-1">Pencari kos yang membuka chat pemilik</div>
    </div>
  </div>

  <!-- Stat Card: Google Maps Clicks -->
  <div class="col-12 col-md-4">
    <div class="card card-kosku p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-muted small text-uppercase fw-bold">Klik Navigasi Google Maps</span>
        <div class="btn-icon-circle-dark" style="width: 38px; height: 38px;">
          <i class="bi bi-compass"></i>
        </div>
      </div>
      <h2 class="display-6 fw-800 text-dark mb-0"><?= number_format($summary['total_maps']) ?></h2>
      <div class="text-muted small mt-1">Pencari kos yang membuka arah jalan</div>
    </div>
  </div>

</div>

<!-- Breakdown Table per Kos -->
<div class="card card-kosku p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold text-dark mb-0">Statistik Performa &amp; Konversi per Kos</h5>
      <p class="text-muted small mb-0">Rasio klik kontak pemilik dan kunjungan halaman per kos</p>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Nama Kos</th>
          <th>Wilayah</th>
          <th>Tipe</th>
          <th class="text-center">Jumlah Views</th>
          <th class="text-center">Klik WhatsApp</th>
          <th class="text-center">Klik Maps</th>
          <th class="text-center">Conversion Rate (WA)</th>
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
              <td><?= esc($s['nama_wilayah'] ?? '-') ?></td>
              <td>
                <span class="badge badge-kosku badge-kosku-dark text-capitalize"><?= esc($s['tipe']) ?></span>
              </td>
              <td class="text-center fw-bold"><?= $views ?></td>
              <td class="text-center fw-bold text-success"><?= $wa ?></td>
              <td class="text-center fw-bold"><?= (int)$s['maps_count'] ?></td>
              <td class="text-center">
                <span class="badge <?= ($ctr >= 15) ? 'badge-available' : 'badge-kosku-light' ?>">
                  <?= $ctr ?>%
                </span>
              </td>
              <td class="text-end">
                <a href="<?= base_url('kos/' . $s['slug']) ?>" target="_blank" class="btn btn-sm btn-icon-circle" title="Lihat">
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
