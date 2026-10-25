<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card-coach mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: rgba(226, 232, 240, 0.7) !important;">
    <div>
      <h4 class="fw-800 text-dark mb-1">Ubah Data Kos: <?= esc($kos['nama']) ?></h4>
      <p class="text-muted small mb-0">Perbarui informasi, harga, ketersediaan kamar, dan koordinat spasial.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/kos/' . $kos['id'] . '/photos') ?>" class="btn btn-sm btn-coach-teal btn-pill">
        <i class="bi bi-images me-1"></i> Kelola Foto Galeri
      </a>
      <a href="<?= base_url('admin/kos') ?>" class="btn btn-sm btn-coach-light btn-pill">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <form action="<?= base_url('admin/kos/edit/' . $kos['id']) ?>" method="post">
    <?= csrf_field() ?>

    <div class="row g-4">
      
      <!-- Left Column: Primary Details & Coordinates -->
      <div class="col-12 col-lg-7">
        
        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Nama Kos <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control rounded-pill" required value="<?= old('nama', $kos['nama']) ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-12 col-md-6">
            <label class="form-label small fw-bold text-dark text-uppercase">Wilayah <span class="text-danger">*</span></label>
            <select name="wilayah_id" id="wilayahSelectAdmin" class="form-select rounded-pill" required>
              <option value="">Pilih Wilayah</option>
              <?php foreach ($wilayahList as $w): ?>
                <option value="<?= $w['id'] ?>" data-lat="<?= $w['center_lat'] ?>" data-lng="<?= $w['center_lng'] ?>" data-zoom="<?= $w['zoom_default'] ?>" <?= (old('wilayah_id', $kos['wilayah_id']) == $w['id']) ? 'selected' : '' ?>>
                  <?= esc($w['nama']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label small fw-bold text-dark text-uppercase">Tipe Kos <span class="text-danger">*</span></label>
            <select name="tipe" class="form-select rounded-pill" required>
              <option value="putri" <?= (old('tipe', $kos['tipe']) === 'putri') ? 'selected' : '' ?>>Putri</option>
              <option value="putra" <?= (old('tipe', $kos['tipe']) === 'putra') ? 'selected' : '' ?>>Putra</option>
              <option value="campur" <?= (old('tipe', $kos['tipe']) === 'campur') ? 'selected' : '' ?>>Campur</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Alamat Lengkap <span class="text-danger">*</span></label>
          <textarea name="alamat" class="form-control rounded-4" rows="2" required><?= old('alamat', $kos['alamat']) ?></textarea>
        </div>

        <!-- Interactive Map Location Picker -->
        <div class="card p-3 rounded-4 bg-light mb-4 border">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label small fw-bold text-dark text-uppercase mb-0">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i> Titik Koordinat Peta (Geser Pin atau Klik)
            </label>
            <span class="badge badge-coach badge-coach-teal" style="font-size: 10px;">Interaktif</span>
          </div>

          <div id="pickerMap" class="rounded-4 border mb-2" style="height: 280px; width: 100%;"></div>

          <div class="row g-2">
            <div class="col-6">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Lat</span>
                <input type="text" name="latitude" id="inputLat" class="form-control bg-white" required value="<?= old('latitude', $kos['latitude']) ?>">
              </div>
            </div>
            <div class="col-6">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Lng</span>
                <input type="text" name="longitude" id="inputLng" class="form-control bg-white" required value="<?= old('longitude', $kos['longitude']) ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Deskripsi Kos</label>
          <textarea name="deskripsi" class="form-control rounded-4" rows="4"><?= old('deskripsi', $kos['deskripsi']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Peraturan &amp; Tata Tertib</label>
          <textarea name="peraturan" class="form-control rounded-4" rows="3"><?= old('peraturan', $kos['peraturan']) ?></textarea>
        </div>

      </div>

      <!-- Right Column: Pricing, Room Counts, Facilities, Contacts -->
      <div class="col-12 col-lg-5">
        
        <!-- Pricing & Rooms -->
        <div class="card p-3 rounded-4 bg-light mb-4 border">
          <h6 class="fw-bold text-dark mb-3">Harga &amp; Kapasitas</h6>
          
          <div class="mb-3">
            <label class="form-label small fw-bold text-dark text-uppercase">Harga Sewa / Bulan (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="harga_min" class="form-control rounded-pill bg-white" required value="<?= old('harga_min', (int)$kos['harga_min']) ?>" step="10000">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold text-dark text-uppercase">Harga Maksimal (Opsional)</label>
            <input type="number" name="harga_max" class="form-control rounded-pill bg-white" value="<?= old('harga_max', $kos['harga_max'] ? (int)$kos['harga_max'] : '') ?>" step="10000">
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label small fw-bold text-dark text-uppercase">Jumlah Kamar</label>
              <input type="number" name="jumlah_kamar" class="form-control rounded-pill bg-white" required min="1" value="<?= old('jumlah_kamar', $kos['jumlah_kamar']) ?>">
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold text-dark text-uppercase">Kamar Kosong</label>
              <input type="number" name="kamar_tersedia" class="form-control rounded-pill bg-white" required min="0" value="<?= old('kamar_tersedia', $kos['kamar_tersedia']) ?>">
            </div>
          </div>
        </div>

        <!-- Contact Person -->
        <div class="card p-3 rounded-4 bg-light mb-4 border">
          <h6 class="fw-bold text-dark mb-3">Kontak Pemilik / Pengelola</h6>

          <div class="mb-3">
            <label class="form-label small fw-bold text-dark text-uppercase">Nama Kontak <span class="text-danger">*</span></label>
            <input type="text" name="kontak_nama" class="form-control rounded-pill bg-white" required value="<?= old('kontak_nama', $kos['kontak_nama']) ?>">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-bold text-dark text-uppercase">Nomor WhatsApp <span class="text-danger">*</span></label>
            <input type="text" name="kontak_wa" class="form-control rounded-pill bg-white" required value="<?= old('kontak_wa', $kos['kontak_wa']) ?>">
          </div>

          <div class="mb-2">
            <label class="form-label small fw-bold text-dark text-uppercase">No Telepon Tambahan (Opsional)</label>
            <input type="text" name="kontak_telepon" class="form-control rounded-pill bg-white" value="<?= old('kontak_telepon', $kos['kontak_telepon']) ?>">
          </div>
        </div>

        <!-- Master Facilities Checkboxes -->
        <div class="card p-3 rounded-4 bg-light mb-4 border">
          <h6 class="fw-bold text-dark mb-2">Fasilitas Kos</h6>
          <div class="row g-2">
            <?php foreach ($fasilitasList as $fas): ?>
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="fasilitas[]" value="<?= $fas['id'] ?>" id="admin-fas-<?= $fas['id'] ?>" <?= in_array($fas['id'], $selectedFasilitasIds) ? 'checked' : '' ?>>
                  <label class="form-check-label small" for="admin-fas-<?= $fas['id'] ?>">
                    <i class="bi <?= esc($fas['ikon']) ?> me-1"></i> <?= esc($fas['nama']) ?>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Status Publikasi -->
        <div class="card p-3 rounded-4 bg-light mb-4 border">
          <label class="form-label small fw-bold text-dark text-uppercase">Status Publikasi</label>
          <select name="status_publikasi" class="form-select rounded-pill bg-white">
            <option value="aktif" <?= ($kos['status_publikasi'] === 'aktif') ? 'selected' : '' ?>>Aktif (Tampil di peta publik)</option>
            <option value="draft" <?= ($kos['status_publikasi'] === 'draft') ? 'selected' : '' ?>>Draft</option>
            <option value="nonaktif" <?= ($kos['status_publikasi'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
          </select>
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="is_unggulan" value="1" id="checkUnggulan" <?= $kos['is_unggulan'] ? 'checked' : '' ?>>
            <label class="form-check-label small fw-bold text-dark" for="checkUnggulan">
              Tandai sebagai Kos Unggulan (Tampil di atas)
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-coach-teal btn-pill py-3 fw-bold fs-6">
            <i class="bi bi-save me-1"></i> Perbarui Data Kos
          </button>
        </div>

      </div>

    </div>
  </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('inputLat');
    const lngInput = document.getElementById('inputLng');
    const wilayahSelect = document.getElementById('wilayahSelectAdmin');

    let curLat = parseFloat(latInput.value) || -7.243542;
    let curLng = parseFloat(lngInput.value) || 109.006528;

    const map = L.map('pickerMap', {
      center: [curLat, curLng],
      zoom: 16,
      zoomControl: true,
      attributionControl: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19
    }).addTo(map);

    let marker = L.marker([curLat, curLng], { draggable: true }).addTo(map);

    function updateCoords(lat, lng) {
      latInput.value = lat.toFixed(7);
      lngInput.value = lng.toFixed(7);
    }

    marker.on('dragend', function (e) {
      const pos = e.target.getLatLng();
      updateCoords(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
      marker.setLatLng(e.latlng);
      updateCoords(e.latlng.lat, e.latlng.lng);
    });
  });
</script>
<?= $this->endSection() ?>
