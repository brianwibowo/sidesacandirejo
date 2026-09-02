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

/* ── Label detail sub-opsi paket ─────────────────────────────────────────── */
function labelDetailPaket($row) {
    $kode    = $row['pilihan_paket_wisata'];
    $detail  = [];

    if (in_array($kode, ['cycling_tour','dokar_tour','walking_tour'])) {
        $opsi = $row['opsi_makan_tour'] ?? '';
        if ($opsi === 'with_lunch')        $detail[] = 'With Lunch';
        elseif ($opsi === 'without_lunch') $detail[] = 'Without Lunch';
    }

    if ($kode === 'meal_only') {
        $jenis = $row['jenis_makanan_paket'] ?? '';
        $jMap  = ['breakfast'=>'Breakfast','lunch'=>'Lunch','dinner'=>'Dinner'];
        if (isset($jMap[$jenis])) $detail[] = $jMap[$jenis];
    }

    if ($kode === 'cooking_lesson') {
        $opsi = $row['opsi_cooking_lesson'] ?? '';
        if ($opsi === 'lesson_only')           $detail[] = 'Lesson Only';
        elseif ($opsi === 'lesson_with_tour')  $detail[] = 'with Tour';
    }

    if ($kode === 'gamelan_class') {
        $opsi = $row['opsi_gamelan'] ?? '';
        if ($opsi === 'with_lunch')        $detail[] = 'With Lunch';
        elseif ($opsi === 'without_lunch') $detail[] = 'Without Lunch';
    }

    return implode(', ', $detail);
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
$hari_id  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
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

    /* ── PAGE TITLE + DATE BADGE ── */
    .page-title { margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-title h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title p  { font-size: 13.5px; color: #6b8f7e; }
    .date-badge {
      display: inline-flex; align-items: center; gap: 10px;
      background: linear-gradient(135deg, #1e3a2f 0%, #2e7d4f 100%);
      color: #fff; border-radius: 12px; padding: 10px 18px;
      box-shadow: 0 4px 14px rgba(46,125,79,0.30);
      flex-shrink: 0;
    }
    .date-badge-icon { opacity: 0.80; flex-shrink: 0; }
    .date-badge-day  { font-size: 28px; font-weight: 700; line-height: 1; }
    .date-badge-right { display: flex; flex-direction: column; gap: 2px; }
    .date-badge-monthyear { font-size: 12px; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: .06em; }
    .date-badge-dayname  { font-size: 11px; opacity: 0.60; letter-spacing: .04em; }

    /* ── STAT CARDS ── */
    .stat-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; }
    .stat-label { font-size: 12.5px; color: #7a9e8e; margin-bottom: 6px; }
    .stat-value { font-size: 26px; font-weight: 600; color: #1e3a2f; line-height: 1; }
    .stat-icon-wrap { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon-wrap.blue   { background: #eaf3fb; color: #3c7abf; }
    .stat-icon-wrap.green  { background: #e4f5ec; color: #2e8a54; }
    .stat-icon-wrap.orange { background: #fdf0e0; color: #c0742a; }
    .stat-icon-wrap.gray   { background: #f1f4f2; color: #6b8f7e; }

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

    /* tooltip */
    .btn-icon::after {
      content: attr(data-tooltip);
      position: absolute; top: calc(100% + 7px); left: 50%; transform: translateX(-50%);
      background: #1e3a2f; color: #fff; font-size: 11.5px; font-weight: 500;
      padding: 4px 9px; border-radius: 6px; white-space: nowrap;
      opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 999;
    }
    .btn-icon::before {
      content: ''; position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%);
      border: 5px solid transparent; border-bottom-color: #1e3a2f;
      opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 999;
    }
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
    td:nth-child(6), th:nth-child(6) { white-space: nowrap; width: 1%; }
    td:nth-child(4), th:nth-child(4) { white-space: nowrap; width: 1%; text-align: center; }
    td:nth-child(1) { white-space: normal; word-break: break-word; min-width: 140px; max-width: 220px; font-weight: 500; }
    td:nth-child(2) { white-space: normal; word-break: break-word; min-width: 160px; max-width: 230px; }
    td:nth-child(3) { white-space: nowrap; }
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
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>
<?php include 'booking_header.php'; ?>

<!-- MAIN CONTENT -->
<main class="booking-content" id="bookingContent">
  <div class="content-inner">

    <div class="page-title">
      <div>
        <h1>Dashboard</h1>
        <p>Ringkasan Booking Hari Ini</p>
      </div>
      <div class="date-badge">
        <div class="date-badge-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="date-badge-day"><?php echo date('j'); ?></div>
        <div class="date-badge-right">
          <div class="date-badge-monthyear"><?php echo $bulan_id[(int)date('n')-1] . ' ' . date('Y'); ?></div>
          <div class="date-badge-dayname"><?php echo $hari_id[(int)date('w')]; ?></div>
        </div>
      </div>
    </div>

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
    <div id="flashMsg" style="display:flex;align-items:center;justify-content:space-between;padding:11px 16px;border-radius:9px;font-size:13.5px;margin-bottom:18px;background:<?php echo $bg;?>;color:<?php echo $clr;?>;border:1px solid <?php echo $bdr;?>;">
      <span><?php echo $ico . ' ' . $txt; ?></span>
      <button onclick="document.getElementById('flashMsg').remove()" style="background:none;border:none;cursor:pointer;font-size:16px;color:inherit;padding:0 4px;">✕</button>
    </div>
    <?php endif; ?>

    <!-- Stat Cards -->
    <div class="stat-cards">
      <div class="stat-card">
        <div><div class="stat-label">Total Booking Hari Ini</div><div class="stat-value"><?php echo $total_booking; ?></div></div>
        <div class="stat-icon-wrap blue">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
      </div>
      <div class="stat-card">
        <div><div class="stat-label">Sudah Check-in</div><div class="stat-value"><?php echo $total_checkin; ?></div></div>
        <div class="stat-icon-wrap green">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
      <div class="stat-card">
        <div><div class="stat-label">Belum Datang (Pending)</div><div class="stat-value"><?php echo $total_pending; ?></div></div>
        <div class="stat-icon-wrap orange">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
      </div>
      <div class="stat-card">
        <div><div class="stat-label">Total Pax Hari Ini</div><div class="stat-value"><?php echo $total_pax; ?></div></div>
        <div class="stat-icon-wrap gray">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
      </div>
    </div>

    <div class="top-actions">
      <a href="tambah_booking.php" class="btn-tambah">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
        Tambah Booking
      </a>
      <div class="search-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama travel / agen wisata..." oninput="updateTable()">
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
                <th>Nama Travel / Agen</th>
                <th>Paket</th>
                <th>Asal</th>
                <th>Pax</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($booking_hari_ini)): ?>
              <tr class="empty-row"><td colspan="6">Belum ada booking untuk hari ini</td></tr>
              <?php else: foreach ($booking_hari_ini as $row):
                $status     = $row['status'];
                $badgeClass = 'badge-' . ($status === 'tidak_hadir' ? 'tidak_hadir' : $status);
                $badgeLabel = $status === 'checkin' ? 'Check-in' : ($status === 'pending' ? 'Pending' : 'Tidak Hadir');
                $asal       = $row['jenis_wisatawan'] === 'Domestik'
                            ? htmlspecialchars($row['kota'] ?? '-')
                            : htmlspecialchars($row['negara'] ?? '-');
              ?>
              <tr class="data-row">
                <td>
                  <?php
                    $agen = trim($row['agen_wisata'] ?? '');
                    echo htmlspecialchars($agen !== '' && strtolower($agen) !== '-' ? $agen : $row['nama']);
                  ?>
                </td>
                <td>
                  <?php
                    $namapaket   = labelPaket($row['pilihan_paket_wisata']);
                    $detailpaket = labelDetailPaket($row);
                  ?>
                  <span><?php echo $namapaket; ?></span>
                  <?php if ($detailpaket): ?>
                    <br><span style="font-size:11.5px;color:#7a9e8e;font-style:italic;"><?php echo htmlspecialchars($detailpaket); ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="font-size:11.5px;color:#7a9e8e;"><?php echo $row['jenis_wisatawan']; ?></span><br>
                  <span><?php echo $asal; ?></span>
                </td>
                <td><?php echo $row['pax']; ?></td>
                <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span></td>
                <td>
                  <?php if ($status === 'pending'): ?>
                  <div class="btn-action-wrap">
                    <button class="btn-icon btn-checkin" data-tooltip="Check-in"
                      onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'checkin', '<?php $lbl = trim($row['agen_wisata'] ?? ''); echo htmlspecialchars(($lbl !== '' && strtolower($lbl) !== '-') ? $lbl : $row['nama'], ENT_QUOTES); ?>')">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                    <button class="btn-icon btn-tidakhadir" data-tooltip="Tidak Hadir"
                      onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'tidak_hadir', '<?php $lbl = trim($row['agen_wisata'] ?? ''); echo htmlspecialchars(($lbl !== '' && strtolower($lbl) !== '-') ? $lbl : $row['nama'], ENT_QUOTES); ?>')">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <a href="edit_booking.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-edit" data-tooltip="Edit Booking">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                    </a>
                  </div>
                  <?php elseif ($status === 'checkin' && $row['checkin_at']): ?>
                    <span style="font-size:12px;color:#7a9e8e;white-space:nowrap;">✓ <?php echo date('H:i', strtotime($row['checkin_at'])); ?></span>
                  <?php endif; ?>
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
            $tgl_b = (int)date('j') + 1; $bln_b = (int)date('n') - 1; $thn_b = date('Y');
            echo $tgl_b . ' ' . $bulan_id[$bln_b] . ' ' . $thn_b;
          ?></p>
        </div>
        <?php if (empty($booking_besok)): ?>
          <div class="tomorrow-empty">Belum ada booking untuk besok</div>
        <?php else: foreach ($booking_besok as $b):
          $b_agen   = trim($b['agen_wisata'] ?? '');
          $b_label  = ($b_agen !== '' && strtolower($b_agen) !== '-') ? $b_agen : $b['nama'];
          $b_detail = labelDetailPaket($b);
        ?>
        <div class="tomorrow-item">
          <div class="tomorrow-item-name"><?php echo htmlspecialchars($b_label); ?></div>
          <div class="tomorrow-item-paket">
            <?php echo labelPaket($b['pilihan_paket_wisata']); ?>
            <?php if ($b_detail): ?><span style="color:#aec9b8;"> · <?php echo htmlspecialchars($b_detail); ?></span><?php endif; ?>
          </div>
          <div class="tomorrow-item-pax">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <?php echo $b['pax']; ?> pax
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>

    </div>
  </div>
  <div class="booking-footer">PTIK INTER UNNES'23. All Rights Reserved.</div>
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
    const tdAgen  = row.querySelector('td:first-child');
    const tdPaket = row.querySelector('td:nth-child(2)');
    const textAgen  = tdAgen  ? tdAgen.innerText.toLowerCase()  : '';
    const textPaket = tdPaket ? tdPaket.innerText.toLowerCase() : '';
    const matchSearch = keyword === '' || textAgen.includes(keyword) || textPaket.includes(keyword);
    if (matchSearch && shown < n) { row.style.display = ''; shown++; }
    else { row.style.display = 'none'; }
  });
  const info = document.getElementById('infoShown');
  if (info) info.textContent = shown;
}
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