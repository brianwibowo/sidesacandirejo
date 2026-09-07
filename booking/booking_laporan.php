<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

if (!function_exists('tglIndo')) {
    function tglIndo($date) {
        global $bulan_id;
        if (!$date) return '-';
        $ts = strtotime($date);
        return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

if (!function_exists('hariIndo')) {
    function hariIndo($date) {
        if (!$date) return '';
        $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return $days[(int)date('w', strtotime($date))];
    }
}

/* ── Filter tanggal (Default: Bulan Berjalan) ────────────────────────────── */
$tgl_mulai = isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] !== '' ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] !== '' ? $_GET['tgl_akhir'] : date('Y-m-t');
$show      = true;

$total_booking  = 0;
$total_checkin  = 0;
$total_tidakdatang = 0;
$total_pax      = 0;
$rekap_harian   = [];
$detail_booking = [];
$chart_labels        = [];
$chart_data          = [];
$chart_data_dom      = [];
$chart_data_manca    = [];
$pie_checkin    = 0;
$pie_pending    = 0;
$pie_tidak      = 0;

$paket_map = [
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

if ($show) {
    $tgl_mulai_esc = mysqli_real_escape_string($db, $tgl_mulai);
    $tgl_akhir_esc = mysqli_real_escape_string($db, $tgl_akhir);

    /* ── Stat total ── */
    try {
        $q = mysqli_query($db,
            "SELECT status, COUNT(*) as jml, SUM(pax) as total_pax
             FROM tb_booking
             WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
             GROUP BY status");
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $total_booking += (int)$r['jml'];
                $total_pax     += (int)$r['total_pax'];
                if ($r['status'] === 'checkin')      { $total_checkin     += (int)$r['jml']; $pie_checkin = (int)$r['jml']; }
                if ($r['status'] === 'tidak_hadir')  { $total_tidakdatang += (int)$r['jml']; $pie_tidak   = (int)$r['jml']; }
                if ($r['status'] === 'pending')      { $pie_pending        = (int)$r['jml']; }
            }
        }
    } catch (Throwable $e) {
        // Log atau lanjutkan jika tabel belum ada data
    }

    /* ── Rekap per hari ── */
    try {
        $q2 = mysqli_query($db,
            "SELECT tanggal_kunjungan,
                    COUNT(*) as total,
                    SUM(CASE WHEN status='checkin'          THEN 1 ELSE 0 END) as jml_checkin,
                    SUM(CASE WHEN status='tidak_hadir'      THEN 1 ELSE 0 END) as jml_tidak,
                    SUM(pax) as total_pax,
                    SUM(CASE WHEN jenis_wisatawan='Domestik'    THEN 1 ELSE 0 END) as jml_domestik,
                    SUM(CASE WHEN jenis_wisatawan!='Domestik'   THEN 1 ELSE 0 END) as jml_mancanegara
             FROM tb_booking
             WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
             GROUP BY tanggal_kunjungan
             ORDER BY tanggal_kunjungan ASC");
        if ($q2) {
            while ($r = mysqli_fetch_assoc($q2)) {
                $rekap_harian[] = $r;
                // Label singkat untuk chart: "11 Apr"
                $ts = strtotime($r['tanggal_kunjungan']);
                $chart_labels[]     = date('j', $ts) . ' ' . substr($bulan_id[(int)date('n',$ts)], 0, 3);
                $chart_data[]       = (int)$r['total'];
                $chart_data_dom[]   = (int)$r['jml_domestik'];
                $chart_data_manca[] = (int)$r['jml_mancanegara'];
            }
        }
    } catch (Throwable $e) {
        // Fallback jika terjadi error
    }

    /* ── Detail semua booking ── */
    try {
        $q3 = mysqli_query($db,
            "SELECT id, nama, agen_wisata, tanggal_kunjungan, pax, pilihan_paket_wisata, opsi_makan_tour, status, driver_agent_guide, local_guide, catatan
             FROM tb_booking
             WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
             ORDER BY tanggal_kunjungan ASC, id ASC");
        if ($q3) {
            while ($r = mysqli_fetch_assoc($q3)) {
                $detail_booking[] = $r;
            }
        }
    } catch (Throwable $e) {
        // Fallback jika kolom catatan belum ada di database lawas
        $q3 = @mysqli_query($db,
            "SELECT id, nama, agen_wisata, tanggal_kunjungan, pax, pilihan_paket_wisata, opsi_makan_tour, status, driver_agent_guide, local_guide, '' AS catatan
             FROM tb_booking
             WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
             ORDER BY tanggal_kunjungan ASC, id ASC");
        if ($q3) {
            while ($r = mysqli_fetch_assoc($q3)) {
                $detail_booking[] = $r;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Booking - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
    .content-inner { padding: 28px 28px 40px; }

    /* ── REPORT CONTROL CARD (HEADER & FILTER UNIFIED IN 1 CARD) ── */
    .report-control-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      margin-bottom: 22px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .card-header-report {
      padding: 18px 24px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
      border-bottom: 1px solid #f0f5f2;
      background: #fff;
    }
    .report-title-content {
      flex: 1;
      min-width: 250px;
    }
    .report-title-content h1 {
      font-size: 20px;
      font-weight: 600;
      color: #1e3a2f;
      margin: 0 0 4px 0;
      letter-spacing: -0.01em;
    }
    .report-title-content p {
      font-size: 13px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
    }
    .report-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }
    .btn-export {
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
      flex-shrink: 0;
    }
    .btn-export:hover {
      background: #2d5540;
      color: #fff !important;
    }

    /* ── CARD ── */
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; }

    /* ── FILTER SECTION INSIDE CONTROL CARD ── */
    .card-filter-section {
      padding: 16px 24px 18px;
      background: #fafcfb;
    }
    .filter-row  { display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin: 0; }
    .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .filter-group label {
      font-size: 11.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #5a7d6d;
      margin: 0;
    }
    .filter-input {
      border: 1px solid #d6e6dc;
      border-radius: 8px;
      padding: 8.5px 12px 8.5px 36px;
      font-size: 13px;
      color: #1e3a2f;
      background: #fff;
      outline: none;
      transition: border-color 0.15s;
      width: 100%;
      height: 38px;
      box-sizing: border-box;
    }
    .filter-input:focus { border-color: #2e7d4f; }
    .input-wrap { position: relative; }
    .input-wrap svg {
      position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
      color: #9ab5a8; pointer-events: none;
    }
    .btn-tampilkan {
      background: #1e3a2f;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0 24px;
      height: 38px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      align-self: flex-end;
    }
    .btn-tampilkan:hover { background: #2d5540; }

    /* ── STAT CARDS ── */
    .stat-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 20px; }
    .stat-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; padding: 18px 22px; }
    .stat-label { font-size: 12.5px; color: #7a9e8e; margin-bottom: 8px; }
    .stat-value { font-size: 28px; font-weight: 700; line-height: 1; color: #1e3a2f; }
    .stat-value.green { color: #2e7d4f; }
    .stat-value.red   { color: #c0392b; }

    /* ── CHART ROW ── */
    .chart-row { display: grid; grid-template-columns: 1fr 340px; gap: 20px; margin-bottom: 24px; }
    @media (max-width: 1080px) {
      .chart-row { grid-template-columns: 1fr; }
    }
    .chart-card {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      padding: 22px 24px; display: flex; flex-direction: column;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .chart-card-header {
      display: flex; align-items: flex-start; justify-content: space-between;
      flex-wrap: wrap; gap: 10px; margin-bottom: 16px;
    }
    .chart-title { font-size: 15.5px; font-weight: 700; color: #1e3a2f; }
    .chart-subtitle { font-size: 12px; color: #7a9e8e; margin-top: 2px; }
    .chart-badge-summary {
      display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: #436956;
      background: #f4f8f5; border: 1px solid #e0ece5; padding: 4px 12px; border-radius: 20px;
    }
    .legend-indicator {
      display: inline-block; width: 8px; height: 8px; border-radius: 50%;
    }
    .dot-manca { background: #2e7d4f; }
    .dot-dom   { background: #133827; }

    .chart-canvas-wrap { position: relative; height: 270px; flex: 1; }

    /* Donut container & center label */
    .donut-wrap {
      position: relative; height: 185px; margin: 4px 0 14px;
      display: flex; align-items: center; justify-content: center;
    }
    .donut-wrap canvas { position: relative; z-index: 2; }
    .donut-center-info {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      text-align: center; pointer-events: none; z-index: 1;
    }
    .donut-total { display: block; font-size: 26px; font-weight: 800; color: #1e3a2f; line-height: 1; }
    .donut-label { display: block; font-size: 10.5px; color: #7a9e8e; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

    /* Donut Legend List */
    .donut-legend-list { display: flex; flex-direction: column; gap: 8px; padding-top: 12px; border-top: 1px solid #f0f5f2; }
    .donut-legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; }
    .donut-legend-left { display: flex; align-items: center; gap: 8px; color: #3a5c4c; font-weight: 500; }
    .status-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .dot-pending  { background: #f59e0b; box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2); }
    .dot-checkin  { background: #10b981; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2); }
    .dot-tidak    { background: #ef4444; box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2); }
    .donut-legend-right { display: flex; align-items: center; gap: 8px; }
    .status-val { font-weight: 700; color: #1e3a2f; }
    .status-pct { font-size: 11px; font-weight: 600; color: #6b8f7e; min-width: 40px; text-align: right; background: #f2f7f4; padding: 2px 6px; border-radius: 4px; }

    /* ── CARD & TABLE ── */
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: hidden; }
    .card-header { padding: 14px 22px 12px; border-bottom: 1px solid #f0f5f2; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .card-header h2 { font-size: 16px; font-weight: 600; color: #1e3a2f; }
    .card-header-right { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #7a9e8e; }
    .show-select { font-size: 12.5px; color: #1e3a2f; border: 1px solid #d6e6dc; border-radius: 6px; padding: 4px 8px; background: #fafcfb; cursor: pointer; outline: none; }
    .show-select:focus { border-color: #2e7d4f; }

    .table-wrapper { overflow-x: auto; border-radius: 0 0 12px 12px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8fbf9; position: relative; z-index: 1; }
    thead th {
      padding: 10px 14px; text-align: left;
      font-size: 11.5px; font-weight: 600; color: #5a7d6d;
      text-transform: uppercase; letter-spacing: 0.04em;
      border-bottom: 1px solid #e8f0ec; white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid #f0f5f2; transition: background 0.15s; position: relative; z-index: 1; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafcfa; }
    tbody td { padding: 10px 14px; font-size: 13px; color: #1e3a2f; vertical-align: middle; position: relative; }
    .num-green { color: #2e7d4f; font-weight: 600; }
    .num-red   { color: #c0392b; font-weight: 600; }
    .empty-row td { text-align: center; color: #9ab5a8; padding: 36px; font-size: 13.5px; font-style: italic; }

    /* ── REKAP SCROLL & SOLID STICKY TABLE ── */
    .rekap-scroll {
      max-height: 400px;
      overflow-y: auto;
      overflow-x: auto;
      position: relative;
    }
    .rekap-scroll::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .rekap-scroll::-webkit-scrollbar-track {
      background: #f2f7f4;
      border-radius: 4px;
    }
    .rekap-scroll::-webkit-scrollbar-thumb {
      background: #b8d2c3;
      border-radius: 4px;
    }
    .rekap-scroll::-webkit-scrollbar-thumb:hover {
      background: #7a9e8e;
    }

    .rekap-table {
      width: 100%;
      border-collapse: separate !important;
      border-spacing: 0 !important;
    }
    .rekap-table thead {
      position: static;
    }
    .rekap-table thead tr {
      position: static !important;
      background: transparent !important;
    }
    .rekap-table thead th {
      position: sticky !important;
      top: 0 !important;
      z-index: 50 !important;
      background-color: #edf5f0 !important;
      color: #2b543f !important;
      font-size: 11.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 12px 16px;
      border-top: none;
      border-bottom: 2px solid #b8d7c4 !important;
      box-shadow: 0 2px 6px rgba(15, 45, 30, 0.08);
      white-space: nowrap;
    }

    .rekap-table tbody tr {
      position: static !important;
      z-index: auto !important;
      transition: background 0.15s;
    }
    .rekap-table tbody tr:hover {
      position: static !important;
      z-index: auto !important;
    }
    .rekap-table tbody td {
      padding: 11px 16px;
      border-bottom: 1px solid #ebf2ee;
      font-size: 13px;
      color: #1e3a2f;
      vertical-align: middle;
      position: static !important;
      z-index: auto !important;
      background-color: #ffffff;
    }
    .rekap-table tbody tr:nth-child(even) td {
      background-color: #fafcfb;
    }
    .rekap-table tbody tr:hover td {
      background-color: #f0f7f3 !important;
    }

    .rekap-table tfoot {
      position: static;
    }
    .rekap-table tfoot tr {
      position: static !important;
    }
    .rekap-table tfoot td {
      position: sticky !important;
      bottom: 0 !important;
      z-index: 45 !important;
      background-color: #e5f0ea !important;
      border-top: 2px solid #b8d7c4 !important;
      border-bottom: none;
      box-shadow: 0 -2px 6px rgba(15, 45, 30, 0.06);
      padding: 13px 16px;
      font-size: 13.5px;
      color: #1e3a2f;
      vertical-align: middle;
    }

    /* ── BADGE ── */
    .badge {
      display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px;
      font-size: 11.5px; font-weight: 500; white-space: nowrap;
    }
    .badge-pending     { background: #fdf0e0; color: #c0742a; border: 1px solid #f5d9a8; }
    .badge-checkin     { background: #e4f5ec; color: #2e7d4f; border: 1px solid #a8d8bc; }
    .badge-tidak_hadir { background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8; }

    /* ── TOMBOL AKSI IKON & TOOLTIP ── */
    .btn-action-wrap { display: flex; gap: 4px; align-items: center; justify-content: center; position: relative; z-index: 2; }
    .btn-icon {
      position: relative; width: 28px; height: 28px; border: none; border-radius: 7px;
      display: inline-flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background 0.15s, transform 0.1s; flex-shrink: 0;
      text-decoration: none;
    }
    .btn-icon:active { transform: scale(0.93); }
    .btn-icon svg { display: block; }
    .btn-detail { background: #f0f4f8; color: #486581; }
    .btn-detail:hover { background: #486581; color: #fff; }

    /* Tooltip & Stacking Context */
    tbody tr:hover { background: #fafcfa; position: relative; z-index: 200; }
    tbody tr:hover td { position: relative; z-index: 200; }
    tbody tr:hover .btn-action-wrap { position: relative; z-index: 210; }
    tbody tr:hover .btn-icon:hover { position: relative; z-index: 250; }
    tbody tr:hover .btn-icon:hover::after,
    tbody tr:hover .btn-icon:hover::before { z-index: 999999; }
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
    .btn-icon:hover::after, .btn-icon:hover::before { opacity: 1; }

    /* ── TABLE FOOTER & PAGINATION ── */
    .table-footer {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 18px; border-top: 1px solid #f0f5f2; flex-wrap: wrap; gap: 10px;
      font-size: 12.5px; color: #6b8f7e;
    }
    .pagination { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
    .page-link {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 28px; height: 28px; padding: 0 7px; font-size: 12px; font-weight: 500;
      border: 1px solid #d6e6dc; border-radius: 6px; color: #2a4535;
      text-decoration: none; background: #fff; transition: all 0.15s; cursor: pointer;
    }
    .page-link:hover { background: #f4fbf6; border-color: #2e7d4f; color: #2e7d4f; }
    .page-item.active .page-link { background: #1e3a2f; border-color: #1e3a2f; color: #fff; font-weight: 600; cursor: default; }
    .page-item.disabled .page-link { opacity: 0.45; pointer-events: none; background: #fafcfb; cursor: default; }

    /* ── PAGE FOOTER ── */
    .booking-footer {
      margin-top: 40px; padding: 14px 28px;
      font-size: 12px; color: #9ab5a8; border-top: 1px solid #e5ede8;
      text-align: center;
    }
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>

<main class="booking-content" id="bookingContent">
  <?php include 'booking_header.php'; ?>

  <div class="content-inner">

    <!-- Header & Filter Unified in 1 Card -->
    <div class="card report-control-card">
      <div class="card-header-report">
        <div class="report-title-content">
          <h1>Laporan Booking</h1>
          <p>Analisis dan statistik booking pengunjung</p>
        </div>
        <?php if ($show): ?>
        <div class="report-actions">
          <a href="cetak_laporan.php?tgl_mulai=<?php echo urlencode($tgl_mulai); ?>&tgl_akhir=<?php echo urlencode($tgl_akhir); ?>" target="_blank" class="btn-export">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export PDF
          </a>
        </div>
        <?php endif; ?>
      </div>

      <div class="card-filter-section">
        <form method="GET" action="" id="filterForm">
          <div class="filter-row">
            <div class="filter-group">
              <label>Tanggal Mulai</label>
              <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="date" name="tgl_mulai" class="filter-input"
                  value="<?php echo htmlspecialchars($tgl_mulai ?? ''); ?>">
              </div>
            </div>
            <div class="filter-group">
              <label>Tanggal Akhir</label>
              <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="date" name="tgl_akhir" class="filter-input"
                  value="<?php echo htmlspecialchars($tgl_akhir ?? ''); ?>">
              </div>
            </div>
            <button type="submit" class="btn-tampilkan">Tampilkan Laporan</button>
          </div>
        </form>
      </div>
    </div>

    <?php if ($show): ?>

    <!-- Stat Cards -->
    <div class="stat-cards">
      <div class="stat-card">
        <div class="stat-label">Total Booking</div>
        <div class="stat-value"><?php echo $total_booking; ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Total Check-in</div>
        <div class="stat-value green"><?php echo $total_checkin; ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Total Tidak Datang</div>
        <div class="stat-value red"><?php echo $total_tidakdatang; ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Total Pax</div>
        <div class="stat-value"><?php echo $total_pax; ?></div>
      </div>
    </div>

    <!-- Charts -->
    <div class="chart-row">
      <!-- Bar chart -->
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Tren Kunjungan Harian</div>
            <div class="chart-subtitle">Distribusi wisatawan mancanegara &amp; domestik</div>
          </div>
          <div class="chart-badge-summary">
            <span class="legend-indicator dot-manca"></span> Mancanegara: <strong><?php echo array_sum($chart_data_manca); ?></strong>
            <span class="legend-indicator dot-dom" style="margin-left: 10px;"></span> Domestik: <strong><?php echo array_sum($chart_data_dom); ?></strong>
          </div>
        </div>
        <div class="chart-canvas-wrap">
          <canvas id="chartBar"></canvas>
        </div>
      </div>
      <!-- Donut chart -->
      <div class="chart-card">
        <div class="chart-card-header">
          <div>
            <div class="chart-title">Status Booking</div>
            <div class="chart-subtitle">Proporsi status reservasi</div>
          </div>
        </div>
        <div class="donut-wrap">
          <canvas id="chartPie"></canvas>
          <div class="donut-center-info">
            <span class="donut-total"><?php echo $total_booking; ?></span>
            <span class="donut-label">Booking</span>
          </div>
        </div>
        <div class="donut-legend-list">
          <div class="donut-legend-item">
            <div class="donut-legend-left">
              <span class="status-dot dot-pending"></span>
              <span>Pending</span>
            </div>
            <div class="donut-legend-right">
              <span class="status-val"><?php echo $pie_pending; ?></span>
              <span class="status-pct"><?php echo $total_booking > 0 ? number_format(($pie_pending/$total_booking)*100, 1) : '0'; ?>%</span>
            </div>
          </div>
          <div class="donut-legend-item">
            <div class="donut-legend-left">
              <span class="status-dot dot-checkin"></span>
              <span>Check-in</span>
            </div>
            <div class="donut-legend-right">
              <span class="status-val"><?php echo $pie_checkin; ?></span>
              <span class="status-pct"><?php echo $total_booking > 0 ? number_format(($pie_checkin/$total_booking)*100, 1) : '0'; ?>%</span>
            </div>
          </div>
          <div class="donut-legend-item">
            <div class="donut-legend-left">
              <span class="status-dot dot-tidak"></span>
              <span>Tidak Hadir</span>
            </div>
            <div class="donut-legend-right">
              <span class="status-val"><?php echo $pie_tidak; ?></span>
              <span class="status-pct"><?php echo $total_booking > 0 ? number_format(($pie_tidak/$total_booking)*100, 1) : '0'; ?>%</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rekap Tabel -->
    <div class="card" style="margin-bottom: 24px;">
      <div class="card-header">
        <div style="display: flex; align-items: center; gap: 8px;">
          <h2>Rekap Data per Hari</h2>
          <span style="font-size: 12.5px; color: #7a9e8e;">&bull; <?php echo count($rekap_harian); ?> Hari Kunjungan</span>
        </div>
        <div style="font-size: 12.5px; color: #6b8f7e;">
          Total: <strong style="color: #1e3a2f;"><?php echo $total_booking; ?></strong> Booking &bull; <strong style="color: #2e7d4f;"><?php echo $total_pax; ?></strong> Pax
        </div>
      </div>
      <div class="table-wrapper rekap-scroll">
        <table class="rekap-table">
          <thead>
            <tr>
              <th style="width: 32%;">Tanggal &amp; Hari</th>
              <th style="text-align: center; width: 17%;">Total Booking</th>
              <th style="text-align: center; width: 17%;">Check-in</th>
              <th style="text-align: center; width: 17%;">Tidak Hadir</th>
              <th style="text-align: center; width: 17%;">Total Pax</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($rekap_harian)): ?>
            <tr class="empty-row"><td colspan="5">Tidak ada data pada rentang tanggal ini</td></tr>
            <?php else: foreach ($rekap_harian as $r): ?>
            <tr>
              <td>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                  <span style="font-weight: 600; color: #1e3a2f; font-size: 13.5px;"><?php echo tglIndo($r['tanggal_kunjungan']); ?></span>
                  <span style="font-size: 11px; font-weight: 600; color: #3a6b52; background: #eaf4ee; border: 1px solid #d2e7da; padding: 2px 8px; border-radius: 4px; letter-spacing: 0.02em;">
                    <?php echo hariIndo($r['tanggal_kunjungan']); ?>
                  </span>
                </div>
              </td>
              <td style="text-align: center;">
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 26px; padding: 0 10px; background: #eef5f1; border: 1px solid #d8e8de; border-radius: 13px; font-weight: 700; font-size: 13px; color: #1e3a2f;">
                  <?php echo (int)$r['total']; ?>
                </span>
              </td>
              <td style="text-align: center;">
                <?php if ($r['jml_checkin'] > 0): ?>
                  <span class="badge badge-checkin" style="font-weight: 700; padding: 3px 10px; font-size: 12px;"><?php echo (int)$r['jml_checkin']; ?></span>
                <?php else: ?>
                  <span style="color: #9ab5a8; font-size: 13px; font-weight: 500;">0</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <?php if ($r['jml_tidak'] > 0): ?>
                  <span class="badge badge-tidak_hadir" style="font-weight: 700; padding: 3px 10px; font-size: 12px;"><?php echo (int)$r['jml_tidak']; ?></span>
                <?php else: ?>
                  <span style="color: #9ab5a8; font-size: 13px; font-weight: 500;">0</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <span style="display: inline-flex; align-items: center; justify-content: center; padding: 3px 12px; background: #eaf4ee; border: 1px solid #d4e7db; border-radius: 6px; font-weight: 700; font-size: 12.5px; color: #1e4530;">
                  <?php echo (int)$r['total_pax']; ?> pax
                </span>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="font-weight: 800; color: #1e3a2f; letter-spacing: 0.02em;">TOTAL KESELURUHAN</td>
              <td style="text-align: center; font-weight: 800; font-size: 15px; color: #1e3a2f;">
                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 28px; padding: 0 12px; background: #ddece3; border: 1px solid #c2ded0; border-radius: 14px; color: #153826;">
                  <?php echo $total_booking; ?>
                </span>
              </td>
              <td style="text-align: center; font-weight: 800; font-size: 14px; color: #2e7d4f;">
                <?php echo $total_checkin; ?>
              </td>
              <td style="text-align: center; font-weight: 800; font-size: 14px; color: #c0392b;">
                <?php echo $total_tidakdatang; ?>
              </td>
              <td style="text-align: center;">
                <span style="display: inline-block; padding: 5px 14px; background: #1e3a2f; color: #fff; border-radius: 6px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                  <?php echo $total_pax; ?> pax
                </span>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Detail Reservasi Booking Tabel -->
    <div class="card">
      <div class="card-header">
        <div style="display: flex; align-items: center; gap: 8px;">
          <h2>Detail Reservasi Booking (<span id="countDisplay"><?php echo count($detail_booking); ?></span> Data)</h2>
          <span style="font-size: 12.5px; color: #7a9e8e;">&bull; Periode: <?php echo tglIndo($tgl_mulai); ?> &mdash; <?php echo tglIndo($tgl_akhir); ?></span>
        </div>
        <div class="card-header-right">
          <div style="position: relative;">
            <input type="text" id="detailSearchInput" placeholder="Cari agen, tamu, driver..." oninput="updateDetailTable(1)"
              style="padding: 6px 12px 6px 30px; font-size: 12.5px; border: 1px solid #d6e6dc; border-radius: 7px; outline: none; width: 200px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="position: absolute; left: 9px; top: 50%; transform: translateY(-50%); color: #9ab5a8; pointer-events: none;">
              <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
          </div>
          <label for="detailShowPerPage">Tampilkan</label>
          <select id="detailShowPerPage" class="show-select" onchange="updateDetailTable(1)">
            <option value="10">10</option>
            <option value="15" selected>15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="99999">Semua</option>
          </select>
          <span>data</span>
        </div>
      </div>
      <div class="table-wrapper">
        <table id="detailBookingTable">
          <thead>
            <tr>
              <th style="width: 40px; text-align: center;">No</th>
              <th style="min-width: 170px;">Agen Wisata</th>
              <th style="width: 130px;">Tanggal Kunjungan</th>
              <th style="width: 80px; text-align: center;">Pax</th>
              <th style="min-width: 170px;">Paket &amp; Layanan</th>
              <th style="min-width: 200px;">Driver / Guide &amp; Catatan</th>
              <th style="width: 100px; text-align: center;">Status</th>
              <th style="width: 60px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody id="detailTableBody">
            <?php if (empty($detail_booking)): ?>
            <tr class="empty-row"><td colspan="8">Tidak ada data detail booking pada rentang tanggal ini</td></tr>
            <?php else: 
              $no = 1;
              foreach ($detail_booking as $d):
                $st = $d['status'];
                $bl = $st === 'checkin' ? 'Check-in' : ($st === 'pending' ? 'Pending' : 'Tidak Hadir');
                $bc = 'badge-' . ($st === 'tidak_hadir' ? 'tidak_hadir' : $st);
                $pk = isset($paket_map[$d['pilihan_paket_wisata']]) ? $paket_map[$d['pilihan_paket_wisata']] : ucwords(str_replace('_',' ',$d['pilihan_paket_wisata']));
                $nama_agen = !empty($d['agen_wisata']) ? $d['agen_wisata'] : (!empty($d['nama']) ? $d['nama'] : '-');
                $opsi_makan = '';
                if ($d['opsi_makan_tour'] === 'with_lunch') $opsi_makan = 'With Lunch';
                elseif ($d['opsi_makan_tour'] === 'without_lunch') $opsi_makan = 'Without Lunch';
                $driver_txt = $d['driver_agent_guide'] ?: 'Belum Ada';
                $guide_txt = $d['local_guide'] ?: 'Belum Ada';
            ?>
            <tr class="detail-row" data-search="<?php echo htmlspecialchars(strtolower($nama_agen . ' ' . $d['nama'] . ' ' . $pk . ' ' . $driver_txt . ' ' . $guide_txt . ' ' . ($d['catatan'] ?? ''))); ?>">
              <td style="text-align: center; color: #7a9e8e; font-size: 12px;" class="row-num"><?php echo $no++; ?></td>
              <td>
                <div style="font-weight: 600; color: #1e3a2f; font-size: 13.5px;"><?php echo htmlspecialchars($nama_agen); ?></div>
                <?php if (!empty($d['agen_wisata']) && !empty($d['nama']) && $d['nama'] !== $d['agen_wisata'] && $d['nama'] !== '-'): ?>
                  <div style="font-size: 12px; color: #7a9e8e; margin-top: 2px;">Tamu: <?php echo htmlspecialchars($d['nama']); ?></div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-weight: 500;"><?php echo tglIndo($d['tanggal_kunjungan']); ?></div>
              </td>
              <td style="text-align: center;">
                <span style="display:inline-block;padding:3px 9px;background:#f0f5f2;border-radius:6px;font-weight:600;font-size:12.5px;color:#2a4535;">
                  <?php echo (int)$d['pax']; ?> pax
                </span>
              </td>
              <td>
                <div style="font-weight: 500; color: #1e3a2f;"><?php echo htmlspecialchars($pk); ?></div>
                <?php if ($opsi_makan): ?>
                  <div style="font-size: 11.5px; color: #2e7d4f; font-weight: 600; margin-top: 3px; display: inline-block; background: #e8f7ee; padding: 1px 8px; border-radius: 4px;">
                    <?php echo $opsi_makan; ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-size: 12px; font-weight: 500; color: #2a4535;">D: <?php echo htmlspecialchars($driver_txt); ?></div>
                <div style="font-size: 11px; color: #7a9e8e;">G: <?php echo htmlspecialchars($guide_txt); ?></div>
                <?php if (!empty($d['catatan'])): ?>
                  <div style="margin-top: 5px; padding: 4px 8px; background: #fffdf7; border: 1px dashed #d6cca8; border-radius: 5px; font-size: 11.5px; color: #7a5c2e; font-style: italic;">
                    <strong>Catatan:</strong> <?php echo nl2br(htmlspecialchars($d['catatan'])); ?>
                  </div>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <span class="badge <?php echo $bc; ?>"><?php echo $bl; ?></span>
              </td>
              <td style="text-align: center;">
                <div class="btn-action-wrap">
                  <a href="detail_booking.php?id=<?php echo $d['id']; ?>&ref=booking_laporan.php" class="btn-icon btn-detail" data-tooltip="Lihat Detail" title="Lihat Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
      <div class="table-footer">
        <div id="detailTableInfo">Menampilkan 0 data</div>
        <ul id="detailPagination" class="pagination"></ul>
      </div>
    </div>

    <?php endif; ?>

  </div><!-- /.content-inner -->

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<?php if ($show): ?>
<script>
/* ── Bar Chart (Stacked Domestik & Mancanegara) ── */
window._rebuildBarChart = function () {
  const canvasEl = document.getElementById('chartBar');
  if (!canvasEl) return;
  const ctxBar = canvasEl.getContext('2d');
  
  // Custom gradient for Mancanegara
  const gradManca = ctxBar.createLinearGradient(0, 0, 0, 260);
  gradManca.addColorStop(0, '#2e7d4f');
  gradManca.addColorStop(1, '#439d67');

  // Custom gradient for Domestik
  const gradDom = ctxBar.createLinearGradient(0, 0, 0, 260);
  gradDom.addColorStop(0, '#133827');
  gradDom.addColorStop(1, '#1b4d36');

  if (window._chartBar) {
    window._chartBar.destroy();
  }

  window._chartBar = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($chart_labels); ?>,
      datasets: [
        {
          label: 'Wisatawan Domestik',
          data: <?php echo json_encode($chart_data_dom); ?>,
          backgroundColor: gradDom,
          hoverBackgroundColor: '#0e2b1e',
          borderRadius: { topLeft: 0, topRight: 0, bottomLeft: 4, bottomRight: 4 },
          borderSkipped: false,
          maxBarThickness: 28,
        },
        {
          label: 'Wisatawan Mancanegara',
          data: <?php echo json_encode($chart_data_manca); ?>,
          backgroundColor: gradManca,
          hoverBackgroundColor: '#246b41',
          borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
          borderSkipped: false,
          maxBarThickness: 28,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(20, 45, 32, 0.94)',
          titleColor: '#ffffff',
          bodyColor: '#e0ece5',
          titleFont: { size: 12.5, weight: 'bold' },
          bodyFont: { size: 12 },
          padding: 12,
          cornerRadius: 8,
          usePointStyle: true,
          callbacks: {
            footer: function(items) {
              let total = 0;
              items.forEach(function(i) { total += i.parsed.y; });
              return 'Total: ' + total + ' Booking';
            }
          }
        }
      },
      scales: {
        x: {
          stacked: true,
          grid: { display: false },
          ticks: {
            color: '#7a9e8e',
            font: { size: 11, weight: '500' },
            maxRotation: 45,
            minRotation: 0,
            autoSkip: true,
            maxTicksLimit: 16
          }
        },
        y: {
          stacked: true,
          beginAtZero: true,
          ticks: {
            color: '#7a9e8e',
            font: { size: 11.5 },
            stepSize: 1,
            precision: 0,
            callback: v => Number.isInteger(v) ? v : ''
          },
          grid: {
            color: '#eef4f0',
            borderDash: [4, 4],
            drawBorder: false,
          }
        }
      }
    }
  });
};

window._rebuildBarChart();

/* ── Donut Chart (Status Booking) ── */
window._rebuildPieChart = function () {
  const canvasEl = document.getElementById('chartPie');
  if (!canvasEl) return;
  const ctxPie = canvasEl.getContext('2d');
  
  if (window._chartPie) {
    window._chartPie.destroy();
  }

  const pieData   = [<?php echo $pie_pending; ?>, <?php echo $pie_checkin; ?>, <?php echo $pie_tidak; ?>];
  const pieLabels = ['Pending', 'Check-in', 'Tidak Hadir'];
  const pieColors = ['#f59e0b', '#10b981', '#ef4444'];
  const pieHover  = ['#d97706', '#059669', '#dc2626'];

  window._chartPie = new Chart(ctxPie, {
    type: 'doughnut',
    data: {
      labels: pieLabels,
      datasets: [{
        data: pieData,
        backgroundColor: pieColors,
        hoverBackgroundColor: pieHover,
        borderWidth: 3,
        borderColor: '#ffffff',
        hoverOffset: 5,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '74%',
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(20, 45, 32, 0.94)',
          titleColor: '#ffffff',
          bodyColor: '#e0ece5',
          titleFont: { size: 12, weight: 'bold' },
          bodyFont: { size: 12 },
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: function (ctx) {
              const val = ctx.parsed || 0;
              const total = <?php echo (int)$total_booking; ?>;
              const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
              return ' ' + ctx.label + ': ' + val + ' Booking (' + pct + '%)';
            }
          }
        }
      }
    }
  });
};

window._rebuildPieChart();

/* ── Pagination & Search Detail Table ── */
let currentDetailPage = 1;

window.updateDetailTable = function (page = 1) {
  currentDetailPage = page;
  const filterInput = document.getElementById('detailSearchInput');
  const filter = (filterInput ? filterInput.value : '').toLowerCase().trim();
  const selectEl = document.getElementById('detailShowPerPage');
  const perPage = parseInt(selectEl ? selectEl.value : '15', 10);
  const rows = Array.from(document.querySelectorAll('.detail-row'));
  
  if (!rows.length) return;

  const matched = rows.filter(r => {
    if (!filter) return true;
    const searchData = r.getAttribute('data-search') || '';
    return searchData.includes(filter);
  });

  const total = matched.length;
  const totalPages = Math.ceil(total / perPage) || 1;
  if (currentDetailPage > totalPages) currentDetailPage = totalPages;
  if (currentDetailPage < 1) currentDetailPage = 1;

  const startIdx = (currentDetailPage - 1) * perPage;
  const endIdx = startIdx + perPage;

  rows.forEach(r => r.style.display = 'none');

  matched.forEach((r, idx) => {
    if (idx >= startIdx && idx < endIdx) {
      r.style.display = '';
      const numCell = r.querySelector('.row-num');
      if (numCell) numCell.textContent = idx + 1;
    }
  });

  const countDisplay = document.getElementById('countDisplay');
  if (countDisplay) countDisplay.textContent = total;

  const infoEl = document.getElementById('detailTableInfo');
  if (infoEl) {
    if (total === 0) {
      infoEl.textContent = 'Tidak ada data yang cocok dengan pencarian';
    } else {
      const from = startIdx + 1;
      const to = Math.min(endIdx, total);
      infoEl.textContent = `Menampilkan ${from} - ${to} dari ${total} data`;
    }
  }

  const paginEl = document.getElementById('detailPagination');
  if (paginEl) {
    if (totalPages <= 1) {
      paginEl.innerHTML = '';
      return;
    }
    let html = '';
    html += `<li class="page-item ${currentDetailPage === 1 ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="updateDetailTable(${currentDetailPage - 1})">&laquo;</a></li>`;
    for (let p = 1; p <= totalPages; p++) {
      if (p === 1 || p === totalPages || (p >= currentDetailPage - 2 && p <= currentDetailPage + 2)) {
        html += `<li class="page-item ${p === currentDetailPage ? 'active' : ''}"><a class="page-link" href="javascript:void(0)" onclick="updateDetailTable(${p})">${p}</a></li>`;
      } else if (p === currentDetailPage - 3 || p === currentDetailPage + 3) {
        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
      }
    }
    html += `<li class="page-item ${currentDetailPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="javascript:void(0)" onclick="updateDetailTable(${currentDetailPage + 1})">&raquo;</a></li>`;
    paginEl.innerHTML = html;
  }
};

document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector('.detail-row')) {
    window.updateDetailTable(1);
  }
});
if (document.querySelector('.detail-row')) {
  window.updateDetailTable(1);
}
</script>
<?php endif; ?>
</body>
</html>