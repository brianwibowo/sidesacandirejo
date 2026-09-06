<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '';
if (empty($id)) {
    header("Location: datapengurus.php");
    exit();
}

$sql   = "SELECT * FROM tb_data_pengurus WHERE id='$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: datapengurus.php");
    exit();
}

$pas_foto_exists = !empty($data['pas_foto']) && file_exists(__DIR__ . '/uploads/pengurus/' . $data['pas_foto']);
$foto_ktp_exists = !empty($data['foto_ktp']) && file_exists(__DIR__ . '/uploads/pengurus/' . $data['foto_ktp']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Pengurus - <?php echo htmlspecialchars($data['nama']); ?> - Arsip Candirejo</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendors -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">
  <link href="css/detail_modern.css?v=1.0" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="detail-page-wrapper">

          <!-- Card Header Page Title -->
          <div class="card page-title-card">
            <div class="detail-header-bar">
              <div class="detail-header-left">
                <h1>Detail Biodata Pengurus</h1>
                <p>Profil kepengurusan organisasi Desa Wisata Candirejo</p>
              </div>
              <div class="detail-header-actions">
                <a href="datapengurus.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editpengurus.php?id=<?php echo urlencode($data['id']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Biodata
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['nama']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  Jabatan: <strong><?php echo htmlspecialchars($data['jabatan']); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  Periode: <strong><?php echo htmlspecialchars($data['periode']); ?></strong>
                </span>
                <?php if (!empty($data['no_telp'])): ?>
                  <span class="summary-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <?php echo htmlspecialchars($data['no_telp']); ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
            <div>
              <span class="badge-pill badge-pill-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Pengurus Aktif
              </span>
            </div>
          </div>

          <!-- Grid Layout -->
          <div class="detail-grid">

            <!-- Left Column: Informasi Rinci Biodata -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                  Biodata Lengkap Pengurus
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <tr>
                      <td class="col-label">Nama Lengkap</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nomor Induk KTP (NIK)</td>
                      <td class="col-value"><?php echo !empty($data['no_ktp']) ? htmlspecialchars($data['no_ktp']) : '-'; ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Jabatan Struktural</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['jabatan']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Periode Masa Bakti</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['periode']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nomor Telepon / HP</td>
                      <td class="col-value">
                        <?php if (!empty($data['no_telp'])): ?>
                          <a href="tel:<?php echo htmlspecialchars($data['no_telp']); ?>" style="color:#2e7d4f;text-decoration:none;font-weight:600;">
                            <i class="fa fa-phone"></i> <?php echo htmlspecialchars($data['no_telp']); ?>
                          </a>
                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-label">Alamat Domisili</td>
                      <td class="col-value"><?php echo !empty($data['alamat']) ? htmlspecialchars($data['alamat']) : '-'; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Pas Foto & Foto KTP -->
            <div>
              <!-- Pas Foto -->
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Pas Foto Resmi
                  </h3>
                </div>
                <div class="detail-card-body text-center">
                  <?php if ($pas_foto_exists): ?>
                    <div style="display:inline-block;border-radius:12px;overflow:hidden;border:2px solid #b5d5c0;box-shadow:0 4px 14px rgba(30,58,47,0.1);">
                      <a href="uploads/pengurus/<?php echo htmlspecialchars($data['pas_foto']); ?>" target="_blank" title="Buka foto ukuran penuh">
                        <img src="uploads/pengurus/<?php echo htmlspecialchars($data['pas_foto']); ?>" alt="Pas Foto" style="width:160px;height:210px;object-fit:cover;display:block;">
                      </a>
                    </div>
                  <?php else: ?>
                    <div style="padding:28px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-user-circle-o" style="font-size:36px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Belum ada pas foto yang diunggah.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Foto KTP -->
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Dokumen Foto KTP
                  </h3>
                </div>
                <div class="detail-card-body text-center">
                  <?php if ($foto_ktp_exists): ?>
                    <div style="border-radius:10px;overflow:hidden;border:1.5px solid #d6e6dc;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                      <a href="uploads/pengurus/<?php echo htmlspecialchars($data['foto_ktp']); ?>" target="_blank" title="Buka KTP ukuran penuh">
                        <img src="uploads/pengurus/<?php echo htmlspecialchars($data['foto_ktp']); ?>" alt="Foto KTP" style="width:100%;max-height:200px;object-fit:contain;display:block;background:#f8faf9;padding:8px;">
                      </a>
                    </div>
                  <?php else: ?>
                    <div style="padding:24px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-id-card-o" style="font-size:32px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Belum ada foto KTP yang diunggah.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

            </div>

          </div>

        </div>
      </div>

      <footer>
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
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