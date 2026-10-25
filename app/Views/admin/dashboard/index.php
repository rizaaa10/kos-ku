<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4">

  <!-- LEFT COLUMN (Cards: Next Game equivalent + Standings Table equivalent) -->
  <div class="col-12 col-xl-7">

    <!-- Top Highlight Card ("Next game" equivalent) -->
    <div class="card-highlight mb-4">
      <div class="highlight-header">
        <span class="highlight-title">Kos Unggulan &amp; Wilayah Kampus</span>
        <a href="<?= base_url('/') ?>" target="_blank" class="highlight-link">
          Lihat Peta <i class="bi bi-arrow-right-short"></i>
        </a>
      </div>

      <?php if ($featuredKos): ?>
        <div class="highlight-meta">
          <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= esc($featuredKos['nama_wilayah'] ?? '-') ?></span>
          <span>•</span>
          <span><i class="bi bi-calendar3 me-1"></i> Kos unggulan</span>
          <span>•</span>
          <span class="badge badge-coach badge-coach-teal text-capitalize">Kos <?= esc($featuredKos['tipe']) ?></span>
        </div>

        <div class="highlight-matchup">
          <div class="team-badge">
            <div class="team-logo-circle text-teal" style="color: var(--coach-teal);">
              <i class="bi bi-house-heart-fill"></i>
            </div>
            <div>
              <div class="fw-800 text-dark" style="font-size: 1.05rem;">
                <?= esc($featuredKos['nama']) ?>
              </div>
              <div class="text-muted small">
                Rp <?= number_format((float) $featuredKos['harga_min'], 0, ',', '.') ?> / bln
              </div>
            </div>
          </div>

          <div class="match-vs-dot" title="Terhubung langsung">
            <i class="bi bi-link-45deg fs-6"></i>
          </div>

          <div class="team-badge">
            <div class="team-logo-circle text-primary" style="color: #0284c7;">
              <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
              <div class="fw-800 text-dark" style="font-size: 1.05rem;">
                <?= esc($featuredKos['nama_wilayah'] ?? '-') ?>
              </div>
              <div class="text-muted small">
                <?= esc($featuredKos['kamar_tersedia']) ?> Kamar Tersedia
              </div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="py-4 text-center">
          <p class="text-muted mb-3">Belum ada kos unggulan yang dapat ditampilkan.</p>
          <a href="<?= base_url('admin/kos/create') ?>" class="btn btn-coach-teal btn-pill">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kos
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Standings Table Card ("Standings" equivalent) -->
    <div class="card-standings">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h6 class="fw-bold text-dark mb-0">Klasemen Kos &amp; Okupansi</h6>
          <span class="text-muted small">Daftar kos terbaru dan ketersediaan kamar</span>
        </div>
        <a href="<?= base_url('admin/kos') ?>" class="highlight-link">
          Lihat Semua <i class="bi bi-arrow-right-short"></i>
        </a>
      </div>

      <div class="table-responsive">
        <table class="table-coach">
          <thead>
            <tr>
              <th style="width: 38px;">#</th>
              <th>Nama Kos &amp; Wilayah</th>
              <th class="text-center">Tipe</th>
              <th class="text-center">Total</th>
              <th class="text-center">Terisi</th>
              <th class="text-center">Sisa</th>
              <th class="text-end">Harga/Bln</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentKos)): ?>
              <?php foreach ($recentKos as $idx => $k): ?>
                <?php
                  $terisi = max(0, (int)$k['jumlah_kamar'] - (int)$k['kamar_tersedia']);
                  $isTop = $idx < 3;
                ?>
                <tr>
                  <td>
                    <span class="rank-pill <?= $isTop ? 'rank-top' : '' ?>"><?= $idx + 1 ?></span>
                  </td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-teal" style="width: 28px; height: 28px; font-size: 13px;">
                        <i class="bi bi-house-door-fill"></i>
                      </div>
                      <div class="text-truncate" style="max-width: 170px;">
                        <a href="<?= base_url('admin/kos/edit/' . $k['id']) ?>" class="fw-bold text-dark text-decoration-none hover-teal text-truncate d-block">
                          <?= esc($k['nama']) ?>
                        </a>
                        <span class="text-muted small" style="font-size: 11px;"><?= esc($k['nama_wilayah'] ?? '-') ?></span>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-coach badge-coach-teal text-capitalize" style="font-size: 10px;">
                      <?= esc($k['tipe']) ?>
                    </span>
                  </td>
                  <td class="text-center fw-semibold text-muted"><?= esc($k['jumlah_kamar']) ?></td>
                  <td class="text-center fw-bold text-dark"><?= $terisi ?></td>
                  <td class="text-center fw-bold text-teal"><?= esc($k['kamar_tersedia']) ?></td>
                  <td class="text-end fw-bold text-dark" style="font-size: 0.85rem;">
                    Rp <?= number_format($k['harga_min'], 0, ',', '.') ?>
                  </td>
                  <td class="text-center">
                    <?php if ($k['status_ketersediaan'] === 'tersedia'): ?>
                      <span class="badge badge-coach badge-coach-available">Tersedia</span>
                    <?php else: ?>
                      <span class="badge badge-coach badge-coach-full">Penuh</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-4 text-muted">Belum ada data kos yang tercatat.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- RIGHT COLUMN (Cards: Games Statistic + 2x2 Stat Grid + Bottom Banner) -->
  <div class="col-12 col-xl-5">

    <!-- Games Statistic equivalent (Statistik Kamar & Kos) -->
    <div class="card-stats-overview mb-4">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="highlight-title">Statistik Hunian Kamar</span>
        <a href="<?= base_url('admin/statistik') ?>" class="highlight-link">
          Lihat Statistik <i class="bi bi-arrow-right-short"></i>
        </a>
      </div>

      <!-- Segmented Horizontal Progress Bar -->
      <div class="segmented-progress">
        <div class="segment-teal" style="width: <?= $occupancyRate ?>%;" title="Kamar Terisi (<?= $occupancyRate ?>%)"></div>
        <div class="segment-rose" style="width: <?= 100 - $occupancyRate ?>%;" title="Kamar Kosong (<?= 100 - $occupancyRate ?>%)"></div>
      </div>

      <!-- Metric Columns -->
      <div class="stats-metrics-grid">
        <div>
          <div class="stat-label-tiny">Total Kos</div>
          <div class="stat-val-bold"><?= esc($totalKos) ?></div>
        </div>
        <div>
          <div class="stat-label-tiny">Terisi</div>
          <div class="stat-val-bold text-teal"><?= esc($kamarTerisiTotal) ?></div>
        </div>
        <div>
          <div class="stat-label-tiny">Kosong</div>
          <div class="stat-val-bold" style="color: #f43f5e;"><?= esc($kamarTersediaTotal) ?></div>
        </div>
        <div>
          <div class="stat-label-tiny">Wilayah</div>
          <div class="stat-val-bold"><?= esc($totalWilayah) ?></div>
        </div>
      </div>
    </div>

    <!-- 2x2 Grid of Stat Cards -->
    <div class="row g-3 mb-4">
      
      <!-- Card 1: Tingkat Okupansi (Purple) -->
      <div class="col-6">
        <div class="stat-widget h-100">
          <div class="stat-icon-circle icon-purple">
            <i class="bi bi-pie-chart-fill"></i>
          </div>
          <div>
            <div class="stat-widget-title">Okupansi</div>
            <div class="stat-widget-value"><?= $occupancyRate ?>%</div>
          </div>
        </div>
      </div>

      <!-- Card 2: Rata-rata Harga (Pink) -->
      <div class="col-6">
        <div class="stat-widget h-100">
          <div class="stat-icon-circle icon-pink">
            <i class="bi bi-tag-fill"></i>
          </div>
          <div>
            <div class="stat-widget-title">Rata2 Harga</div>
            <div class="stat-widget-value">Rp <?= number_format($avgPrice / 1000, 0) ?>rb</div>
          </div>
        </div>
      </div>

      <!-- Card 3: Total Kunjungan (Amber) -->
      <div class="col-6">
        <div class="stat-widget h-100">
          <div class="stat-icon-circle icon-amber">
            <i class="bi bi-eye-fill"></i>
          </div>
          <div>
            <div class="stat-widget-title">Kunjungan</div>
            <div class="stat-widget-value"><?= number_format($summaryStats['total_views'] ?? 0) ?></div>
          </div>
        </div>
      </div>

      <!-- Card 4: Konversi WhatsApp (Teal) -->
      <div class="col-6">
        <div class="stat-widget h-100">
          <div class="stat-icon-circle icon-teal">
            <i class="bi bi-whatsapp"></i>
          </div>
          <div>
            <div class="stat-widget-title">Konversi WA</div>
            <div class="stat-widget-value"><?= $waConversionRate ?>%</div>
          </div>
        </div>
      </div>

    </div>

    <!-- Bottom Action Banner ("Setup training for next week" equivalent) -->
    <div class="card-banner-action">
      <div class="banner-decorative-circle"></div>
      <div class="banner-decorative-sphere"></div>

      <div class="banner-tag">Pemetaan Spasial GIS</div>
      <div class="banner-title">
        Petakan Kos Baru &amp; Atur Titik Koordinat Peta
      </div>

      <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('admin/kos/create') ?>" class="btn-banner-white">
          <i class="bi bi-plus-circle-fill"></i> Tambah Kos Sekarang
        </a>
      </div>
    </div>

  </div>

</div>

<?= $this->endSection() ?>
