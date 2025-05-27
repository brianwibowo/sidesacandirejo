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

    <title>Arsip Surat Desa Candirejo Borobudur</title>

    <!-- Bootstrap -->
    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/icon.ico">

    <!-- Custom Theme Style -->
    <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        
        <!-- Profile and Sidebarmenu -->
        <?php include("sidebarmenu.php"); ?>
        <!-- /Profile and Sidebarmenu -->
        
        <!-- top navigation -->
        <?php include("header.php"); ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row">
            <div class="col-md-12">
              <div class="">
                <div class="x_content">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">     
                      <center>
                          <h1><b>Selamat Datang, <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Tamu'; ?></b></h1>
                      </center>
                      <br><br>  
                    </div>
                  </div>

                  <div class="row">
                    <?php include '../koneksi/koneksi.php';
                    $sql1 = "SELECT * FROM tb_arsip_surat_masuk";  
                    $query1 = mysqli_query($db, $sql1);
                    $jumlah1 = mysqli_num_rows($query1);
                    ?>
                    <div class="animated flipInY col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="tile-stats">
                        <div class="icon"><i class="fa fa-inbox"></i></div>
                        <div class="count"><?php echo "$jumlah1" ?></div>
                        <h3>Surat Masuk</h3>
                        <p>Telah diarsipkan</p>
                      </div>
                    </div>

                    <?php
                    $sql2 = "SELECT * FROM tb_arsip_surat_keluar";  
                    $query2 = mysqli_query($db, $sql2);
                    $jumlah2 = mysqli_num_rows($query2);
                    ?>
                    <div class="animated flipInY col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <div class="tile-stats">
                        <div class="icon"><i class="fa fa-send"></i></div>
                        <div class="count"><?php echo "$jumlah2" ?></div>
                        <h3>Surat Keluar</h3>
                        <p>Telah diarsipkan</p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <a href="inputbuatsurat.php" class="btn btn-primary btn-lg">
                          <i class="fa fa-pencil"></i> Buat Surat
                        </a>
                      </center>
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
            Apriansyah Wibowo. All Rights Reserved.</a>
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
    <!-- Custom Theme Scripts -->
    <script src="../assets/build/js/custom.min.js"></script>
  </body>
</html>
