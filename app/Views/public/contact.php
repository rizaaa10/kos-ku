<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">
      
      <div class="text-center mb-5">
        <span class="badge badge-coach badge-coach-teal px-3 py-2 text-uppercase mb-3">Kontak &amp; Dukungan</span>
        <h1 class="fw-800 text-dark display-6 tracking-tight mb-2">Bantuan &amp; Pelaporan Data</h1>
        <p class="text-muted">Punya pertanyaan, koreksi data, atau ingin mendaftarkan kos Anda?</p>
      </div>

      <div class="row g-4 mb-5">
        <div class="col-12 col-md-6">
          <div class="card card-kosku p-4 h-100">
            <div class="btn-icon-circle-dark mb-3" style="width: 44px; height: 44px;">
              <i class="bi bi-flag fs-5"></i>
            </div>
            <h5 class="fw-bold text-dark">Laporkan Data Kos</h5>
            <p class="text-secondary small mb-3">Jika Anda menemukan nomor WhatsApp pemilik tidak aktif, alamat salah, atau status ketersediaan keliru, laporkan kepada tim kami.</p>
            <a href="mailto:support@kosku.id?subject=Laporan%20Data%20Kos" class="btn btn-sm btn-kosku-light btn-pill mt-auto">
              Kirim Email Laporan
            </a>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card card-kosku p-4 h-100">
            <div class="btn-icon-circle-dark mb-3" style="width: 44px; height: 44px;">
              <i class="bi bi-house-add fs-5"></i>
            </div>
            <h5 class="fw-bold text-dark">Daftarkan Kos Anda</h5>
            <p class="text-secondary small mb-3">Pemilik kos yang ingin propertinya diverifikasi dan ditampilkan di peta interaktif kosKu secara gratis dapat menghubungi admin.</p>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20kosKu,%20saya%20pemilik%20kos%20ingin%20mendaftarkan%20kos%20saya" target="_blank" class="btn btn-sm btn-kosku-dark btn-pill mt-auto">
              Hubungi via WhatsApp
            </a>
          </div>
        </div>
      </div>

      <div class="card card-kosku p-4 p-md-5">
        <h4 class="fw-bold text-dark mb-4">Kirim Pesan Cepat</h4>
        <form onsubmit="event.preventDefault(); alert('Terima kasih, pesan Anda telah berhasil dikirim ke tim admin kosKu.'); this.reset();">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label small fw-bold">Nama Lengkap</label>
              <input type="text" class="form-control rounded-pill" required placeholder="Masukkan nama Anda">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label small fw-bold">Email / No WhatsApp</label>
              <input type="text" class="form-control rounded-pill" required placeholder="cth: 08123456789">
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Subjek</label>
              <select class="form-select rounded-pill">
                <option>Pertanyaan Umum</option>
                <option>Lapor Data Kos Tidak Sesuai</option>
                <option>Pendaftaran Listing Kos Baru</option>
                <option>Kerjasama Kampus / Instansi</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Isi Pesan</label>
              <textarea class="form-control rounded-4" rows="4" required placeholder="Tuliskan pesan atau detail laporan Anda..."></textarea>
            </div>
            <div class="col-12 pt-2">
              <button type="submit" class="btn btn-kosku-dark btn-pill px-4 py-2">
                <i class="bi bi-send me-1"></i> Kirim Pesan
              </button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
