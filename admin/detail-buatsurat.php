<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id_bagian']) ? mysqli_real_escape_string($db, trim($_GET['id_bagian'])) : (isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '');
if (empty($id)) {
    header("Location: databuatsurat.php");
    exit();
}

$sql   = "SELECT * FROM tb_bagian WHERE id_bagian='$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: databuatsurat.php");
    exit();
}

// Format tanggal
function tgl_indo($tgl) {
    if (empty($tgl) || $tgl === '0000-00-00') return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tgl)));
    return (int)$pecahkan[2] . ' ' . ($bulan[(int)$pecahkan[1]] ?? '') . ' ' . $pecahkan[0];
}

$has_photo = !empty($data['gambar']) && file_exists(__DIR__ . '/../bagian/images/' . $data['gambar']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Bagian - <?php echo htmlspecialchars($data['nama_bagian']); ?> - Arsip Candirejo</title>

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
                <h1>Detail Data Bagian</h1>
                <p>Informasi profil admin bagian pembuat surat Desa Candirejo</p>
              </div>
              <div class="detail-header-actions">
                <a href="databuatsurat.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editbuatsurat.php?id_bagian=<?php echo urlencode($data['id_bagian']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Bagian
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['nama_bagian']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  Petugas: <strong><?php echo htmlspecialchars($data['nama_lengkap']); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                  Username: <strong>@<?php echo htmlspecialchars($data['username_admin_bagian']); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                  ID: #<?php echo htmlspecialchars($data['id_bagian']); ?>
                </span>
              </div>
            </div>
            <div>
              <span class="badge-pill badge-pill-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Bagian Aktif
              </span>
            </div>
          </div>

          <!-- Grid Layout -->
          <div class="detail-grid">

            <!-- Left Column: Informasi Rinci -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  Informasi Detail Akun Bagian
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <tr>
                      <td class="col-label">ID Bagian</td>
                      <td class="col-value">#<?php echo htmlspecialchars($data['id_bagian']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nama Bagian / Divisi</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama_bagian']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Username Login</td>
                      <td class="col-value">@<?php echo htmlspecialchars($data['username_admin_bagian']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nama Lengkap Petugas</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Tanggal Lahir</td>
                      <td class="col-value"><?php echo tgl_indo($data['tanggal_lahir_bagian']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nomor HP / WhatsApp</td>
                      <td class="col-value">
                        <?php if (!empty($data['no_hp_bagian'])): ?>
                          <a href="tel:<?php echo htmlspecialchars($data['no_hp_bagian']); ?>" style="color:#2e7d4f;text-decoration:none;font-weight:600;">
                            <i class="fa fa-phone"></i> <?php echo htmlspecialchars($data['no_hp_bagian']); ?>
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

            <!-- Right Column: Foto Profil Petugas Bagian -->
            <div>
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Foto Petugas Bagian
                  </h3>
                </div>
                <div class="detail-card-body text-center">
                  <?php if ($has_photo): ?>
                    <div style="display:inline-block;border-radius:12px;overflow:hidden;border:2.5px solid #b5d5c0;box-shadow:0 4px 14px rgba(30,58,47,0.1);">
                      <a href="../bagian/images/<?php echo htmlspecialchars($data['gambar']); ?>" target="_blank" title="Lihat foto ukuran penuh">
                        <img src="../bagian/images/<?php echo htmlspecialchars($data['gambar']); ?>" alt="Foto Petugas" style="width:160px;height:200px;object-fit:cover;display:block;">
                      </a>
                    </div>
                  <?php else: ?>
                    <div style="padding:28px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-user-circle-o" style="font-size:36px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Belum ada foto petugas yang diunggah.</div>
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