<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

/* ── Label paket ─────────────────────────────────────────────────────────── */
if (!function_exists('labelPaket')) {
    function labelPaket($kode) {
        $map = [
            'meal_only'                   => 'Meal Only',
            'studi_banding'               => 'Studi Banding',
            'fun_game'                    => 'Paket Fun Game',
            'pelajar_live_in'             => 'Paket Pelajar – Live In',
            'pelajar_field_trip_one_day'  => 'Pelajar – Field Trip 1 Day',
            'pelajar_field_trip_half_day' => 'Pelajar – Field Trip Half Day',
            'cycling_tour'                => 'Cycling Village Tour',
            'traditional_dance'           => 'Traditional Dance',
            'walking_tour'                => 'Walking Around Village',
            'homestay'                    => 'Homestay Candirejo',
            'serenade'                    => 'Serenade Menoreh',
            'cooking_lesson'              => 'Cooking Lesson',
            'gamelan_class'               => 'Gamelan Class',
            'village_experience'          => 'Village Experience',
            'dokar_tour'                  => 'Dokar Village Tour',
            'inspection'                  => 'Inspection',
            'lainnya'                     => 'Lainnya',
        ];
        return isset($map[$kode]) ? $map[$kode] : (!empty($kode) ? ucwords(str_replace('_', ' ', $kode)) : 'Lainnya');
    }
}

/* ── Helper Tanggal Indonesia ────────────────────────────────────────────── */
if (!function_exists('tglIndo')) {
    $bulan_id = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    function tglIndo($date) {
        global $bulan_id;
        if (!$date || $date === '0000-00-00') return '-';
        $ts = strtotime($date);
        return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

/* ── Helper Foto Pengunjung ──────────────────────────────────────────────── */
if (!function_exists('getFotoList')) {
    function getFotoList($db, $id_pengunjung, $kolom_foto) {
        if (!empty($kolom_foto)) {
            $decoded = json_decode($kolom_foto, true);
            if (is_array($decoded) && count($decoded) > 0) return $decoded;
            if (!empty(trim($kolom_foto))) return [trim($kolom_foto)];
        }
        $pid = (int)$id_pengunjung;
        $res = mysqli_query($db, "SELECT nama_file FROM tb_foto_pengunjung WHERE id_pengunjung = $pid ORDER BY id_foto ASC");
        $list = [];
        while ($r = mysqli_fetch_assoc($res)) {
            if (!empty($r['nama_file'])) $list[] = $r['nama_file'];
        }
        return $list;
    }
}

/* ── Filter & Search ─────────────────────────────────────────────────────── */
$search   = isset($_GET['search'])  ? mysqli_real_escape_string($db, trim($_GET['search']))  : '';
$tanggal  = isset($_GET['tanggal']) ? mysqli_real_escape_string($db, trim($_GET['tanggal'])) : '';

$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND (agen_wisata LIKE '%$search%' OR nama LIKE '%$search%' OR pilihan_paket_wisata LIKE '%$search%' OR driver_agent_guide LIKE '%$search%' OR local_guide LIKE '%$search%' OR kota LIKE '%$search%' OR negara LIKE '%$search%')";
}
if ($tanggal !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    $where .= " AND tanggal_kunjungan = '$tanggal'";
}

/* ── Pagination (Default: 10 baris pas satu layar tanpa scroll) ─────────── */
$allowed_per_page = [10, 15, 25, 50];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowed_per_page) ? (int)$_GET['per_page'] : 10;

$count_query = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_data_pengunjung $where");
$count_row   = mysqli_fetch_assoc($count_query);
$total_data  = (int)($count_row['total'] ?? 0);

$total_pages = max(1, (int)ceil($total_data / $per_page));
$page = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;

$sql_all = "SELECT * FROM tb_data_pengunjung $where ORDER BY tanggal_kunjungan DESC, id DESC LIMIT $per_page OFFSET $offset";
$q_all   = mysqli_query($db, $sql_all);
$pengunjungs = [];
while ($r = mysqli_fetch_assoc($q_all)) {
    $pengunjungs[] = $r;
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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Data Pengunjung Wisata - Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <!-- Modern Admin Design System -->
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">
  <link href="css/tabel_modern.css?v=2.3" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* ── CARD & FILTER BAR IDENTIK SISTEM BOOKING (1:1 PIXEL PERFECT) ── */
    .booking-card {
      background: #fff !important;
      border: 1px solid #e5ede8 !important;
      border-radius: 12px !important;
      overflow: visible !important;
      box-shadow: 0 1px 4px rgba(0,0,0,0.04) !important;
      margin-bottom: 24px !important;
    }
    .filter-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      border-bottom: 1px solid #f0f5f2;
      flex-wrap: wrap;
      background: #fff;
      border-radius: 12px 12px 0 0;
    }
    .search-wrap {
      position: relative;
      flex: 1;
      min-width: 180px;
      max-width: 340px;
    }
    .search-wrap svg {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ab5a8;
      pointer-events: none;
    }
    .search-input {
      width: 100%;
      border: 1px solid #d6e6dc;
      border-radius: 8px;
      padding: 7px 12px 7px 32px;
      font-size: 12.5px;
      color: #1e3a2f;
      background: #fff;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
      height: 34px;
    }
    .search-input::placeholder { color: #aec9b8; }
    .search-input:focus { border-color: #2e7d4f; box-shadow: 0 0 0 3px rgba(46,125,79,.1); }

    .filter-select, .filter-date {
      border: 1px solid #d6e6dc;
      border-radius: 8px;
      padding: 6px 11px;
      font-size: 12.5px;
      color: #1e3a2f;
      background: #fff;
      outline: none;
      cursor: pointer;
      transition: border-color 0.15s;
      height: 34px;
    }
    .filter-select:focus, .filter-date:focus { border-color: #2e7d4f; }

    .btn-filter {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      background: #eef4f0;
      border: 1px solid #d6e6dc;
      border-radius: 8px;
      color: #2e7d4f;
      cursor: pointer;
      transition: all 0.15s;
      flex-shrink: 0;
    }
    .btn-filter:hover { background: #2e7d4f; color: #fff; border-color: #2e7d4f; }

    .btn-reset-filter {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 10px;
      font-size: 12px;
      font-weight: 500;
      color: #b03030;
      background: #fdf2f2;
      border: 1px solid #f5c6c6;
      border-radius: 8px;
      text-decoration: none !important;
      transition: all 0.15s;
      height: 34px;
    }
    .btn-reset-filter:hover { background: #fce4e4; color: #8a1f1f; }

    .btn-export-outline {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 0 11px;
      height: 34px;
      font-size: 12px;
      font-weight: 600;
      color: #2e7d4f;
      background: #fff;
      border: 1px solid #c2ded0;
      border-radius: 8px;
      text-decoration: none !important;
      transition: all 0.15s;
    }
    .btn-export-outline:hover { background: #f0f7f3; color: #1e3a2f; border-color: #2e7d4f; }

    .btn-tambah {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      height: 34px;
      padding: 0 14px;
      border: 1px solid #1e3a2f;
      border-radius: 8px;
      background: #1e3a2f;
      color: #fff !important;
      text-decoration: none !important;
      font-size: 12.5px;
      font-weight: 600;
      white-space: nowrap;
      cursor: pointer;
      transition: background 0.15s, border-color 0.15s;
    }
    .btn-tambah:hover { background: #2d5540; border-color: #2d5540; }

    /* ── TABEL ELEGAN IDENTIK WEB BOOKING ── */
    .table-wrapper {
      overflow-x: auto;
      border-radius: 0 0 12px 12px;
    }
    table.tabel-booking {
      width: 100% !important;
      border-collapse: collapse !important;
      text-align: left !important;
      margin: 0 !important;
      border: none !important;
    }
    table.tabel-booking thead tr {
      background: #f8fbf9 !important;
      position: relative;
      z-index: 1;
      border-bottom: 1px solid #e8f0ec !important;
    }
    table.tabel-booking thead th {
      padding: 10px 14px !important;
      font-size: 11.5px !important;
      font-weight: 600 !important;
      color: #5a7d6d !important;
      text-transform: uppercase !important;
      letter-spacing: 0.04em !important;
      border: none !important;
      border-bottom: 1px solid #e8f0ec !important;
      white-space: nowrap !important;
    }
    table.tabel-booking tbody tr {
      border-bottom: 1px solid #f0f5f2 !important;
      transition: background 0.15s;
      position: relative;
      z-index: 1;
      background: #fff;
    }
    table.tabel-booking tbody tr:last-child {
      border-bottom: none !important;
    }
    table.tabel-booking tbody td {
      padding: 9px 14px !important;
      font-size: 13px !important;
      color: #1e3a2f !important;
      vertical-align: middle !important;
      position: relative;
      border: none !important;
      border-bottom: 1px solid #f0f5f2 !important;
    }
    table.tabel-booking tbody tr:hover {
      background: #fafcfa !important;
      position: relative;
      z-index: 200;
    }
    table.tabel-booking tbody tr:hover td {
      position: relative;
      z-index: 200;
    }
    table.tabel-booking tbody tr:hover .btn-action-wrap {
      position: relative;
      z-index: 210;
    }
    table.tabel-booking tbody tr:hover .btn-icon:hover {
      position: relative;
      z-index: 250;
    }
    table.tabel-booking tbody tr:hover .btn-icon:hover::after,
    table.tabel-booking tbody tr:hover .btn-icon:hover::before {
      z-index: 999999;
    }

    /* ── BADGES IDENTIK REFERENSI ── */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }
    .badge-pax {
      background: #e6f7ef !important;
      color: #059669 !important;
      border: 1px solid #a7f3d0 !important;
      border-radius: 20px !important;
      font-weight: 700 !important;
      font-size: 11.5px !important;
      padding: 3px 10px !important;
      white-space: nowrap !important;
      display: inline-block !important;
    }
    .badge-checkin {
      background: #e4f5ec !important;
      color: #2e7d4f !important;
      border: 1px solid #a8d8bc !important;
      border-radius: 20px !important;
    }
    .badge-pending {
      background: #fef9e7 !important;
      color: #b7791f !important;
      border: 1px solid #fde68a !important;
      border-radius: 20px !important;
    }

    /* ── TOMBOL AKSI IKON ── */
    .btn-action-wrap {
      display: flex;
      gap: 4px;
      align-items: center;
      justify-content: flex-end;
      position: relative;
      z-index: 2;
    }
    .btn-icon {
      position: relative;
      width: 28px;
      height: 28px;
      border: none;
      border-radius: 7px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.15s, transform 0.1s;
      flex-shrink: 0;
      text-decoration: none !important;
    }
    .btn-icon:active { transform: scale(0.93); }
    .btn-icon svg { display: block; }
    .btn-detail { background: #f0f4f8; color: #486581; }
    .btn-detail:hover { background: #486581; color: #fff; }
    .btn-edit   { background: #eef3ff; color: #3b6fd4; }
    .btn-edit:hover   { background: #3b6fd4; color: #fff; }
    .btn-hapus  { background: #fceaea; color: #c0392b; }
    .btn-hapus:hover  { background: #c0392b; color: #fff; }

    /* Tooltip */
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

    /* ── PAGINATION & FOOTER IDENTIK REFERENSI ── */
    .table-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      border-top: 1px solid #f0f5f2;
      flex-wrap: wrap;
      gap: 10px;
      font-size: 12.5px;
      color: #6b8f7e;
      background: #fff;
      border-radius: 0 0 12px 12px;
    }
    .pagination {
      display: flex;
      align-items: center;
      gap: 4px;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .page-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 28px;
      height: 28px;
      padding: 0 7px;
      font-size: 12px;
      font-weight: 500;
      border: 1px solid #d6e6dc;
      border-radius: 6px;
      color: #2a4535;
      text-decoration: none !important;
      background: #fff;
      transition: all 0.15s;
    }
    .page-link:hover {
      background: #f4fbf6;
      border-color: #2e7d4f;
      color: #2e7d4f;
    }
    .page-item.active .page-link {
      background: #1e3a2f !important;
      border-color: #1e3a2f !important;
      color: #fff !important;
      font-weight: 600 !important;
    }
    .page-item.disabled .page-link {
      opacity: 0.45;
      pointer-events: none;
      background: #fafcfb;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebar menu -->
      <?php include("sidebarmenu.php"); ?>
      <!-- /Profile and Sidebar menu -->

      <!-- top navigation -->
      <?php include("header.php"); ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>

          <?php if (!empty($_SESSION['notif_hapus'])): ?>
          <div class="alert alert-<?php echo $_SESSION['notif_hapus'] === 'berhasil' ? 'success' : 'danger'; ?> alert-dismissible" role="alert" style="margin-bottom:14px; border-radius:8px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php if ($_SESSION['notif_hapus'] === 'berhasil'): ?>
              <i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> Data pengunjung berhasil dihapus.
            <?php else: ?>
              <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> Terjadi kesalahan saat menghapus data.
            <?php endif; ?>
          </div>
          <?php unset($_SESSION['notif_hapus']); endif; ?>

          <div class="card booking-card">
            <!-- Card Header (Judul & Subtitle di dalam Card Modern) -->
            <div class="card-header-table">
              <div>
                <h1>Data Pengunjung Wisata</h1>
                <p>Kelola seluruh riwayat kunjungan wisatawan Desa Wisata Candirejo</p>
              </div>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="" id="filterForm">
              <div class="filter-bar">
                <!-- Search Live Input -->
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

                <!-- Rows per page (Default 10 baris sesuai gambar referensi) -->
                <select name="per_page" id="perPage" class="filter-select" style="min-width:115px;" onchange="document.getElementById('filterForm').submit()">
                  <option value="10" <?php echo $per_page === 10 ? 'selected' : ''; ?>>10 baris</option>
                  <option value="15" <?php echo $per_page === 15 ? 'selected' : ''; ?>>15 baris</option>
                  <option value="25" <?php echo $per_page === 25 ? 'selected' : ''; ?>>25 baris</option>
                  <option value="50" <?php echo $per_page === 50 ? 'selected' : ''; ?>>50 baris</option>
                </select>

                <!-- Submit filter icon -->
                <button type="submit" class="btn-filter" title="Terapkan Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18M7 9h10M11 14h2M13 19h-2"/>
                  </svg>
                </button>

                <?php if ($search !== '' || $tanggal !== ''): ?>
                <a href="datapengunjung.php" class="btn-reset-filter" title="Reset Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                  Reset
                </a>
                <?php endif; ?>

                <!-- Right side action buttons -->
                <div class="filter-bar-right">
                  <a href="export/export_data_pengunjung.php" class="btn-export-outline" title="Unduh PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                  </a>
                  <a href="export/exportExcel_data_pengunjung.php" class="btn-export-outline" title="Unduh Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                  </a>
                  <a href="inputdatapengunjung.php" class="btn-tambah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Pengunjung
                  </a>
                </div>
              </div>
            </form>

            <!-- Table Wrapper -->
            <div class="table-wrapper">
              <table class="tabel-booking" id="tabelPengunjung">
                <thead>
                  <tr>
                    <th>AGEN WISATA</th>
                    <th>TANGGAL KUNJUNGAN</th>
                    <th>PAX</th>
                    <th>PAKET &amp; LAYANAN</th>
                    <th>DRIVER / GUIDE</th>
                    <th>STATUS</th>
                    <th style="text-align:right; padding-right:16px;">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($pengunjungs)): ?>
                  <tr class="empty-row">
                    <td colspan="7" style="text-align:center; padding: 36px 14px; color:#9ab5a8;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:6px; display:inline-block;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                      <div style="font-size:13px;">Tidak ada data pengunjung yang sesuai.</div>
                    </td>
                  </tr>
                  <?php else: foreach ($pengunjungs as $row):
                    $agenText = !empty(trim((string)($row['agen_wisata'] ?? ''))) ? htmlspecialchars($row['agen_wisata']) : '-';
                    $tamuText = !empty(trim((string)($row['nama'] ?? ''))) ? htmlspecialchars($row['nama']) : '-';

                    $asal = ($row['jenis_wisatawan'] === 'Domestik')
                      ? (!empty($row['kota']) ? $row['kota'] : 'Domestik')
                      : (!empty($row['negara']) ? $row['negara'] : 'Mancanegara');

                    $paket_display = labelPaket($row['pilihan_paket_wisata'] ?? '');

                    $detail_paket = '';
                    $paket_utama  = $row['pilihan_paket_wisata'] ?? '';
                    if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($row['opsi_makan_tour'])) {
                      $detail_paket = ($row['opsi_makan_tour'] === 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                    } elseif ($paket_utama === 'meal_only' && !empty($row['jenis_makanan_paket'])) {
                      $detail_paket = ucfirst($row['jenis_makanan_paket']);
                    } elseif ($paket_utama === 'cooking_lesson' && !empty($row['opsi_cooking_lesson'])) {
                      $detail_paket = ($row['opsi_cooking_lesson'] === 'lesson_with_tour') ? 'Cooking Lesson + Tour' : 'Cooking Lesson Saja';
                    } elseif ($paket_utama === 'gamelan_class' && !empty($row['opsi_gamelan'])) {
                      $detail_paket = ($row['opsi_gamelan'] === 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                    }

                    $driver = !empty(trim((string)($row['driver_agent_guide'] ?? ''))) ? htmlspecialchars($row['driver_agent_guide']) : '-';
                    $guide  = !empty(trim((string)($row['local_guide'] ?? ''))) ? htmlspecialchars($row['local_guide']) : '-';

                    $foto_arr = getFotoList($db, $row['id'], $row['foto'] ?? '');
                  ?>
                  <tr>
                    <td>
                      <div style="font-weight: 600; color: #1e3a2f; font-size: 13px;"><?php echo $agenText; ?></div>
                      <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">Tamu: <?php echo $tamuText; ?></div>
                    </td>
                    <td>
                      <div style="font-weight: 500; font-size: 12.5px; color: #1e3a2f;"><?php echo tglIndo($row['tanggal_kunjungan']); ?></div>
                      <div style="font-size: 11px; color: #7a9e8e;"><?php echo htmlspecialchars($asal); ?></div>
                    </td>
                    <td>
                      <span class="badge badge-pax"><?php echo (int)$row['pax']; ?> Pax</span>
                    </td>
                    <td>
                      <div style="font-size: 12.5px; font-weight: 500; color: #1e3a2f;"><?php echo htmlspecialchars($paket_display); ?></div>
                      <?php if (!empty($detail_paket)): ?>
                        <div style="font-size: 10.5px; color: #2e7d4f; font-weight: 600; margin-top: 1px;">
                          <?php echo htmlspecialchars($detail_paket); ?>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="font-size: 12px; font-weight: 500; color: #2a4535;">D: <?php echo $driver; ?></div>
                      <div style="font-size: 11px; color: #7a9e8e;">G: <?php echo $guide; ?></div>
                    </td>
                    <td>
                      <span class="badge badge-checkin">Check-in</span>
                      <?php if (!empty($foto_arr)): ?>
                        <div style="margin-top: 4px; display: flex; align-items: center; gap: 3px;">
                          <?php foreach (array_slice($foto_arr, 0, 2) as $f): ?>
                            <a href="uploads/pengunjung/<?php echo htmlspecialchars($f); ?>" target="_blank" title="Lihat Foto">
                              <img src="uploads/pengunjung/<?php echo htmlspecialchars($f); ?>" style="width: 20px; height: 20px; object-fit: cover; border-radius: 4px; border: 1px solid #d6e6dc;">
                            </a>
                          <?php endforeach; ?>
                          <?php if (count($foto_arr) > 2): ?>
                            <span style="font-size: 10px; color: #7a9e8e; font-weight: 600;">+<?php echo count($foto_arr)-2; ?></span>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:right; padding-right:16px;">
                      <div class="btn-action-wrap">
                        <!-- Detail -->
                        <a href="detail-datapengunjung.php?id=<?php echo urlencode($row['id']); ?>" class="btn-icon btn-detail" data-tooltip="Lihat Detail" title="Lihat Detail">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <!-- Edit -->
                        <a href="editpengunjung.php?id=<?php echo urlencode($row['id']); ?>" class="btn-icon btn-edit" data-tooltip="Edit Data" title="Edit Data">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                        </a>
                        <!-- Hapus -->
                        <button type="button" class="btn-icon btn-hapus" data-tooltip="Hapus Data" title="Hapus Data" onclick="konfirmasiHapus(<?php echo (int)$row['id']; ?>, '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Footer & Pagination Identik Referensi -->
            <div class="table-footer">
              <div>
                Menampilkan <strong><?php echo $total_data > 0 ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $per_page, $total_data); ?></strong> dari <strong><?php echo $total_data; ?></strong> data pengunjung
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

        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-left text-muted" style="font-size:12px;">
          © <?php echo date("Y"); ?> Desa Wisata Candirejo Borobudur
        </div>
        <div class="pull-right text-muted" style="font-size:12px;">
          Supported by DRTPM KEMDIKBUDRISTEK
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
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script type="text/javascript">
  function konfirmasiHapus(id, nama) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Data Pengunjung?',
        text: 'Data pengunjung "' + (nama || '') + '" akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c0392b',
        cancelButtonColor: '#6b8f7e',
        confirmButtonText: '<i class="fa fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'proses/proses_hapusdatapengunjung.php?id=' + id;
        }
      });
    } else {
      if (confirm('Yakin ingin menghapus data pengunjung ini?')) {
        window.location.href = 'proses/proses_hapusdatapengunjung.php?id=' + id;
      }
    }
  }

  $(document).ready(function() {
    function adjustContentHeight() {
      var windowH = $(window).height();
      var leftH = $('.left_col').outerHeight() || 0;
      var navH = $('.nav_menu').outerHeight() || $('.top_nav').outerHeight() || 50;
      var footerH = $('footer').outerHeight() || 40;
      var minH = Math.max(windowH, leftH) - navH - footerH;
      $('.right_col').css('min-height', minH > 200 ? minH : windowH);
    }

    // Instant client-side live filter while typing
    $('#searchInput').on('input', function() {
      var val = $(this).val().toLowerCase();
      $('#tabelPengunjung tbody tr').filter(function() {
        if ($(this).hasClass('empty-row')) return;
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
      });
    });

    adjustContentHeight();
    $(window).on('resize', adjustContentHeight);
    setTimeout(adjustContentHeight, 200);
  });
  </script>

</body>

</html>