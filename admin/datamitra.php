<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

// Inisialisasi variabel filter
$filter_kategori = '';
$where_clause = '';

// Cek apakah ada filter yang diterapkan dari GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['kategori_usaha']) && !empty($_GET['kategori_usaha'])) {
    $filter_kategori = mysqli_real_escape_string($db, $_GET['kategori_usaha']);
    $where_clause = " WHERE m.kategori_usaha = '$filter_kategori'";
}
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Data Mitra - Desa Candirejo</title>

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
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
                  <h2>Data Mitra</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <div class="row" style="margin-bottom: 20px;">
                    <form action="" method="get" class="form-inline">
                      <div class="col-md-4">
                        <label for="kategori_usaha">Filter Kategori Usaha:</label>
                        <select name="kategori_usaha" id="kategori_usaha" class="form-control">
                          <option value="">Semua Kategori</option>
                          <option value="UMKM" <?php echo ($filter_kategori == 'UMKM') ? 'selected' : ''; ?>>UMKM</option>
                          <option value="Local Guide" <?php echo ($filter_kategori == 'Local Guide') ? 'selected' : ''; ?>>Local Guide</option>
                          <option value="Catering" <?php echo ($filter_kategori == 'Catering') ? 'selected' : ''; ?>>Catering</option>
                          <option value="Dokar" <?php echo ($filter_kategori == 'Dokar') ? 'selected' : ''; ?>>Dokar</option>
                          <option value="Homestay" <?php echo ($filter_kategori == 'Homestay') ? 'selected' : ''; ?>>Homestay</option>
                          <option value="Kerajinan" <?php echo ($filter_kategori == 'Kerajinan') ? 'selected' : ''; ?>>Kerajinan</option>
                        </select>
                      </div>
                      <div class="col-md-8">
                        <button type="submit" class="btn btn-info"><i class="fa fa-filter"></i> Tampilkan</button>
                        <a href="datamitra.php" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</a>
                        <a href="inputdatamitra.php" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Tambah Mitra</a>
                      </div>
                    </form>
                  </div>

                  <?php
                  // Query mengambil file PERTAMA dari masing-masing tabel relasi
                  $sql = "SELECT 
                            m.*, 
                            (SELECT fl.nama_file FROM tb_foto_legalitas fl WHERE fl.id_mitra = m.id ORDER BY fl.id_file ASC LIMIT 1) AS file_legalitas_utama,
                            (SELECT fk.nama_file FROM tb_foto_kegiatan fk WHERE fk.id_mitra = m.id ORDER BY fk.id_foto ASC LIMIT 1) AS foto_kegiatan_utama
                          FROM tb_data_mitra m
                          $where_clause
                          ORDER BY m.id DESC";

                  $query = mysqli_query($db, $sql);
                  $total = mysqli_num_rows($query);

                  if ($total == 0) {
                    echo "<center><h2>Belum Ada Data Mitra</h2></center>";
                  } else { ?>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Nama Pemilik</th>
                          <th>Nama Usaha</th>
                          <th>Kategori Usaha</th>
                          <th>Alamat</th>
                          <th>Nomor Telepon</th>
                          <th>Legalitas Usaha</th>
                          <th style="text-align:center;">Bukti Legalitas</th>
                          <th style="text-align:center;">Foto Kegiatan</th>
                          <th style="text-align:center;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        while ($data = mysqli_fetch_array($query)) {
                          
                          // ================== PERBAIKAN: JANGAN TAMBAHKAN SUBFOLDER LAGI ==================
                          // Nama file dari database SUDAH termasuk subfolder: 'legalitas/file.pdf' atau 'kegiatan/foto.jpg'
                          
                          // --- Proses Thumbnail Bukti Legalitas ---
                          $thumb_legalitas_html = '-';
                          if (!empty($data['file_legalitas_utama'])) {
                            // Langsung pakai nama file dari database (sudah ada subfoldernya)
                            $file_url = '/admin/uploads/mitra/' . htmlspecialchars($data['file_legalitas_utama']);
                            $ext = strtolower(pathinfo($data['file_legalitas_utama'], PATHINFO_EXTENSION));
                            
                            if ($ext == 'pdf') {
                              $thumb_legalitas_html = '<a href="' . $file_url . '" target="_blank"><i class="fa fa-file-pdf-o" style="font-size: 2.5em; color: #d9534f;"></i></a>';
                            } else {
                              $thumb_legalitas_html = '<a href="' . $file_url . '" target="_blank"><img src="' . $file_url . '" style="width:50px; height:50px; border-radius:5px; object-fit: cover;"></a>';
                            }
                          }

                          // --- Proses Thumbnail Foto Kegiatan ---
                          $thumb_kegiatan_html = '-';
                          if (!empty($data['foto_kegiatan_utama'])) {
                            // Langsung pakai nama file dari database (sudah ada subfoldernya)
                            $file_url = '/admin/uploads/mitra/' . htmlspecialchars($data['foto_kegiatan_utama']);
                            $thumb_kegiatan_html = '<a href="' . $file_url . '" target="_blank"><img src="' . $file_url . '" style="width:50px; height:50px; border-radius:5px; object-fit: cover;"></a>';
                          }
                          // ================== AKHIR PERBAIKAN ==================

                          echo '<tr>
                                  <td>' . htmlspecialchars($data['nama_pemilik']) . '</td>
                                  <td>' . htmlspecialchars($data['nama_usaha']) . '</td>
                                  <td>' . htmlspecialchars($data['kategori_usaha']) . '</td>
                                  <td>' . htmlspecialchars($data['alamat']) . '</td>
                                  <td>' . htmlspecialchars($data['nomor_telp']) . '</td>
                                  <td>' . htmlspecialchars($data['legalitas_usaha']) . '</td>
                                  <td class="text-center">' . $thumb_legalitas_html . '</td>
                                  <td class="text-center">' . $thumb_kegiatan_html . '</td>
                                  <td class="text-center">
                                    <a href="detail-mitra.php?id=' . $data['id'] . '" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-eye"></i></a>
                                    <a href="editmitra.php?id=' . $data['id'] . '" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm(\'Anda Yakin Akan Menghapus Data Mitra Ini?\')" href="proses/proses_hapusmitra.php?id=' . $data['id'] . '" class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-trash-o"></i></a>
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
      
      <footer>
        <div class="pull-right"></div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>