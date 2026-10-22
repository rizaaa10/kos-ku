<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card card-kosku p-4 mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="fw-800 text-dark mb-1">Daftar Semua Kos</h4>
      <p class="text-muted small mb-0">Kelola informasi listing, ketersediaan kamar, dan titik koordinat peta.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/kos/create') ?>" class="btn btn-kosku-dark btn-pill">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kos Baru
      </a>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <form action="<?= base_url('admin/kos') ?>" method="get" class="row g-2 mb-4 bg-light p-3 rounded-4">
    <div class="col-12 col-md-4">
      <input type="text" name="q" class="form-control rounded-pill bg-white" placeholder="Cari nama atau alamat..." value="<?= esc($q ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <select name="wilayah_id" class="form-select rounded-pill bg-white">
        <option value="">Semua Wilayah</option>
        <?php foreach ($wilayahList as $w): ?>
          <option value="<?= $w['id'] ?>" <?= (($wilayahId ?? '') == $w['id']) ? 'selected' : '' ?>>
            <?= esc($w['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-3">
      <select name="status" class="form-select rounded-pill bg-white">
        <option value="">Semua Status Publikasi</option>
        <option value="aktif" <?= (($status ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
        <option value="nonaktif" <?= (($status ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
        <option value="draft" <?= (($status ?? '') === 'draft') ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>
    <div class="col-12 col-md-2 d-flex gap-2">
      <button type="submit" class="btn btn-kosku-dark btn-pill w-100">Filter</button>
      <a href="<?= base_url('admin/kos') ?>" class="btn btn-light btn-pill border">Reset</a>
    </div>
  </form>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width: 70px;">Foto</th>
          <th>Nama Kos &amp; Alamat</th>
          <th>Wilayah</th>
          <th>Tipe</th>
          <th>Harga/Bulan</th>
          <th>Ketersediaan</th>
          <th>Publikasi</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($kosList)): ?>
          <?php foreach ($kosList as $k): ?>
            <tr>
              <td>
                <img src="<?= base_url($k['foto_utama'] ? $k['foto_utama']['path_file'] : 'uploads/kos/sample_kos_1.svg') ?>" class="rounded-3 border" style="width: 56px; height: 42px; object-fit: cover;" alt="Foto">
              </td>
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
                <form action="<?= base_url('admin/kos/toggle-status/' . $k['id']) ?>" method="post" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm <?= ($k['status_publikasi'] === 'aktif') ? 'btn-dark' : 'btn-outline-secondary' ?> btn-pill px-3 py-1" style="font-size: 11px;" title="Klik untuk beralih status">
                    <?= esc($k['status_publikasi']) ?>
                  </button>
                </form>
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <a href="<?= base_url('kos/' . $k['slug']) ?>" target="_blank" class="btn btn-sm btn-icon-circle" title="Lihat Tampilan Publik">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= base_url('admin/kos/' . $k['id'] . '/photos') ?>" class="btn btn-sm btn-icon-circle" title="Kelola Foto">
                    <i class="bi bi-images"></i>
                  </a>
                  <a href="<?= base_url('admin/kos/edit/' . $k['id']) ?>" class="btn btn-sm btn-icon-circle" title="Ubah Data">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="<?= base_url('admin/kos/delete/' . $k['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kos <?= esc(addslashes($k['nama'])) ?>?');" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-icon-circle text-danger border-0" title="Hapus Kos">
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
