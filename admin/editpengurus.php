<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
ob_start();

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  echo "<script>alert('ID tidak valid!'); window.location='datapengurus.php';</script>";
  exit;
}

// Ambil data pengurus berdasarkan ID dengan prepared statement untuk keamanan
$query = "SELECT * FROM tb_data_pengurus WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_pengurus = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$data_pengurus) {
  echo "<script>alert('Data tidak ditemukan!'); window.location='datapengurus.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Pengurus - Arsip Desa Candirejo</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../img/icon.ico">
  <link rel="shortcut icon" type="image/x-icon" href="../img/icon.ico">
  <link rel="icon" type="image/png" href="../img/icon.ico">
  <link rel="apple-touch-icon" href="../img/icon.ico">

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Edit Data Pengurus</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_editpengurus.php" name="formeditpengurus" method="post"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <!-- Hidden input untuk ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data_pengurus['id'] ?? $id); ?>">
                    <input type="hidden" name="foto_ktp_old" value="<?php echo htmlspecialchars($data_pengurus['foto_ktp'] ?? ''); ?>">
                    <input type="hidden" name="pas_foto_old" value="<?php echo htmlspecialchars($data_pengurus['pas_foto'] ?? ''); ?>">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama" name="nama" required="required" maxlength="100"
                          placeholder="Masukkan Nama Pengurus" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengurus['nama'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_ktp">No. KTP <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="no_ktp" name="no_ktp" required="required" maxlength="20"
                          placeholder="Masukkan No. KTP" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengurus['no_ktp'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jabatan">Jabatan <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="jabatan" name="jabatan" required="required" maxlength="50"
                          placeholder="Masukkan Jabatan" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengurus['jabatan'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="periode">Periode <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="periode" name="periode" required="required" maxlength="20"
                          placeholder="Masukkan Periode" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengurus['periode'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="alamat" name="alamat" required="required" class="form-control col-md-7 col-xs-12"
                          placeholder="Masukkan Alamat"><?php echo htmlspecialchars($data_pengurus['alamat'] ?? ''); ?></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_telp">No. Telepon <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="no_telp" name="no_telp" required="required" maxlength="15"
                          placeholder="Masukkan No. Telepon" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengurus['no_telp'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="foto_ktp">Foto KTP</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($data_pengurus['foto_ktp'])): ?>
                          <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data_pengurus['foto_ktp']); ?>" 
                               alt="Foto KTP" style="max-width: 300px; max-height: 300px; object-fit: contain; margin-bottom: 10px;"><br>
                        <?php endif; ?>
                        <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*" class="form-control col-md-7 col-xs-12">
                        <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
                        <?php if (!empty($data_pengurus['foto_ktp'])): ?>
                      
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pas_foto">Pas Foto</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($data_pengurus['pas_foto'])): ?>
                          <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data_pengurus['pas_foto']); ?>" 
                               alt="Pas Foto" style="max-width: 300px; max-height: 300px; object-fit: contain; margin-bottom: 10px;"><br>
                        <?php endif; ?>
                        <input type="file" id="pas_foto" name="pas_foto" accept="image/*" class="form-control col-md-7 col-xs-12">
                        <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
                        <?php if (!empty($data_pengurus['pas_foto'])): ?>
                       
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="datapengurus.php" class="btn btn-primary">Kembali</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>
</body>

</html>
<?php ob_end_flush(); ?>