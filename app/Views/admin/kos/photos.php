<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card card-kosku p-4 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
      <span class="badge badge-kosku badge-kosku-dark text-uppercase mb-1">Galeri Foto</span>
      <h4 class="fw-800 text-dark mb-1"><?= esc($kos['nama']) ?></h4>
      <p class="text-muted small mb-0">Kelola foto-foto kos, tentukan foto sampul utama, dan unggah foto baru.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/kos/edit/' . $kos['id']) ?>" class="btn btn-sm btn-kosku-light btn-pill">
        <i class="bi bi-pencil me-1"></i> Edit Kos
      </a>
      <a href="<?= base_url('admin/kos') ?>" class="btn btn-sm btn-kosku-light btn-pill">
        <i class="bi bi-arrow-left me-1"></i> Daftar Kos
      </a>
    </div>
  </div>

  <!-- Upload New Photos Form -->
  <div class="card p-4 rounded-4 bg-light mb-4 border">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-arrow-up me-2"></i> Unggah Foto Baru</h5>
    <form action="<?= base_url('admin/kos/' . $kos['id'] . '/photos/upload') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="row g-3 align-items-end">
        <div class="col-12 col-md-6">
          <label class="form-label small fw-bold text-dark text-uppercase">Pilih File Foto (Bisa Banyak Sekaligus)</label>
          <input type="file" name="fotos[]" class="form-control rounded-pill bg-white" multiple required accept="image/*">
        </div>
        <div class="col-12 col-md-4">
          <label class="form-label small fw-bold text-dark text-uppercase">Keterangan Singkat</label>
          <input type="text" name="keterangan" class="form-control rounded-pill bg-white" placeholder="cth: Kamar Mandi / Tampak Depan">
        </div>
        <div class="col-12 col-md-2">
          <button type="submit" class="btn btn-kosku-dark btn-pill w-100">
            <i class="bi bi-upload me-1"></i> Unggah
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Photo Grid -->
  <h5 class="fw-bold text-dark mb-3">Foto Tersimpan (<?= count($photos) ?>)</h5>
  <?php if (!empty($photos)): ?>
    <div class="row g-4">
      <?php foreach ($photos as $p): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card card-kosku h-100 overflow-hidden position-relative <?= $p['is_utama'] ? 'border-2 border-dark shadow' : '' ?>">
            <img src="<?= base_url($p['path_file']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Foto Kos">
            
            <?php if ($p['is_utama']): ?>
              <span class="position-absolute top-0 start-0 m-2 badge badge-kosku badge-kosku-dark">
                <i class="bi bi-star-fill text-warning me-1"></i> Foto Utama
              </span>
            <?php endif; ?>

            <div class="card-body p-3 d-flex flex-column justify-content-between">
              <div class="text-truncate small text-muted mb-2"><?= esc($p['keterangan'] ?? 'Tanpa keterangan') ?></div>
              
              <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <?php if (!$p['is_utama']): ?>
                  <form action="<?= base_url('admin/kos/' . $kos['id'] . '/photos/' . $p['id'] . '/set-main') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-kosku-light btn-pill" style="font-size: 11px;">
                      Jadikan Utama
                    </button>
                  </form>
                <?php else: ?>
                  <span class="small fw-bold text-dark"><i class="bi bi-check2"></i> Sampul</span>
                <?php endif; ?>

                <form action="<?= base_url('admin/kos/' . $kos['id'] . '/photos/' . $p['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus foto ini?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-icon-circle text-danger border-0" title="Hapus Foto">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-light text-center py-5 border rounded-4 text-muted">
      Belum ada foto yang diunggah untuk kos ini. Silakan unggah foto di atas.
    </div>
  <?php endif; ?>

</div>

<?= $this->endSection() ?>
