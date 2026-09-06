<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '';
if (empty($id)) {
    header("Location: datapenjualanusaha.php");
    exit();
}

$sql   = "SELECT * FROM tb_data_penjualan_usaha WHERE id='$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: datapenjualanusaha.php");
    exit();
}

function fmtRupiah($val) {
    $clean = preg_replace('/[^0-9]/', '', (string)$val);
    if (!empty($clean)) {
        return 'Rp ' . number_format((float)$clean, 0, ',', '.');
    }
    return 'Rp 0';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Penjualan - <?php echo htmlspecialchars($data['produk']); ?> - Arsip Candirejo</title>

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
                <h1>Detail Penjualan Usaha</h1>
                <p>Rincian data transaksi penjualan produk dan usaha Desa Wisata Candirejo</p>
              </div>
              <div class="detail-header-actions">
                <a href="datapenjualanusaha.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editpenjualan.php?id=<?php echo urlencode($data['id']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Transaksi
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['produk']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                  Kode: <strong><?php echo htmlspecialchars($data['kode_data'] ?? '-'); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                  Kuantitas: <strong><?php echo htmlspecialchars($data['jumlah']); ?> Item</strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Total Nilai: <strong style="color:#10b981;"><?php echo fmtRupiah($data['total']); ?></strong>
                </span>
              </div>
            </div>
            <div>
              <span class="badge-pill badge-pill-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                Penjualan Tercatat
              </span>
            </div>
          </div>

          <!-- Single / Grid Layout -->
          <div class="detail-grid">

            <!-- Left: Rincian Keuangan & Transaksi -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                  Rincian Transaksi Penjualan
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <tr>
                      <td class="col-label">Kode Transaksi</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['kode_data'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nama Produk / Jasa</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['produk']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Jumlah / Volume</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['jumlah']); ?> Item</td>
                    </tr>
                    <tr>
                      <td class="col-label">Harga Satuan</td>
                      <td class="col-value"><?php echo fmtRupiah($data['harga']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Total Pembayaran</td>
                      <td class="col-value" style="color:#10b981;font-size:16px;">
                        <?php echo fmtRupiah($data['total']); ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right: Ringkasan Nilai & Aksi -->
            <div>
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ringkasan Keuangan
                  </h3>
                </div>
                <div class="detail-card-body">
                  <div style="background:#f8faf9;border:1px solid #e0eae4;border-radius:10px;padding:18px;text-align:center;margin-bottom:16px;">
                    <div style="font-size:12px;font-weight:600;color:#6b8f7e;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;">Total Pendapatan Transaksi</div>
                    <div style="font-size:26px;font-weight:700;color:#1e3a2f;"><?php echo fmtRupiah($data['total']); ?></div>
                    <div style="font-size:12px;color:#7a9e8e;margin-top:4px;">(<?php echo htmlspecialchars($data['jumlah']); ?> item &times; <?php echo fmtRupiah($data['harga']); ?>)</div>
                  </div>

                  <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="editpenjualan.php?id=<?php echo urlencode($data['id']); ?>" class="btn-edit-detail" style="justify-content:center;">
                      <i class="fa fa-edit"></i> Ubah Data Penjualan
                    </a>
                    <a href="datapenjualanusaha.php" class="btn-back-detail" style="justify-content:center;">
                      <i class="fa fa-arrow-left"></i> Kembali ke Tabel Penjualan
                    </a>
                  </div>
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