<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php'; // Pindahkan koneksi ke atas agar lebih rapi
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Data Pengurus - Desa Candirejo</title>

  <link rel="shortcut icon" href="../img/icon.ico">

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  
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
                  <h2>Data Pengurus</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <a href="inputdatapengurus.php" class="btn btn-primary" style="margin-bottom: 20px;">
                    <i class="fa fa-plus"></i> Tambah Pengurus
                  </a>

                  <?php
                  $sql = "SELECT 
                            p.*, 
                            (SELECT fp_ktp.nama_file FROM tb_foto_pengurus fp_ktp WHERE fp_ktp.id_pengurus = p.id AND fp_ktp.jenis_foto = 'KTP' LIMIT 1) AS file_ktp,
                            (SELECT fp_pas.nama_file FROM tb_foto_pengurus fp_pas WHERE fp_pas.id_pengurus = p.id AND fp_pas.jenis_foto = 'Pas Foto' LIMIT 1) AS file_pas_foto
                          FROM tb_data_pengurus p
                          ORDER BY p.id DESC";

                  $query = mysqli_query($db, $sql);
                  $total = mysqli_num_rows($query);

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
                        while ($data = mysqli_fetch_array($query)) {
                          
                          // --- Proses Thumbnail Foto KTP ---
                          $thumb_ktp_html = '-';
                          if (!empty($data['file_ktp'])) {
                            $file_url = '/admin/uploads/pengurus/' . htmlspecialchars($data['file_ktp']);
                            $thumb_ktp_html = '<a href="' . $file_url . '" target="_blank"><img src="' . $file_url . '" style="width:50px; height:50px; border-radius:5px; object-fit: cover;"></a>';
                          }

                          // --- Proses Thumbnail Pas Foto ---
                          $thumb_pas_foto_html = '-';
                          if (!empty($data['file_pas_foto'])) {
                            $file_url = '/admin/uploads/pengurus/' . htmlspecialchars($data['file_pas_foto']);
                            $thumb_pas_foto_html = '<a href="' . $file_url . '" target="_blank"><img src="' . $file_url . '" style="width:50px; height:50px; border-radius:5px; object-fit: cover;"></a>';
                          }

                          echo '<tr>
                                  <td>' . htmlspecialchars($data['nama']) . '</td>
                                  <td>' . htmlspecialchars($data['no_ktp']) . '</td>
                                  <td>' . htmlspecialchars($data['jabatan']) . '</td>
                                  <td>' . htmlspecialchars($data['periode']) . '</td>
                                  <td>' . htmlspecialchars($data['alamat']) . '</td>
                                  <td>' . htmlspecialchars($data['no_telp']) . '</td>
                                  <td class="text-center">' . $thumb_ktp_html . '</td>
                                  <td class="text-center">' . $thumb_pas_foto_html . '</td>
                                  <td class="text-center">
                                    <a href="detail-pengurus.php?id=' . $data['id'] . '" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-eye"></i></a>
                                    <a href="editpengurus.php?id=' . $data['id'] . '" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm(\'Anda Yakin Akan Menghapus Data Pengurus Ini?\')" href="proses/proses_hapuspengurus.php?id=' . $data['id'] . '" class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-trash-o"></i></a>
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