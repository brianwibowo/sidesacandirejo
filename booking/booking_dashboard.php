<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

/* ── Admin login ─────────────────────────────────────────────────────────── */
$sql_admin   = "SELECT * FROM tb_admin WHERE id_admin='" . $_SESSION['id'] . "'";
$query_admin = mysqli_query($db, $sql_admin);
$admin_login = mysqli_fetch_array($query_admin);
$nama        = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';
$initials    = strtoupper(substr($nama, 0, 1));

/* ── Label paket ─────────────────────────────────────────────────────────── */
function labelPaket($kode) {
    $map = [
        'meal_only'                   => 'Breakfast/Lunch/Dinner Only',
        'studi_banding'               => 'Studi Banding',
        'fun_game'                    => 'Paket Fun Game',
        'pelajar_live_in'             => 'Pelajar – Live In',
        'pelajar_field_trip_one_day'  => 'Pelajar – Field Trip One Day',
        'pelajar_field_trip_half_day' => 'Pelajar – Field Trip Half Day',
        'cycling_tour'                => 'Cycling Village Tour',
        'traditional_dance'           => 'Traditional Dance',
        'walking_tour'                => 'Walking Around Village',
        'homestay'                    => 'Homestay',
        'serenade'                    => 'Serenade Menoreh',
        'cooking_lesson'              => 'Cooking Lesson',
        'gamelan_class'               => 'Gamelan Class',
        'village_experience'          => 'Village Experience',
        'dokar_tour'                  => 'Dokar Village Tour',
        'inspection'                  => 'Inspection',
        'lainnya'                     => 'Lainnya',
    ];
    return isset($map[$kode]) ? $map[$kode] : ucwords(str_replace('_', ' ', $kode));
}

/* ── Query: booking hari ini & besok ─────────────────────────────────────── */
$today    = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$q_hari_ini = mysqli_query($db,
    "SELECT * FROM tb_booking WHERE tanggal_kunjungan = '$today' ORDER BY id DESC");
$booking_hari_ini = [];
while ($r = mysqli_fetch_assoc($q_hari_ini)) $booking_hari_ini[] = $r;

$q_besok = mysqli_query($db,
    "SELECT * FROM tb_booking WHERE tanggal_kunjungan = '$tomorrow' AND status = 'pending' ORDER BY id ASC");
$booking_besok = [];
while ($r = mysqli_fetch_assoc($q_besok)) $booking_besok[] = $r;

/* ── Stat counts ─────────────────────────────────────────────────────────── */
$total_booking = count($booking_hari_ini);
$total_checkin = count(array_filter($booking_hari_ini, fn($r) => $r['status'] === 'checkin'));
$total_pending = count(array_filter($booking_hari_ini, fn($r) => $r['status'] === 'pending'));
$total_pax     = array_sum(array_column($booking_hari_ini, 'pax'));

$bulan_id = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f7f5; color: #1e3a2f; min-height: 100vh; }

    /* ── CONTENT ── */
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 28px 40px; }
    /* ── CARD GLOBAL ── */
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.03); }

    /* ── PAGE TITLE CARD ── */
    .page-title-card {
      margin-bottom: 22px;
      overflow: hidden;
    }
    .card-header-table {
      padding: 18px 24px 16px;
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
    }
    .card-header-table p {
      font-size: 13px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
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
      gap: 7px;
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

    /* ── STAT CARDS GRID (Identik Web Arsip) ── */
    .stat-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 22px;
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
    .stat-icon-wrap.orange { background: #fdf0e0; color: #c0742a; }
    .stat-icon-wrap.purple { background: #f3f0fd; color: #7c3aed; }

    /* ── TOMBOL TAMBAH + SEARCH BAR ── */
    .top-actions { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .btn-tambah { display: inline-flex; align-items: center; gap: 8px; background: #1e3a2f; color: #fff; border: none; border-radius: 8px; padding: 11px 20px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background 0.15s; flex-shrink: 0; }
    .btn-tambah:hover { background: #2d5540; color: #fff; }
    .search-wrap { position: relative; flex: 1; max-width: 320px; }
    .search-wrap svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #9ab5a8; pointer-events: none; }
    .search-input { width: 100%; border: 1px solid #d6e6dc; border-radius: 8px; padding: 10px 14px 10px 36px; font-size: 13.5px; color: #1e3a2f; background: #fff; outline: none; transition: border-color 0.15s; }
    .search-input::placeholder { color: #aec9b8; }
    .search-input:focus { border-color: #2e7d4f; }

    /* ── TOMBOL AKSI IKON ── */
    .btn-action-wrap { display: flex; gap: 5px; align-items: center; }
    .btn-icon {
      position: relative; width: 28px; height: 28px; border: none; border-radius: 7px;
      display: inline-flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background 0.15s, transform 0.1s; flex-shrink: 0;
      text-decoration: none;
    }
    .btn-icon:active { transform: scale(0.93); }
    .btn-icon svg { display: block; }
    .btn-checkin    { background: #e4f5ec; color: #2e7d4f; }
    .btn-checkin:hover { background: #2e7d4f; color: #fff; }
    .btn-tidakhadir { background: #fceaea; color: #c0392b; }
    .btn-tidakhadir:hover { background: #c0392b; color: #fff; }
    .btn-edit { background: #eef3ff; color: #3b6fd4; }
    .btn-edit:hover { background: #3b6fd4; color: #fff; }
    .btn-detail { background: #f0f4f8; color: #486581; }
    .btn-detail:hover { background: #486581; color: #fff; }

    /* tooltip — muncul ke atas dengan segitiga & z-index tinggi */
    tbody tr { position: relative; z-index: 1; }
    tbody td { position: relative; }
    tbody tr:hover { position: relative; z-index: 200; }
    tbody tr:hover td { position: relative; z-index: 200; }
    tbody tr:hover .btn-action-wrap { position: relative; z-index: 210; }
    tbody tr:hover .btn-icon:hover { position: relative; z-index: 250; }
    tbody tr:hover .btn-icon:hover::after,
    tbody tr:hover .btn-icon:hover::before { z-index: 999999; }
    .btn-action-wrap { position: relative; z-index: 2; }
    .btn-icon::after {
      content: attr(data-tooltip);
      position: absolute; bottom: calc(100% + 7px); right: 50%; transform: translateX(50%);
      background: #1e3a2f; color: #fff; font-size: 11px; font-weight: 500;
      padding: 4px 8px; border-radius: 5px; white-space: nowrap;
      opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 999999;
      box-shadow: 0 3px 8px rgba(0,0,0,0.22);
    }
    .btn-icon::before {
      content: ''; position: absolute; bottom: calc(100% + 2px); right: 50%; transform: translateX(50%);
      border: 5px solid transparent; border-top-color: #1e3a2f;
      opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 999999;
    }
    .btn-icon:last-child::after  { right: 0; transform: none; }
    .btn-icon:last-child::before { right: 9px; transform: none; }
    .btn-icon:hover::after, .btn-icon:hover::before { opacity: 1; }

    /* ── TABLE ── */
    .dashboard-grid { display: grid; grid-template-columns: 1fr 220px; gap: 20px; align-items: start; }
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: visible; }
    .card-header { padding: 14px 22px 12px; border-bottom: 1px solid #f0f5f2; border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
    .card-header h2 { font-size: 16px; font-weight: 600; color: #1e3a2f; }
    .card-header-right { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #7a9e8e; }
    .show-select { font-size: 12.5px; color: #1e3a2f; border: 1px solid #d6e6dc; border-radius: 6px; padding: 4px 8px; background: #fafcfb; cursor: pointer; outline: none; }
    .show-select:focus { border-color: #2e7d4f; }
    .table-wrapper { overflow: hidden; border-radius: 0 0 12px 12px; }
    .table-footer { padding: 10px 22px; border-top: 1px solid #f0f5f2; font-size: 12.5px; color: #9ab5a8; display: flex; align-items: center; justify-content: space-between; }
    table { width: 100%; border-collapse: collapse; }
    tbody td { padding: 12px 10px; font-size: 13px; color: #2a4535; border-bottom: 1px solid #f4f7f5; vertical-align: middle; }
    thead th { padding: 10px 10px; text-align: left; font-size: 11.5px; font-weight: 600; color: #7a9e8e; text-transform: uppercase; letter-spacing: 0.04em; background: #fafcfb; border-bottom: 1px solid #eff4f1; }
    td:nth-child(5), th:nth-child(5),
    td:nth-child(6), th:nth-child(6),
    td:nth-child(7), th:nth-child(7) { white-space: nowrap; width: 1%; }
    td:nth-child(2) { white-space: normal; word-break: break-word; max-width: 150px; }
    td:nth-child(1), td:nth-child(3), td:nth-child(4) { white-space: nowrap; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: #fafcfb; }
    .empty-row td { text-align: center; color: #9ab5a8; font-style: italic; padding: 30px; }
    .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
    .badge-pending      { background: #fdf0e0; color: #c0742a; }
    .badge-checkin      { background: #e4f5ec; color: #2e7d4f; }
    .badge-tidak_hadir  { background: #fceaea; color: #b03030; }

    /* ── BOOKING BESOK ── */
    .tomorrow-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: hidden; }
    .tomorrow-header { padding: 18px 20px 10px; border-bottom: 1px solid #f0f5f2; }
    .tomorrow-header h2 { font-size: 15px; font-weight: 600; color: #1e3a2f; }
    .tomorrow-header p  { font-size: 12.5px; color: #7a9e8e; margin-top: 2px; }
    .tomorrow-item { padding: 12px 20px; border-bottom: 1px solid #f4f7f5; }
    .tomorrow-item:last-child { border-bottom: none; }
    .tomorrow-item-name  { font-size: 13.5px; font-weight: 500; color: #1e3a2f; margin-bottom: 3px; }
    .tomorrow-item-paket { font-size: 12.5px; color: #7a9e8e; margin-bottom: 5px; }
    .tomorrow-item-pax   { display: flex; align-items: center; gap: 5px; font-size: 12.5px; color: #7a9e8e; }
    .tomorrow-empty      { padding: 24px 20px; font-size: 13px; color: #9ab5a8; font-style: italic; text-align: center; }

    .booking-footer { text-align: right; padding: 16px 28px; font-size: 12.5px; color: #9ab5a8; border-top: 1px solid #e5ede8; margin-top: 20px; }

    @media (max-width: 1100px) {
      .stat-cards { grid-template-columns: repeat(2,1fr); }
      .dashboard-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
      .stat-cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>
<?php include 'booking_header.php'; ?>

<!-- MAIN CONTENT -->
<main class="booking-content" id="bookingContent">
  <div class="content-inner">

    <?php
    $flash_map = [
      'sukses_tambah'   => ['✅', 'Booking baru berhasil disimpan.', '#e4f5ec', '#1e6b3c', '#b5dfc5'],
      'sukses_edit'     => ['✅', 'Data booking berhasil diperbarui.', '#e4f5ec', '#1e6b3c', '#b5dfc5'],
      'tidak_bisa_edit' => ['⚠️', 'Booking yang sudah check-in/tidak hadir tidak bisa diedit.', '#fdf0e0', '#7a4a00', '#e8cc8a'],
      'gagal'           => ['❌', htmlspecialchars($_GET['detail'] ?? 'Terjadi kesalahan.'), '#fceaea', '#8b2020', '#e8a0a0'],
    ];
    if (isset($_GET['msg']) && isset($flash_map[$_GET['msg']])):
      [$ico, $txt, $bg, $clr, $bdr] = $flash_map[$_GET['msg']];
    ?>
    <div id="flashMsg" style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-radius:10px;font-size:13.5px;margin-bottom:18px;background:<?php echo $bg;?>;color:<?php echo $clr;?>;border:1px solid <?php echo $bdr;?>;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
      <span><?php echo $ico . ' ' . $txt; ?></span>
      <button onclick="document.getElementById('flashMsg').remove()" style="background:none;border:none;cursor:pointer;font-size:16px;color:inherit;padding:0 4px;">✕</button>
    </div>
    <?php endif; ?>

    <!-- Card Header Page Title -->
    <div class="card page-title-card">
      <div class="card-header-table">
        <div>
          <div class="header-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Sistem Booking Candirejo
          </div>
          <h1>Dashboard Sistem Booking</h1>
          <p>Ringkasan reservasi dan kunjungan wisatawan hari ini &mdash; <strong><?php echo date('j') . ' ' . $bulan_id[(int)date('n')-1] . ' ' . date('Y'); ?></strong></p>
        </div>
        <div class="header-actions">
          <a href="../admin/index.php" target="_blank" class="btn-secondary-link" title="Buka Portal Arsip Surat Candirejo">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Portal Arsip <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6m4-3h6v6m-11 5L21 3"/></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- 4 Stat Cards (Identik Web Arsip) -->
    <div class="stat-cards">
      <!-- Total Booking Hari Ini -->
      <a href="booking_semua.php" class="stat-card">
        <div>
          <div class="stat-label">Total Booking Hari Ini</div>
          <div class="stat-value"><?php echo number_format($total_booking, 0, ',', '.'); ?></div>
          <div class="stat-sub">Total reservasi jadwal hari ini</div>
        </div>
        <div class="stat-icon-wrap blue">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </a>

      <!-- Sudah Check-in -->
      <a href="booking_checkin.php" class="stat-card">
        <div>
          <div class="stat-label">Sudah Check-in</div>
          <div class="stat-value"><?php echo number_format($total_checkin, 0, ',', '.'); ?></div>
          <div class="stat-sub">Wisatawan sudah tiba di lokasi</div>
        </div>
        <div class="stat-icon-wrap green">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </a>

      <!-- Belum Datang (Pending) -->
      <a href="booking_pending.php" class="stat-card">
        <div>
          <div class="stat-label">Belum Datang (Pending)</div>
          <div class="stat-value"><?php echo number_format($total_pending, 0, ',', '.'); ?></div>
          <div class="stat-sub">Menunggu kedatangan wisatawan</div>
        </div>
        <div class="stat-icon-wrap orange">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
          </svg>
        </div>
      </a>

      <!-- Total Pax Hari Ini -->
      <a href="booking_semua.php" class="stat-card">
        <div>
          <div class="stat-label">Total Wisatawan (Pax)</div>
          <div class="stat-value"><?php echo number_format($total_pax, 0, ',', '.'); ?></div>
          <div class="stat-sub">Total orang berkunjung hari ini</div>
        </div>
        <div class="stat-icon-wrap purple">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
      </a>
    </div>

    <div class="top-actions">
      <a href="tambah_booking.php" class="btn-tambah">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
        Tambah Booking
      </a>
      <div class="search-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari agen wisata, paket, driver, atau asal..." oninput="updateTable()">
      </div>
    </div>

    <div class="dashboard-grid">

      <!-- Tabel Booking Hari Ini -->
      <div class="card">
        <div class="card-header">
          <h2>Booking Hari Ini</h2>
          <div class="card-header-right">
            <label for="showPerPage">Tampilkan</label>
            <select id="showPerPage" class="show-select" onchange="updateTable()">
              <option value="10">10</option>
              <option value="15" selected>15</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="999">Semua</option>
            </select>
            <span>data</span>
          </div>
        </div>
        <div class="table-wrapper">
          <table id="tabelHariIni">
            <thead>
              <tr>
                <th>Agen Wisata</th>
                <th>Paket</th>
                <th>Asal</th>
                <th>Driver / Agent Guide</th>
                <th>Pax</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($booking_hari_ini)): ?>
              <tr class="empty-row"><td colspan="7">Belum ada booking untuk hari ini</td></tr>
              <?php else: foreach ($booking_hari_ini as $row):
                $status       = $row['status'];
                $badgeClass   = 'badge-' . ($status === 'tidak_hadir' ? 'tidak_hadir' : $status);
                $badgeLabel   = $status === 'checkin' ? 'Check-in' : ($status === 'pending' ? 'Pending' : 'Tidak Hadir');
                $asal         = $row['jenis_wisatawan'] === 'Domestik'
                              ? htmlspecialchars($row['kota'] ?? '-')
                              : htmlspecialchars($row['negara'] ?? '-');
                $nama_agen    = (!empty(trim((string)($row['agen_wisata'] ?? ''))) && strtolower(trim((string)$row['agen_wisata'])) !== 'null')
                              ? htmlspecialchars($row['agen_wisata'])
                              : '-';
                $opsi_makan   = '';
                if (!empty($row['opsi_makan_tour'])) {
                  if ($row['opsi_makan_tour'] === 'with_lunch') {
                    $opsi_makan = 'With Lunch';
                  } elseif ($row['opsi_makan_tour'] === 'without_lunch') {
                    $opsi_makan = 'Without Lunch';
                  } else {
                    $opsi_makan = ucwords(str_replace('_', ' ', $row['opsi_makan_tour']));
                  }
                }
                $raw_driver   = trim((string)($row['driver_agent_guide'] ?? ''));
                $driver_guide = (!empty($raw_driver) && strtolower($raw_driver) !== 'null' && strtolower($raw_driver) !== 'belum ada')
                              ? htmlspecialchars($raw_driver)
                              : 'Belum ada';
              ?>
              <tr class="data-row">
                <td><strong><?php echo $nama_agen; ?></strong></td>
                <td>
                  <div><?php echo labelPaket($row['pilihan_paket_wisata']); ?></div>
                  <?php if (!empty($opsi_makan)): ?>
                    <div style="font-size:11.5px;color:#7a9e8e;margin-top:2px;font-weight:500;"><?php echo $opsi_makan; ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="font-size:11.5px;color:#7a9e8e;"><?php echo $row['jenis_wisatawan']; ?></span><br>
                  <span><?php echo $asal; ?></span>
                </td>
                <td>
                  <?php if ($driver_guide === 'Belum ada'): ?>
                    <span style="color:#9ab5a8;font-style:italic;font-size:12.5px;">Belum ada</span>
                  <?php else: ?>
                    <span><?php echo $driver_guide; ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo $row['pax']; ?></td>
                <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span></td>
                <td>
                  <div class="btn-action-wrap">
                    <a href="detail_booking.php?id=<?php echo $row['id']; ?>&ref=booking_dashboard.php" class="btn-icon btn-detail" data-tooltip="Lihat Detail">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <?php if ($status === 'pending'): ?>
                    <button class="btn-icon btn-checkin" data-tooltip="Check-in"
                      onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'checkin', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                    <button class="btn-icon btn-tidakhadir" data-tooltip="Tidak Hadir"
                      onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'tidak_hadir', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <a href="edit_booking.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-edit" data-tooltip="Edit Booking">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                    </a>
                    <?php elseif ($status === 'checkin' && $row['checkin_at']): ?>
                      <span style="font-size:12px;color:#7a9e8e;white-space:nowrap;">✓ <?php echo date('H:i', strtotime($row['checkin_at'])); ?></span>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
        <?php if (!empty($booking_hari_ini)): ?>
        <div class="table-footer">
          <span>Menampilkan <strong id="infoShown"><?php echo min(15, count($booking_hari_ini)); ?></strong> dari <strong><?php echo count($booking_hari_ini); ?></strong> data</span>
        </div>
        <?php endif; ?>
      </div>

      <!-- Booking Besok -->
      <div class="tomorrow-card">
        <div class="tomorrow-header">
          <h2>Booking Besok</h2>
          <p><?php
            $besok_ts = strtotime('+1 day');
            echo date('j', $besok_ts) . ' ' . $bulan_id[(int)date('n', $besok_ts) - 1] . ' ' . date('Y', $besok_ts);
          ?></p>
        </div>
        <?php if (empty($booking_besok)): ?>
          <div class="tomorrow-empty">Belum ada booking untuk besok</div>
        <?php else: foreach ($booking_besok as $b):
          $nama_agen_b = (!empty(trim((string)($b['agen_wisata'] ?? ''))) && strtolower(trim((string)$b['agen_wisata'])) !== 'null')
                        ? htmlspecialchars($b['agen_wisata'])
                        : '-';
        ?>
        <div class="tomorrow-item" style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
          <div style="flex:1;min-width:0;">
            <div class="tomorrow-item-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><strong><?php echo $nama_agen_b; ?></strong></div>
            <div class="tomorrow-item-paket"><?php echo labelPaket($b['pilihan_paket_wisata']); ?></div>
            <div class="tomorrow-item-pax">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <?php echo $b['pax']; ?> pax
            </div>
          </div>
          <a href="detail_booking.php?id=<?php echo $b['id']; ?>&ref=booking_dashboard.php" class="btn-icon btn-detail" data-tooltip="Lihat Detail" style="flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </a>
        </div>
        <?php endforeach; endif; ?>
      </div>

    </div>
  </div>
  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<!-- Form tersembunyi untuk submit aksi -->
<form id="formAksi" action="proses/proses_checkin.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdBooking">
  <input type="hidden" name="aksi"       id="inputAksi">
</form>

<script>
function updateTable() {
  const n = parseInt(document.getElementById('showPerPage').value);
  const keyword = (document.getElementById('searchInput').value || '').toLowerCase().trim();
  const rows = document.querySelectorAll('#tabelHariIni tbody .data-row');
  let shown = 0;
  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    const matchSearch = keyword === '' || text.includes(keyword);
    if (matchSearch && shown < n) { row.style.display = ''; shown++; }
    else { row.style.display = 'none'; }
  });
  const info = document.getElementById('infoShown');
  if (info) info.textContent = shown;
}
// Init on load
document.addEventListener('DOMContentLoaded', updateTable);

function konfirmasiAksi(id, aksi, nama) {
  const isCheckin = aksi === 'checkin';
  Swal.fire({
    title: isCheckin ? 'Konfirmasi Check-in' : 'Tandai Tidak Hadir',
    html: `<p style="font-size:15px;color:#555;">
      ${isCheckin
        ? 'Check-in untuk <strong>' + nama + '</strong>?<br><small style="color:#9ab5a8;margin-top:6px;display:block;">Data otomatis masuk ke Data Pengunjung. Foto bisa ditambahkan nanti oleh admin.</small>'
        : 'Tandai <strong>' + nama + '</strong> sebagai tidak hadir?'}
    </p>`,
    icon: isCheckin ? 'question' : 'warning',
    iconColor: isCheckin ? '#2e7d4f' : '#c0392b',
    showCancelButton: true,
    confirmButtonText: isCheckin ? 'Ya, Check-in' : 'Ya, Tidak Hadir',
    cancelButtonText: 'Batal',
    confirmButtonColor: isCheckin ? '#2e7d4f' : '#c0392b',
    cancelButtonColor: '#aaa',
  }).then(result => {
    if (result.isConfirmed) {
      document.getElementById('inputIdBooking').value = id;
      document.getElementById('inputAksi').value      = aksi;
      document.getElementById('formAksi').submit();
    }
  });
}
</script>
</body>
</html>