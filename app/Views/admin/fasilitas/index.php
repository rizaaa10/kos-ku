<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4">
  
  <!-- Form Tambah Fasilitas (Left) -->
  <div class="col-12 col-lg-4">
    <div class="card card-kosku p-4">
      <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-1"></i> Tambah Fasilitas Baru</h5>
      <form action="<?= base_url('admin/fasilitas/create') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Nama Fasilitas <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control rounded-pill" required placeholder="cth: Water Heater / Dapur Bersama">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Ikon Bootstrap Icons <span class="text-danger">*</span></label>
          <input type="text" name="ikon" class="form-control rounded-pill" required placeholder="cth: bi-wifi, bi-snow, bi-droplet">
          <div class="form-text small">Gunakan class icon dari Bootstrap Icons (misal: <code>bi-wifi</code>, <code>bi-snow</code>, <code>bi-tv</code>).</div>
        </div>

        <div class="mb-4">
          <label class="form-label small fw-bold text-dark text-uppercase">Kategori Fasilitas</label>
          <select name="kategori" class="form-select rounded-pill">
            <option value="kamar">Fasilitas Kamar (Pribadi)</option>
            <option value="umum">Fasilitas Umum (Bersama)</option>
          </select>
        </div>

        <button type="submit" class="btn btn-kosku-dark btn-pill w-100 py-2 fw-bold">
          <i class="bi bi-save me-1"></i> Simpan Fasilitas
        </button>
      </form>
    </div>
  </div>

  <!-- Table Daftar Fasilitas (Right) -->
  <div class="col-12 col-lg-8">
    <div class="card card-kosku p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="fw-bold text-dark mb-0">Master Data Fasilitas</h5>
          <span class="text-muted small">Daftar fasilitas yang dapat dipilih saat mendaftarkan kos</span>
        </div>
        <span class="badge badge-kosku badge-kosku-dark"><?= count($fasilitasList) ?> Fasilitas</span>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">Ikon</th>
              <th>Nama Fasilitas</th>
              <th>Kategori</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($fasilitasList)): ?>
              <?php foreach ($fasilitasList as $f): ?>
                <tr>
                  <td>
                    <div class="btn-icon-circle-dark" style="width: 36px; height: 36px;">
                      <i class="bi <?= esc($f['ikon']) ?>"></i>
                    </div>
                  </td>
                  <td>
                    <span class="fw-bold text-dark"><?= esc($f['nama']) ?></span>
                    <span class="text-muted small ms-2 font-monospace">(<?= esc($f['ikon']) ?>)</span>
                  </td>
                  <td>
                    <span class="badge badge-kosku <?= ($f['kategori'] === 'kamar') ? 'badge-kosku-dark' : 'badge-kosku-light' ?> text-capitalize">
                      <?= esc($f['kategori']) ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <!-- Edit Button trigger modal -->
                    <button class="btn btn-sm btn-icon-circle" data-bs-toggle="modal" data-bs-target="#editFasModal<?= $f['id'] ?>" title="Ubah">
                      <i class="bi bi-pencil"></i>
                    </button>

                    <!-- Delete Form -->
                    <form action="<?= base_url('admin/fasilitas/delete/' . $f['id']) ?>" method="post" onsubmit="return confirm('Hapus fasilitas <?= esc(addslashes($f['nama'])) ?>?');" class="d-inline">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm btn-icon-circle text-danger border-0" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>

                    <!-- Edit Modal -->
                    <div class="modal fade text-start" id="editFasModal<?= $f['id'] ?>" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-kosku-xl border-0 shadow">
                          <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-bold text-dark">Ubah Fasilitas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form action="<?= base_url('admin/fasilitas/edit/' . $f['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="modal-body p-4">
                              <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Fasilitas</label>
                                <input type="text" name="nama" class="form-control rounded-pill" required value="<?= esc($f['nama']) ?>">
                              </div>
                              <div class="mb-3">
                                <label class="form-label small fw-bold">Ikon Class</label>
                                <input type="text" name="ikon" class="form-control rounded-pill" required value="<?= esc($f['ikon']) ?>">
                              </div>
                              <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori</label>
                                <select name="kategori" class="form-select rounded-pill">
                                  <option value="kamar" <?= ($f['kategori'] === 'kamar') ? 'selected' : '' ?>>Fasilitas Kamar (Pribadi)</option>
                                  <option value="umum" <?= ($f['kategori'] === 'umum') ? 'selected' : '' ?>>Fasilitas Umum (Bersama)</option>
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer border-top">
                              <button type="button" class="btn btn-light btn-pill" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-kosku-dark btn-pill px-4">Simpan Perubahan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center py-4 text-muted">Belum ada fasilitas.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>

<?= $this->endSection() ?>
