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

        // Nama file unik agar tidak tertimpa
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
<title>Tambah Admin - Arsip Surat Desa Candirejo</title>
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
      <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Tambah Admin Baru</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">

                <div class="form-group">
                  <label class="control-label col-md-3">Nama Admin <span class="required">*</span></label>
                  <div class="col-md-6">
                    <input type="text" name="nama_admin" required class="form-control" placeholder="Masukkan nama lengkap">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Username <span class="required">*</span></label>
                  <div class="col-md-6">
                    <input type="text" name="username_admin" required class="form-control" placeholder="Masukkan username">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Password <span class="required">*</span></label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="password" id="password_admin" name="password_admin" required class="form-control" placeholder="Masukkan password">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default" id="togglePassword" tabindex="-1">
                          <i class="fa fa-eye" id="eyeIcon"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Role <span class="required">*</span></label>
                  <div class="col-md-6">
                    <select name="role" class="form-control" required>
                      <option value="">Pilih Role</option>
                      <option value="superadmin">Superadmin</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Foto Profil</label>
                  <div class="col-md-6">
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Opsional.</small>
                  </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-md-offset-3">
                    <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <a href="manajemen_admin.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                  </div>
                </div>

              </form>
            </div>
          </div>
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