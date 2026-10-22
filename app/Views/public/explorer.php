<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filter & Control Bar -->
<div class="bg-white border-bottom py-2 px-3 px-lg-4">
  <div class="row g-2 align-items-center">
    
    <!-- Select Wilayah -->
    <div class="col-12 col-md-3 col-lg-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
          <i class="bi bi-geo-alt"></i>
        </span>
        <select id="wilayahSelect" class="form-select form-select-sm border-start-0 rounded-end-pill bg-light fw-medium">
          <option value="">Semua Wilayah</option>
          <?php foreach ($wilayahList as $w): ?>
            <option value="<?= $w['id'] ?>" <?= ($selectedWilayahId == $w['id']) ? 'selected' : '' ?>>
              <?= esc($w['nama']) ?> (<?= $w['total_kos'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Quick Search Query -->
    <div class="col-12 col-md-3 col-lg-3">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light border-end-0 rounded-start-pill text-muted ps-3">
          <i class="bi bi-search"></i>
        </span>
        <input type="text" id="searchQuery" class="form-control form-select-sm border-start-0 rounded-end-pill bg-light" placeholder="Cari nama kos atau alamat..." value="<?= esc($q) ?>">
      </div>
    </div>

    <!-- Quick Chips Tipe Kos -->
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-center gap-1 overflow-x-auto py-1">
      <div class="chip-filter chip-tipe <?= empty($selectedTipe) ? 'active' : '' ?>" data-tipe="">
        Semua
      </div>
      <div class="chip-filter chip-tipe <?= ($selectedTipe === 'putri') ? 'active' : '' ?>" data-tipe="putri">
        <i class="bi bi-gender-female"></i> Putri
      </div>
      <div class="chip-filter chip-tipe <?= ($selectedTipe === 'putra') ? 'active' : '' ?>" data-tipe="putra">
        <i class="bi bi-gender-male"></i> Putra
      </div>
      <div class="chip-filter chip-tipe <?= ($selectedTipe === 'campur') ? 'active' : '' ?>" data-tipe="campur">
        <i class="bi bi-people"></i> Campur
      </div>
    </div>

    <!-- Additional Action Buttons (Filter Modal, GPS Near Me, Sort) -->
    <div class="col-12 col-lg-2 d-flex justify-content-end align-items-center gap-1">
      <button class="btn btn-sm btn-kosku-light btn-pill" type="button" data-bs-toggle="modal" data-bs-target="#filterModal" title="Filter Lanjutan">
        <i class="bi bi-sliders"></i> Filter
      </button>

      <button id="btnNearMe" class="btn btn-sm btn-kosku-light btn-pill" title="Cari kos terdekat dengan GPS">
        <i class="bi bi-geo"></i>
      </button>

      <select id="sortSelect" class="form-select form-select-sm rounded-pill border bg-white" style="width: auto; font-size: 11px;">
        <option value="terbaru">Terbaru</option>
        <option value="harga_asc">Termurah</option>
        <option value="harga_desc">Termahal</option>
        <option value="kamar_tersedia">Kamar Terbanyak</option>
      </select>
    </div>

  </div>
</div>

<!-- Main Explorer Split Screen Layout -->
<div class="container-fluid p-0 explorer-container">
  <div class="row g-0 h-100">
    
    <!-- Left List Cards Panel (Desktop 35%, Mobile Stacks) -->
    <div class="col-12 col-lg-5 col-xl-4 explorer-sidebar bg-white p-3 p-lg-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="fw-800 text-dark mb-0">Daftar Kos</h5>
          <span class="text-muted small">Menampilkan <strong id="kosCountBadge" class="text-dark"><?= count($kosList) ?></strong> kos pilihan</span>
        </div>
        <button class="btn btn-sm btn-light btn-pill text-muted" onclick="KosKu.resetFilters()">
          <i class="bi bi-arrow-counterclockwise"></i> Reset
        </button>
      </div>

      <!-- Kos Cards Container (Dynamically Synchronized) -->
      <div id="kosListCards">
        <!-- Rendered by KosKu JavaScript -->
      </div>
    </div>

    <!-- Right Map View -->
    <div class="col-12 col-lg-7 col-xl-8 explorer-map-wrapper">
      <div id="map" style="width: 100%; height: 100%;"></div>
    </div>

  </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-kosku-xl border-0 shadow-lg">
      <div class="modal-header border-bottom px-4 py-3">
        <h5 class="modal-title fw-bold text-dark" id="filterModalLabel">
          <i class="bi bi-sliders me-2"></i> Filter Pencarian Kos
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        
        <!-- Rentang Harga -->
        <div class="mb-4">
          <label class="form-label fw-bold text-dark small text-uppercase">Rentang Harga per Bulan (Rp)</label>
          <div class="row g-2">
            <div class="col-6">
              <input type="number" id="filterHargaMin" class="form-control rounded-pill" placeholder="Min (cth: 500000)" step="50000">
            </div>
            <div class="col-6">
              <input type="number" id="filterHargaMax" class="form-control rounded-pill" placeholder="Max (cth: 1500000)" step="50000">
            </div>
          </div>
        </div>

        <!-- Status Ketersediaan -->
        <div class="mb-4">
          <label class="form-label fw-bold text-dark small text-uppercase">Ketersediaan Kamar</label>
          <select id="filterKetersediaan" class="form-select rounded-pill">
            <option value="">Semua Status</option>
            <option value="tersedia">Hanya Kamar Tersedia</option>
            <option value="penuh">Termasuk Kamar Penuh</option>
          </select>
        </div>

        <!-- Fasilitas Checkboxes -->
        <div class="mb-3">
          <label class="form-label fw-bold text-dark small text-uppercase mb-2">Fasilitas yang Dibutuhkan</label>
          <div class="row g-2">
            <?php foreach ($fasilitasList as $fas): ?>
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input filter-fasilitas-check" type="checkbox" value="<?= $fas['id'] ?>" id="fas-check-<?= $fas['id'] ?>">
                  <label class="form-check-label small" for="fas-check-<?= $fas['id'] ?>">
                    <i class="bi <?= esc($fas['ikon']) ?> text-muted me-1"></i> <?= esc($fas['nama']) ?>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
      <div class="modal-footer border-top px-4 py-3">
        <button type="button" class="btn btn-light btn-pill" onclick="KosKu.resetFilters()" data-bs-dismiss="modal">
          Reset
        </button>
        <button type="button" class="btn btn-kosku-dark btn-pill px-4" onclick="KosKu.fetchKos()" data-bs-dismiss="modal">
          Terapkan Filter
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const defaultCenter = <?= json_encode($defaultCenter) ?>;
    const initialKos = <?= json_encode($kosList) ?>;
    const wilayahList = <?= json_encode($wilayahList) ?>;
    const selectedWilayahId = '<?= esc($selectedWilayahId) ?>';

    KosKu.init({
      defaultCenter: defaultCenter,
      initialKos: initialKos,
      wilayahList: wilayahList,
      selectedWilayahId: selectedWilayahId
    });
  });
</script>
<?= $this->endSection() ?>
