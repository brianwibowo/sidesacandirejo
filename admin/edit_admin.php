<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: manajemen_admin.php");
    exit();
}
$id_to_edit = intval($_GET['id']);

$query = mysqli_query($db, "SELECT * FROM tb_admin WHERE id_admin='$id_to_edit'");
if(mysqli_num_rows($query) == 0){
    $_SESSION['alert'] = ['type'=>'danger','msg'=>'Admin tidak ditemukan.'];
    header("Location: manajemen_admin.php");
    exit();
}
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Admin - Arsip Surat Desa Candirejo</title>
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
        <div class="col-md-6 col-md-offset-3">
          <div class="x_panel">
            <div class="x_title">
              <h2><i class="fa fa-edit"></i> Edit Admin</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form action="proses/proses_edit_admin.php" method="post" class="form-horizontal">
                <input type="hidden" name="id_admin" value="<?= $data['id_admin'] ?>">

                <div class="form-group">
                  <label class="col-sm-3 control-label">Nama Admin</label>
                  <div class="col-sm-9">
                    <input type="text" name="nama_admin" class="form-control" required
                           value="<?= htmlspecialchars($data['nama_admin']) ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Username</label>
                  <div class="col-sm-9">
                    <input type="text" name="username_admin" class="form-control" required
                           value="<?= htmlspecialchars($data['username_admin']) ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Role</label>
                  <div class="col-sm-9">
                    <select name="role" class="form-control" required>
                      <option value="superadmin" <?= $data['role']=='superadmin' ? 'selected' : '' ?>>Superadmin</option>
                      <option value="admin"      <?= $data['role']=='admin'      ? 'selected' : '' ?>>Admin</option>
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
<script src="../assets/build/js/custom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
<?php if(isset($_SESSION['alert'])): ?>
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
    confirmButtonText: 'OK'
});
<?php endif; ?>
</script>
</body>
</html>