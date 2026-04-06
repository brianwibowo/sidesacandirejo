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

  <title>Arsip Surat Desa Candirejo Borobudur </title>

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
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
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
                  <h2>Surat Keluar ><small>Detail Surat Keluar</small></h2>
                  <div class="clearfix"></div>
                </div>
                <?php include '../koneksi/koneksi.php';
                     $id			= mysqli_real_escape_string($db,$_GET['id']);
                     $sql  		= "SELECT * FROM tb_arsip_surat_keluar where No='".$id."'";                        
                     $query  	= mysqli_query($db, $sql);
                   $data 		= mysqli_fetch_array($query);
                   $absensi_files = json_decode($data['lampiran_absensi'] ?? '[]', true);
                   if (!is_array($absensi_files)) $absensi_files = [];
                   $notulen_files = json_decode($data['lampiran_notulen'] ?? '[]', true);
                   if (!is_array($notulen_files)) $notulen_files = [];
                   $dokumentasi_files = json_decode($data['dokumentasi_foto'] ?? '[]', true);
                   if (!is_array($dokumentasi_files)) $dokumentasi_files = [];
                 ?>
                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Surat Keluar</h2>
                      </div>
                    </div>
                    <div class="x_content">
                    </div>
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td width="40%">No</td>
                          <td><?php echo $data['No']?></td>
                        </tr>
                        <tr>
                          <td width="40%">Tanggal Keluar</td>
                          <td><?php echo $data['tanggal_keluar']?></td>
                        </tr>
                        <tr>
                          <td>Nomor Surat</td>
                          <td><?php echo $data['nomor_surat']?></td>
                        </tr>
                        <tr>
                          <td>Penerima</td>
                          <td><?php echo $data['penerima']?></td>
                        </tr>
                        <tr>
                          <td>Tempat Acara</td>
                          <td><?php echo !empty($data['tempat_acara']) ? $data['tempat_acara'] : '-'?></td>
                        </tr>
                        <tr>
                          <td>Tanggal Kegiatan</td>
                          <td><?php echo !empty($data['tanggal_kegiatan']) ? $data['tanggal_kegiatan'] : '-'?></td>
                        </tr>
                        <tr>
                          <td>Perihal</td>
                          <td><?php echo $data['perihal']?></td>
                        </tr>
                        <tr>
                          <td>File</td>
                          <td>
                            <?php
                              if (empty($data['file_surat'])) {
                                echo '<span style="color: #95a5a6;">-</span>';
                              } else {
                                $file_surat_url = preg_replace('/^\.\.\//', '', $data['file_surat']);
                                echo '<div style="max-width: 220px;">';
                                echo '<a href="' . htmlspecialchars($file_surat_url, ENT_QUOTES, 'UTF-8') . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="Unduh File Surat"><i class="fa fa-download"></i> File 1</a>';
                                echo '</div>';
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Absensi</td>
                          <td>
                            <?php
                              if (empty($absensi_files)) {
                                echo '<span style="color: #95a5a6;">-</span>';
                              } else {
                                echo '<div style="max-width: 220px;">';
                                foreach ($absensi_files as $idx => $filename) {
                                  $fileUrl = 'uploads/surat_keluar_lampiran/' . rawurlencode($filename);
                                  echo '<a href="' . $fileUrl . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '"><i class="fa fa-download"></i> File ' . ($idx + 1) . '</a>';
                                }
                                echo '</div>';
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Notulen</td>
                          <td>
                            <?php
                              if (empty($notulen_files)) {
                                echo '<span style="color: #95a5a6;">-</span>';
                              } else {
                                echo '<div style="max-width: 220px;">';
                                foreach ($notulen_files as $idx => $filename) {
                                  $fileUrl = 'uploads/surat_keluar_lampiran/' . rawurlencode($filename);
                                  echo '<a href="' . $fileUrl . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '"><i class="fa fa-download"></i> File ' . ($idx + 1) . '</a>';
                                }
                                echo '</div>';
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Foto/Dokumentasi</td>
                          <td>
                            <?php
                              if (empty($dokumentasi_files)) {
                                echo '<span style="color: #95a5a6;">-</span>';
                              } else {
                                echo '<div style="max-width: 220px;">';
                                foreach ($dokumentasi_files as $idx => $filename) {
                                  $fileUrl = 'uploads/surat_keluar_lampiran/' . rawurlencode($filename);
                                  echo '<a href="' . $fileUrl . '" class="btn btn-xs btn-primary" style="display:block;margin-bottom:4px;" download title="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '"><i class="fa fa-download"></i> File ' . ($idx + 1) . '</a>';
                                }
                                echo '</div>';
                              }
                            ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="text-right">
                      <a href="datasuratkeluar.php" class="btn btn-success"><span
                          class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
                    </div>

                  </div>

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

  <!-- Image Viewer Modal -->
  <div id="imageModal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9);">
    <style>
      #imageModal {
        animation: fadeIn 0.3s;
      }
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
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
        top: 20px;
        right: 30px;
        color: #aaa;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10;
      }
      .modal-image-close:hover {
        color: #000;
      }
      .modal-image-filename {
        background: #f0f0f0;
        padding: 10px 20px;
        text-align: center;
        font-size: 12px;
        color: #666;
        border-top: 1px solid #ddd;
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
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    const filename = document.getElementById('modalFilename');
    img.src = imagePath;
    filename.textContent = imagePath.split('/').pop();
    modal.style.display = 'block';
  }

  function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
  }

  window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  }

  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeImageModal();
    }
  });
  </script>

</body>

</html>