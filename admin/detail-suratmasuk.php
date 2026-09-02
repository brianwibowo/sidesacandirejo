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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    .foto-gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 8px;
    }
    .foto-gallery a {
      display: block;
      width: 110px;
      height: 110px;
      border-radius: 6px;
      overflow: hidden;
      border: 2px solid #26B99A;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
      transition: transform .2s;
    }
    .foto-gallery a:hover {
      transform: scale(1.05);
    }
    .foto-gallery a img {
      width: 100%;
      height: 100%;
      object-fit: cover;
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
          <div class="page-title">
            <div class="title_left"><h3>Surat Masuk</h3></div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Surat Masuk &rsaquo; <small>Detail Surat Masuk</small></h2>
                  <div class="clearfix"></div>
                </div>

                <?php
                include '../koneksi/koneksi.php';
                $id    = mysqli_real_escape_string($db, $_GET['id']);
                $sql   = "SELECT * FROM tb_arsip_surat_masuk WHERE No='$id'";
                $query = mysqli_query($db, $sql);
                $data  = mysqli_fetch_array($query);

                // Parse foto lampiran
                $fotos = [];
                if (!empty($data['lampiran_foto'])) {
                    $decoded = json_decode($data['lampiran_foto'], true);
                    $fotos = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                             ? $decoded : [$data['lampiran_foto']];
                }
                ?>

                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Surat Masuk</h2>
                      </div>
                    </div>
                    <div class="x_content"></div>

                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td width="35%">Tanggal Masuk</td>
                          <td><?php echo htmlspecialchars($data['tanggal_terima']); ?></td>
                        </tr>
                        <tr>
                          <td>Nomor Urut</td>
                          <td><?php echo htmlspecialchars($data['No']); ?></td>
                        </tr>
                        <tr>
                          <td>Nomor Surat</td>
                          <td><?php echo htmlspecialchars($data['nomor_surat']); ?></td>
                        </tr>
                        <tr>
                          <td>Tanggal Surat</td>
                          <td><?php echo htmlspecialchars($data['tanggal_surat']); ?></td>
                        </tr>
                        <tr>
                          <td>Pengirim</td>
                          <td><?php echo htmlspecialchars($data['pengirim']); ?></td>
                        </tr>
                        <tr>
                          <td>Perihal</td>
                          <td><?php echo htmlspecialchars($data['perihal']); ?></td>
                        </tr>
                        <tr>
                          <td>Penerima</td>
                          <td><?php echo htmlspecialchars($data['penerima_surat']); ?></td>
                        </tr>
                        <tr>
                          <td>Disposisi</td>
                          <td><?php echo htmlspecialchars($data['disposisi']); ?></td>
                        </tr>
                        <tr>
                          <td>File Surat</td>
                          <td>
                            <a href="<?php echo htmlspecialchars('uploads/' . $data['file_surat']); ?>"
                               target="_blank" class="btn btn-sm btn-default">
                              <i class="fa fa-file-pdf-o"></i> Unduh / Lihat File
                            </a>
                          </td>
                        </tr>
                        <tr>
                          <td>Lampiran Foto</td>
                          <td>
                            <?php if (!empty($fotos)): ?>
                              <p class="text-muted" style="margin-bottom:6px;">
                                <small><?php echo count($fotos); ?> foto lampiran</small>
                              </p>
                              <div class="foto-gallery">
                                <?php foreach ($fotos as $foto): ?>
                                  <a href="<?php echo htmlspecialchars('uploads/' . $foto); ?>" target="_blank"
                                     title="Klik untuk memperbesar">
                                    <img src="<?php echo htmlspecialchars('uploads/' . $foto); ?>"
                                         alt="Lampiran Foto">
                                  </a>
                                <?php endforeach; ?>
                              </div>
                            <?php else: ?>
                              <span class="text-muted"><i class="fa fa-image"></i> Tidak ada lampiran foto</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="text-right">
                      <a href="datasuratmasuk.php" class="btn btn-success">
                        <span class="glyphicon glyphicon-arrow-left"></span> Kembali
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <footer>
        <div class="pull-right"></div>
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
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>