<?php
session_start();
include '../koneksi/koneksi.php';
include 'login/ceksession.php';

$id = intval($_GET['id'] ?? 0);

// Ambil data admin yang akan di-reset
$query_admin = mysqli_query($db, "SELECT nama_admin, username_admin, role FROM tb_admin WHERE id_admin='$id'");
if (!$query_admin || mysqli_num_rows($query_admin) === 0) {
    header("Location: manajemen_admin.php");
    exit;
}
$target_admin = mysqli_fetch_assoc($query_admin);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password Admin - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    .strength-bar {
      height: 5px; border-radius: 4px; margin-top: 6px;
      transition: all 0.3s;
    }
    .strength-label {
      font-size: 11px; margin-top: 4px; font-weight: 600;
    }
    .strength-weak   { background: #e74c3c; }
    .strength-medium { background: #f39c12; }
    .strength-strong { background: #27ae60; }
    .label-weak   { color: #e74c3c; }
    .label-medium { color: #f39c12; }
    .label-strong { color: #27ae60; }

    .admin-badge {
      display: inline-flex; align-items: center; gap: 10px;
      background: #f0f8f4; border: 1.5px solid #b5d5c0;
      border-radius: 10px; padding: 12px 16px;
      margin-bottom: 4px;
    }
    .admin-badge .avatar {
      width: 44px; height: 44px; border-radius: 50%;
      background: var(--primary-color, #1e3a2f);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 18px; font-weight: 700;
      flex-shrink: 0;
    }
    .admin-badge .info strong {
      display: block; font-size: 14px; font-weight: 700; color: #2a4535;
    }
    .admin-badge .info span {
      font-size: 12px; color: #6b8f7e;
    }
    .role-pill {
      display: inline-block; padding: 2px 10px; border-radius: 20px;
      font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
      background: #daf0e3; color: #1e6c45;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Reset Password Admin</h1>
            <p>Atur ulang kata sandi akun admin</p>
          </div>
          <a href="manajemen_admin.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
              <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Manajemen Admin
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
              </div>
              <h2>Reset Password</h2>
            </div>
          </div>

          <div class="form-card-body">
            <!-- Target admin info -->
            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Target Admin
            </div>

            <div class="form-row">
              <label class="form-label">Admin yang Direset
                <small>Informasi akun yang akan diperbarui</small>
              </label>
              <div>
                <div class="admin-badge">
                  <div class="avatar"><?php echo strtoupper(substr($target_admin['nama_admin'], 0, 1)); ?></div>
                  <div class="info">
                    <strong><?php echo htmlspecialchars($target_admin['nama_admin']); ?></strong>
                    <span>@<?php echo htmlspecialchars($target_admin['username_admin']); ?></span><br>
                    <span class="role-pill"><?php echo htmlspecialchars($target_admin['role']); ?></span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Divider -->
            <div class="section-title" style="margin-top:8px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              Password Baru
            </div>

            <form method="POST" action="proses/proses_reset_password.php" id="reset-form">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <div class="form-row">
                <label class="form-label">Password Baru <span class="req">*</span>
                  <small>Minimal 6 karakter</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="password" id="password" name="password" required
                      class="form-input" placeholder="Masukkan password baru"
                      minlength="6" oninput="checkStrength(this.value)">
                    <span class="input-group-addon-modern" id="togglePass" style="cursor:pointer;"
                      onclick="toggleVisibility('password','eyeIcon1')">
                      <i class="fa fa-eye" id="eyeIcon1"></i>
                    </span>
                  </div>
                  <div id="strength-container" style="display:none;margin-top:6px;">
                    <div id="strength-bar" class="strength-bar" style="width:0%;"></div>
                    <div id="strength-label" class="strength-label"></div>
                  </div>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Konfirmasi Password <span class="req">*</span>
                  <small>Ulangi password baru</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="password" id="confirm_password" name="confirm_password" required
                      class="form-input" placeholder="Ulangi password baru">
                    <span class="input-group-addon-modern" style="cursor:pointer;"
                      onclick="toggleVisibility('confirm_password','eyeIcon2')">
                      <i class="fa fa-eye" id="eyeIcon2"></i>
                    </span>
                  </div>
                  <div id="match-msg" style="font-size:12px;margin-top:4px;display:none;"></div>
                </div>
              </div>

              <div class="info-bar-modern" style="margin-top:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Password admin akan segera diperbarui. Admin akan perlu login ulang menggunakan password baru ini.
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit" id="btn-reset">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:4px"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  Reset Password
                </button>
                <a href="manajemen_admin.php" class="btn-cancel">
                  <i class="fa fa-times"></i> Batal
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <footer>
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  function toggleVisibility(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon  = document.getElementById(iconId);
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  function checkStrength(val) {
    var container = document.getElementById('strength-container');
    var bar       = document.getElementById('strength-bar');
    var label     = document.getElementById('strength-label');

    if (!val) { container.style.display = 'none'; return; }
    container.style.display = 'block';

    var score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    bar.className = 'strength-bar';
    label.className = 'strength-label';

    if (score <= 2) {
      bar.style.width = '33%'; bar.classList.add('strength-weak');
      label.textContent = '⚠ Lemah'; label.classList.add('label-weak');
    } else if (score <= 3) {
      bar.style.width = '66%'; bar.classList.add('strength-medium');
      label.textContent = '◑ Sedang'; label.classList.add('label-medium');
    } else {
      bar.style.width = '100%'; bar.classList.add('strength-strong');
      label.textContent = '✓ Kuat'; label.classList.add('label-strong');
    }
    checkMatch();
  }

  function checkMatch() {
    var pass    = document.getElementById('password').value;
    var confirm = document.getElementById('confirm_password').value;
    var msg     = document.getElementById('match-msg');
    if (!confirm) { msg.style.display = 'none'; return; }
    msg.style.display = 'block';
    if (pass === confirm) {
      msg.innerHTML = '<i class="fa fa-check" style="color:#27ae60;"></i> <span style="color:#27ae60;">Password cocok</span>';
    } else {
      msg.innerHTML = '<i class="fa fa-times" style="color:#e74c3c;"></i> <span style="color:#e74c3c;">Password tidak cocok</span>';
    }
  }

  document.getElementById('confirm_password').addEventListener('input', checkMatch);

  document.getElementById('reset-form').addEventListener('submit', function(e) {
    var pass    = document.getElementById('password').value;
    var confirm = document.getElementById('confirm_password').value;
    if (pass !== confirm) {
      e.preventDefault();
      Swal.fire({
        icon: 'error', title: 'Password Tidak Cocok',
        text: 'Password baru dan konfirmasi password harus sama.',
        confirmButtonColor: '#1e3a2f'
      });
      return;
    }
    if (pass.length < 6) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning', title: 'Password Terlalu Pendek',
        text: 'Password minimal 6 karakter.',
        confirmButtonColor: '#1e3a2f'
      });
    }
  });

  <?php if (isset($_SESSION['alert'])): ?>
  <?php
    $type  = $_SESSION['alert']['type'];
    $msg   = $_SESSION['alert']['msg'];
    $icon  = $type == 'success' ? 'success' : ($type == 'danger' ? 'error' : 'warning');
    $title = $type == 'success' ? 'Berhasil' : ($type == 'danger' ? 'Gagal' : 'Perhatian');
    unset($_SESSION['alert']);
  ?>
  Swal.fire({
    icon: '<?= $icon ?>',
    title: '<?= $title ?>',
    html: '<?= addslashes($msg) ?>',
    confirmButtonColor: '#1e3a2f',
    confirmButtonText: 'OK'
  });
  <?php endif; ?>
  </script>
</body>
</html>