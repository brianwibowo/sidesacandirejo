<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

/* ── Label paket ─────────────────────────────────────────────────────────── */
if (!function_exists('labelPaket')) {
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
}

/* ── Filter & Search ─────────────────────────────────────────────────────── */
$search   = isset($_GET['search'])  ? mysqli_real_escape_string($db, trim($_GET['search']))  : '';
$tanggal  = isset($_GET['tanggal']) ? mysqli_real_escape_string($db, trim($_GET['tanggal'])) : '';

$where = "WHERE status = 'tidak_hadir'";
if ($search !== '') {
    $where .= " AND (agen_wisata LIKE '%$search%' OR nama LIKE '%$search%' OR pilihan_paket_wisata LIKE '%$search%' OR driver_agent_guide LIKE '%$search%' OR local_guide LIKE '%$search%')";
}
if ($tanggal !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    $where .= " AND tanggal_kunjungan = '$tanggal'";
}

/* ── Pagination (Default: 10 baris agar pas satu layar tanpa scroll vertikal) ───── */
$allowed_per_page = [10, 15, 25, 50];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowed_per_page) ? (int)$_GET['per_page'] : 10;

$count_query = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_booking $where");
$count_row   = mysqli_fetch_assoc($count_query);
$total_data  = (int)($count_row['total'] ?? 0);

$total_pages = max(1, (int)ceil($total_data / $per_page));
$page = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

$sql_all = "SELECT * FROM tb_booking $where ORDER BY tanggal_kunjungan DESC, id DESC LIMIT $per_page OFFSET $offset";
$q_all   = mysqli_query($db, $sql_all);
$bookings = [];
while ($r = mysqli_fetch_assoc($q_all)) $bookings[] = $r;

if (!function_exists('tglIndo')) {
    $bulan_id = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    function tglIndo($date) {
        global $bulan_id;
        if (!$date) return '-';
        $ts = strtotime($date);
        return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

if (!function_exists('getPageUrl')) {
    function getPageUrl($p) {
        $params = $_GET;
        $params['page'] = $p;
        return '?' . http_build_query($params);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Tidak Datang - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f7f5; color: #1e3a2f; min-height: 100vh;
    }
    h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }

    /* ── LAYOUT ── */
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 18px 24px 16px; }

    /* ── PAGE TITLE ── */
    .page-title { margin-bottom: 14px; }
    .page-title h1 { font-size: 20px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title p  { font-size: 12.5px; color: #6b8f7e; }

    /* ── CARD ── */
    .card {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      overflow: visible; box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    /* ── CARD HEADER TABLE ── */
    .card-header-table {
      padding: 16px 20px 14px;
      border-bottom: 1px solid #f0f5f2;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      background: #fff;
      border-radius: 12px 12px 0 0;
    }
    .card-header-table h1 {
      font-size: 19px;
      font-weight: 600;
      color: #1e3a2f;
      margin: 0 0 3px 0;
      letter-spacing: -0.01em;
    }
    .card-header-table p {
      font-size: 12.5px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
    }
    .card-header-table + form .filter-bar,
    .card-header-table + .filter-bar {
      border-radius: 0;
    }

    /* ── FILTER BAR ── */
    .filter-bar {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 14px; border-bottom: 1px solid #f0f5f2;
      flex-wrap: wrap; background: #fff; border-radius: 12px 12px 0 0;
    }
    .search-wrap { position: relative; flex: 1; min-width: 180px; max-width: 340px; }
    .search-wrap svg {
      position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
      color: #9ab5a8; pointer-events: none;
    }
    .search-input {
      width: 100%; border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 7px 12px 7px 32px; font-size: 12.5px; color: #1e3a2f;
      background: #fff; outline: none; transition: border-color 0.15s, box-shadow 0.15s;
    }
    .search-input::placeholder { color: #aec9b8; }
    .search-input:focus { border-color: #2e7d4f; box-shadow: 0 0 0 3px rgba(46,125,79,.1); }

    .filter-select, .filter-date {
      border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 7px 11px; font-size: 12.5px; color: #1e3a2f;
      background: #fff; outline: none; cursor: pointer; transition: border-color 0.15s;
    }
    .filter-select:focus, .filter-date:focus { border-color: #2e7d4f; }

    .btn-filter {
      display: inline-flex; align-items: center; justify-content: center;
      width: 32px; height: 32px; background: #eef4f0; border: 1px solid #d6e6dc;
      border-radius: 8px; color: #2e7d4f; cursor: pointer; transition: all 0.15s;
    }
    .btn-filter:hover { background: #2e7d4f; color: #fff; border-color: #2e7d4f; }

    .btn-reset-filter {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 6px 10px; font-size: 12px; font-weight: 500; color: #b03030;
      background: #fdf2f2; border: 1px solid #f5c6c6; border-radius: 8px;
      text-decoration: none; transition: all 0.15s;
    }
    .btn-reset-filter:hover { background: #fce4e4; color: #8a1f1f; }

    .btn-tambah {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      height: 32px; padding: 0 13px;
      border: 1px solid #1e3a2f; border-radius: 8px;
      background: #1e3a2f; color: #fff; text-decoration: none;
      font-size: 12.5px; font-weight: 600; white-space: nowrap; cursor: pointer;
      font-family: inherit; transition: background 0.15s, border-color 0.15s;
    }
    .btn-tambah:hover { background: #2d5540; border-color: #2d5540; color: #fff; }

    /* ── TABLE ── */
    .table-wrapper { overflow-x: auto; border-radius: 0 0 12px 12px; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    thead tr { background: #f8fbf9; position: relative; z-index: 1; }
    thead th {
      padding: 10px 14px; font-size: 11.5px; font-weight: 600; color: #5a7d6d;
      text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #e8f0ec;
      white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid #f0f5f2; transition: background 0.15s; position: relative; z-index: 1; }
    tbody tr:last-child { border-bottom: none; }
    tbody td {
      padding: 8px 14px; font-size: 13px; color: #1e3a2f;
      vertical-align: middle; position: relative;
    }

    /* Stacking context: Baris yang di-hover mendapat z-index jauh lebih tinggi agar tooltip tidak tertimpa button baris atasnya */
    tbody tr:hover { background: #fafcfa; position: relative; z-index: 200; }
    tbody tr:hover td { position: relative; z-index: 200; }
    tbody tr:hover .btn-action-wrap { position: relative; z-index: 210; }
    tbody tr:hover .btn-icon:hover { position: relative; z-index: 250; }
    tbody tr:hover .btn-icon:hover::after,
    tbody tr:hover .btn-icon:hover::before { z-index: 999999; }

    /* Badge */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;
      white-space: nowrap; text-transform: capitalize;
    }
    .badge-pax { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-weight: 700; padding: 2px 7px; border-radius: 6px; }
    .badge-tidak_hadir { background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8; }

    /* ── TOMBOL AKSI IKON ── */
    .btn-action-wrap { display: flex; gap: 4px; align-items: center; justify-content: flex-end; position: relative; z-index: 2; }
    .btn-icon {
      position: relative; width: 28px; height: 28px; border: none; border-radius: 7px;
      display: inline-flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background 0.15s, transform 0.1s; flex-shrink: 0;
      text-decoration: none; font-family: inherit;
    }
    .btn-icon:active { transform: scale(0.93); }
    .btn-icon svg { display: block; }
    .btn-detail     { background: #f0f4f8; color: #486581; }
    .btn-detail:hover { background: #486581; color: #fff; }
    .btn-edit       { background: #eef3ff; color: #3b6fd4; }
    .btn-edit:hover { background: #3b6fd4; color: #fff; }
    .btn-hapus      { background: #fceaea; color: #c0392b; }
    .btn-hapus:hover { background: #c0392b; color: #fff; }

    /* tooltip muncul ke atas dengan z-index tinggi */
    .btn-icon::after {
      content: attr(data-tooltip);
      position: absolute;
      bottom: calc(100% + 7px);
      right: 50%;
      transform: translateX(50%);
      background: #1e3a2f;
      color: #fff;
      font-size: 11px;
      font-weight: 500;
      padding: 4px 8px;
      border-radius: 5px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.15s, transform 0.15s;
      z-index: 999999;
      box-shadow: 0 3px 8px rgba(0,0,0,0.22);
    }
    .btn-icon::before {
      content: '';
      position: absolute;
      bottom: calc(100% + 2px);
      right: 50%;
      transform: translateX(50%);
      border: 5px solid transparent;
      border-top-color: #1e3a2f;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.15s;
      z-index: 999999;
    }
    .btn-icon:last-child::after  { right: 0; transform: none; }
    .btn-icon:last-child::before { right: 9px; transform: none; }
    .btn-icon:hover::after, .btn-icon:hover::before { opacity: 1; }

    /* ── PAGINATION & FOOTER ── */
    .table-footer {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 16px; border-top: 1px solid #f0f5f2; flex-wrap: wrap; gap: 10px;
      font-size: 12.5px; color: #6b8f7e;
    }
    .pagination {
      display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0;
    }
    .page-link {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 28px; height: 28px; padding: 0 7px; font-size: 12px; font-weight: 500;
      border: 1px solid #d6e6dc; border-radius: 6px; color: #2a4535;
      text-decoration: none; background: #fff; transition: all 0.15s;
    }
    .page-link:hover { background: #f4fbf6; border-color: #2e7d4f; color: #2e7d4f; }
    .page-item.active .page-link { background: #1e3a2f; border-color: #1e3a2f; color: #fff; font-weight: 600; }
    .page-item.disabled .page-link { opacity: 0.45; pointer-events: none; background: #fafcfb; }

    .booking-footer {
      margin-top: 16px; padding: 10px 24px;
      font-size: 11.5px; color: #9ab5a8; border-top: 1px solid #e5ede8;
      text-align: center;
    }

    /* ── MODAL POPUP FORM ── */
    .modal-overlay {
      position: fixed; inset: 0;
      background: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 9999999;
      display: none; align-items: center; justify-content: center;
      padding: 16px; opacity: 0; transition: opacity 0.2s ease;
    }
    .modal-overlay.show { display: flex; opacity: 1; }

    .modal-card {
      background: #fff; width: 100%; max-width: 640px; max-height: 90vh;
      border-radius: 14px; box-shadow: 0 25px 60px rgba(0,0,0,0.3);
      display: flex; flex-direction: column; overflow: hidden;
      transform: translateY(14px) scale(0.97); transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-overlay.show .modal-card { transform: translateY(0) scale(1); }

    .modal-card-header {
      padding: 14px 20px; border-bottom: 1px solid #eef4f1;
      display: flex; align-items: center; justify-content: space-between;
      background: #fff; flex-shrink: 0;
    }
    .modal-hicon {
      width: 36px; height: 36px; background: #e4f5ec; border-radius: 9px;
      display: flex; align-items: center; justify-content: center; color: #2e7d4f; flex-shrink: 0;
    }
    .modal-spinner {
      width: 32px; height: 32px; border: 3px solid #e0ede6;
      border-top-color: #2e7d4f; border-radius: 50%;
      animation: spin 0.7s linear infinite; margin: 0 auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .modal-card-header h2 { font-size: 15.5px; font-weight: 600; color: #1e3a2f; margin: 0; line-height: 1.2; }
    .modal-card-header p  { font-size: 11.5px; color: #6b8f7e; margin: 2px 0 0; }
    .modal-btn-close {
      background: none; border: none; font-size: 22px; color: #9ab5a8;
      cursor: pointer; padding: 2px 6px; border-radius: 6px; line-height: 1; transition: all 0.15s;
    }
    .modal-btn-close:hover { background: #f0f4f2; color: #1e3a2f; }

    .modal-card-body { padding: 16px 20px; overflow-y: auto; flex: 1; }
    .modal-card-body::-webkit-scrollbar { width: 6px; }
    .modal-card-body::-webkit-scrollbar-track { background: #f8faf9; }
    .modal-card-body::-webkit-scrollbar-thumb { background: #cce0d6; border-radius: 4px; }
    .modal-card-body::-webkit-scrollbar-thumb:hover { background: #9bbfae; }

    .modal-section-title {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .06em; color: #5a7d6d; margin: 16px 0 10px;
      padding-bottom: 6px; border-bottom: 1px solid #eef4f1;
      display: flex; align-items: center; gap: 6px;
    }
    .modal-section-title:first-child { margin-top: 0; }

    .modal-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 580px) { .modal-grid-2 { grid-template-columns: 1fr; } }

    .modal-field { display: flex; flex-direction: column; gap: 5px; }
    .modal-label { font-size: 12.5px; font-weight: 600; color: #2a4535; }
    .modal-input, .modal-select {
      width: 100%; padding: 8px 12px; font-size: 13px; color: #1e3a2f;
      border: 1px solid #d6e6dc; border-radius: 7px; background: #fff;
      outline: none; transition: border-color 0.15s, box-shadow 0.15s; font-family: inherit;
    }
    .modal-input:focus, .modal-select:focus {
      border-color: #2e7d4f; box-shadow: 0 0 0 3px rgba(46,125,79,.12);
    }
    .modal-select {
      cursor: pointer; appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a9e8e' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px;
    }

    .modal-sub-box {
      background: #f6fbf8; border: 1px solid #ddeee5; border-radius: 8px;
      padding: 10px 12px; margin-top: 8px;
    }
    .modal-sub-label {
      font-size: 11px; font-weight: 700; color: #6b8f7e;
      text-transform: uppercase; letter-spacing: .05em; margin-bottom: 7px;
    }

    .modal-radio-group { display: flex; gap: 7px; flex-wrap: wrap; }
    .modal-radio-pill {
      display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
      border: 1.5px solid #d6e6dc; border-radius: 7px; cursor: pointer;
      font-size: 12.5px; transition: all 0.15s; background: #fff; user-select: none;
    }
    .modal-radio-pill:hover { border-color: #2e7d4f; background: #f4fbf6; }
    .modal-radio-pill input[type="radio"] { accent-color: #2e7d4f; width: 13px; height: 13px; cursor: pointer; }
    .modal-radio-pill.active { border-color: #2e7d4f; background: #edf8f2; color: #1e6b3c; font-weight: 600; }

    .modal-card-footer {
      padding: 12px 20px; border-top: 1px solid #eef4f1; background: #fafcfb;
      display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-shrink: 0;
    }
    .btn-modal-cancel {
      padding: 7px 14px; font-size: 12.5px; font-weight: 500; color: #5a7d6d;
      background: #fff; border: 1px solid #d6e6dc; border-radius: 7px;
      cursor: pointer; transition: all 0.15s;
    }
    .btn-modal-cancel:hover { background: #f0f4f2; color: #1e3a2f; }
    .btn-modal-submit {
      padding: 7px 18px; font-size: 12.5px; font-weight: 600; color: #fff;
      background: #2e7d4f; border: 1px solid #2e7d4f; border-radius: 7px;
      cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
      transition: all 0.15s; font-family: inherit;
    }
    .btn-modal-submit:hover { background: #23633e; border-color: #23633e; }
    .btn-modal-submit:disabled { opacity: 0.65; cursor: not-allowed; }
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>

<main class="booking-content" id="bookingContent">
  <?php include 'booking_header.php'; ?>

  <div class="content-inner">
    <div class="card">
      <!-- Card Header (Judul & Subtitle di dalam Card) -->
      <div class="card-header-table">
        <div>
          <h1>Booking Tidak Datang</h1>
          <p>Daftar booking yang tidak hadir sesuai jadwal kunjungan</p>
        </div>
      </div>

      <!-- Filter bar -->
      <form method="GET" action="" id="filterForm">
        <div class="filter-bar">
          <!-- Search -->
          <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input
              type="text"
              name="search"
              id="searchInput"
              class="search-input"
              placeholder="Cari agen wisata, paket, atau pemandu..."
              value="<?php echo htmlspecialchars($search); ?>"
              autocomplete="off"
            >
          </div>

          <!-- Filter Tanggal (Date) -->
          <input
            type="date"
            name="tanggal"
            id="filterTanggal"
            class="filter-date"
            title="Filter Tanggal Kunjungan"
            value="<?php echo htmlspecialchars($tanggal); ?>"
            onchange="document.getElementById('filterForm').submit()"
          >

          <!-- Show per page (Default 10 baris) -->
          <select name="per_page" id="perPage" class="filter-select" style="min-width:115px;" onchange="document.getElementById('filterForm').submit()">
            <option value="10" <?php echo $per_page === 10 ? 'selected' : ''; ?>>10 baris</option>
            <option value="15" <?php echo $per_page === 15 ? 'selected' : ''; ?>>15 baris</option>
            <option value="25" <?php echo $per_page === 25 ? 'selected' : ''; ?>>25 baris</option>
            <option value="50" <?php echo $per_page === 50 ? 'selected' : ''; ?>>50 baris</option>
          </select>

          <!-- Submit icon -->
          <button type="submit" class="btn-filter" title="Terapkan Filter">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M3 4h18M7 9h10M11 14h2M13 19h-2"/>
            </svg>
          </button>

          <?php if ($search !== '' || $tanggal !== ''): ?>
          <a href="booking_tidakdatang.php" class="btn-reset-filter" title="Reset Filter">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 18L18 6M6 6l12 12"/></svg>
            Reset
          </a>
          <?php endif; ?>

          <div style="margin-left:auto;">
            <a href="tambah_booking.php?ref=booking_tidakdatang.php" class="btn-tambah">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
              Tambah Booking
            </a>
          </div>
        </div>
      </form>

      <!-- Table -->
      <div class="table-wrapper">
        <table id="tabelTidakDatang">
          <thead>
            <tr>
              <th>Agen Wisata</th>
              <th>Tanggal Kunjungan</th>
              <th>Pax</th>
              <th>Paket &amp; Layanan</th>
              <th>Driver / Guide</th>
              <th>Status</th>
              <th style="text-align:right;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($bookings)): ?>
            <tr>
              <td colspan="7" style="text-align:center; padding: 28px 14px; color:#9ab5a8;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:6px; display:inline-block;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <div style="font-size:13px;">Tidak ada data booking tidak datang ditemukan.</div>
              </td>
            </tr>
            <?php else: foreach ($bookings as $row):
              $agenText = !empty(trim($row['agen_wisata'] ?? '')) ? htmlspecialchars($row['agen_wisata']) : 'Belum Ada';
              $tamuText = !empty(trim($row['nama'] ?? '')) ? htmlspecialchars($row['nama']) : '-';
              $asal = $row['jenis_wisatawan'] === 'Domestik' ? ($row['kota'] ?: 'Domestik') : ($row['negara'] ?: 'Mancanegara');
              $driver = !empty(trim($row['driver_agent_guide'] ?? '')) ? htmlspecialchars($row['driver_agent_guide']) : 'Belum Ada';
              $lg = !empty(trim($row['local_guide'] ?? '')) ? htmlspecialchars($row['local_guide']) : 'Belum Ada';
            ?>
            <tr>
              <td>
                <div style="font-weight: 600; color: #1e3a2f; font-size: 13px;"><?php echo $agenText; ?></div>
                <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">Tamu: <?php echo $tamuText; ?></div>
              </td>
              <td>
                <div style="font-weight: 500; font-size: 12.5px;"><?php echo tglIndo($row['tanggal_kunjungan']); ?></div>
                <div style="font-size: 11px; color: #7a9e8e;"><?php echo htmlspecialchars($asal); ?></div>
              </td>
              <td>
                <span class="badge badge-pax"><?php echo (int)$row['pax']; ?> Pax</span>
              </td>
              <td>
                <div style="font-size: 12.5px; font-weight: 500;"><?php echo htmlspecialchars(labelPaket($row['pilihan_paket_wisata'])); ?></div>
                <?php if ($row['opsi_makan_tour']): ?>
                  <div style="font-size: 10.5px; color: #2e7d4f; font-weight: 600;">
                    <?php echo $row['opsi_makan_tour'] === 'with_lunch' ? 'With Lunch' : 'Without Lunch'; ?>
                  </div>
                <?php elseif ($row['jenis_makanan_paket']): ?>
                  <div style="font-size: 10.5px; color: #b7791f; font-weight: 600;">
                    🍽 <?php echo ucfirst($row['jenis_makanan_paket']); ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-size: 12px; font-weight: 500; color: #2a4535;">D: <?php echo $driver; ?></div>
                <div style="font-size: 11px; color: #7a9e8e;">G: <?php echo $lg; ?></div>
              </td>
              <td>
                <span class="badge badge-tidak_hadir">Tidak Datang</span>
              </td>
              <td>
                <div class="btn-action-wrap">
                  <!-- Detail -->
                  <a href="detail_booking.php?id=<?php echo $row['id']; ?>&ref=booking_tidakdatang.php" class="btn-icon btn-detail" data-tooltip="Lihat Detail" title="Lihat Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>

                  <!-- Edit -->
                  <a href="edit_booking.php?id=<?php echo (int)$row['id']; ?>&ref=booking_tidakdatang.php" class="btn-icon btn-edit" data-tooltip="Edit Booking" title="Edit Booking">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  </a>

                  <!-- Hapus -->
                  <button
                    type="button"
                    class="btn-icon btn-hapus"
                    data-tooltip="Hapus Booking"
                    title="Hapus Booking"
                    onclick="konfirmasiHapus(<?php echo (int)$row['id']; ?>, '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Footer & Pagination -->
      <div class="table-footer">
        <div>
          Menampilkan <strong><?php echo $total_data > 0 ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $per_page, $total_data); ?></strong> dari <strong><?php echo $total_data; ?></strong> data booking tidak datang
        </div>

        <?php if ($total_pages > 1): ?>
        <ul class="pagination">
          <!-- Previous -->
          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo getPageUrl($page - 1); ?>" title="Halaman Sebelumnya">&laquo;</a>
          </li>

          <?php
            $start_p = max(1, $page - 2);
            $end_p   = min($total_pages, $page + 2);

            if ($start_p > 1):
          ?>
            <li class="page-item"><a class="page-link" href="<?php echo getPageUrl(1); ?>">1</a></li>
            <?php if ($start_p > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
          <?php endif; ?>

          <?php for ($i = $start_p; $i <= $end_p; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
              <a class="page-link" href="<?php echo getPageUrl($i); ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($end_p < $total_pages): ?>
            <?php if ($end_p < $total_pages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
            <li class="page-item"><a class="page-link" href="<?php echo getPageUrl($total_pages); ?>"><?php echo $total_pages; ?></a></li>
          <?php endif; ?>

          <!-- Next -->
          <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo getPageUrl($page + 1); ?>" title="Halaman Berikutnya">&raquo;</a>
          </li>
        </ul>
        <?php endif; ?>
      </div>

    </div><!-- /.card -->

  </div><!-- /.content-inner -->

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<!-- ── MODAL POP-UP CREATE & UPDATE BOOKING ── -->
<div id="modalBookingOverlay" class="modal-overlay" onclick="handleOverlayClick(event)">
  <div class="modal-card" id="modalBookingCard" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <!-- Header -->
    <div class="modal-card-header">
      <div style="display:flex;align-items:center;gap:12px;">
        <div class="modal-hicon" id="modalHicon">
          <svg id="iconTambah" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M12 4v16m8-8H4"/></svg>
          <svg id="iconEdit" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
        </div>
        <div>
          <h2 id="modalTitle">Tambah Booking Baru</h2>
          <p id="modalSubtitle">Isi formulir data booking baru pengunjung Desa Wisata Candirejo</p>
        </div>
      </div>
      <button type="button" class="modal-btn-close" onclick="tutupModalBooking()" title="Tutup Modal">&times;</button>
    </div>

    <!-- Form -->
    <form id="formModalBooking" onsubmit="handleFormSubmit(event)">
      <input type="hidden" name="aksi" id="modalAksi" value="tambah">
      <input type="hidden" name="id" id="modalId" value="">
      <input type="hidden" name="is_ajax" value="1">

      <div class="modal-card-body" id="modalBody">
        
        <!-- Loading State for Edit -->
        <div id="modalLoading" style="display:none;text-align:center;padding:45px 20px;color:#2e7d4f;">
          <div class="modal-spinner"></div>
          <div style="font-size:13px;font-weight:500;margin-top:12px;color:#527866;">Memuat data booking...</div>
        </div>

        <div id="modalFormFields">
          <!-- INFORMASI KUNJUNGAN -->
          <div class="modal-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
            Informasi Kunjungan
          </div>

          <div class="modal-grid-2">
            <div class="modal-field">
              <label class="modal-label">Tanggal Kunjungan<span class="req" style="color:#c0392b;">*</span></label>
              <input type="date" name="tanggal_kunjungan" id="m_tanggal_kunjungan" required class="modal-input">
            </div>
            <div class="modal-field">
              <label class="modal-label">Jumlah Pax<span class="req" style="color:#c0392b;">*</span></label>
              <input type="number" name="pax" id="m_pax" required min="0" max="9999" value="0" class="modal-input">
            </div>
          </div>

          <div class="modal-field" style="margin-top:10px;">
            <label class="modal-label">Pilihan Paket Wisata<span class="req" style="color:#c0392b;">*</span></label>
            <select name="pilihan_paket_wisata" id="m_pilihan_paket_wisata" required class="modal-select">
              <option value="">-- Pilih Paket Wisata --</option>
              <option value="meal_only">Breakfast / Lunch / Dinner Only</option>
              <option value="studi_banding">Studi Banding</option>
              <option value="fun_game">Paket Fun Game</option>
              <option value="pelajar_live_in">Paket Pelajar - Live In Candirejo</option>
              <option value="pelajar_field_trip_one_day">Paket Pelajar - Field Trip One Day</option>
              <option value="pelajar_field_trip_half_day">Paket Pelajar - Field Trip Half Day</option>
              <option value="cycling_tour">Cycling Village Tour with/without Lunch</option>
              <option value="traditional_dance">Traditional Dance</option>
              <option value="walking_tour">Walking Around Village with/without Lunch</option>
              <option value="homestay">Homestay - Stay At Local House</option>
              <option value="serenade">Serenade At The Foot Of Menoreh Hill</option>
              <option value="cooking_lesson">Cooking Lesson with/without Tour</option>
              <option value="gamelan_class">Gamelan Class with/without Lunch</option>
              <option value="village_experience">Village Experience</option>
              <option value="dokar_tour">Dokar Village Tour with/without Lunch</option>
              <option value="inspection">Inspection</option>
              <option value="lainnya">Lainnya</option>
            </select>
          </div>

          <!-- Sub: Opsi Makan Tour -->
          <div class="modal-sub-box" id="m_sub_makan_tour" style="display:none;">
            <div class="modal-sub-label">Pilih Opsi Makan (Tour)</div>
            <div class="modal-radio-group">
              <label class="modal-radio-pill">
                <input type="radio" name="opsi_makan_tour" value="without_lunch">
                Without Lunch
              </label>
              <label class="modal-radio-pill">
                <input type="radio" name="opsi_makan_tour" value="with_lunch">
                With Lunch
              </label>
            </div>
          </div>

          <!-- Sub: Jenis Makanan -->
          <div class="modal-sub-box" id="m_sub_jenis_makanan" style="display:none;">
            <div class="modal-sub-label">Pilih Waktu Makan</div>
            <div class="modal-radio-group">
              <label class="modal-radio-pill"><input type="radio" name="jenis_makanan_paket" value="breakfast"> Breakfast</label>
              <label class="modal-radio-pill"><input type="radio" name="jenis_makanan_paket" value="lunch"> Lunch</label>
              <label class="modal-radio-pill"><input type="radio" name="jenis_makanan_paket" value="dinner"> Dinner</label>
            </div>
          </div>

          <!-- Sub: Cooking Lesson -->
          <div class="modal-sub-box" id="m_sub_cooking" style="display:none;">
            <div class="modal-sub-label">Pilih Opsi Cooking Lesson</div>
            <div class="modal-radio-group">
              <label class="modal-radio-pill"><input type="radio" name="opsi_cooking_lesson" value="lesson_only"> Lesson Only</label>
              <label class="modal-radio-pill"><input type="radio" name="opsi_cooking_lesson" value="lesson_with_tour"> Lesson with Tour</label>
            </div>
          </div>

          <!-- Sub: Gamelan -->
          <div class="modal-sub-box" id="m_sub_gamelan" style="display:none;">
            <div class="modal-sub-label">Pilih Opsi Gamelan Class</div>
            <div class="modal-radio-group">
              <label class="modal-radio-pill"><input type="radio" name="opsi_gamelan" value="without_lunch"> Without Lunch</label>
              <label class="modal-radio-pill"><input type="radio" name="opsi_gamelan" value="with_lunch"> With Lunch</label>
            </div>
          </div>

          <!-- DATA WISATAWAN -->
          <div class="modal-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Data Wisatawan
          </div>

          <div class="modal-field">
            <label class="modal-label">Nama Wisatawan / Tamu<span class="req" style="color:#c0392b;">*</span></label>
            <input type="text" name="nama" id="m_nama" required maxlength="100" class="modal-input" placeholder="Nama wisatawan atau rombongan">
          </div>

          <div class="modal-field" style="margin-top:10px;">
            <label class="modal-label">Jenis Wisatawan<span class="req" style="color:#c0392b;">*</span></label>
            <div class="modal-radio-group">
              <label class="modal-radio-pill" id="m_pill_domestik">
                <input type="radio" name="jenis_wisatawan" value="Domestik" id="m_jw_domestik" required>
                Domestik
              </label>
              <label class="modal-radio-pill" id="m_pill_mancanegara">
                <input type="radio" name="jenis_wisatawan" value="Mancanegara" id="m_jw_mancanegara">
                Mancanegara
              </label>
            </div>
          </div>

          <div class="modal-sub-box" id="m_sub_kota" style="display:none;margin-top:8px;">
            <label class="modal-label">Kota Asal<span class="req" style="color:#c0392b;">*</span></label>
            <input type="text" name="kota" id="m_kota" maxlength="100" class="modal-input" placeholder="Contoh: Yogyakarta, Semarang, Jakarta">
          </div>

          <div class="modal-sub-box" id="m_sub_negara" style="display:none;margin-top:8px;">
            <label class="modal-label">Negara Asal<span class="req" style="color:#c0392b;">*</span></label>
            <input type="text" name="negara" id="m_negara" maxlength="100" class="modal-input" placeholder="Contoh: Netherlands, Australia, Japan">
          </div>

          <!-- OPERASIONAL & PEMANDU -->
          <div class="modal-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/></svg>
            Operasional &amp; Pemandu
          </div>

          <div class="modal-field">
            <label class="modal-label">Agen Wisata <small style="font-size:11px;color:#8aab9b;font-weight:normal;">(Opsional, default 'Belum Ada')</small></label>
            <input type="text" name="agen_wisata" id="m_agen_wisata" maxlength="100" class="modal-input" placeholder="Nama agen wisata (opsional)">
          </div>

          <div class="modal-grid-2" style="margin-top:10px;">
            <div class="modal-field">
              <label class="modal-label">Driver / Agent Guide <small style="font-size:11px;color:#8aab9b;font-weight:normal;">(Opsional)</small></label>
              <input type="text" name="driver_agent_guide" id="m_driver_agent_guide" maxlength="100" value="Belum Ada" class="modal-input" placeholder="Belum Ada">
            </div>
            <div class="modal-field">
              <label class="modal-label">Local Guide <small style="font-size:11px;color:#8aab9b;font-weight:normal;">(Opsional)</small></label>
              <input type="text" name="local_guide" id="m_local_guide" maxlength="100" value="Belum Ada" class="modal-input" placeholder="Belum Ada">
            </div>
          </div>

          <div class="modal-field" style="margin-top:10px;">
            <label class="modal-label">Catatan Tambahan <small style="font-size:11px;color:#8aab9b;font-weight:normal;">(Opsional)</small></label>
            <textarea name="catatan" id="m_catatan" rows="2" class="modal-input" style="resize:vertical;min-height:60px;line-height:1.45;" placeholder="Tuliskan catatan khusus atau keterangan tambahan..."></textarea>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-card-footer">
        <button type="button" class="btn-modal-cancel" onclick="tutupModalBooking()">Batal</button>
        <button type="submit" class="btn-modal-submit" id="btnModalSubmit">
          <span id="btnModalIcon">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M5 13l4 4L19 7"/></svg>
          </span>
          <span id="btnModalText">Simpan Booking</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Form tersembunyi untuk proses hapus booking -->
<form id="formHapus" action="proses/proses_hapus_booking.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdHapus">
  <input type="hidden" name="ref" value="booking_tidakdatang.php">
</form>

<script>
/* Search on enter */
document.getElementById('searchInput').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    document.getElementById('filterForm').submit();
  }
});

function konfirmasiHapus(id, nama) {
  Swal.fire({
    title: 'Hapus Booking',
    html: `<p style="font-size:15px;color:#555;">Hapus data booking <strong>${nama}</strong>?</p><small style="color:#9ab5a8;">Aksi ini tidak dapat dibatalkan.</small>`,
    icon: 'warning',
    iconColor: '#c0392b',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#c0392b',
    cancelButtonColor: '#aaa',
  }).then(result => {
    if (result.isConfirmed) {
      document.getElementById('inputIdHapus').value = id;
      document.getElementById('formHapus').submit();
    }
  });
}

/* Modal Logic */
function sembunyikanSemuaSubPaket() {
  ['m_sub_makan_tour', 'm_sub_jenis_makanan', 'm_sub_cooking', 'm_sub_gamelan'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.style.display = 'none';
      el.querySelectorAll('input[type="radio"]').forEach(r => { r.checked = false; });
      el.querySelectorAll('.modal-radio-pill').forEach(p => p.classList.remove('active'));
    }
  });
}

function updateSubPaket(paket) {
  const map = {
    cycling_tour: 'm_sub_makan_tour',
    dokar_tour: 'm_sub_makan_tour',
    walking_tour: 'm_sub_makan_tour',
    meal_only: 'm_sub_jenis_makanan',
    cooking_lesson: 'm_sub_cooking',
    gamelan_class: 'm_sub_gamelan'
  };
  if (map[paket]) {
    const el = document.getElementById(map[paket]);
    if (el) el.style.display = 'block';
  }
}

document.getElementById('m_pilihan_paket_wisata').addEventListener('change', function() {
  sembunyikanSemuaSubPaket();
  updateSubPaket(this.value);
});

function setRadioVal(name, val) {
  const radios = document.querySelectorAll(`input[name="${name}"]`);
  radios.forEach(r => {
    r.checked = (r.value === val);
    const pill = r.closest('.modal-radio-pill');
    if (pill) pill.classList.toggle('active', r.checked);
  });
}

function setJenisWisatawan(jenis) {
  const isDom = jenis === 'Domestik';
  document.getElementById('m_jw_domestik').checked = isDom;
  document.getElementById('m_jw_mancanegara').checked = !isDom;
  document.getElementById('m_pill_domestik').classList.toggle('active', isDom);
  document.getElementById('m_pill_mancanegara').classList.toggle('active', !isDom);

  const subKota = document.getElementById('m_sub_kota');
  const subNegara = document.getElementById('m_sub_negara');
  const kota = document.getElementById('m_kota');
  const negara = document.getElementById('m_negara');

  if (isDom) {
    subKota.style.display = 'block';
    subNegara.style.display = 'none';
    kota.required = true;
    negara.required = false;
    negara.value = '';
  } else {
    subKota.style.display = 'none';
    subNegara.style.display = 'block';
    negara.required = true;
    kota.required = false;
    kota.value = '';
  }
}

document.getElementById('m_jw_domestik').addEventListener('change', function() {
  if (this.checked) setJenisWisatawan('Domestik');
});
document.getElementById('m_jw_mancanegara').addEventListener('change', function() {
  if (this.checked) setJenisWisatawan('Mancanegara');
});

document.addEventListener('change', function(e) {
  if (e.target.type === 'radio' && e.target.closest('.modal-radio-group')) {
    const grp = e.target.closest('.modal-radio-group');
    grp.querySelectorAll('.modal-radio-pill').forEach(p => p.classList.remove('active'));
    const pill = e.target.closest('.modal-radio-pill');
    if (pill) pill.classList.add('active');
  }
});

function tampilkanModal() {
  const overlay = document.getElementById('modalBookingOverlay');
  overlay.style.display = 'flex';
  setTimeout(() => overlay.classList.add('show'), 10);
  document.body.style.overflow = 'hidden';
}

function tutupModalBooking() {
  const overlay = document.getElementById('modalBookingOverlay');
  overlay.classList.remove('show');
  setTimeout(() => {
    overlay.style.display = 'none';
    document.body.style.overflow = '';
  }, 200);
}

function handleOverlayClick(e) {
  if (e.target.id === 'modalBookingOverlay') {
    tutupModalBooking();
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const overlay = document.getElementById('modalBookingOverlay');
    if (overlay && overlay.classList.contains('show')) {
      tutupModalBooking();
    }
  }
});

function bukaModalTambah() {
  document.getElementById('formModalBooking').reset();
  document.getElementById('modalAksi').value = 'tambah';
  document.getElementById('modalId').value = '';
  document.getElementById('modalTitle').textContent = 'Tambah Booking Baru';
  document.getElementById('modalSubtitle').textContent = 'Isi formulir data booking baru pengunjung Desa Wisata Candirejo';
  document.getElementById('btnModalText').textContent = 'Simpan Booking';
  document.getElementById('iconTambah').style.display = 'block';
  document.getElementById('iconEdit').style.display = 'none';

  document.getElementById('m_pax').value = '0';
  document.getElementById('m_agen_wisata').value = '';
  document.getElementById('m_driver_agent_guide').value = 'Belum Ada';
  document.getElementById('m_local_guide').value = 'Belum Ada';
  document.getElementById('m_catatan').value = '';
  setJenisWisatawan('Domestik');
  sembunyikanSemuaSubPaket();

  document.getElementById('modalLoading').style.display = 'none';
  document.getElementById('modalFormFields').style.display = 'block';

  tampilkanModal();
}

function bukaModalEdit(id) {
  document.getElementById('formModalBooking').reset();
  document.getElementById('modalAksi').value = 'edit';
  document.getElementById('modalId').value = id;
  document.getElementById('modalTitle').textContent = 'Edit Data Booking';
  document.getElementById('modalSubtitle').textContent = 'Perbarui informasi reservasi booking pengunjung';
  document.getElementById('btnModalText').textContent = 'Perbarui Booking';
  document.getElementById('iconTambah').style.display = 'none';
  document.getElementById('iconEdit').style.display = 'block';

  document.getElementById('modalLoading').style.display = 'block';
  document.getElementById('modalFormFields').style.display = 'none';

  tampilkanModal();

  fetch('proses/get_booking.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'success') {
        tutupModalBooking();
        Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message || 'Tidak dapat memuat data.' });
        return;
      }
      const d = res.data;

      document.getElementById('m_tanggal_kunjungan').value = d.tanggal_kunjungan || '';
      document.getElementById('m_pax').value = d.pax !== null ? d.pax : 0;
      document.getElementById('m_nama').value = d.nama || '';
      document.getElementById('m_pilihan_paket_wisata').value = d.pilihan_paket_wisata || '';

      // Set sub-opsi paket
      sembunyikanSemuaSubPaket();
      updateSubPaket(d.pilihan_paket_wisata);
      if (d.opsi_makan_tour) setRadioVal('opsi_makan_tour', d.opsi_makan_tour);
      if (d.jenis_makanan_paket) setRadioVal('jenis_makanan_paket', d.jenis_makanan_paket);
      if (d.opsi_cooking_lesson) setRadioVal('opsi_cooking_lesson', d.opsi_cooking_lesson);
      if (d.opsi_gamelan) setRadioVal('opsi_gamelan', d.opsi_gamelan);

      // Jenis Wisatawan
      setJenisWisatawan(d.jenis_wisatawan || 'Domestik');
      if (d.jenis_wisatawan === 'Domestik') {
        document.getElementById('m_kota').value = d.kota || '';
      } else {
        document.getElementById('m_negara').value = d.negara || '';
      }

      // Operasional & Agen
      document.getElementById('m_agen_wisata').value = (d.agen_wisata && d.agen_wisata !== 'Belum Ada') ? d.agen_wisata : '';
      document.getElementById('m_driver_agent_guide').value = d.driver_agent_guide || 'Belum Ada';
      document.getElementById('m_local_guide').value = d.local_guide || 'Belum Ada';
      document.getElementById('m_catatan').value = d.catatan || '';

      document.getElementById('modalLoading').style.display = 'none';
      document.getElementById('modalFormFields').style.display = 'block';
    })
    .catch(err => {
      tutupModalBooking();
      Swal.fire({ icon: 'error', title: 'Kesalahan Jaringan', text: 'Gagal menghubungi server.' });
    });
}

function handleFormSubmit(e) {
  e.preventDefault();
  const form = document.getElementById('formModalBooking');
  if (!form.reportValidity()) return;

  const btnSubmit = document.getElementById('btnModalSubmit');
  const btnText = document.getElementById('btnModalText');
  const originalText = btnText.textContent;

  btnSubmit.disabled = true;
  btnText.textContent = 'Menyimpan...';

  const formData = new FormData(form);

  fetch('proses/proses_booking.php', {
    method: 'POST',
    body: formData,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(res => {
    btnSubmit.disabled = false;
    btnText.textContent = originalText;

    if (res.status === 'success') {
      tutupModalBooking();
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: res.message,
        timer: 1800,
        timerProgressBar: true,
        showConfirmButton: false
      }).then(() => {
        window.location.reload();
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Gagal Menyimpan!',
        text: res.message || 'Terjadi kesalahan sistem.',
        confirmButtonColor: '#c0392b'
      });
    }
  })
  .catch(err => {
    btnSubmit.disabled = false;
    btnText.textContent = originalText;
    Swal.fire({
      icon: 'error',
      title: 'Kesalahan Koneksi',
      text: 'Gagal mengirim data ke server.',
      confirmButtonColor: '#c0392b'
    });
  });
}

<?php if (isset($_GET['msg'])): ?>
(function() {
  const msg = "<?php echo htmlspecialchars($_GET['msg']); ?>";
  const detail = "<?php echo htmlspecialchars($_GET['detail'] ?? ''); ?>";
  if (msg === 'sukses_tambah') {
    Swal.fire({ icon: 'success', title: 'Booking Berhasil Ditambahkan!', text: 'Data booking baru telah tersimpan ke dalam sistem.', confirmButtonColor: '#2e7d4f' });
  } else if (msg === 'sukses_edit') {
    Swal.fire({ icon: 'success', title: 'Booking Berhasil Diperbarui!', text: 'Perubahan data booking berhasil disimpan.', confirmButtonColor: '#2e7d4f' });
  } else if (msg === 'sukses_hapus') {
    Swal.fire({ icon: 'success', title: 'Booking Berhasil Dihapus!', text: 'Data booking telah dihapus dari sistem.', confirmButtonColor: '#2e7d4f' });
  } else if (msg === 'gagal') {
    Swal.fire({ icon: 'error', title: 'Gagal Memproses!', text: detail || 'Terjadi kesalahan sistem.', confirmButtonColor: '#c0392b' });
  }
  if (window.history.replaceState) {
    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
  }
})();
<?php endif; ?>
</script>
</body>
</html>