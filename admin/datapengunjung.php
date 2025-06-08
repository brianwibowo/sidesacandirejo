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

  <title>Data Pengunjung Wisata Desa Candirejo Borobudur</title>

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
      <!-- Profile and Sidebar menu -->
      <?php
      include("sidebarmenu.php");
      ?>
      <!-- /Profile and Sidebar menu -->

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
                  <h2>Data Pengunjung Wisata</h2>
                  <div class="clearfix"></div>
                </div>
                <form action="downloadlaporan_pengunjung.php" name="download_pengunjung" method="post"
                  enctype="multipart/form-data" class="form-horizontal form-label-left">
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
                  <a href="export/export_data_pengunjung.php" class="btn btn-danger"><i class="fa fa-download"></i>
                    Unduh Laporan
                    PDF</></a>
                  <a href="export/exportExcel_data_pengunjung.php" class="btn btn-success"><i
                      class="fa fa-download"></i>
                    Unduh Laporan
                    Excel</></a>
                  <a href="inputdatapengunjung.php"><button type="button" class="btn btn-primary"><i
                        class="fa fa-plus"></i> Tambah Pengunjung</button></a>
                </form>
                <div class="x_content">
                  <div class="x_content">
                    <?php
                    include '../koneksi/koneksi.php';
                    $sql1      = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
                    $query1    = mysqli_query($db, $sql1);
                    $total    = mysqli_num_rows($query1);
                    if ($total == 0) {
                      echo "<center><h2>Belum Ada Data Pengunjung</h2></center>";
                    } else { ?>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Tanggal Kunjungan</th>
                          <th>Pilihan Paket Wisata</th>
                          <th>Detail Paket</th>
                          <th>Jenis Wisatawan</th>
                          <th>Kota/Negara</th>
                          <th>Nama</th>
                          <th>Pax</th>
                          <th>Agen Wisata</th>
                          <th>Driver/Agent Guide</th>
                          <th>Local Guide</th>
                          <th>Foto</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          while ($data = mysqli_fetch_array($query1)) {
                            // Format nama paket wisata untuk ditampilkan
                            $paket_display = '';
                            switch($data['pilihan_paket_wisata']) {
                              case 'meal_only':
                                $paket_display = 'Breakfast/Lunch/Dinner Only';
                                break;
                              case 'studi_banding':
                                $paket_display = 'Studi Banding';
                                break;
                              case 'fun_game':
                                $paket_display = 'Paket Fun Game';
                                break;
                              case 'pelajar_live_in':
                                $paket_display = 'Paket Pelajar - Live In Candirejo';
                                break;
                              case 'pelajar_field_trip_one_day':
                                $paket_display = 'Paket Pelajar – Field Trip One Day';
                                break;
                              case 'pelajar_field_trip_half_day':
                                $paket_display = 'Paket Pelajar – Field Trip Half Day';
                                break;
                              case 'cycling_tour':
                                $paket_display = 'Cycling Village Tour Candirejo';
                                break;
                              case 'traditional_dance':
                                $paket_display = 'Traditional Dance';
                                break;
                              case 'walking_tour':
                                $paket_display = 'Walking Around Village';
                                break;
                              case 'homestay':
                                $paket_display = 'Stay At Local House In Candirejo Village (Homestay)';
                                break;
                              case 'serenade':
                                $paket_display = 'Serenade At The Foot Of Menoreh Hill';
                                break;
                              case 'cooking_lesson':
                                $paket_display = 'Cooking Lesson';
                                break;
                              case 'village_experience':
                                $paket_display = 'Village Experience';
                                break;
                              case 'dokar_tour':
                                $paket_display = 'Dokar Village Tour Candirejo';
                                break;
                              default:
                                $paket_display = htmlspecialchars($data['pilihan_paket_wisata']);
                            }

                            // Format detail paket berdasarkan opsi tambahan yang sesuai dengan paket utama
                            $detail_paket = '';
                            $paket_utama = $data['pilihan_paket_wisata'];
                            
                            // Untuk paket cycling_tour, dokar_tour, walking_tour - tampilkan opsi makan
                            if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($data['opsi_makan_tour'])) {
                              $detail_paket = ($data['opsi_makan_tour'] == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                            }
                            // Untuk paket meal_only - tampilkan jenis makanan
                            elseif ($paket_utama == 'meal_only' && !empty($data['jenis_makanan_paket'])) {
                              $makanan_map = [
                                'breakfast' => 'Breakfast',
                                'lunch' => 'Lunch', 
                                'dinner' => 'Dinner'
                              ];
                              $detail_paket = $makanan_map[$data['jenis_makanan_paket']] ?? $data['jenis_makanan_paket'];
                            }
                            // Untuk paket cooking_lesson - tampilkan opsi cooking
                            elseif ($paket_utama == 'cooking_lesson' && !empty($data['opsi_cooking_lesson'])) {
                              $detail_paket = ($data['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'Cooking Lesson + Tour' : 'Cooking Lesson Saja';
                            }
                            
                            if (empty($detail_paket)) {
                              $detail_paket = '-';
                            }

                            $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];
                            
                            echo '<tr>
                                <td>' . htmlspecialchars($data['tanggal_kunjungan']) . '</td>
                                <td>' . $paket_display . '</td>
                                <td>' . $detail_paket . '</td>
                                <td>' . htmlspecialchars($data['jenis_wisatawan']) . '</td>
                                <td>' . htmlspecialchars($lokasi) . '</td>
                                <td>' . htmlspecialchars($data['nama']) . '</td>
                                <td>' . htmlspecialchars($data['pax']) . ' orang</td>
                                <td>' . htmlspecialchars($data['agen_wisata'] ?? '-') . '</td>
                                <td>' . htmlspecialchars($data['driver_agent_guide']) . '</td>
                                <td>' . htmlspecialchars($data['local_guide']) . '</td>
                                <td>' . (!empty($data['foto']) ? '<img src="../admin/uploads/pengunjung/' . htmlspecialchars($data['foto']) . '" style="max-width:60px;max-height:60px;">' : '-') . '</td>
                                <td style="text-align:center;">
                                    <a href="detail-datapengunjung.php?id=' . urlencode($data['id']) . '"><button type="button" title="Detail" class="btn btn-info btn-xs"><i class="fa fa-file-image-o"></i></button></a><br>
                                    <a href="editpengunjung.php?id=' . urlencode($data['id'])  . '"><button type="button" title="Edit" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button></a><br>
                                    <a onclick="return konfirmasi()" href="proses/proses_hapusdatapengunjung.php?id=' . $data['id'] . '"><button type="button" title="Hapus" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button></a>
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
    return confirm("Apakah Anda yakin akan menghapus data ini?");
  }
  </script>
</body>

</html>