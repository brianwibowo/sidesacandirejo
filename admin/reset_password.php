<?php
session_start();
include '../koneksi/koneksi.php';

$id = intval($_GET['id']); // pastikan ID valid
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password Admin</title>
<link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top:50px; max-width:500px;">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-key"></i> Reset Password Admin</h3>
    </div>
    <div class="panel-body">
      <form method="POST" action="proses/proses_reset_password.php" class="form-horizontal">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
          <label class="col-sm-4 control-label">Password Baru</label>
          <div class="col-sm-8">
            <input type="password" name="password" class="form-control" required placeholder="Masukkan password baru">
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-8 col-sm-offset-4">
            <button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> Reset Password</button>
            <a href="manajemen_admin.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
<script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>