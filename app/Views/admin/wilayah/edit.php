<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="card-coach mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: rgba(226, 232, 240, 0.7) !important;">
    <div>
      <h4 class="fw-800 text-dark mb-1">Ubah Wilayah: <?= esc($wilayah['nama']) ?></h4>
      <p class="text-muted small mb-0">Perbarui informasi nama wilayah, koordinat tengah, dan batas GeoJSON.</p>
    </div>
    <a href="<?= base_url('admin/wilayah') ?>" class="btn btn-sm btn-coach-light btn-pill">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
  </div>

  <form action="<?= base_url('admin/wilayah/edit/' . $wilayah['id']) ?>" method="post">
    <?= csrf_field() ?>

    <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Nama Wilayah <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control rounded-pill" required value="<?= old('nama', $wilayah['nama']) ?>">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-6">
            <label class="form-label small fw-bold text-dark text-uppercase">Tipe Wilayah</label>
            <select name="tipe" class="form-select rounded-pill">
              <option value="Kecamatan" <?= ($wilayah['tipe'] === 'Kecamatan') ? 'selected' : '' ?>>Kecamatan</option>
              <option value="Kelurahan" <?= ($wilayah['tipe'] === 'Kelurahan') ? 'selected' : '' ?>>Kelurahan</option>
              <option value="Area Kampus" <?= ($wilayah['tipe'] === 'Area Kampus') ? 'selected' : '' ?>>Area Kampus</option>
              <option value="Kota" <?= ($wilayah['tipe'] === 'Kota') ? 'selected' : '' ?>>Kota</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label small fw-bold text-dark text-uppercase">Zoom Bawaan Peta</label>
            <input type="number" name="zoom_default" class="form-control rounded-pill" required min="10" max="18" value="<?= old('zoom_default', $wilayah['zoom_default']) ?>">
          </div>
        </div>

        <!-- Coordinates map picker -->
        <div class="card p-3 rounded-4 bg-light mb-3 border">
          <label class="form-label small fw-bold text-dark text-uppercase mb-2">Titik Pusat Wilayah (Klik pada Peta)</label>
          <div id="wilayahPickerMap" class="rounded-4 border mb-2" style="height: 250px; width: 100%;"></div>
          <div class="row g-2">
            <div class="col-6">
              <input type="text" name="center_lat" id="wLat" class="form-control form-control-sm rounded-pill bg-white" required value="<?= old('center_lat', $wilayah['center_lat']) ?>">
            </div>
            <div class="col-6">
              <input type="text" name="center_lng" id="wLng" class="form-control form-control-sm rounded-pill bg-white" required value="<?= old('center_lng', $wilayah['center_lng']) ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase">Batas Wilayah GeoJSON (Opsional)</label>
          <textarea name="geojson" id="inputGeojson" class="form-control rounded-4 font-monospace small" rows="12"><?= old('geojson', $wilayah['geojson']) ?></textarea>
          <div class="form-text small">Format standar GeoJSON polygon.</div>
        </div>

        <div class="d-grid pt-3">
          <button type="submit" class="btn btn-coach-teal btn-pill py-3 fw-bold fs-6">
            <i class="bi bi-save me-1"></i> Perbarui Wilayah
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
    const latIn = document.getElementById('wLat');
    const lngIn = document.getElementById('wLng');

    let curLat = parseFloat(latIn.value) || -7.243542;
    let curLng = parseFloat(lngIn.value) || 109.006528;

    const map = L.map('wilayahPickerMap', {
      center: [curLat, curLng],
      zoom: 14,
      zoomControl: true,
      attributionControl: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    let marker = L.marker([curLat, curLng], { draggable: true }).addTo(map);

    // Draw existing geojson if available
    const geojsonRaw = document.getElementById('inputGeojson').value;
    if (geojsonRaw) {
      try {
        const geoObj = JSON.parse(geojsonRaw);
        const geoLayer = L.geoJSON(geoObj, {
          style: { color: '#18181b', weight: 2, fillOpacity: 0.1 }
        }).addTo(map);
        map.fitBounds(geoLayer.getBounds());
      } catch (e) {}
    }

    function update(lat, lng) {
      latIn.value = lat.toFixed(7);
      lngIn.value = lng.toFixed(7);
    }

    marker.on('dragend', function (e) {
      const pos = e.target.getLatLng();
      update(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
      marker.setLatLng(e.latlng);
      update(e.latlng.lat, e.latlng.lng);
    });
  });
</script>
<?= $this->endSection() ?>
