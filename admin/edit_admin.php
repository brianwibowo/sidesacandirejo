<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

// Proteksi halaman hanya untuk superadmin
if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

// Ambil ID admin yang akan diedit
if(!isset($_GET['id'])){
    header("Location: manajemen_admin.php");
    exit();
}
$id_to_edit = intval($_GET['id']); // ID dari URL

// Ambil data admin dari DB
$query = mysqli_query($db, "SELECT * FROM tb_admin WHERE id_admin='$id_to_edit'");
if(mysqli_num_rows($query) == 0){
    echo "<script>alert('Admin tidak ditemukan');window.location='manajemen_admin.php';</script>";
    exit();
}
$data = mysqli_fetch_assoc($query); // data admin untuk form
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Arsip Surat Desa Candirejo Borobudur</title>
<link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
<div class="container body">
  <div class="main_container">
    <?php include("sidebarmenu.php"); ?>
    <?php include("header.php"); ?>

    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-edit"></i> Edit Admin</h3>
            </div>
            <div class="panel-body">
              <form action="proses/proses_edit_admin.php" method="post" class="form-horizontal">
                <input type="hidden" name="id_admin" value="<?php echo $data['id_admin']; ?>">

                <div class="form-group">
                  <label class="col-sm-3 control-label">Nama Admin</label>
                  <div class="col-sm-9">
                    <input type="text" name="nama_admin" class="form-control" required
                           value="<?php echo htmlspecialchars($data['nama_admin']); ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Username</label>
                  <div class="col-sm-9">
                    <input type="text" name="username_admin" class="form-control" required
                           value="<?php echo htmlspecialchars($data['username_admin']); ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Role</label>
                  <div class="col-sm-9">
                    <select name="role" class="form-control" required>
                      <option value="superadmin" <?php if($data['role']=='superadmin') echo 'selected'; ?>>Superadmin</option>
                      <option value="admin" <?php if($data['role']=='admin') echo 'selected'; ?>>Admin</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-9 col-sm-offset-3">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    <a href="manajemen_admin.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
                  </div>
                </div>

              </form>
            </div>
          </div>
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
</body>
</html>