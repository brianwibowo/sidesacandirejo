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
<title>Edit Admin - Arsip Desa Candirejo</title>
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
          <h1>Edit Admin</h1>
          <p>Perbarui data akun admin yang ada</p>
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
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h2>Edit Data Admin</h2>
          </div>
        </div>
        <div class="form-card-body">
          <div class="info-bar-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Mengedit akun: <strong><?= htmlspecialchars($data['nama_admin']) ?></strong>
          </div>

          <form action="proses/proses_edit_admin.php" method="post">
            <input type="hidden" name="id_admin" value="<?= $data['id_admin'] ?>">

            <div class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Identitas & Akses
            </div>

            <div class="form-row">
              <label class="form-label">Nama Admin <span class="req">*</span>
                <small>Nama tampil di sistem</small>
              </label>
              <div>
                <input type="text" name="nama_admin" class="form-input" required maxlength="70"
                  value="<?= htmlspecialchars($data['nama_admin']) ?>">
              </div>
            </div>

            <div class="form-row">
              <label class="form-label">Username <span class="req">*</span>
                <small>Digunakan untuk login</small>
              </label>
              <div>
                <input type="text" name="username_admin" class="form-input" required maxlength="50"
                  value="<?= htmlspecialchars($data['username_admin']) ?>">
              </div>
            </div>

            <div class="form-row">
              <label class="form-label">Role <span class="req">*</span>
                <small>Level akses pengguna</small>
              </label>
              <div>
                <select name="role" class="form-select input-md" required>
                  <option value="superadmin" <?= $data['role']=='superadmin' ? 'selected' : '' ?>>Superadmin</option>
                  <option value="admin" <?= $data['role']=='admin' ? 'selected' : '' ?>>Admin</option>
                </select>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-submit">
                <i class="fa fa-save"></i> Simpan Perubahan
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