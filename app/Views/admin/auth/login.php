<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin — kosKu</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/kosku.css') ?>">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">

  <div class="card card-kosku p-4 p-md-5 shadow-lg border-0" style="max-width: 440px; width: 100%;">
    
    <!-- Brand -->
    <div class="text-center mb-4">
      <div class="btn-icon-circle-dark mx-auto mb-3" style="width: 52px; height: 52px; border-radius: 16px !important;">
        <i class="bi bi-geo-alt-fill text-white fs-3"></i>
      </div>
      <h3 class="fw-800 text-dark tracking-tight mb-1">kosKu Admin</h3>
      <p class="text-muted small">Masuk untuk mengelola data kos dan pemetaan wilayah.</p>
    </div>

    <!-- Flash message -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger border-0 rounded-4 small mb-3 py-2">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-dark border-0 rounded-4 small mb-3 py-2">
        <i class="bi bi-check-circle-fill me-1"></i> <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= base_url('admin/login') ?>" method="post">
      <?= csrf_field() ?>
      
      <div class="mb-3">
        <label class="form-label small fw-bold text-dark text-uppercase">Email Admin</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
            <i class="bi bi-envelope"></i>
          </span>
          <input type="email" name="email" class="form-control rounded-end-pill bg-light border-start-0 py-2" required placeholder="admin@kosku.id" value="<?= old('email', 'admin@kosku.id') ?>">
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label small fw-bold text-dark text-uppercase">Kata Sandi</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="password" class="form-control rounded-end-pill bg-light border-start-0 py-2" required placeholder="••••••••" value="admin123">
        </div>
        <div class="form-text small text-muted">Akun bawaan seeder: <code>admin@kosku.id</code> / <code>admin123</code></div>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-kosku-dark btn-pill py-2 fw-bold">
          <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
        </button>
      </div>

      <div class="text-center">
        <a href="<?= base_url('/') ?>" class="text-decoration-none text-muted small hover-dark">
          <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Publik
        </a>
      </div>
    </form>

  </div>

</body>
</html>
