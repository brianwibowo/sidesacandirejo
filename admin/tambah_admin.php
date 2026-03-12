<?php
date_default_timezone_set('Asia/Jakarta'); // WIB
session_start();    
include "../koneksi/koneksi.php";
include "login/ceksession.php";

// Cek role superadmin
if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

// Proses form saat disubmit
if(isset($_POST['submit'])){
    $nama_admin = mysqli_real_escape_string($db, $_POST['nama_admin']);
    $username_admin = mysqli_real_escape_string($db, $_POST['username_admin']);
    
    // Hash password dengan SHA-1 (sama seperti reset password)
    $password_admin = sha1($_POST['password_admin']);
    
    $role = $_POST['role'];

    // Upload gambar ke folder admin/images
    $gambar = null;
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0){
        $filename = basename($_FILES['gambar']['name']); // nama asli file
        $target_dir = __DIR__ . "/images/"; // folder admin/images
        $target_file = $target_dir . $filename; // simpan langsung dengan nama aslinya

        if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)){
            // Simpan hanya nama file di DB
            $gambar = $filename;
        } else {
            $error = "Gagal upload gambar.";
        }
    }

    // Insert ke database
    $sql_insert = "INSERT INTO tb_admin (nama_admin, username_admin, password, role, gambar, last_active) 
                   VALUES ('$nama_admin', '$username_admin', '$password_admin', '$role', '$gambar', NULL)";
    if(mysqli_query($db, $sql_insert)){
        header("Location:manajemen_admin.php?status=success");
        exit();
    } else {
        $error = "Gagal menambahkan admin: " . mysqli_error($db);
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

<!-- Bootstrap -->
<link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="../assets/build/css/custom.min.css" rel="stylesheet">
<link rel="shortcut icon" href="../img/icon.ico">

</head>
<body class="nav-md">
<div class="container body">
  <div class="main_container">

    <!-- Sidebar -->
    <?php include("sidebarmenu.php"); ?>
    <!-- /Sidebar -->

    <!-- Top navigation -->
    <?php include("header.php"); ?>
    <!-- /Top navigation -->

    <!-- Page content -->
    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Tambah Admin Baru</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <?php if(isset($error)){ echo '<div class="alert alert-danger">'.$error.'</div>'; } ?>
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">

                <div class="form-group">
                  <label class="control-label col-md-3" for="nama_admin">Nama Admin <span class="required">*</span></label>
                  <div class="col-md-6">
                    <input type="text" id="nama_admin" name="nama_admin" required="required" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3" for="username_admin">Username <span class="required">*</span></label>
                  <div class="col-md-6">
                    <input type="text" id="username_admin" name="username_admin" required="required" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3" for="password_admin">Password <span class="required">*</span></label>
                  <div class="col-md-6">
                    <input type="password" id="password_admin" name="password_admin" required="required" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3" for="role">Role <span class="required">*</span></label>
                  <div class="col-md-6">
                    <select id="role" name="role" class="form-control" required>
                      <option value="">Pilih Role</option>
                      <option value="superadmin">Superadmin</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3" for="gambar">Gambar</label>
                  <div class="col-md-6">
                    <input type="file" id="gambar" name="gambar" class="form-control">
                  </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-md-offset-3">
                    <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <a href="manajemen_admin.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Page content -->

    <!-- Footer -->
    <footer>
      <div class="pull-right">Apriansyah Wibowo. All Rights Reserved.</div>
      <div class="clearfix"></div>
    </footer>
    <!-- /Footer -->

  </div>
</div>

<!-- jQuery -->
<script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="../assets/build/js/custom.min.js"></script>

</body>
</html>