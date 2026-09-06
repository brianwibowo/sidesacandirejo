<?php
date_default_timezone_set('Asia/Jakarta');
session_start();    
include "../koneksi/koneksi.php";
include "login/ceksession.php";

if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

if(isset($_POST['submit'])){
    $nama_admin     = mysqli_real_escape_string($db, trim($_POST['nama_admin']));
    $username_admin = mysqli_real_escape_string($db, trim($_POST['username_admin']));
    $password_admin = sha1($_POST['password_admin']);
    $role           = mysqli_real_escape_string($db, $_POST['role']);

    // Cek username sudah dipakai
    $cek = mysqli_query($db, "SELECT id_admin FROM tb_admin WHERE username_admin='$username_admin'");
    if(mysqli_num_rows($cek) > 0){
        $_SESSION['alert'] = ['type'=>'warning','msg'=>'Username <strong>'.$username_admin.'</strong> sudah digunakan, silakan pilih username lain.'];
        header("Location: tambah_admin.php");
        exit();
    }

    // Upload gambar
    $gambar = null;
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $ext_allowed = ['jpg','jpeg','png','gif','webp'];
        $ext         = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $ext_allowed)){
            $_SESSION['alert'] = ['type'=>'danger','msg'=>'Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF.'];
            header("Location: tambah_admin.php");
            exit();
        }

        $filename    = time() . '_' . basename($_FILES['gambar']['name']);
        $target_dir  = __DIR__ . "/images/";
        $target_file = $target_dir . $filename;

        if(!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)){
            $_SESSION['alert'] = ['type'=>'danger','msg'=>'Gagal upload gambar. Pastikan folder <code>admin/images/</code> memiliki permission write.'];
            header("Location: tambah_admin.php");
            exit();
        }
        $gambar = $filename;
    }

    $sql_insert = "INSERT INTO tb_admin (nama_admin, username_admin, password, role, gambar, last_active) 
                   VALUES ('$nama_admin', '$username_admin', '$password_admin', '$role', ".($gambar ? "'$gambar'" : "NULL").", NULL)";

    if(mysqli_query($db, $sql_insert)){
        $_SESSION['alert'] = ['type'=>'success','msg'=>"Admin <strong>$nama_admin</strong> berhasil ditambahkan."];
        header("Location: manajemen_admin.php");
        exit();
    } else {
        $_SESSION['alert'] = ['type'=>'danger','msg'=>'Gagal menambahkan admin: ' . mysqli_error($db)];
        header("Location: tambah_admin.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Admin - Arsip Desa Candirejo</title>
<link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../assets/build/css/custom.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="shortcut icon" href="../img/icon.ico">
</head>
<body class="nav-md">
<div class="container body">
  <div class="main_container">

    <?php include("sidebarmenu.php"); ?>
    <?php include("header.php"); ?>

    <div class="right_col" role="main">
      <div class="page-title-modern">
        <div class="page-title-left">
          <h1>Tambah Admin Baru</h1>
          <p>Daftarkan akun admin baru untuk sistem arsip</p>
        </div>
        <a href="manajemen_admin.php" class="btn-back-modern">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
          Kembali ke Manajemen Admin
        </a>
      </div>

      <div class="form-card">
        <div class="form-card-header">
          <div class="form-card-header-left">
            <div class="hicon">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h2>Data Admin Baru</h2>
          </div>
        </div>
        <div class="form-card-body">
          <form action="" method="post" enctype="multipart/form-data">

            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Identitas Admin
            </div>

            <div class="form-row">
              <label class="form-label">Nama Admin <span class="req">*</span>
                <small>Nama lengkap admin</small>
              </label>
              <div>
                <input type="text" name="nama_admin" required class="form-input"
                  placeholder="Masukkan nama lengkap" maxlength="70">
              </div>
            </div>

            <div class="form-row">
              <label class="form-label">Username <span class="req">*</span>
                <small>Digunakan untuk login</small>
              </label>
              <div>
                <input type="text" name="username_admin" required class="form-input"
                  placeholder="Masukkan username" maxlength="50">
              </div>
            </div>

            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              Keamanan & Akses
            </div>

            <div class="form-row">
              <label class="form-label">Password <span class="req">*</span>
                <small>Minimal 6 karakter</small>
              </label>
              <div>
                <div class="input-group-modern">
                  <input type="password" id="password_admin" name="password_admin" required class="form-input"
                    placeholder="Masukkan password">
                  <span class="input-group-addon-modern" id="togglePassword" style="cursor:pointer;">
                    <i class="fa fa-eye" id="eyeIcon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="form-row">
              <label class="form-label">Role <span class="req">*</span>
                <small>Level akses pengguna</small>
              </label>
              <div>
                <select name="role" class="form-select input-md" required>
                  <option value="">Pilih Role</option>
                  <option value="superadmin">Superadmin</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div>

            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              Foto Profil
            </div>

            <div class="form-row">
              <label class="form-label">Foto Profil
                <small>JPG, PNG, GIF, WEBP – Opsional</small>
              </label>
              <div>
                <div class="upload-area-modern" onclick="document.getElementById('gambar').click()" style="padding:16px;">
                  <div class="upload-icon"><i class="fa fa-user-circle"></i></div>
                  <div class="upload-text-main">Klik untuk upload foto profil</div>
                  <div class="upload-text-sub">JPG, PNG, GIF, WEBP – Opsional</div>
                </div>
                <input type="file" name="gambar" id="gambar" class="hidden-file-input" accept="image/*">
                <div id="preview-gambar" style="margin-top:8px;"></div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" name="submit" class="btn-submit">
                <i class="fa fa-save"></i> Simpan Admin
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
      <div class="pull-right">Apriansyah Wibowo. All Rights Reserved.</div>
      <div class="clearfix"></div>
    </footer>
  </div>
</div>

<script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
<script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/build/js/custom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
// Toggle show/hide password
document.getElementById('togglePassword').addEventListener('click', function(){
    var input = document.getElementById('password_admin');
    var icon  = document.getElementById('eyeIcon');
    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
});

// SweetAlert dari session PHP
<?php if(isset($_SESSION['alert'])): ?>
<?php
    $type = $_SESSION['alert']['type'];
    $msg  = $_SESSION['alert']['msg'];
    $icon = $type == 'success' ? 'success' : ($type == 'danger' ? 'error' : 'warning');
    $title = $type == 'success' ? 'Berhasil' : ($type == 'danger' ? 'Gagal' : 'Perhatian');
    unset($_SESSION['alert']);
?>
Swal.fire({
    icon: '<?= $icon ?>',
    title: '<?= $title ?>',
    html: '<?= addslashes($msg) ?>',
    confirmButtonText: 'OK'
});
<?php endif; ?>
</script>
</body>
</html>