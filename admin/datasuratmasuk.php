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
                  <h2>Data Surat Masuk</h2>
                  <div class="clearfix"></div>
                </div>
                <form action="downloadlaporan_suratmasuk.php" name="download_suratmasuk" method="post"
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
                  <a href="export/export_surat_masuk.php" class=" btn btn-danger"><i class="fa fa-download"></i> Unduh
                    Laporan PDF</a>
                  <a href="export/exportExcel_surat_masuk.php" class=" btn btn-success"><i class="fa fa-download"></i>
                    Unduh
                    Laporan Excel</a>
                  <a href="inputsuratmasuk.php"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i>
                      Tambah Surat Masuk</button></a>
                </form>
                <div class="x_content">
                  <div class="x_content">
                    <?php
                    include '../koneksi/koneksi.php';
                    $sql1      = "SELECT * FROM tb_arsip_surat_masuk order by	nomor_surat asc";
                    $query1    = mysqli_query($db, $sql1);
                    $total    = mysqli_num_rows($query1);
                    if ($total == 0) {
                      echo "<center><h2>Belum Ada Data Surat Masuk</h2></center>";
                    } else { ?>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="8%">Nomor Surat</th>
                          <th width="8%">Tanggal Terima</th>
                          <th width="8%">Tanggal Surat</th>
                          <th width="10%">Pengirim</th>
                          <th width="10%">Penerima</th>
                          <th width="10%">Disposisi</th>
                          <th width="12%">Perihal</th>
                          <th width="8%">Kode</th>
                          <th width="10%">Keterangan</th>
                          <th width="3%">Aksi</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          $query1 = mysqli_query($db, "SELECT * FROM tb_arsip_surat_masuk");
                          while ($data = mysqli_fetch_array($query1)) {
                            echo '<tr>
                                <td>' . htmlspecialchars($data['No']) . '</td>
                                <td>' . htmlspecialchars($data['nomor_surat']) . '</td>
                                <td>' . htmlspecialchars($data['tanggal_terima']) . '</td>
                                <td>' . htmlspecialchars($data['tanggal_surat']) . '</td>
                                <td>' . htmlspecialchars($data['pengirim']) . '</td>
                                <td>' . htmlspecialchars($data['penerima_surat']) . '</td>
                                <td>' . htmlspecialchars($data['disposisi']) . '</td>
                                <td>' . htmlspecialchars($data['perihal']) . '</td>
                                <td>' . htmlspecialchars($data['kode']) . '</td>
                                <td>' . htmlspecialchars($data['keterangan']) . '</td>
                                <td style="text-align:center; white-space: nowrap;">
                                <a href="surat_masuk/' . htmlspecialchars($data['file_surat']) . '"><button type="button" title="Unduh File" class="btn btn-success btn-xs"><i class="fa fa-download"></i></button></a><br>
                                ' . ($data['lampiran_foto'] ? '<a href="surat_masuk/' . htmlspecialchars($data['lampiran_foto']) . '"><button type="button" title="Lihat Foto" class="btn btn-info btn-xs"><i class="fa fa-image"></i></button></a><br>' : '') . '
                                <a href="detail-suratmasuk.php?id=' . $data['No'] . '"><button type="button" title="Detail" class="btn btn-info btn-xs"><i class="fa fa-file-image-o"></i></button></a><br>
                                <a href="editsuratmasuk.php?id=' . $data['No'] . '"><button type="button" title="Edit" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button></a><br>
                                <a onclick="return konfirmasi()" href="proses/proses_hapussuratmasuk.php?id=' . $data['No'] . '"><button type="button" title="Hapus" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></a>
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
  <script type="text/javascript" language="JavaScript">
  function konfirmasi() {
    tanya = confirm("Anda Yakin Akan Menghapus Data ?");
    if (tanya == true) return true;
    else return false;
  }
  </script>

</body>

</html>