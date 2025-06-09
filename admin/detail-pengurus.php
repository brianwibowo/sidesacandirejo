<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Detail Data Pengurus - Arsip Desa Candirejo</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../img/icon.ico">
  <link rel="shortcut icon" type="image/x-icon" href="../img/icon.ico">
  <link rel="icon" type="image/png" href="../img/icon.ico">
  <link rel="apple-touch-icon" href="../img/icon.ico">

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../assets/images/favicon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      <!-- Profile and Sidebarmenu -->
      <?php
        include("sidebarmenu.php");
        ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
        include("header.php");
        ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Data Pengurus</h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data Pengurus ><small>Detail Data Pengurus</small></h2>
                  <div class="clearfix"></div>
                </div>
                <?php include '../koneksi/koneksi.php';
                     $id			= mysqli_real_escape_string($db,$_GET['id']);
                     $sql  		= "SELECT * FROM tb_data_pengurus where id='".$id."'";                        
                     $query  	= mysqli_query($db, $sql);
                     $data 		= mysqli_fetch_array($query);?>
                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Data Pengurus</h2>
                      </div>
                    </div>
                    <div class="x_content">
                    </div>
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td width="200">Nama</td>
                          <td>:</td>
                          <td><?php echo !empty($data['nama']) ? $data['nama'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>No. KTP</td>
                          <td>:</td>
                          <td><?php echo !empty($data['no_ktp']) ? $data['no_ktp'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Jabatan</td>
                          <td>:</td>
                          <td><?php echo !empty($data['jabatan']) ? $data['jabatan'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Periode</td>
                          <td>:</td>
                          <td><?php echo !empty($data['periode']) ? $data['periode'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Alamat</td>
                          <td>:</td>
                          <td><?php echo !empty($data['alamat']) ? $data['alamat'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>No. Telepon</td>
                          <td>:</td>
                          <td><?php echo !empty($data['no_telp']) ? $data['no_telp'] : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Foto KTP</td>
                          <td>:</td>
                          <td>
                            <?php if(!empty($data['foto_ktp'])): ?>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                     
                                        <div class="card-body text-center">
                                            <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data['foto_ktp']); ?>" 
                                                 alt="Foto KTP" class="img-fluid" style="max-width: 300px; max-height: 300px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Pas Foto</td>
                          <td>:</td>
                          <td>
                            <?php if(!empty($data['pas_foto'])): ?>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                     
                                        <div class="card-body text-center">
                                            <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data['pas_foto']); ?>" 
                                                 alt="Pas Foto" class="img-fluid" style="max-width: 300px; max-height: 300px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="text-right">
                      <a href="datapengurus.php" class="btn btn-success"><span
                          class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
                    </div>

                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
      <div class="pull-right">

      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
  </div>
  </div>

  <!-- jQuery -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- morris.js -->
  <script src="../assets/vendors/raphael/raphael.min.js"></script>
  <script src="../assets/vendors/morris.js/morris.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

</body>

</html>