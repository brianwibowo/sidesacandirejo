<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

// Ambil ID dari URL dan validasi
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (empty($id)) {
    echo "<script>alert('ID tidak valid!'); window.location='datamitra.php';</script>";
    exit;
}

// Ambil data mitra dengan prepared statement
$stmt_mitra = $db->prepare("SELECT * FROM tb_data_mitra WHERE id = ?");
$stmt_mitra->bind_param("i", $id);
$stmt_mitra->execute();
$result_mitra = $stmt_mitra->get_result();
$mitra_data = $result_mitra->fetch_assoc();
$stmt_mitra->close();

if (!$mitra_data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='datamitra.php';</script>";
    exit;
}

// === AMBIL SEMUA FILE BUKTI LEGALITAS ===
$semua_legalitas = [];
$stmt_legalitas = $db->prepare("SELECT nama_file FROM tb_foto_legalitas WHERE id_mitra = ?");
$stmt_legalitas->bind_param("i", $id);
$stmt_legalitas->execute();
$result_legalitas = $stmt_legalitas->get_result();
while ($row = $result_legalitas->fetch_assoc()) {
    $semua_legalitas[] = $row['nama_file'];
}
$stmt_legalitas->close();

// === AMBIL SEMUA FOTO KEGIATAN ===
$semua_kegiatan = [];
$stmt_kegiatan = $db->prepare("SELECT nama_file FROM tb_foto_kegiatan WHERE id_mitra = ?");
$stmt_kegiatan->bind_param("i", $id);
$stmt_kegiatan->execute();
$result_kegiatan = $stmt_kegiatan->get_result();
while ($row = $result_kegiatan->fetch_assoc()) {
    $semua_kegiatan[] = $row['nama_file'];
}
$stmt_kegiatan->close();
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Data Mitra - Desa Candirejo Borobudur</title>

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <style>
    .file-preview {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 15px;
      background: #f8f8f8;
      text-align: center;
      min-height: 150px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .file-preview i {
      font-size: 3em;
      color: #d9534f;
      margin-bottom: 10px;
    }
    .file-preview img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 5px;
    }
    .foto-kegiatan-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 15px;
      margin-top: 15px;
    }
    .foto-kegiatan-item {
      position: relative;
      width: 100%;
      padding-bottom: 100%; /* Aspect ratio 1:1 (persegi) */
      overflow: hidden;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    .foto-kegiatan-item img {
      position: absolute;
      top: 0;
      left: 0;
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

      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Detail Data Mitra</h3>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Detail untuk: <?php echo htmlspecialchars($mitra_data['nama_usaha']); ?></h2>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <!-- INFORMASI UTAMA -->
                  <div class="col-md-12">
                    <h4>Informasi Mitra</h4>
                        <table class="table table-striped">
                            <tbody>
                                <tr><td width="25%">Nama Pemilik</td><td>: <?= htmlspecialchars($mitra_data['nama_pemilik']) ?></td></tr>
                                <tr><td>Nama Usaha</td><td>: <?= htmlspecialchars($mitra_data['nama_usaha']) ?></td></tr>
                                <tr><td>Kategori Usaha</td><td>: <?= htmlspecialchars($mitra_data['kategori_usaha']) ?></td></tr>
                                <tr><td>Alamat</td><td>: <?= nl2br(htmlspecialchars($mitra_data['alamat'])) ?></td></tr>
                                <tr><td>Nomor Telepon</td><td>: <?= htmlspecialchars($mitra_data['nomor_telp']) ?></td></tr>
                                <tr><td>Legalitas Usaha</td><td>: <?= htmlspecialchars($mitra_data['legalitas_usaha']) ?></td></tr>
                            </tbody>
                        </table>
                  </div>

                  <!-- BUKTI LEGALITAS -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <h4>Bukti Legalitas</h4>
                    <?php if (!empty($semua_legalitas)): ?>
                      <div class="row">
                        <?php foreach ($semua_legalitas as $file): 
                          // ================== PERBAIKAN: URL YANG BENAR ==================
                          // $file sudah berisi 'legalitas/file123.pdf' dari database
                          $file_url = '/admin/uploads/mitra/' . htmlspecialchars($file);
                          // ================== AKHIR PERBAIKAN ==================
                          $is_pdf = strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'pdf';
                        ?>
                          <div class="col-md-3 col-sm-4 col-xs-6">
                            <div class="file-preview">
                              <a href="<?php echo $file_url; ?>" target="_blank">
                                <?php if ($is_pdf): ?>
                                  <i class="fa fa-file-pdf-o"></i>
                                  <p style="margin: 0; word-break: break-all; font-size: 12px;"><?php echo htmlspecialchars(basename($file)); ?></p>
                                <?php else: ?>
                                  <img src="<?php echo $file_url; ?>" alt="Bukti Legalitas">
                                <?php endif; ?>
                              </a>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <p class="text-muted">Tidak ada file bukti legalitas.</p>
                    <?php endif; ?>
                  </div>

                  <!-- FOTO KEGIATAN USAHA (GRID PERSEGI) -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <h4>Foto Kegiatan Usaha</h4>
                    <?php if (!empty($semua_kegiatan)): ?>
                      <div class="foto-kegiatan-grid">
                        <?php foreach ($semua_kegiatan as $foto): 
                          // ================== PERBAIKAN: URL YANG BENAR ==================
                          // $foto sudah berisi 'kegiatan/foto456.jpg' dari database
                          $foto_url = '/admin/uploads/mitra/' . htmlspecialchars($foto);
                          // ================== AKHIR PERBAIKAN ==================
                        ?>
                          <div class="foto-kegiatan-item">
                            <a href="<?php echo $foto_url; ?>" target="_blank">
                              <img src="<?php echo $foto_url; ?>" alt="Foto Kegiatan">
                            </a>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <p class="text-muted">Tidak ada foto kegiatan usaha.</p>
                    <?php endif; ?>
                  </div>

                  <!-- TOMBOL KEMBALI -->
                  <div class="col-xs-12" style="margin-top: 30px;">
                    <div class="text-right">
                      <a href="datamitra.php" class="btn btn-success">
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
  <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>