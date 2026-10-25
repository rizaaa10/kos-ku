<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card-coach mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="fw-800 text-dark mb-1">Daftar Semua Kos</h4>
      <p class="text-muted small mb-0">Kelola informasi listing, ketersediaan kamar, dan titik koordinat spasial.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/kos/create') ?>" class="btn btn-coach-teal btn-pill">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kos Baru
      </a>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <form action="<?= base_url('admin/kos') ?>" method="get" class="row g-2 mb-4 p-3 rounded-4" style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.8);">
    <div class="col-12 col-md-4">
      <input type="text" name="q" class="form-control rounded-pill bg-white border" placeholder="Cari nama atau alamat..." value="<?= esc($q ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <select name="wilayah_id" class="form-select rounded-pill bg-white border">
        <option value="">Semua Wilayah</option>
        <?php foreach ($wilayahList as $w): ?>
          <option value="<?= $w['id'] ?>" <?= (($wilayahId ?? '') == $w['id']) ? 'selected' : '' ?>>
            <?= esc($w['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-3">
      <select name="status" class="form-select rounded-pill bg-white border">
        <option value="">Semua Status Publikasi</option>
        <option value="aktif" <?= (($status ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
        <option value="nonaktif" <?= (($status ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
        <option value="draft" <?= (($status ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>
    <div class="col-12 col-md-2 d-flex gap-2">
      <button type="submit" class="btn btn-coach-teal btn-pill w-100">Filter</button>
      <a href="<?= base_url('admin/kos') ?>" class="btn btn-coach-light btn-pill">Reset</a>
    </div>
  </form>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table-coach">
      <thead>
        <tr>
          <th style="width: 70px;">Foto</th>
          <th>Nama Kos &amp; Alamat</th>
          <th>Wilayah</th>
          <th class="text-center">Tipe</th>
          <th class="text-end">Harga/Bulan</th>
          <th class="text-center">Ketersediaan</th>
          <th class="text-center">Publikasi</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($kosList)): ?>
          <?php foreach ($kosList as $k): ?>
            <tr>
              <td>
                <?php if (!empty($k['foto_utama'])): ?>
                  <img src="<?= base_url($k['foto_utama']['path_file']) ?>" class="rounded-3 border" style="width: 54px; height: 40px; object-fit: cover;" alt="Foto <?= esc($k['nama']) ?>">
                <?php else: ?>
                  <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 54px; height: 40px;" title="Foto belum tersedia">
                    <i class="bi bi-image"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="fw-bold text-dark"><?= esc($k['nama']) ?></div>
                <div class="text-muted small text-truncate" style="max-width: 250px; font-size: 11px;"><?= esc($k['alamat']) ?></div>
              </td>
              <td class="text-muted small"><?= esc($k['nama_wilayah'] ?? '-') ?></td>
              <td class="text-center">
                <span class="badge badge-coach badge-coach-teal text-capitalize"><?= esc($k['tipe']) ?></span>
              </td>
              <td class="text-end fw-bold text-dark">Rp <?= number_format($k['harga_min'], 0, ',', '.') ?></td>
              <td class="text-center">
                <?php if ($k['status_ketersediaan'] === 'tersedia'): ?>
                  <span class="badge badge-coach badge-coach-available">Tersedia (<?= $k['kamar_tersedia'] ?>)</span>
                <?php else: ?>
                  <span class="badge badge-coach badge-coach-full">Penuh</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <form action="<?= base_url('admin/kos/toggle-status/' . $k['id']) ?>" method="post" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm <?= ($k['status_publikasi'] === 'aktif') ? 'btn-coach-teal' : 'btn-coach-light' ?> btn-pill px-3 py-1" style="font-size: 11px;" title="Klik untuk beralih status">
                    <?= esc($k['status_publikasi']) ?>
                  </button>
                </form>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a href="<?= base_url('kos/' . $k['slug']) ?>" target="_blank" class="topbar-btn" style="width: 32px; height: 32px;" title="Lihat Tampilan Publik">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= base_url('admin/kos/' . $k['id'] . '/photos') ?>" class="topbar-btn" style="width: 32px; height: 32px;" title="Kelola Foto">
                    <i class="bi bi-images"></i>
                  </a>
                  <a href="<?= base_url('admin/kos/edit/' . $k['id']) ?>" class="topbar-btn" style="width: 32px; height: 32px;" title="Ubah Data">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="<?= base_url('admin/kos/delete/' . $k['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kos <?= esc(addslashes($k['nama'])) ?>?');" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="topbar-btn text-danger border-0" style="width: 32px; height: 32px;" title="Hapus Kos">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
              Tidak ada data kos yang sesuai dengan kriteria pencarian.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
