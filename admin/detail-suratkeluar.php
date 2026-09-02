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
  <!-- bootstrap-wysiwyg -->
  <link href="../assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* ── Thumbnail dokumentasi ─────────────────────────────────────── */
    .foto-thumb {
      display: inline-block;
      margin: 3px;
      border: 2px solid #ddd;
      border-radius: 6px;
      overflow: hidden;
      cursor: pointer;
      transition: border-color .2s, transform .2s;
    }
    .foto-thumb:hover {
      border-color: #26B99A;
      transform: scale(1.05);
    }
    .foto-thumb img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      display: block;
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
            <div class="title_left">
              <h3>Surat Keluar</h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Surat Keluar &rsaquo; <small>Detail Surat Keluar</small></h2>
                  <div class="clearfix"></div>
                </div>

                <?php
                include '../koneksi/koneksi.php';
                $id    = mysqli_real_escape_string($db, $_GET['id']);
                $sql   = "SELECT * FROM tb_arsip_surat_keluar WHERE No = '$id'";
                $query = mysqli_query($db, $sql);
                $data  = mysqli_fetch_array($query);

                $absensi_files     = json_decode($data['lampiran_absensi']  ?? '[]', true);
                if (!is_array($absensi_files))     $absensi_files     = [];
                $notulen_files     = json_decode($data['lampiran_notulen']  ?? '[]', true);
                if (!is_array($notulen_files))     $notulen_files     = [];
                $dokumentasi_files = json_decode($data['dokumentasi_foto']  ?? '[]', true);
                if (!is_array($dokumentasi_files)) $dokumentasi_files = [];
                ?>

                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Surat Keluar</h2>
                      </div>
                    </div>
                    <div class="x_content"></div>

                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td width="40%">No</td>
                          <td><?php echo htmlspecialchars($data['No']); ?></td>
                        </tr>
                        <tr>
                          <td>Tanggal Keluar</td>
                          <td><?php echo htmlspecialchars($data['tanggal_keluar']); ?></td>
                        </tr>
                        <tr>
                          <td>Nomor Surat</td>
                          <td><?php echo htmlspecialchars($data['nomor_surat']); ?></td>
                        </tr>
                        <tr>
                          <td>Penerima</td>
                          <td><?php echo htmlspecialchars($data['penerima']); ?></td>
                        </tr>
                        <tr>
                          <td>Jenis Surat</td>
                          <td><?php echo htmlspecialchars(ucfirst($data['jenis_surat'] ?? 'keterangan')); ?></td>
                        </tr>
                        <tr>
                          <td>Tempat Acara</td>
                          <td><?php echo !empty($data['tempat_acara']) ? htmlspecialchars($data['tempat_acara']) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Tanggal Kegiatan</td>
                          <td><?php echo !empty($data['tanggal_kegiatan']) ? htmlspecialchars($data['tanggal_kegiatan']) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Jam Kegiatan</td>
                          <td><?php echo !empty($data['jam_kegiatan']) ? htmlspecialchars(substr($data['jam_kegiatan'], 0, 5)) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td>Perihal</td>
                          <td><?php echo htmlspecialchars($data['perihal']); ?></td>
                        </tr>
                        <tr>
                          <td>Keterangan</td>
                          <td><?php echo !empty($data['keterangan']) ? htmlspecialchars($data['keterangan']) : '-'; ?></td>
                        </tr>

                        <!-- File Surat -->
                        <tr>
                          <td>File Surat</td>
                          <td>
                            <?php
                            if (empty($data['file_surat'])) {
                              echo '<span style="color:#95a5a6;">-</span>';
                            } else {
                              $file_url = 'uploads/' . basename($data['file_surat']);
                              echo '<a href="' . htmlspecialchars($file_url) . '" class="btn btn-xs btn-primary" download>'
                                 . '<i class="fa fa-download"></i> Unduh File Surat</a>';
                            }
                            ?>
                          </td>
                        </tr>

                        <!-- Absensi: tombol download -->
                        <tr>
                          <td>Absensi</td>
                          <td>
                            <?php
                            if (empty($absensi_files)) {
                              echo '<span style="color:#95a5a6;">-</span>';
                            } else {
                              echo '<div style="max-width:220px;">';
                              foreach ($absensi_files as $idx => $filename) {
                                $fileUrl = 'uploads/' . rawurlencode($filename);
                                echo '<a href="' . $fileUrl . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '">'
                                   . '<i class="fa fa-download"></i> File ' . ($idx + 1) . '</a>';
                              }
                              echo '</div>';
                            }
                            ?>
                          </td>
                        </tr>

                        <!-- Notulen: tombol download -->
                        <tr>
                          <td>Notulen</td>
                          <td>
                            <?php
                            if (empty($notulen_files)) {
                              echo '<span style="color:#95a5a6;">-</span>';
                            } else {
                              echo '<div style="max-width:220px;">';
                              foreach ($notulen_files as $idx => $filename) {
                                $fileUrl = 'uploads/' . rawurlencode($filename);
                                echo '<a href="' . $fileUrl . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '">'
                                   . '<i class="fa fa-download"></i> File ' . ($idx + 1) . '</a>';
                              }
                              echo '</div>';
                            }
                            ?>
                          </td>
                        </tr>

                        <!-- Dokumentasi: tampil sebagai foto, klik buka modal -->
                        <tr>
                          <td>Foto / Dokumentasi</td>
                          <td>
                            <?php
                            if (empty($dokumentasi_files)) {
                              echo '<span style="color:#95a5a6;">-</span>';
                            } else {
                              foreach ($dokumentasi_files as $filename) {
                                $fileUrl   = 'uploads/' . rawurlencode($filename);
                                $safe_name = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');
                                $safe_url  = htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8');
                                echo '<span class="foto-thumb" onclick="viewImage(\'' . $safe_url . '\')" title="' . $safe_name . '">'
                                   . '<img src="' . $safe_url . '" alt="' . $safe_name . '" loading="lazy">'
                                   . '</span>';
                              }
                            }
                            ?>
                          </td>
                        </tr>

                      </tbody>
                    </table>

                    <div class="text-right">
                      <a href="datasuratkeluar.php" class="btn btn-success">
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
  <!-- morris.js -->
  <script src="../assets/vendors/raphael/raphael.min.js"></script>
  <script src="../assets/vendors/morris.js/morris.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- ── Image Viewer Modal ──────────────────────────────────────────── -->
  <div id="imageModal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.9);">
    <style>
      #imageModal { animation: fadeIn .3s; }
      @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
      .modal-image-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        width: 90%;
        max-width: 800px;
        height: 90%;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
      }
      .modal-image-content img {
        flex: 1;
        object-fit: contain;
        max-width: 100%;
        max-height: 100%;
        padding: 20px;
      }
      .modal-image-close {
        position: absolute;
        top: 10px;
        right: 20px;
        color: #aaa;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10;
      }
      .modal-image-close:hover { color: #000; }
      .modal-image-filename {
        background: #f0f0f0;
        padding: 10px 20px;
        text-align: center;
        font-size: 12px;
        color: #666;
        border-top: 1px solid #ddd;
        border-radius: 0 0 8px 8px;
      }
    </style>
    <div class="modal-image-content">
      <span class="modal-image-close" onclick="closeImageModal()">&times;</span>
      <img id="modalImage" src="" alt="viewed image" />
      <div class="modal-image-filename" id="modalFilename"></div>
    </div>
  </div>

  <script>
    function viewImage(imagePath) {
      document.getElementById('modalImage').src = imagePath;
      document.getElementById('modalFilename').textContent = decodeURIComponent(imagePath.split('/').pop());
      document.getElementById('imageModal').style.display = 'block';
    }
    function closeImageModal() {
      document.getElementById('imageModal').style.display = 'none';
    }
    window.onclick = function(e) {
      if (e.target === document.getElementById('imageModal')) closeImageModal();
    };
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeImageModal();
    });
  </script>

</body>
</html>