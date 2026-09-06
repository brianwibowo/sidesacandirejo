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
  <!-- bootstrap-progressbar -->
  <link href="../assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* ── DASHBOARD ALIGNED WITH WEB BOOKING ── */
    .content-dashboard {
      padding: 0;
    }
    .card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    /* ── PAGE TITLE CARD ── */
    .page-title-card {
      margin-bottom: 22px;
      overflow: hidden;
    }
    .card-header-table {
      padding: 20px 24px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
      background: #fff;
    }
    .header-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #eef7f2;
      border: 1px solid #d2ebd9;
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 11px;
      font-weight: 600;
      color: #1e6b3c;
      margin-bottom: 6px;
    }
    .card-header-table h1 {
      font-size: 20px;
      font-weight: 600;
      color: #1e3a2f;
      margin: 0 0 4px 0;
      letter-spacing: -0.01em;
      font-family: inherit;
    }
    .card-header-table p {
      font-size: 13px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
      max-width: 680px;
    }
    .header-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-tambah-action {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #1e3a2f;
      color: #fff !important;
      border: none;
      border-radius: 8px;
      padding: 9px 18px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none !important;
      transition: background 0.15s, transform 0.1s;
    }
    .btn-tambah-action:hover {
      background: #2d5540;
      color: #fff !important;
    }
    .btn-secondary-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #f4fbf6;
      color: #1e3a2f !important;
      border: 1px solid #c8e4d3;
      border-radius: 8px;
      padding: 9px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none !important;
      transition: all 0.15s;
    }
    .btn-secondary-link:hover {
      background: #1e3a2f;
      color: #fff !important;
      border-color: #1e3a2f;
    }

    /* ── STAT CARDS GRID ── */
    .stat-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 22px;
    }
    @media (max-width: 1100px) {
      .stat-cards { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
      .stat-cards { grid-template-columns: 1fr; }
    }
    .stat-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      padding: 18px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-decoration: none !important;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
      transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
    }
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 14px rgba(30, 58, 47, 0.08);
      border-color: #b8d4c4;
    }
    .stat-label {
      font-size: 12.5px;
      color: #7a9e8e;
      margin-bottom: 4px;
      font-weight: 500;
    }
    .stat-value {
      font-size: 26px;
      font-weight: 700;
      color: #1e3a2f;
      line-height: 1.1;
    }
    .stat-sub {
      font-size: 11.5px;
      color: #9ab5a8;
      margin-top: 4px;
    }
    .stat-icon-wrap {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .stat-icon-wrap.blue   { background: #eaf3fb; color: #3c7abf; }
    .stat-icon-wrap.green  { background: #e4f5ec; color: #2e8a54; }
    .stat-icon-wrap.purple { background: #f3f0fd; color: #7c3aed; }
    .stat-icon-wrap.orange { background: #fdf0e0; color: #c0742a; }

    /* ── QUICK ACTIONS CARD ── */
    .quick-actions-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .card-header-quick {
      padding: 16px 24px 14px;
      border-bottom: 1px solid #f0f5f2;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px;
    }
    .card-header-quick h2 {
      font-size: 16px;
      font-weight: 600;
      color: #1e3a2f;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: inherit;
    }
    .quick-subtitle {
      font-size: 12.5px;
      color: #7a9e8e;
    }
    .quick-actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      padding: 18px 24px 22px;
    }
    @media (max-width: 1100px) {
      .quick-actions-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
      .quick-actions-grid { grid-template-columns: 1fr; }
    }
    .quick-btn {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      background: #fafcfb;
      border: 1px solid #e8f0ec;
      border-radius: 10px;
      text-decoration: none !important;
      transition: all 0.15s;
    }
    .quick-btn:hover {
      background: #f0f7f3;
      border-color: #b8d4c4;
      transform: translateY(-2px);
      box-shadow: 0 3px 10px rgba(30, 58, 47, 0.06);
    }
    .quick-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .quick-icon.blue   { background: #eaf3fb; color: #2563eb; }
    .quick-icon.green  { background: #e4f5ec; color: #10b981; }
    .quick-icon.purple { background: #f3f0fd; color: #8b5cf6; }
    .quick-icon.amber  { background: #fef3c7; color: #f59e0b; }
    .quick-text {
      flex: 1;
      min-width: 0;
    }
    .quick-text strong {
      display: block;
      font-size: 13.5px;
      font-weight: 600;
      color: #1e3a2f;
      line-height: 1.2;
      margin-bottom: 2px;
    }
    .quick-text span {
      display: block;
      font-size: 11.5px;
      color: #7a9e8e;
      line-height: 1.3;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .quick-arrow {
      color: #9ab5a8;
      flex-shrink: 0;
      transition: transform 0.15s, color 0.15s;
    }
    .quick-btn:hover .quick-arrow {
      color: #1e3a2f;
      transform: translateX(3px);
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
        <div class="content-dashboard">
          <?php
          include '../koneksi/koneksi.php';
          $sql1 = "SELECT COUNT(*) as c FROM tb_arsip_surat_masuk";
          $jumlah1 = mysqli_fetch_assoc(mysqli_query($db, $sql1))['c'];

          $sql2 = "SELECT COUNT(*) as c FROM tb_arsip_surat_keluar";
          $jumlah2 = mysqli_fetch_assoc(mysqli_query($db, $sql2))['c'];

          $sql3 = "SELECT COUNT(*) as c FROM tb_data_pengunjung";
          $jumlah3 = mysqli_fetch_assoc(mysqli_query($db, $sql3))['c'];

          $sql4 = "SELECT COUNT(*) as c FROM tb_data_mitra";
          $jumlah4 = mysqli_fetch_assoc(mysqli_query($db, $sql4))['c'];
          $nama_admin = isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Administrator';
          ?>

          <!-- Card Header Page Title -->
          <div class="card page-title-card">
            <div class="card-header-table">
              <div>
                <div class="header-badge">
                  <i class="fa fa-shield"></i>
                  Portal Administrasi Terpadu
                </div>
                <h1>Selamat Datang, <?php echo $nama_admin; ?>!</h1>
                <p>Sistem Informasi dan Manajemen Arsip Desa Wisata Candirejo. Kelola arsip persuratan, data pengunjung, kemitraan, dan pantau analitik wisata secara efisien.</p>
              </div>
              <div class="header-actions">
                <a href="analitik.php" class="btn-tambah-action">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                  Buka Analitik Data
                </a>
                <a href="../booking/booking_dashboard.php" target="_blank" class="btn-secondary-link" title="Buka Sistem Booking Candirejo">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Sistem Booking
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6m4-3h6v6m-11 5L21 3"/></svg>
                </a>
              </div>
            </div>
          </div>

          <!-- 4 Stat Cards (CSS Grid — identik dengan booking_dashboard.php) -->
          <div class="stat-cards">
            <!-- Surat Masuk -->
            <a href="datasuratmasuk.php" class="stat-card">
              <div>
                <div class="stat-label">Arsip Surat Masuk</div>
                <div class="stat-value"><?php echo number_format($jumlah1, 0, ',', '.'); ?></div>
                <div class="stat-sub">Total surat masuk diarsipkan</div>
              </div>
              <div class="stat-icon-wrap blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </a>

            <!-- Surat Keluar -->
            <a href="datasuratkeluar.php" class="stat-card">
              <div>
                <div class="stat-label">Arsip Surat Keluar</div>
                <div class="stat-value"><?php echo number_format($jumlah2, 0, ',', '.'); ?></div>
                <div class="stat-sub">Total surat keluar diarsipkan</div>
              </div>
              <div class="stat-icon-wrap green">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
              </div>
            </a>

            <!-- Data Pengunjung -->
            <a href="datapengunjung.php" class="stat-card">
              <div>
                <div class="stat-label">Data Pengunjung Wisata</div>
                <div class="stat-value"><?php echo number_format($jumlah3, 0, ',', '.'); ?></div>
                <div class="stat-sub">Total sesi kunjungan wisata</div>
              </div>
              <div class="stat-icon-wrap purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
            </a>

            <!-- Data Mitra -->
            <a href="datamitra.php" class="stat-card">
              <div>
                <div class="stat-label">Kemitraan Aktif</div>
                <div class="stat-value"><?php echo number_format($jumlah4, 0, ',', '.'); ?></div>
                <div class="stat-sub">Total data mitra terdaftar</div>
              </div>
              <div class="stat-icon-wrap orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </a>
          </div>

          <!-- Quick Action Shortcuts Card -->
          <div class="quick-actions-card">
            <div class="card-header-quick">
              <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#10b981;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Aksi Cepat Administrasi
              </h2>
              <span class="quick-subtitle">Pintasan ke fitur yang sering digunakan</span>
            </div>
            <div class="quick-actions-grid">
              <a href="inputsuratmasuk.php" class="quick-btn">
                <div class="quick-icon blue">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div class="quick-text">
                  <strong>Arsipkan Surat Masuk</strong>
                  <span>Tambah arsip surat masuk baru</span>
                </div>
                <svg class="quick-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
              </a>
              <a href="inputsuratkeluar.php" class="quick-btn">
                <div class="quick-icon green">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                  </svg>
                </div>
                <div class="quick-text">
                  <strong>Arsipkan Surat Keluar</strong>
                  <span>Tambah arsip surat keluar baru</span>
                </div>
                <svg class="quick-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
              </a>
              <a href="inputdatapengunjung.php" class="quick-btn">
                <div class="quick-icon purple">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                  </svg>
                </div>
                <div class="quick-text">
                  <strong>Tambah Pengunjung</strong>
                  <span>Input data kunjungan wisata baru</span>
                </div>
                <svg class="quick-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
              </a>
              <a href="analitik.php" class="quick-btn">
                <div class="quick-icon amber">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                </div>
                <div class="quick-text">
                  <strong>Analitik &amp; Grafik Wisata</strong>
                  <span>Lihat laporan dan statistik data</span>
                </div>
                <svg class="quick-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
              </a>
            </div>
          </div>

        </div>
      </div>
      <!-- /page content -->

      <footer>
        <div class="pull-right">
          Apriansyah Wibowo. All Rights Reserved.
        </div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <!-- jQuery (sekali saja) -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- Update online status (fetch, tanpa duplikat) -->
  <script>
    function updateOnlineStatus() {
      fetch("update_status.php")
        .then(response => console.log("status updated"))
        .catch(error => console.log(error));
    }
    setInterval(updateOnlineStatus, 30000);
  </script>
</body>

</html>