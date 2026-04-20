<?php
session_start();
include "login/ceksession.php";
?>
<!DOCTYPE html>

<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* ── Wrapper scroll horizontal ──────────────────────────────────── */
    .table-responsive-custom {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      position: relative;
    }

    /* ── Sticky kolom Aksi (kolom terakhir) ─────────────────────────── */
    #datatable thead tr th:last-child,
    #datatable tbody tr td:last-child {
      position: sticky;
      right: 0;
      z-index: 2;
      background-color: #fff;
      box-shadow: -3px 0 6px -2px rgba(0, 0, 0, 0.15);
      white-space: nowrap;
    }

    /* Zebra stripe tetap jalan pada sticky cell */
    #datatable tbody tr.odd  td:last-child { background-color: #f9f9f9; }
    #datatable tbody tr.even td:last-child { background-color: #ffffff; }

    /* Hover row */
    #datatable tbody tr:hover td:last-child { background-color: #f0faf8; }

    /* Header sticky */
    #datatable thead tr th:last-child {
      background-color: #f2f2f2;
      z-index: 3;
    }

    /* ── Thumbnail dokumentasi ──────────────────────────────────────── */
    .foto-thumb {
      display: inline-block;
      margin: 2px;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
      cursor: pointer;
    }
    .foto-thumb img {
      width: 48px;
      height: 48px;
      object-fit: cover;
      display: block;
      transition: opacity .2s;
    }
    .foto-thumb img:hover { opacity: .75; }

    /* Badge jumlah foto jika lebih dari 3 */
    .foto-more {
      display: inline-block;
      width: 48px;
      height: 48px;
      line-height: 48px;
      text-align: center;
      background: #31708f;
      color: #fff;
      font-size: 12px;
      font-weight: bold;
      border-radius: 4px;
      vertical-align: top;
      margin: 2px;
      cursor: default;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data Surat Keluar</h2>
                  <div class="clearfix"></div>
                </div>

                <form action="datasuratkeluar.php" method="get" style="margin-bottom:15px;">
                  <div class="row" style="display:flex; align-items:center; flex-wrap:wrap; gap:6px; padding:0 15px;">
                    <div style="width:160px;">
                      <select name="bulan" class="select2_single form-control" tabindex="-1">
                        <option value="">Pilih Bulan</option>
                        <?php
                        $bulan_list_sk = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                        foreach ($bulan_list_sk as $val => $nama) {
                          $sel = (isset($_GET['bulan']) && $_GET['bulan'] == $val) ? 'selected' : '';
                          echo '<option value="'.$val.'" '.$sel.'>'.$nama.'</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div style="width:110px;">
                      <select name="tahun" class="select2_single form-control" tabindex="-1">
                        <option value="">Pilih Tahun</option>
                        <?php
                        include '../koneksi/koneksi.php';
                        $row_tmin = mysqli_fetch_assoc(mysqli_query($db, "SELECT YEAR(MIN(tanggal_keluar)) as tmin FROM tb_arsip_surat_keluar"));
                        $tmin_sk = !empty($row_tmin['tmin']) ? (int)$row_tmin['tmin'] : (int)date('Y');
                        for ($t = $tmin_sk; $t <= (int)date('Y') + 1; $t++) {
                          $sel = (isset($_GET['tahun']) && $_GET['tahun'] == $t) ? 'selected' : '';
                          echo '<option value="'.$t.'" '.$sel.'>'.$t.'</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-search"></i> Filter</button>
                    <a href="datasuratkeluar.php" class="btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Reset</a>
                    <div style="flex:1;"></div>
                    <a href="export/export_surat_keluar.php" class="btn btn-danger btn-sm"><i class="fa fa-download"></i> Unduh PDF</a>
                    <a href="export/exportExcel_surat_keluar.php" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Unduh Excel</a>
                    <a href="inputsuratkeluar.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Surat Keluar</a>
                  </div>
                </form>

                <div class="x_content">
                  <div class="x_content">
                    <?php
                    $where_sk = "WHERE 1=1";
                    if (!empty($_GET['bulan'])) {
                      $fb_sk = mysqli_real_escape_string($db, $_GET['bulan']);
                      $where_sk .= " AND MONTH(tanggal_keluar) = '$fb_sk'";
                    }
                    if (!empty($_GET['tahun'])) {
                      $ft_sk = mysqli_real_escape_string($db, $_GET['tahun']);
                      $where_sk .= " AND YEAR(tanggal_keluar) = '$ft_sk'";
                    }
                    $sql1   = "SELECT * FROM tb_arsip_surat_keluar $where_sk ORDER BY nomor_surat ASC";
                    $query1 = mysqli_query($db, $sql1);
                    $total  = mysqli_num_rows($query1);
                    if ($total == 0) {
                      echo "<center><h2>Belum Ada Data Surat Keluar</h2></center>";
                    } else { ?>
                    <div class="table-responsive-custom">
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th width="12%">Nomor Surat</th>
                          <th width="8%">Tanggal Keluar</th>
                          <th width="10%">Penerima</th>
                          <th width="8%">Jenis Surat</th>
                          <th width="10%">Perihal</th>
                          <th width="10%">Tempat Acara</th>
                          <th width="8%">Tanggal Kegiatan</th>
                          <th width="7%">Jam Kegiatan</th>
                          <th width="7%">Absensi</th>
                          <th width="7%">Notulen</th>
                          <th width="8%">Dokumentasi</th>
                          <th width="10%">Keterangan</th>
                          <th width="5%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        while ($data = mysqli_fetch_array($query1)) {

                          $absensi_files    = json_decode($data['lampiran_absensi']  ?? '[]', true);
                          if (!is_array($absensi_files))    $absensi_files    = [];
                          $notulen_files    = json_decode($data['lampiran_notulen']  ?? '[]', true);
                          if (!is_array($notulen_files))    $notulen_files    = [];
                          $dokumentasi_files = json_decode($data['dokumentasi_foto'] ?? '[]', true);
                          if (!is_array($dokumentasi_files)) $dokumentasi_files = [];

                          // ── Absensi: tombol download ────────────────────
                          $absensi_cell = '-';
                          if (count($absensi_files) > 0) {
                            $absensi_cell = '';
                            foreach ($absensi_files as $idx => $file_name) {
                              $safe_name = htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8');
                              $file_url  = 'uploads/' . rawurlencode($file_name);
                              $absensi_cell .= '<a href="'.$file_url.'" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="'.$safe_name.'"><i class="fa fa-download"></i> File '.($idx+1).'</a>';
                            }
                          }

                          // ── Notulen: tombol download ────────────────────
                          $notulen_cell = '-';
                          if (count($notulen_files) > 0) {
                            $notulen_cell = '';
                            foreach ($notulen_files as $idx => $file_name) {
                              $safe_name = htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8');
                              $file_url  = 'uploads/' . rawurlencode($file_name);
                              $notulen_cell .= '<a href="'.$file_url.'" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="'.$safe_name.'"><i class="fa fa-download"></i> File '.($idx+1).'</a>';
                            }
                          }

                          // ── Dokumentasi: tampil thumbnail foto ─────────
                          // Klik thumbnail → buka foto penuh di tab baru
                          // Jika > 3 foto → tampilkan 3 + badge "+N lagi"
                          $dokumentasi_cell = '-';
                          if (count($dokumentasi_files) > 0) {
                            $dokumentasi_cell = '';
                            $max_thumb = 3;
                            $total_dok = count($dokumentasi_files);
                            foreach ($dokumentasi_files as $idx => $file_name) {
                              if ($idx >= $max_thumb) break;
                              $safe_name = htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8');
                              $file_url  = 'uploads/' . rawurlencode($file_name);
                              $dokumentasi_cell .= '<a href="'.$file_url.'" target="_blank" class="foto-thumb" title="'.$safe_name.'">'
                                                 . '<img src="'.$file_url.'" alt="'.$safe_name.'" loading="lazy">'
                                                 . '</a>';
                            }
                            if ($total_dok > $max_thumb) {
                              $sisa = $total_dok - $max_thumb;
                              $dokumentasi_cell .= '<span class="foto-more">+'.$sisa.'</span>';
                            }
                          }

                          echo '<tr>
                            <td>'.htmlspecialchars($data['No']).'</td>
                            <td>'.htmlspecialchars($data['nomor_surat']).'</td>
                            <td>'.htmlspecialchars($data['tanggal_keluar']).'</td>
                            <td>'.htmlspecialchars($data['penerima']).'</td>
                            <td>'.htmlspecialchars(ucfirst($data['jenis_surat'] ?? 'keterangan')).'</td>
                            <td>'.htmlspecialchars($data['perihal']).'</td>
                            <td>'.(!empty($data['tempat_acara']) ? htmlspecialchars($data['tempat_acara']) : '-').'</td>
                            <td>'.(!empty($data['tanggal_kegiatan']) ? htmlspecialchars($data['tanggal_kegiatan']) : '-').'</td>
                            <td>'.(!empty($data['jam_kegiatan']) ? htmlspecialchars(substr($data['jam_kegiatan'], 0, 5)) : '-').'</td>
                            <td>'.$absensi_cell.'</td>
                            <td>'.$notulen_cell.'</td>
                            <td>'.$dokumentasi_cell.'</td>
                            <td>'.htmlspecialchars($data['keterangan']).'</td>
                            <td style="text-align:center; white-space:nowrap;">
                              <a href="uploads/'.htmlspecialchars(basename($data['file_surat'])).'" class="btn btn-success btn-xs" title="Unduh File"><i class="fa fa-download"></i></a><br>
                              <a href="detail-suratkeluar.php?id='.htmlspecialchars($data['No']).'" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-file-image-o"></i></a><br>
                              <a href="editsuratkeluar.php?id='.htmlspecialchars($data['No']).'" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a><br>
                              <button type="button" title="Hapus" class="btn btn-danger btn-xs"
                                onclick="konfirmasiHapus('.htmlspecialchars($data['No']).')">
                                <i class="fa fa-trash-o"></i>
                              </button>
                            </td>
                          </tr>';
                        }
                        ?>
                      </tbody>
                    </table>
                    </div><!-- /.table-responsive-custom -->
                    <?php } ?>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <footer>
        <div class="pull-right">Supported by DRTPM</div>
        <div class="clearfix"></div>
      </footer>
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
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
    // ── Konfirmasi hapus dengan SweetAlert ────────────────────────────
    function konfirmasiHapus(id) {
      Swal.fire({
        icon: 'warning',
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: '<i class="fa fa-trash-o"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then(function(result) {
        if (result.isConfirmed) {
          window.location = 'proses/proses_hapussuratkeluar.php?id=' + id;
        }
      });
    }

    // ── Tampilkan SweetAlert dari redirect status ──────────────────────
    $(document).ready(function() {

      <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'deleted'): ?>
        Swal.fire({
          icon: 'success',
          title: 'Dihapus!',
          text: 'Data berhasil dihapus.',
          confirmButtonColor: '#26B99A',
          confirmButtonText: 'OK',
          timer: 3000,
          timerProgressBar: true
        });
        <?php elseif ($_GET['status'] === 'success'): ?>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Operasi berhasil dilakukan.',
          confirmButtonColor: '#26B99A',
          confirmButtonText: 'OK',
          timer: 3000,
          timerProgressBar: true
        });
        <?php elseif ($_GET['status'] === 'error'): ?>
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: '<?php echo htmlspecialchars($_GET["msg"] ?? "Terjadi kesalahan."); ?>',
          confirmButtonColor: '#e74c3c',
          confirmButtonText: 'Tutup'
        });
        <?php endif; ?>
      <?php endif; ?>

    }); // ← TUTUP $(document).ready
  </script>

</body>
</html>