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

  <title>Data Pengurus Desa Candirejo Borobudur</title>

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
  <!-- Datatables -->
  <link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
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
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data Pengurus</h2>
                  <div class="clearfix"></div>
                </div>
                <form action="downloadlaporan_pengurus.php" name="download_pengurus" method="post"
                  enctype="multipart/form-data" id="demo-form2" data-parsley-validate
                  class="form-horizontal form-label-left">
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <select name="bulan" class="select2_single form-control" tabindex="-1">
                      <option>Pilih Bulan</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <select name="tahun" class="select2_single form-control" tabindex="-1">
                      <option>Pilih Tahun</option>
                      <?php
                      for ($tahun = 2024; $tahun <= 2030; $tahun++) {
                        echo  '<option value="' . $tahun . '">' . $tahun . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <a href="export/export_data_pengurus.php" class="btn btn-danger"><i class="fa fa-download"></i> Unduh
                    Laporan
                    PDF</a>
                  <a href="export/exportExcel_data_pengurus.php" class="btn btn-success"><i class="fa fa-download"></i>
                    Unduh
                    Laporan
                    Excel</a>
                  <a href="inputdatapengurus.php"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                      Tambah Pengurus</button></a>
                </form>
                <div class="x_content">
                  <?php
                  include '../koneksi/koneksi.php';
                  $sql1      = "SELECT * FROM tb_data_pengurus ORDER BY id ASC";
                  $query1    = mysqli_query($db, $sql1);
                  $total    = mysqli_num_rows($query1);
                  if ($total == 0) {
                    echo "<center><h2>Belum Ada Data Pengurus</h2></center>";
                  } else { ?>
                  <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th width="15%">Nama</th>
                        <th width="12%">No. KTP</th>
                        <th width="12%">Jabatan</th>
                        <th width="10%">Periode</th>
                        <th width="15%">Alamat</th>
                        <th width="10%">No. Telp</th>
                        <th width="10%">Foto KTP</th>
                        <th width="10%">Pas Foto</th>
                        <th width="3%">Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        while ($data = mysqli_fetch_array($query1)) {
                          echo '<tr>
                          <td>' . htmlspecialchars($data['nama']) . '</td>
                          <td>' . htmlspecialchars($data['no_ktp']) . '</td>
                          <td>' . htmlspecialchars($data['jabatan']) . '</td>
                          <td>' . htmlspecialchars($data['periode']) . '</td>
                          <td>' . htmlspecialchars($data['alamat']) . '</td>
                          <td>' . htmlspecialchars($data['no_telp']) . '</td>
                          <td>' . (!empty($data['foto_ktp']) ? '<img src="../admin/uploads/pengurus/' . htmlspecialchars($data['foto_ktp']) . '" style="max-width: 100px;">' : '-') . '</td>
                          <td>' . (!empty($data['pas_foto']) ? '<img src="../admin/uploads/pengurus/' . htmlspecialchars($data['pas_foto']) . '" style="max-width: 100px;">' : '-') . '</td>
                          <td>
                            <a href="detail-pengurus.php?id=' . $data['id'] . '"><button type="button" title="Detail"
                                class="btn btn-info btn-xs"><i class="fa fa-file-image-o"></i></button></a>
                            <a href="editpengurus.php?id=' . $data['id'] . '"><button type="button" title="Edit"
                                class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button></a>
                            <a onclick="return konfirmasi()"
                              href="proses/proses_hapuspengurus.php?id=' . $data['id'] . '"><button type="button" title="Hapus"
                                class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></a>
                          </td>
                          </tr>';
                        }
                      ?>
                    </tbody>
                  </table>

                  <?php } ?>
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
          Supported by DRTPM
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
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- Datatables -->
  <script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="../assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="../assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="../assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
  <script src="../assets/vendors/jszip/dist/jszip.min.js"></script>
  <script src="../assets/vendors/pdfmake/build/pdfmake.min.js"></script>
  <script src="../assets/vendors/pdfmake/build/vfs_fonts.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
  <script>
    function konfirmasi() {
      return confirm("Apakah Anda yakin ingin menghapus data ini?");
    }
  </script>

</body>

</html> 