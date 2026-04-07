<?php
session_start();
include "login/ceksession.php";
?>

<!DOCTYPE html>

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

  <style>
    /* ===== CARD TOTAL KUNJUNGAN ===== */
    .card-total-kunjungan {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 14px 18px;
      color: #fff;
      box-shadow: 0 4px 18px rgba(102, 126, 234, 0.30);
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 16px;
      position: relative;
      overflow: hidden;
    }
    .card-total-kunjungan::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 110px;
      height: 110px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
    }
    .card-total-kunjungan::after {
      content: '';
      position: absolute;
      bottom: -40px;
      right: 40px;
      width: 150px;
      height: 150px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }
    .card-total-kunjungan .icon-wrap {
      background: rgba(255,255,255,0.18);
      border-radius: 50%;
      width: 46px;
      height: 46px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 20px;
      color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .card-total-kunjungan .info-wrap .label-text {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      opacity: 0.88;
      margin-bottom: 2px;
      text-transform: uppercase;
    }
    .card-total-kunjungan .info-wrap .count-number {
      font-size: 28px;
      font-weight: 700;
      line-height: 1;
      letter-spacing: -0.5px;
    }
    .card-total-kunjungan .info-wrap .count-number span {
      font-size: 13px;
      font-weight: 400;
      opacity: 0.85;
      margin-left: 4px;
    }
    .card-total-kunjungan .info-wrap .sub-text {
      font-size: 10px;
      opacity: 0.75;
      margin-top: 3px;
    }

    /* ===== TOGGLE SWITCH ===== */
    .toggle-mode-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 6px;
    }
    .toggle-label {
      font-size: 10px;
      opacity: 0.85;
      font-weight: 500;
    }
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 38px;
      height: 20px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(255,255,255,0.3);
      border-radius: 20px;
      transition: 0.3s;
    }
    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 14px; width: 14px;
      left: 3px; bottom: 3px;
      background-color: white;
      border-radius: 50%;
      transition: 0.3s;
    }
    .toggle-switch input:checked + .toggle-slider {
      background-color: rgba(255,255,255,0.55);
    }
    .toggle-switch input:checked + .toggle-slider:before {
      transform: translateX(18px);
    }

    /* ===== BADGE JENIS WISATAWAN ===== */
    .badge-jenis {
      display: inline-block;
      padding: 1px 7px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.2px;
      white-space: nowrap;
      line-height: 1.4;
    }
    .badge-mancanegara {
      background-color: #27ae60;
      color: #fff;
    }
    .badge-domestik {
      background-color: #2980b9;
      color: #fff;
    }
    .badge-pelajar {
      background-color: #e67e22;
      color: #fff;
    }
    .badge-lainnya {
      background-color: #95a5a6;
      color: #fff;
    }

    /* ===== BADGE PAX ===== */
    .badge-pax {
      display: inline-block;
      padding: 1px 8px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      background-color: #555;
      color: #fff;
      white-space: nowrap;
      line-height: 1.4;
    }

    /* ===== FILTER PANEL ===== */
    .filter-panel {
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 10px;
      padding: 16px 20px;
      margin-bottom: 20px;
    }
    .filter-panel .filter-title {
      font-size: 14px;
      font-weight: 600;
      color: #555;
      margin-bottom: 12px;
    }
    .filter-panel .filter-title i {
      margin-right: 6px;
      color: #667eea;
    }
  </style>
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
                  <h2><i class="fa fa-users"></i> Data Pengunjung Wisata</h2>
                  <div class="clearfix"></div>
                </div>

                <?php
                include '../koneksi/koneksi.php';

                // Hitung total keseluruhan PAX (bukan jumlah row)
                $sql_total = "SELECT SUM(pax) as total_pax FROM tb_data_pengunjung";
                $query_total = mysqli_query($db, $sql_total);
                $row_total = mysqli_fetch_assoc($query_total);
                $total_pax = $row_total['total_pax'] ?? 0;

                // Hitung juga jumlah kunjungan (row)
                $sql_kunjungan = "SELECT COUNT(*) as total_kunjungan FROM tb_data_pengunjung";
                $query_kunjungan = mysqli_query($db, $sql_kunjungan);
                $row_kunjungan = mysqli_fetch_assoc($query_kunjungan);
                $total_kunjungan = $row_kunjungan['total_kunjungan'] ?? 0;
                ?>

                <!-- CARD TOTAL KUNJUNGAN -->
                <div class="row" style="padding: 0 15px 10px 15px;">
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card-total-kunjungan">
                      <div class="icon-wrap">
                        <i class="fa fa-globe" id="card-icon"></i>
                      </div>
                      <div class="info-wrap">
                        <div class="label-text" id="card-label">Total Kunjungan Keseluruhan</div>
                        <div class="count-number" id="card-count">
                          <?php echo number_format($total_pax, 0, ',', '.'); ?>
                          <span id="card-unit">orang</span>
                        </div>
                        <div class="sub-text" id="card-sub">
                          <i class="fa fa-users"></i> Jumlah total pax seluruh kunjungan
                        </div>
                        <!-- TOGGLE MODE -->
                        <div class="toggle-mode-wrap">
                          <span class="toggle-label" id="lbl-pax" style="font-weight:700;">Pax</span>
                          <label class="toggle-switch">
                            <input type="checkbox" id="toggleMode" onchange="switchTotalMode(this)">
                            <span class="toggle-slider"></span>
                          </label>
                          <span class="toggle-label" id="lbl-sesi">Sesi</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                var totalPax = <?php echo (int)$total_pax; ?>;
                var totalSesi = <?php echo (int)$total_kunjungan; ?>;
                function switchTotalMode(chk) {
                  var isSesi = chk.checked;
                  document.getElementById('card-icon').className = isSesi ? 'fa fa-calendar' : 'fa fa-globe';
                  document.getElementById('card-label').textContent = isSesi ? 'Total Sesi Kunjungan' : 'Total Kunjungan Keseluruhan';
                  document.getElementById('card-count').childNodes[0].nodeValue = (isSesi ? totalSesi : totalPax).toLocaleString('id-ID') + ' ';
                  document.getElementById('card-unit').textContent = isSesi ? 'sesi' : 'orang';
                  document.getElementById('card-sub').innerHTML = isSesi
                    ? '<i class="fa fa-info-circle"></i> Jumlah kunjungan tanpa dihitung pax'
                    : '<i class="fa fa-users"></i> Jumlah total pax seluruh kunjungan';
                  document.getElementById('lbl-pax').style.fontWeight = isSesi ? '400' : '700';
                  document.getElementById('lbl-sesi').style.fontWeight = isSesi ? '700' : '400';
                }
                </script>

                <!-- FILTER PANEL -->
                <div class="filter-panel" style="margin: 0 15px 15px 15px;">
                  <div class="filter-title"><i class="fa fa-filter"></i> Filter Data</div>
                  <form action="downloadlaporan_pengunjung.php" name="download_pengunjung" method="post"
                    enctype="multipart/form-data" class="form-inline">
                    <div class="form-group" style="margin-right:10px;">
                      <label style="margin-right:6px; font-size:13px;">Bulan:</label>
                      <select name="bulan" class="form-control input-sm">
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
                    <div class="form-group" style="margin-right:10px;">
                      <label style="margin-right:6px; font-size:13px;">Tahun:</label>
                      <select name="tahun" class="form-control input-sm">
                        <option>Pilih Tahun</option>
                        <?php
                        for ($tahun = 2024; $tahun <= 2030; $tahun++) {
                          echo '<option value="' . $tahun . '">' . $tahun . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-right:6px;">
                      <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="datapengunjung.php" class="btn btn-warning btn-sm">
                      <i class="fa fa-refresh"></i> Reset Filter
                    </a>
                  </form>
                </div>

                <!-- TOMBOL AKSI -->
                <div style="padding: 0 15px 15px 15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                  <a href="inputdatapengunjung.php">
                    <button type="button" class="btn btn-primary">
                      <i class="fa fa-plus"></i> Tambah Pengunjung
                    </button>
                  </a>
                  <div>
                    <a href="export/export_data_pengunjung.php" class="btn btn-danger" style="margin-right:6px;">
                      <i class="fa fa-file-pdf-o"></i> Unduh PDF
                    </a>
                    <a href="export/exportExcel_data_pengunjung.php" class="btn btn-success">
                      <i class="fa fa-file-excel-o"></i> Unduh Excel
                    </a>
                  </div>
                </div>

                <div class="x_content">
                  <?php
                  $sql1   = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
                  $query1 = mysqli_query($db, $sql1);
                  $total  = mysqli_num_rows($query1);
                  if ($total == 0) {
                    echo "<center><h2>Belum Ada Data Pengunjung</h2></center>";
                  } else { ?>
                  <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Paket Wisata</th>
                        <th>Detail</th>
                        <th>Jenis</th>
                        <th>Kota/Negara</th>
                        <th>Nama</th>
                        <th>Pax</th>
                        <th>Agen Wisata</th>
                        <th>Driver/Guide</th>
                        <th>Local Guide</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        while ($data = mysqli_fetch_array($query1)) {
                          // Format nama paket wisata
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

                          // Format detail paket
                          $detail_paket = '';
                          $paket_utama = $data['pilihan_paket_wisata'];

                          if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($data['opsi_makan_tour'])) {
                            $detail_paket = ($data['opsi_makan_tour'] == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                          } elseif ($paket_utama == 'meal_only' && !empty($data['jenis_makanan_paket'])) {
                            $makanan_map = [
                              'breakfast' => 'Breakfast',
                              'lunch'     => 'Lunch',
                              'dinner'    => 'Dinner'
                            ];
                            $detail_paket = $makanan_map[$data['jenis_makanan_paket']] ?? $data['jenis_makanan_paket'];
                          } elseif ($paket_utama == 'cooking_lesson' && !empty($data['opsi_cooking_lesson'])) {
                            $detail_paket = ($data['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'Cooking Lesson + Tour' : 'Cooking Lesson Saja';
                          }

                          if (empty($detail_paket)) {
                            $detail_paket = '-';
                          }

                          $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];

                          // Badge Jenis Wisatawan
                          $jenis = htmlspecialchars($data['jenis_wisatawan']);
                          $jenis_lower = strtolower($jenis);
                          if (strpos($jenis_lower, 'mancanegara') !== false) {
                            $badge_class = 'badge-mancanegara';
                          } elseif (strpos($jenis_lower, 'domestik') !== false) {
                            $badge_class = 'badge-domestik';
                          } elseif (strpos($jenis_lower, 'pelajar') !== false) {
                            $badge_class = 'badge-pelajar';
                          } else {
                            $badge_class = 'badge-lainnya';
                          }
                          $jenis_badge = '<span class="badge-jenis ' . $badge_class . '">' . $jenis . '</span>';

                          // Badge Pax
                          $pax_val = htmlspecialchars($data['pax']);
                          $pax_badge = '<span class="badge-pax">' . $pax_val . ' orang</span>';

                          echo '<tr>
                              <td>' . htmlspecialchars($data['tanggal_kunjungan']) . '</td>
                              <td>' . $paket_display . '</td>
                              <td>' . $detail_paket . '</td>
                              <td>' . $jenis_badge . '</td>
                              <td>' . htmlspecialchars($lokasi) . '</td>
                              <td>' . htmlspecialchars($data['nama']) . '</td>
                              <td>' . $pax_badge . '</td>
                              <td>' . htmlspecialchars($data['agen_wisata'] ?? '-') . '</td>
                              <td>' . htmlspecialchars($data['driver_agent_guide']) . '</td>
                              <td>' . htmlspecialchars($data['local_guide']) . '</td>
                              <td>' . (!empty($data['foto']) ? '<img src="../admin/uploads/pengunjung/' . htmlspecialchars($data['foto']) . '" style="max-width:60px;max-height:60px;border-radius:6px;">' : '-') . '</td>
                              <td style="text-align:center;">
                                  <a href="detail-datapengunjung.php?id=' . urlencode($data['id']) . '"><button type="button" title="Detail" class="btn btn-info btn-xs"><i class="fa fa-file-image-o"></i></button></a><br>
                                  <a href="editpengunjung.php?id=' . urlencode($data['id']) . '"><button type="button" title="Edit" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button></a><br>
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