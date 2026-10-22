<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Overall Information Summary Cards -->
<div class="row g-4 mb-4">
  
  <!-- Black Card: Overall Information -->
  <div class="col-12 col-xl-7">
    <div class="card-kosku-dark p-4 h-100 d-flex flex-column justify-content-between">
      <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="badge badge-kosku badge-kosku-light text-uppercase">Ringkasan Sistem</span>
          <span class="small text-secondary"><i class="bi bi-clock me-1"></i> Terakhir diperbarui: <?= date('d M Y') ?></span>
        </div>
        <h4 class="fw-800 text-white mb-1">Status Operasional kosKu</h4>
        <p class="text-secondary small mb-4">Pemantauan data sebaran kos, ketersediaan kamar, dan interaksi pengguna.</p>
      </div>

      <div class="row g-3 text-center border-top border-secondary pt-3 mt-2">
        <div class="col-4 border-end border-secondary">
          <div class="display-6 fw-800 text-white mb-0"><?= esc($totalKos) ?></div>
          <div class="text-secondary small">Total Kos</div>
        </div>
        <div class="col-4 border-end border-secondary">
          <div class="display-6 fw-800 text-white mb-0"><?= esc($kosTersedia) ?></div>
          <div class="text-secondary small">Kos Tersedia</div>
        </div>
        <div class="col-4">
          <div class="display-6 fw-800 text-white mb-0"><?= esc($totalWilayah) ?></div>
          <div class="text-secondary small">Wilayah (GIS)</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Progress Ring & Occupancy Card -->
  <div class="col-12 col-md-6 col-xl-5">
    <div class="card card-kosku p-4 h-100 d-flex flex-column justify-content-between">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0">Tingkat Okupansi Kamar</h6>
        <span class="badge badge-kosku badge-kosku-dark"><?= $occupancyRate ?>% Terisi</span>
      </div>

      <div class="d-flex align-items-center justify-content-center py-2 gap-4">
        <!-- SVG Progress Ring -->
        <div class="position-relative" style="width: 110px; height: 110px;">
          <svg width="110" height="110" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="42" fill="none" stroke="#f4f4f5" stroke-width="12" />
            <circle class="progress-ring-circle" cx="50" cy="50" r="42" fill="none" stroke="#18181b" stroke-width="12" 
                    stroke-dasharray="264" stroke-dashoffset="<?= 264 - (264 * $occupancyRate / 100) ?>" stroke-linecap="round" />
          </svg>
          <div class="position-absolute top-50 start-50 translate-middle text-center">
            <span class="fs-4 fw-800 text-dark"><?= $occupancyRate ?>%</span>
          </div>
        </div>

        <div>
          <div class="mb-2">
            <span class="text-muted small">Total Kapasitas:</span>
            <div class="fw-bold text-dark"><?= esc($totalKamar) ?> Kamar</div>
          </div>
          <div>
            <span class="text-muted small">Kamar Kosong:</span>
            <div class="fw-bold text-dark"><?= esc($kamarTersediaTotal) ?> Kamar</div>
          </div>
        </div>
      </div>

      <div class="pt-2 border-top text-center text-muted small">
        <i class="bi bi-info-circle me-1"></i> Data dihitung dari seluruh kamar aktif di sistem.
      </div>
    </div>
  </div>

</div>

<!-- Second Row: Weekly Chart & Quick Action Card -->
<div class="row g-4 mb-4">
  
  <!-- Weekly Progress Monochrome Chart -->
  <div class="col-12 col-lg-8">
    <div class="card card-kosku p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="fw-bold text-dark mb-0">Trafik Kunjungan 7 Hari Terakhir</h5>
          <span class="text-muted small">Aktivitas pencari kos yang melihat peta &amp; listing</span>
        </div>
        <div class="d-flex gap-2">
          <span class="badge badge-kosku badge-kosku-dark"><i class="bi bi-eye me-1"></i> <?= $summaryStats['total_views'] ?> Views</span>
          <span class="badge badge-kosku badge-kosku-light"><i class="bi bi-whatsapp me-1"></i> <?= $summaryStats['total_wa'] ?> Klik WA</span>
        </div>
      </div>
      <div style="height: 240px; position: relative;">
        <canvas id="weeklyChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Quick Action & Add Kos Dashed Card -->
  <div class="col-12 col-lg-4">
    <div class="d-flex flex-column gap-3 h-100">
      
      <!-- Dashed Add Card -->
      <a href="<?= base_url('admin/kos/create') ?>" class="card-kosku-dashed p-4 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center flex-grow-1">
        <div class="btn-icon-circle-dark mb-2" style="width: 48px; height: 48px;">
          <i class="bi bi-plus-lg fs-4"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">Tambah Kos Baru</h6>
        <p class="text-muted small mb-0">Input kos, pilih titik peta, dan upload foto.</p>
      </a>

      <!-- Quick Wilayah Link -->
      <div class="card card-kosku p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <div class="btn-icon-circle" style="width: 40px; height: 40px;">
              <i class="bi bi-pin-map fs-5"></i>
            </div>
            <div>
              <div class="fw-bold text-dark small">Kelola Wilayah &amp; GeoJSON</div>
              <div class="text-muted" style="font-size: 11px;"><?= $totalWilayah ?> wilayah terdaftar</div>
            </div>
          </div>
          <a href="<?= base_url('admin/wilayah') ?>" class="btn btn-sm btn-kosku-dark btn-pill px-3">
            Buka
          </a>
        </div>
      </div>

    </div>
  </div>

</div>

<!-- Third Row: Recent Kos Table ("Last Projects") -->
<div class="card card-kosku p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold text-dark mb-0">Data Kos Terbaru</h5>
      <span class="text-muted small">Kos yang baru saja diinput atau diperbarui</span>
    </div>
    <a href="<?= base_url('admin/kos') ?>" class="btn btn-sm btn-kosku-light btn-pill">
      Lihat Semua Kos <i class="bi bi-arrow-right ms-1"></i>
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Nama Kos</th>
          <th>Wilayah</th>
          <th>Tipe</th>
          <th>Harga/Bln</th>
          <th>Ketersediaan</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recentKos)): ?>
          <?php foreach ($recentKos as $k): ?>
            <tr>
              <td>
                <div class="fw-bold text-dark"><?= esc($k['nama']) ?></div>
                <div class="text-muted small text-truncate" style="max-width: 250px;"><?= esc($k['alamat']) ?></div>
              </td>
              <td><?= esc($k['nama_wilayah'] ?? '-') ?></td>
              <td>
                <span class="badge badge-kosku badge-kosku-dark text-capitalize"><?= esc($k['tipe']) ?></span>
              </td>
              <td class="fw-bold text-dark">Rp <?= number_format($k['harga_min'], 0, ',', '.') ?></td>
              <td>
                <?php if ($k['status_ketersediaan'] === 'tersedia'): ?>
                  <span class="badge badge-available">Tersedia (<?= $k['kamar_tersedia'] ?>)</span>
                <?php else: ?>
                  <span class="badge badge-full">Penuh</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge <?= ($k['status_publikasi'] === 'aktif') ? 'bg-dark' : 'bg-secondary' ?> text-capitalize">
                  <?= esc($k['status_publikasi']) ?>
                </span>
              </td>
              <td class="text-end">
                <a href="<?= base_url('admin/kos/edit/' . $k['id']) ?>" class="btn btn-sm btn-icon-circle" title="Ubah">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= base_url('admin/kos/' . $k['id'] . '/photos') ?>" class="btn btn-sm btn-icon-circle" title="Galeri Foto">
                  <i class="bi bi-images"></i>
                </a>
                <a href="<?= base_url('kos/' . $k['slug']) ?>" target="_blank" class="btn btn-sm btn-icon-circle" title="Lihat Halaman Publik">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">Belum ada data kos yang diinput.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const weeklyData = <?= json_encode($summaryStats['weekly'] ?? []) ?>;
    const labels = weeklyData.map(d => d.short);
    const dataCounts = weeklyData.map(d => d.count);

    const ctx = document.getElementById('weeklyChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Trafik Kunjungan',
            data: dataCounts,
            borderColor: '#18181b',
            backgroundColor: 'rgba(24, 24, 27, 0.05)',
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointBackgroundColor: '#18181b',
            pointRadius: 4,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { precision: 0, font: { family: "'Plus Jakarta Sans'" } },
              grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
              ticks: { font: { family: "'Plus Jakarta Sans'" } },
              grid: { display: false }
            }
          }
        }
      });
    }
  });
</script>
<?= $this->endSection() ?>
