<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Detail Data Pengunjung Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
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
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Detail Data Pengunjung</h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Detail Pengunjung <small>Detail Data Pengunjung</small></h2>
                  <div class="clearfix"></div>
                </div>
                <?php
                  include '../koneksi/koneksi.php';
                  $id = mysqli_real_escape_string($db, $_GET['id']);
                  $sql = "SELECT * FROM tb_data_pengunjung WHERE id='$id'";
                  $query = mysqli_query($db, $sql);
                  $data = mysqli_fetch_array($query);
                  
      
                  ?>
                <div class="x_content">
                  <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                    <div class="profile_img">
                      <div id="crop-avatar">
                        <!-- Current avatar -->
                        <?php if (!empty($data['foto'])): ?>
                        <img class="img-responsive avatar-view"
                          src="../admin/uploads/pengunjung/<?php echo htmlspecialchars($data['foto']); ?>" alt="Avatar">
                        <?php else: ?>
                        <img class="img-responsive avatar-view" src="../img/default-avatar.png" alt="Default Avatar">
                        <?php endif; ?>
                      </div>
                    </div>
                    <h3 align="center"><?php echo isset($data['nama']) ? $data['nama'] : 'N/A'; ?></h3>
                    <br />
                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Pengunjung</h2>
                      </div>
                    </div>
                    <div class="x_content">
                      <table class="table table-striped">
                        <tbody>
                          <tr>
                            <td width="50%">Kode Data</td>
                            <td><?php echo isset($data['kode_data']) ? $data['kode_data'] : '-'; ?></td>
                          </tr>
                          <tr>
                            <td>Pilihan Paket Wisata</td>
                            <td><?php echo isset($data['pilihan_paket_wisata']) ? $data['pilihan_paket_wisata'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Jenis Wisatawan</td>
                            <td><?php echo isset($data['jenis_wisatawan']) ? $data['jenis_wisatawan'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Kota</td>
                            <td><?php echo isset($data['kota']) ? $data['kota'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Negara</td>
                            <td><?php echo isset($data['negara']) ? $data['negara'] : '-'; ?></td>
                          </tr>
                          <tr>
                            <td>Nama</td>
                            <td><?php echo isset($data['nama']) ? $data['nama'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Pax (Jumlah Wisatawan)</td>
                            <td><?php echo isset($data['pax']) ? $data['pax'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Agen Wisata</td>
                            <td><?php echo isset($data['agen_wisata']) ? $data['agen_wisata'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Driver/Agent Guide</td>
                            <td><?php echo isset($data['driver_agent_guide']) ? $data['driver_agent_guide'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            <td>Local Guide</td>
                            <td><?php echo isset($data['local_guide']) ? $data['local_guide'] : 'N/A'; ?></td>
                          </tr>
                          <tr>
                            
                          </tr>
                        </tbody>
                      </table>
                      <div class="text-right">
                        <a href="datapengunjung.php" class="btn btn-success"><span
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
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
</body>

</html>