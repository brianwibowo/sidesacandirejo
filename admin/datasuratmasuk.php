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
    /* ── Wrapper scroll horizontal ─────────────────────────────── */
    .table-responsive-custom {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      position: relative;
    }

    /* ── Sticky kolom Aksi (kolom terakhir) ─────────────────────── */
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
    #datatable tbody tr.odd td:last-child  { background-color: #f9f9f9; }
    #datatable tbody tr.even td:last-child { background-color: #ffffff; }

    /* Hover row */
    #datatable tbody tr:hover td:last-child { background-color: #f0faf8; }

    /* Header sticky */
    #datatable thead tr th:last-child {
      background-color: #f2f2f2;
      z-index: 3;
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
                  <h2>Data Surat Masuk</h2>
                  <div class="clearfix"></div>
                </div>

                <form action="downloadlaporan_suratmasuk.php" name="download_suratmasuk" method="post"
                  enctype="multipart/form-data" id="demo-form2"
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
                      <?php for ($tahun = 2024; $tahun <= 2030; $tahun++): ?>
                        <option value="<?php echo $tahun; ?>"><?php echo $tahun; ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                  <a href="export/export_surat_masuk.php" class="btn btn-danger">
                    <i class="fa fa-download"></i> Unduh Laporan PDF
                  </a>
                  <a href="export/exportExcel_surat_masuk.php" class="btn btn-success">
                    <i class="fa fa-download"></i> Unduh Laporan Excel
                  </a>
                  <a href="inputsuratmasuk.php">
                    <button type="button" class="btn btn-primary">
                      <i class="fa fa-plus"></i> Tambah Surat Masuk
                    </button>
                  </a>
                </form>

                <div class="x_content">
                  <div class="x_content">
                    <?php
                    include '../koneksi/koneksi.php';
                    $sql1   = "SELECT * FROM tb_arsip_surat_masuk ORDER BY nomor_surat ASC";
                    $query1 = mysqli_query($db, $sql1);
                    $total  = mysqli_num_rows($query1);
                    if ($total == 0) {
                      echo "<center><h2>Belum Ada Data Surat Masuk</h2></center>";
                    } else { ?>
                    <div class="row" style="margin-bottom: 15px;">
                      <div class="col-md-3 col-sm-4 col-xs-12">
                        <label for="sort_column" style="margin-bottom: 4px;">Urutkan Berdasarkan</label>
                        <select id="sort_column" class="form-control">
                          <option value="0">No</option>
                          <option value="1">Nomor Surat</option>
                          <option value="2">Tanggal Terima</option>
                          <option value="3">Tanggal Surat</option>
                        </select>
                      </div>
                      <div class="col-md-2 col-sm-3 col-xs-12">
                        <label for="sort_direction" style="margin-bottom: 4px;">Arah</label>
                        <select id="sort_direction" class="form-control">
                          <option value="asc" selected>Asc</option>
                          <option value="desc">Desc</option>
                        </select>
                      </div>
                      <div class="col-md-2 col-sm-3 col-xs-12" style="padding-top: 24px;">
                        <button type="button" id="btn_sort" class="btn btn-info">Terapkan Urutan</button>
                      </div>
                    </div>
                    <div class="table-responsive-custom">
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
                          <th width="10%">Keterangan</th>
                          <th width="3%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $query1 = mysqli_query($db, "SELECT * FROM tb_arsip_surat_masuk");
                        while ($data = mysqli_fetch_array($query1)) {
                          // Hitung jumlah foto lampiran
                          $foto_count = 0;
                          if (!empty($data['lampiran_foto'])) {
                            $decoded = json_decode($data['lampiran_foto'], true);
                            $foto_count = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                              ? count($decoded) : 1;
                          }

                          echo '<tr>
                            <td>' . htmlspecialchars($data['No']) . '</td>
                            <td>' . htmlspecialchars($data['nomor_surat']) . '</td>
                            <td>' . htmlspecialchars($data['tanggal_terima']) . '</td>
                            <td>' . htmlspecialchars($data['tanggal_surat']) . '</td>
                            <td>' . htmlspecialchars($data['pengirim']) . '</td>
                            <td>' . htmlspecialchars($data['penerima_surat']) . '</td>
                            <td>' . htmlspecialchars($data['disposisi']) . '</td>
                            <td>' . htmlspecialchars($data['perihal']) . '</td>
                            <td>' . htmlspecialchars($data['keterangan']) . '</td>
                            <td style="text-align:center; white-space:nowrap;">
                              <a href="surat_masuk/' . htmlspecialchars($data['file_surat']) . '" title="Unduh File">
                                <button type="button" class="btn btn-success btn-xs"><i class="fa fa-download"></i></button>
                              </a><br>';

                          // Tombol foto — tampilkan jika ada lampiran
                          if ($foto_count > 0) {
                            echo '<a href="detail-suratmasuk.php?id=' . $data['No'] . '" title="Lihat ' . $foto_count . ' Foto">
                                    <button type="button" class="btn btn-info btn-xs">
                                      <i class="fa fa-image"></i>
                                      ' . ($foto_count > 1 ? '<span class="badge" style="background:#fff;color:#31708f;font-size:9px;">' . $foto_count . '</span>' : '') . '
                                    </button>
                                  </a><br>';
                          }

                          echo '<a href="detail-suratmasuk.php?id=' . $data['No'] . '" title="Detail">
                                  <button type="button" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></button>
                                </a><br>
                                <a href="editsuratmasuk.php?id=' . $data['No'] . '" title="Edit">
                                  <button type="button" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                                </a><br>
                                <button type="button" title="Hapus" class="btn btn-danger btn-xs"
                                  onclick="konfirmasiHapus(' . $data['No'] . ')">
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
          window.location = 'proses/proses_hapussuratmasuk.php?id=' + id;
        }
      });
    }

    // ── Tampilkan SweetAlert dari redirect status ──────────────────────
    // ── Sort DataTable ─────────────────────────────────────────────
    $(document).ready(function() {
      if ($.fn.DataTable.isDataTable('#datatable')) {
        var table = $('#datatable').DataTable();

        // Default: Nomor Surat ascending
        table.order([1, 'asc']).draw();

        $('#btn_sort').on('click', function() {
          var selectedColumn    = parseInt($('#sort_column').val(), 10);
          var selectedDirection = $('#sort_direction').val();
          table.order([selectedColumn, selectedDirection]).draw();
        });
      }
    });

    <?php if (isset($_GET['status'])): ?>
    document.addEventListener('DOMContentLoaded', function () {
      <?php if ($_GET['status'] === 'success'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Operasi berhasil dilakukan.',
        confirmButtonColor: '#26B99A',
        confirmButtonText: 'OK',
        timer: 3000,
        timerProgressBar: true
      });
      <?php elseif ($_GET['status'] === 'deleted'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Dihapus!',
        text: 'Data berhasil dihapus.',
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
    });
    <?php endif; ?>
  </script>
</body>
</html>