<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card card-kosku p-4 mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="fw-800 text-dark mb-1">Kelola Wilayah &amp; Spasial (GIS)</h4>
      <p class="text-muted small mb-0">Kelola master data kecamatan, kelurahan, titik pusat, dan batas polygon GeoJSON.</p>
    </div>
    <a href="<?= base_url('admin/wilayah/create') ?>" class="btn btn-kosku-dark btn-pill">
      <i class="bi bi-plus-lg me-1"></i> Tambah Wilayah Baru
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Nama Wilayah</th>
          <th>Tipe</th>
          <th>Titik Pusat (Lat, Lng)</th>
          <th>Zoom Bawaan</th>
          <th>Batas GeoJSON</th>
          <th>Jumlah Kos</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($wilayahList)): ?>
          <?php foreach ($wilayahList as $w): ?>
            <tr>
              <td>
                <div class="fw-bold text-dark"><?= esc($w['nama']) ?></div>
              </td>
              <td>
                <span class="badge badge-kosku badge-kosku-light"><?= esc($w['tipe']) ?></span>
              </td>
              <td class="font-monospace small"><?= number_format($w['center_lat'], 5) ?>, <?= number_format($w['center_lng'], 5) ?></td>
              <td><?= esc($w['zoom_default']) ?></td>
              <td>
                <?php if (!empty($w['geojson'])): ?>
                  <span class="badge bg-dark rounded-pill"><i class="bi bi-check-circle me-1"></i> Terpasang</span>
                <?php else: ?>
                  <span class="badge bg-secondary rounded-pill">Titik Pusat Saja</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge badge-kosku badge-kosku-dark"><?= esc($w['total_kos']) ?> Kos</span>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a href="<?= base_url('?wilayah_id=' . $w['id']) ?>" target="_blank" class="btn btn-sm btn-icon-circle" title="Lihat di Peta Publik">
                    <i class="bi bi-geo-alt"></i>
                  </a>
                  <a href="<?= base_url('admin/wilayah/edit/' . $w['id']) ?>" class="btn btn-sm btn-icon-circle" title="Ubah">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="<?= base_url('admin/wilayah/delete/' . $w['id']) ?>" method="post" onsubmit="return confirm('Hapus wilayah <?= esc(addslashes($w['nama'])) ?>?');" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-icon-circle text-danger border-0" title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">Belum ada wilayah terdaftar.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
