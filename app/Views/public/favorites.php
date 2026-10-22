<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <span class="badge badge-kosku badge-kosku-dark mb-2">Penyimpanan Lokal</span>
      <h2 class="fw-800 text-dark mb-1">Kos Favorit Saya</h2>
      <p class="text-muted small mb-0">Daftar kos yang telah Anda tandai sebagai favorit tersimpan di peramban ini.</p>
    </div>
    <a href="<?= base_url('/') ?>" class="btn btn-kosku-dark btn-pill">
      <i class="bi bi-map me-1"></i> Kembali ke Peta
    </a>
  </div>

  <div id="favoritesListContainer">
    <div class="text-center py-5">
      <div class="spinner-border text-dark"></div>
      <p class="text-muted small mt-2">Memuat daftar favorit...</p>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('favoritesListContainer');
    let stored = [];
    try {
      stored = JSON.parse(localStorage.getItem('kosku_favorites')) || [];
    } catch (e) {
      stored = [];
    }

    if (stored.length === 0) {
      container.innerHTML = `
        <div class="card card-kosku p-5 text-center my-4">
          <div class="mb-3 text-muted"><i class="bi bi-heart fs-1"></i></div>
          <h4 class="fw-bold text-dark">Belum ada kos favorit</h4>
          <p class="text-muted small mb-4">Jelajahi peta dan klik ikon hati pada kos yang menarik perhatian Anda untuk menyimpannya di sini.</p>
          <div>
            <a href="/" class="btn btn-kosku-dark btn-pill">
              <i class="bi bi-compass me-1"></i> Jelajahi Peta Sekarang
            </a>
          </div>
        </div>
      `;
      return;
    }

    fetch('/api/kos')
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          const allKos = res.data;
          const favKos = allKos.filter(k => stored.includes(parseInt(k.id)));

          if (favKos.length === 0) {
            container.innerHTML = `
              <div class="card card-kosku p-5 text-center my-4">
                <h5 class="fw-bold text-dark">Kos favorit yang tersimpan tidak ditemukan</h5>
                <p class="text-muted small mb-3">Mungkin data telah diperbarui atau dinonaktifkan.</p>
                <a href="/" class="btn btn-kosku-dark btn-pill">Jelajahi Kos</a>
              </div>
            `;
            return;
          }

          let html = `<div class="row g-4">`;
          favKos.forEach(k => {
            const thumb = k.foto_utama ? `/${k.foto_utama.path_file}` : '/uploads/kos/sample_kos_1.svg';
            const price = 'Rp ' + Number(k.harga_min).toLocaleString('id-ID');

            html += `
              <div class="col-12 col-md-6 col-lg-4" id="fav-card-${k.id}">
                <div class="card card-kosku h-100 overflow-hidden">
                  <div class="position-relative">
                    <img src="${thumb}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${k.nama}">
                    <span class="position-absolute top-0 start-0 m-3 badge badge-kosku badge-kosku-dark text-capitalize">Kos ${k.tipe}</span>
                    <button class="position-absolute top-0 end-0 m-3 btn btn-icon-circle bg-white shadow-sm" onclick="removeFavorite(${k.id})">
                      <i class="bi bi-heart-fill text-danger fs-6"></i>
                    </button>
                  </div>
                  <div class="card-body p-4 d-flex flex-column">
                    <span class="small text-muted fw-bold text-uppercase">${k.nama_wilayah || ''}</span>
                    <h5 class="fw-bold text-dark mb-1 text-truncate">${k.nama}</h5>
                    <p class="text-muted small text-truncate mb-3">${k.alamat}</p>
                    <div class="fs-5 fw-800 text-dark mb-3">${price} <span class="fs-6 fw-normal text-muted">/bln</span></div>
                    
                    <div class="mt-auto d-flex gap-2">
                      <a href="/kos/${k.slug}" class="btn btn-sm btn-kosku-dark btn-pill w-100">
                        Lihat Detail
                      </a>
                      <a href="https://wa.me/${k.kontak_wa}" target="_blank" class="btn btn-sm btn-kosku-light btn-icon-circle" title="Chat WA">
                        <i class="bi bi-whatsapp text-success"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          html += `</div>`;
          container.innerHTML = html;
        }
      })
      .catch(err => {
        container.innerHTML = `<div class="alert alert-danger">Gagal memuat data favorit.</div>`;
      });
  });

  function removeFavorite(id) {
    try {
      let stored = JSON.parse(localStorage.getItem('kosku_favorites')) || [];
      stored = stored.filter(i => i !== id);
      localStorage.setItem('kosku_favorites', JSON.stringify(stored));
      const card = document.getElementById(`fav-card-${id}`);
      if (card) card.remove();
      const badge = document.getElementById('favCountBadge');
      if (badge) {
        badge.textContent = stored.length;
        if (stored.length === 0) badge.style.display = 'none';
      }
      if (stored.length === 0) {
        location.reload();
      }
    } catch (e) {}
  }
</script>
<?= $this->endSection() ?>
