<?php
session_start();
include "login/ceksession.php";
include "../koneksi/koneksi.php";

$id = mysqli_real_escape_string($db, $_SESSION['id']);
$sql = "SELECT * FROM tb_admin WHERE id_admin='$id'";
$query = mysqli_query($db, $sql);
$data = mysqli_fetch_array($query);

if (!$data) {
    header("Location: index.php");
    exit();
}

// Data counts for statistics
$q_sm = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_arsip_surat_masuk");
$c_sm = $q_sm ? (int)mysqli_fetch_assoc($q_sm)['total'] : 0;

$q_sk = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_arsip_surat_keluar");
$c_sk = $q_sk ? (int)mysqli_fetch_assoc($q_sk)['total'] : 0;

$q_pg = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_data_pengunjung");
$c_pg = $q_pg ? (int)mysqli_fetch_assoc($q_pg)['total'] : 0;

$q_bk = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_booking");
$c_bk = $q_bk ? (int)mysqli_fetch_assoc($q_bk)['total'] : 0;

$nama_admin     = htmlspecialchars($data['nama_admin'] ?? 'Administrator');
$username_admin = htmlspecialchars($data['username_admin'] ?? 'admin');
$id_admin       = htmlspecialchars($data['id_admin'] ?? '-');
$role_admin     = strtolower($data['role'] ?? 'admin');
$is_superadmin  = ($role_admin === 'superadmin');
$avatar_file    = $data['gambar'] ?? '';
$has_avatar     = (!empty($avatar_file) && file_exists(__DIR__ . "/images/" . $avatar_file));

// Format last active
$last_active = $data['last_active'] ?? null;
$online_text = "Online (Sesi Ini)";
$online_color = "#10b981";
$last_active_formatted = "Saat ini aktif";
if (!empty($last_active)) {
    $last_active_formatted = date('d M Y, H:i', strtotime($last_active)) . " WIB";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya - Desa Wisata Candirejo</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Vendors -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f7f5;
      color: #1e3a2f;
      min-height: 100vh;
    }

    /* Content Layout */
    .booking-content {
      margin-left: 240px;
      padding-top: 58px;
      min-height: 100vh;
      transition: margin-left 0.25s;
    }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 32px 48px; max-width: 1280px; margin: 0 auto; }

    /* Page Header Card */
    .profile-header-card {
      background: #ffffff;
      border: 1px solid #e0ebe4;
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
      padding: 18px 24px;
      margin-bottom: 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
    }
    .profile-header-title h1 {
      font-family: 'Outfit', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: #142a20;
      margin: 0 0 4px 0;
      letter-spacing: -0.02em;
    }
    .profile-header-title p {
      font-size: 13.5px;
      color: #527967;
      margin: 0;
    }
    .profile-header-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-profile-nav {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 15px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #2d5540 !important;
      background: #ffffff;
      border: 1px solid #d8e5dd;
      text-decoration: none !important;
      transition: all 0.15s ease;
      box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .btn-profile-nav:hover {
      background: #f0f7f3;
      border-color: #bad3c3;
      color: #142a20 !important;
      transform: translateY(-1px);
    }
    .btn-profile-arsip {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 15px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #1e6b3c !important;
      background: #eef7f2;
      border: 1px solid #cce5d6;
      text-decoration: none !important;
      transition: all 0.15s ease;
    }
    .btn-profile-arsip:hover {
      background: #e0f2e8;
      border-color: #aed9be;
      color: #134e2a !important;
      transform: translateY(-1px);
    }
    .btn-edit-profile {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #ffffff !important;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: 1px solid #059669;
      text-decoration: none !important;
      transition: all 0.15s ease;
      box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
    }
    .btn-edit-profile:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
    }

    /* Hero Card */
    .profile-hero-card {
      background: #fff;
      border: 1px solid #e0ebe4;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(20, 42, 32, 0.05);
      margin-bottom: 24px;
    }
    .profile-hero-banner {
      height: 130px;
      background: linear-gradient(135deg, #142a20 0%, #1e3a2f 50%, #2e7d4f 100%);
      position: relative;
    }
    .profile-hero-banner::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.1) 1.2px, transparent 1.2px);
      background-size: 18px 18px;
      pointer-events: none;
    }
    .profile-hero-content {
      padding: 22px 24px 22px 24px;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      position: relative;
    }
    .profile-hero-left {
      display: flex;
      align-items: flex-end;
      gap: 20px;
      margin-top: -55px;
      flex-wrap: wrap;
    }
    .profile-avatar-wrap {
      position: relative;
      flex-shrink: 0;
    }
    .profile-avatar-img {
      width: 104px;
      height: 104px;
      border-radius: 50%;
      object-fit: cover;
      background: #fff;
      border: 4px solid #fff;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
      display: block;
    }
    .profile-avatar-fallback {
      width: 104px;
      height: 104px;
      border-radius: 50%;
      background: #2e7d4f;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      font-weight: 700;
      border: 4px solid #fff;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
    }
    .profile-status-indicator {
      position: absolute;
      bottom: 6px;
      right: 6px;
      width: 17px;
      height: 17px;
      border-radius: 50%;
      background: #10b981;
      border: 2.5px solid #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .profile-hero-info {
      padding-bottom: 4px;
    }
    .profile-hero-name {
      font-family: 'Outfit', sans-serif;
      font-size: 22px;
      font-weight: 700;
      color: #142a20;
      margin: 0 0 6px 0;
      line-height: 1.2;
    }
    .profile-badges-row {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .badge-username-tag {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      background: #eef5f1;
      color: #2d5540;
      border: 1px solid #d4e5dc;
    }
    .badge-role-tag {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      background: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }
    .badge-status-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }
    .status-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #10b981;
    }
    .profile-hero-buttons {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-logout-profile {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 9px;
      font-size: 13px;
      font-weight: 600;
      color: #dc2626;
      background: #fef2f2;
      border: 1px solid #fecaca;
      text-decoration: none;
      transition: all 0.18s ease;
    }
    .btn-logout-profile:hover {
      background: #fee2e2;
      border-color: #fca5a5;
      color: #b91c1c;
    }

    /* Stats Grid */
    .profile-stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .profile-stat-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      padding: 18px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
      transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .profile-stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(20, 42, 32, 0.06);
    }
    .stat-label {
      font-size: 12.5px;
      font-weight: 600;
      color: #6b8f7e;
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    .stat-val {
      font-family: 'Outfit', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: #142a20;
      line-height: 1.1;
    }
    .profile-stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .stat-icon-purple { background: #f3e8ff; color: #7e22ce; }
    .stat-icon-emerald { background: #d1fae5; color: #047857; }
    .stat-icon-amber { background: #fef3c7; color: #b45309; }
    .stat-icon-blue { background: #e0f2fe; color: #0369a1; }

    /* Main Details Grid */
    .profile-details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
      gap: 20px;
    }
    @media (max-width: 900px) {
      .profile-details-grid { grid-template-columns: 1fr; }
    }
    .profile-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 14px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .profile-card-header {
      padding: 16px 22px;
      border-bottom: 1px solid #edf3f0;
      background: #fafcfb;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .header-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: #eef5f1;
      color: #2d5540;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .profile-card-header h2 {
      font-family: 'Outfit', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: #142a20;
      margin: 0;
    }
    .profile-card-body {
      padding: 22px;
      flex: 1;
    }
    .info-list-rows {
      display: flex;
      flex-direction: column;
    }
    .info-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #f0f5f2;
      font-size: 13.5px;
    }
    .info-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }
    .info-item:first-child {
      padding-top: 0;
    }
    .info-label {
      color: #527967;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .info-label svg {
      color: #8bb19e;
      flex-shrink: 0;
    }
    .info-value {
      font-weight: 600;
      color: #142a20;
      text-align: right;
    }

    /* Permission items */
    .permission-items-wrap {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .permission-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px 14px;
      background: #fbfdfc;
      border: 1px solid #eef3f0;
      border-radius: 10px;
    }
    .permission-icon {
      width: 28px;
      height: 28px;
      border-radius: 6px;
      background: #ecfdf5;
      color: #059669;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .permission-text h4 {
      font-size: 13.5px;
      font-weight: 600;
      color: #142a20;
      margin: 0 0 2px 0;
    }
    .permission-text p {
      font-size: 12.5px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
    }

    /* Security Notice */
    .security-notice-box {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 10px;
      padding: 14px 16px;
      margin-top: 18px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .security-notice-box svg {
      color: #16a34a;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .security-notice-text {
      font-size: 12.5px;
      color: #166534;
      line-height: 1.5;
    }
    .security-notice-text strong {
      color: #14532d;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      <!-- Sidebar Menu -->
      <?php include("sidebarmenu.php"); ?>
      <!-- /Sidebar Menu -->

      <!-- Top Navigation -->
      <?php include("header.php"); ?>
      <!-- /Top Navigation -->

      <!-- Page Content -->
      <div class="right_col" role="main">
        <div class="profile-page-wrapper">

          <!-- Page Header -->
                <div class="profile-header-card">
        <div class="profile-header-title">
          <h1>Profil Administrator</h1>
          <p>Informasi detail akun, kredensial, dan hak akses Sistem Booking Desa Wisata Candirejo</p>
        </div>
        <div class="profile-header-actions">
          <a href="booking_dashboard.php" class="btn-profile-nav" title="Dashboard Booking">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <span>Dashboard Booking</span>
          </a>

          <a href="../admin/index.php" class="btn-profile-arsip" title="Dashboard Arsip">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
            </svg>
            <span>Dashboard Arsip</span>
          </a>

          <a href="editprofile.php" class="btn-edit-profile" title="Edit Profil">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
              <path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/>
              <path d="M3 21h18"/>
            </svg>
            <span>Edit Profil</span>
          </a>
        </div>
      </div>

          <!-- Hero Profile Card -->
          <div class="profile-hero-card">
            <div class="profile-hero-banner"></div>
            <div class="profile-hero-content">
              <div class="profile-hero-left">
                <div class="profile-avatar-wrap">
                  <?php if ($has_avatar): ?>
                    <img src="images/<?php echo htmlspecialchars($avatar_file); ?>" alt="Avatar" class="profile-avatar-img">
                  <?php else: ?>
                    <div class="profile-avatar-fallback">
                      <?php echo strtoupper(substr($nama_admin, 0, 1)); ?>
                    </div>
                  <?php endif; ?>
                  <span class="profile-status-indicator" title="Status: Online"></span>
                </div>
                <div class="profile-hero-info">
                  <h2 class="profile-hero-name"><?php echo $nama_admin; ?></h2>
                  <div class="profile-badges-row">
                    <span class="badge-username-tag">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                      </svg>
                      <?php echo $username_admin; ?>
                    </span>
                    <?php if ($is_superadmin): ?>
                      <span class="badge-user-role badge-role-superadmin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                          <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Superadmin
                      </span>
                    <?php else: ?>
                      <span class="badge-user-role badge-role-admin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                          <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Administrator
                      </span>
                    <?php endif; ?>
                    <span class="badge-status-tag">
                      <span class="status-dot"></span>
                      Aktif
                    </span>
                  </div>
                </div>
              </div>
              <div class="profile-hero-buttons">
                <a href="editprofile.php" class="btn-edit-profile">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Ganti Foto &amp; Data
                </a>
                <a href="../koneksi/proses_logout.php" class="btn-logout-profile" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Keluar
                </a>
              </div>
            </div>
          </div>

          <!-- Quick Stats Row -->
          <div class="profile-stats-grid">
            <div class="profile-stat-card">
              <div class="profile-stat-info">
                <div class="stat-label">Surat Masuk</div>
                <div class="stat-val"><?php echo number_format($c_sm, 0, ',', '.'); ?></div>
              </div>
              <div class="profile-stat-icon stat-icon-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
              </div>
            </div>

            <div class="profile-stat-card">
              <div class="profile-stat-info">
                <div class="stat-label">Surat Keluar</div>
                <div class="stat-val"><?php echo number_format($c_sk, 0, ',', '.'); ?></div>
              </div>
              <div class="profile-stat-icon stat-icon-blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>

            <div class="profile-stat-card">
              <div class="profile-stat-info">
                <div class="stat-label">Data Pengunjung</div>
                <div class="stat-val"><?php echo number_format($c_pg, 0, ',', '.'); ?></div>
              </div>
              <div class="profile-stat-icon stat-icon-emerald">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
            </div>

            <div class="profile-stat-card">
              <div class="profile-stat-info">
                <div class="stat-label">Total Booking</div>
                <div class="stat-val"><?php echo number_format($c_bk, 0, ',', '.'); ?></div>
              </div>
              <div class="profile-stat-icon stat-icon-purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Main Details Grid -->
          <div class="profile-details-grid">
        
        <!-- Left Card: Informasi Akun -->
        <div class="profile-card">
          <div class="profile-card-header">
            <div class="header-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <h2>Detail Informasi Akun</h2>
          </div>
          <div class="profile-card-body">
            <div class="info-list-rows">
              <div class="info-item">
                <span class="info-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                  </svg>
                  ID Administrator
                </span>
                <span class="info-value">#<?php echo $id_admin; ?></span>
              </div>

              <div class="info-item">
                <span class="info-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Nama Lengkap
                </span>
                <span class="info-value"><?php echo $nama_admin; ?></span>
              </div>

              <div class="info-item">
                <span class="info-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                  Username
                </span>
                <span class="info-value">@<?php echo $username_admin; ?></span>
              </div>

              <div class="info-item">
                <span class="info-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  Tingkat Akses
                </span>
                <span class="info-value" style="color: #059669;">Administrator Sistem</span>
              </div>

              <div class="info-item">
                <span class="info-label">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Status Akun
                </span>
                <span class="info-value" style="color: #10b981;">Aktif &amp; Terverifikasi</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Card: Hak Akses Fitur Booking -->
        <div class="profile-card">
          <div class="profile-card-header">
            <div class="header-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h2>Hak Akses Sistem Booking</h2>
          </div>
          <div class="profile-card-body">
            <div class="permission-items-wrap">
              <div class="permission-item">
                <div class="permission-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
                <div class="permission-text">
                  <h4>Manajemen Booking &amp; Reservasi</h4>
                  <p>Membuat, menyunting status reservasi (Pending, Check-in, Batal), dan mencatat wisatawan.</p>
                </div>
              </div>

              <div class="permission-item">
                <div class="permission-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
                <div class="permission-text">
                  <h4>Kalender Jadwal Kunjungan</h4>
                  <p>Memantau kapasitas harian rombongan dan kalender kedatangan paket wisata Desa Candirejo.</p>
                </div>
              </div>

              <div class="permission-item">
                <div class="permission-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
                <div class="permission-text">
                  <h4>Rekapitulasi &amp; Cetak Laporan</h4>
                  <p>Mengakses filter periode, ekspor PDF/Excel, dan cetak laporan pendapatan paket wisata.</p>
                </div>
              </div>
            </div>

            <div class="security-notice-box">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
              <div class="security-notice-text">
                <strong>Pemberitahuan Keamanan:</strong> Sesi Anda terlindungi enkripsi. Pastikan selalu logout ketika selesai bertugas pada perangkat publik atau kantor.
              </div>
            </div>
          </div>
        </div>

      </div>

        </div>
      </div>
      <!-- /page content -->

      <!-- Footer Content -->
      <footer>
        <div class="pull-right">
          Sistem Informasi Desa Wisata Candirejo Borobudur
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /Footer Content -->

    </div>
  </div>

  <!-- Scripts -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>