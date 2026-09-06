<?php
session_start();
include "login/ceksession.php";
include "../koneksi/koneksi.php";

// Helper Nama Paket
function formatPaketName($raw) {
    switch ($raw) {
        case 'meal_only': return 'Breakfast/Lunch/Dinner Only';
        case 'studi_banding': return 'Studi Banding';
        case 'fun_game': return 'Paket Fun Game';
        case 'pelajar_live_in': return 'Paket Pelajar - Live In Candirejo';
        case 'pelajar_field_trip_one_day': return 'Paket Pelajar – Field Trip One Day';
        case 'pelajar_field_trip_half_day': return 'Paket Pelajar – Field Trip Half Day';
        case 'cycling_tour': return 'Cycling Village Tour Candirejo';
        case 'traditional_dance': return 'Traditional Dance';
        case 'walking_tour': return 'Walking Around Village';
        case 'homestay': return 'Stay At Local House (Homestay)';
        case 'serenade': return 'Serenade Foot Of Menoreh Hill';
        case 'cooking_lesson': return 'Cooking Lesson';
        case 'gamelan_class': return 'Gamelan Class';
        case 'village_experience': return 'Village Experience';
        case 'dokar_tour': return 'Dokar Village Tour Candirejo';
        case 'inspection': return 'Inspection Tour';
        case 'lainnya': return 'Paket Lainnya';
        default: return ucwords(str_replace('_', ' ', $raw));
    }
}

// Helper Normalisasi Nama Negara
function normalizeCountryName($country) {
    $c = trim($country);
    $lc = strtolower($c);
    if (in_array($lc, ['prancis', 'france', 'french'])) return 'Prancis';
    if (in_array($lc, ['inggris', 'uk', 'england', 'london', 'londok, uk'])) return 'Inggris (UK)';
    if (in_array($lc, ['usa', 'us', 'amerika', 'texas'])) return 'Amerika Serikat (USA)';
    if (in_array($lc, ['belanda', 'belanda`'])) return 'Belanda';
    if (in_array($lc, ['swiss', 'switzerlan'])) return 'Swiss';
    if (in_array($lc, ['jerman', 'german', 'jermas'])) return 'Jerman';
    if (in_array($lc, ['hongkong', 'hong kong'])) return 'Hong Kong';
    if (in_array($lc, ['slovakia', 'slovikia'])) return 'Slovakia';
    if (in_array($lc, ['luxemburg', 'luxembrug'])) return 'Luksemburg';
    if (in_array($lc, ['korea selatan', 'korea'])) return 'Korea Selatan';
    return $c;
}

// 1. Tangkap Filter
$filter_tahun = isset($_GET['tahun']) ? trim($_GET['tahun']) : 'all';
$filter_bulan = isset($_GET['bulan']) ? trim($_GET['bulan']) : 'all';
$filter_jenis = isset($_GET['jenis']) ? trim($_GET['jenis']) : 'all';

// Query list tahun unik dari database
$thn_res = mysqli_query($db, "SELECT DISTINCT YEAR(tanggal_kunjungan) as thn FROM tb_data_pengunjung WHERE tanggal_kunjungan IS NOT NULL AND tanggal_kunjungan != '0000-00-00' ORDER BY thn DESC");
$available_years = [];
while ($r = mysqli_fetch_assoc($thn_res)) {
    if ((int)$r['thn'] > 2000) {
        $available_years[] = (int)$r['thn'];
    }
}

// Bangun WHERE clause
$where = "WHERE 1=1";
if (!empty($filter_tahun) && $filter_tahun !== 'all') {
    $safe_thn = mysqli_real_escape_string($db, $filter_tahun);
    $where .= " AND YEAR(tanggal_kunjungan) = '$safe_thn'";
}
if (!empty($filter_bulan) && $filter_bulan !== 'all') {
    $safe_bln = sprintf('%02d', (int)$filter_bulan);
    $where .= " AND MONTH(tanggal_kunjungan) = '$safe_bln'";
}
if (!empty($filter_jenis) && $filter_jenis !== 'all') {
    $safe_jenis = mysqli_real_escape_string($db, $filter_jenis);
    $where .= " AND jenis_wisatawan = '$safe_jenis'";
}

// 2. Query KPI Ringkasan
$kpi_q = mysqli_query($db, "SELECT 
    COUNT(*) as total_kunjungan,
    COALESCE(SUM(pax), 0) as total_pax,
    COALESCE(SUM(CASE WHEN jenis_wisatawan = 'Mancanegara' THEN pax ELSE 0 END), 0) as pax_manca,
    COALESCE(SUM(CASE WHEN jenis_wisatawan = 'Domestik' THEN pax ELSE 0 END), 0) as pax_domestik,
    COALESCE(SUM(CASE WHEN opsi_makan_tour = 'with_lunch' THEN pax ELSE 0 END), 0) as pax_with_lunch,
    COALESCE(SUM(CASE WHEN opsi_makan_tour = 'without_lunch' THEN pax ELSE 0 END), 0) as pax_without_lunch
FROM tb_data_pengunjung $where");
$kpi = mysqli_fetch_assoc($kpi_q);

$total_kunjungan = (int)$kpi['total_kunjungan'];
$total_pax       = (int)$kpi['total_pax'];
$pax_manca       = (int)$kpi['pax_manca'];
$pax_domestik    = (int)$kpi['pax_domestik'];
$pax_with_lunch  = (int)$kpi['pax_with_lunch'];
$pax_without_lunch = (int)$kpi['pax_without_lunch'];

// 3. Query Ranking Paket Wisata (Terpopuler s/d Paling Sedikit)
$paket_q = mysqli_query($db, "SELECT 
    pilihan_paket_wisata,
    COUNT(*) as jml_kunjungan,
    COALESCE(SUM(pax), 0) as total_pax
FROM tb_data_pengunjung
$where AND pilihan_paket_wisata IS NOT NULL AND pilihan_paket_wisata != ''
GROUP BY pilihan_paket_wisata
ORDER BY total_pax DESC");

$paket_list = [];
$top_paket_name = '-';
$top_paket_pax  = 0;

while ($row = mysqli_fetch_assoc($paket_q)) {
    $raw = $row['pilihan_paket_wisata'];
    $label = formatPaketName($raw);
    $pax = (int)$row['total_pax'];
    $kunjungan = (int)$row['jml_kunjungan'];
    $pct = ($total_pax > 0) ? round(($pax / $total_pax) * 100, 1) : 0;
    
    $item = [
        'code'          => $raw,
        'label'         => $label,
        'jml_kunjungan' => $kunjungan,
        'total_pax'     => $pax,
        'avg_pax'       => ($kunjungan > 0) ? round($pax / $kunjungan, 1) : 0,
        'percentage'    => $pct
    ];
    $paket_list[] = $item;

    if ($top_paket_name === '-') {
        $top_paket_name = $label;
        $top_paket_pax  = $pax;
    }
}

// 4. Query Ranking Negara Asal Mancanegara
$negara_q = mysqli_query($db, "SELECT 
    negara,
    COUNT(*) as jml_kunjungan,
    COALESCE(SUM(pax), 0) as total_pax
FROM tb_data_pengunjung
$where AND jenis_wisatawan = 'Mancanegara' AND negara IS NOT NULL AND negara != '' AND negara != '-'
GROUP BY negara
ORDER BY total_pax DESC");

$negara_aggregated = [];
while ($row = mysqli_fetch_assoc($negara_q)) {
    $norm = normalizeCountryName($row['negara']);
    if (!isset($negara_aggregated[$norm])) {
        $negara_aggregated[$norm] = [
            'negara'        => $norm,
            'jml_kunjungan' => 0,
            'total_pax'     => 0
        ];
    }
    $negara_aggregated[$norm]['jml_kunjungan'] += (int)$row['jml_kunjungan'];
    $negara_aggregated[$norm]['total_pax']     += (int)$row['total_pax'];
}

usort($negara_aggregated, function($a, $b) {
    return $b['total_pax'] - $a['total_pax'];
});

$top_negara_name = '-';
$top_negara_pax  = 0;
$total_pax_negara = 0;
foreach ($negara_aggregated as $idx => &$neg) {
    $total_pax_negara += $neg['total_pax'];
    $neg['avg_pax'] = ($neg['jml_kunjungan'] > 0) ? round($neg['total_pax'] / $neg['jml_kunjungan'], 1) : 0;
    if ($idx === 0) {
        $top_negara_name = $neg['negara'];
        $top_negara_pax  = $neg['total_pax'];
    }
}
unset($neg);

foreach ($negara_aggregated as &$neg) {
    $neg['percentage'] = ($total_pax_negara > 0) ? round(($neg['total_pax'] / $total_pax_negara) * 100, 1) : 0;
}
unset($neg);

// 5. Tren Kunjungan Per Bulan (Jan - Des)
$bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$tren_manca   = array_fill(1, 12, 0);
$tren_dom     = array_fill(1, 12, 0);
$tren_total   = array_fill(1, 12, 0);

$monthly_q = mysqli_query($db, "SELECT 
    MONTH(tanggal_kunjungan) as bln,
    COALESCE(SUM(CASE WHEN jenis_wisatawan = 'Mancanegara' THEN pax ELSE 0 END), 0) as pax_manca,
    COALESCE(SUM(CASE WHEN jenis_wisatawan = 'Domestik' THEN pax ELSE 0 END), 0) as pax_domestik,
    COALESCE(SUM(pax), 0) as total_pax
FROM tb_data_pengunjung
$where AND tanggal_kunjungan IS NOT NULL AND tanggal_kunjungan != '0000-00-00'
GROUP BY bln
ORDER BY bln ASC");

while ($m_row = mysqli_fetch_assoc($monthly_q)) {
    $b = (int)$m_row['bln'];
    if ($b >= 1 && $b <= 12) {
        $tren_manca[$b] = (int)$m_row['pax_manca'];
        $tren_dom[$b]   = (int)$m_row['pax_domestik'];
        $tren_total[$b] = (int)$m_row['total_pax'];
    }
}

// Data JSON untuk Chart.js
$chart_paket_labels = [];
$chart_paket_pax    = [];
$chart_paket_sesi   = [];
foreach ($paket_list as $p) {
    $chart_paket_labels[] = $p['label'];
    $chart_paket_pax[]    = $p['total_pax'];
    $chart_paket_sesi[]   = $p['jml_kunjungan'];
}

// Top 10 Negara untuk Chart + Lainnya
$chart_negara_labels = [];
$chart_negara_pax    = [];
$chart_negara_sesi   = [];
$other_pax = 0;
$other_sesi = 0;

foreach ($negara_aggregated as $idx => $neg) {
    if ($idx < 10) {
        $chart_negara_labels[] = $neg['negara'];
        $chart_negara_pax[]    = $neg['total_pax'];
        $chart_negara_sesi[]   = $neg['jml_kunjungan'];
    } else {
        $other_pax += $neg['total_pax'];
        $other_sesi += $neg['jml_kunjungan'];
    }
}
if ($other_pax > 0) {
    $chart_negara_labels[] = 'Negara Lainnya';
    $chart_negara_pax[]    = $other_pax;
    $chart_negara_sesi[]   = $other_sesi;
}

$pct_manca = ($total_pax > 0) ? round(($pax_manca / $total_pax) * 100, 1) : 0;
$pct_dom   = ($total_pax > 0) ? round(($pax_domestik / $total_pax) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Analitik Data Pengunjung - Desa Wisata Candirejo</title>

  <!-- Google Fonts Inter & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Font Awesome -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* Styling Dasar & Palet Modern Candirejo (Identik Web Booking Laporan) */
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Plus Jakarta Sans', sans-serif !important;
      color: #1e3a2f !important;
      background-color: #f4f7f5 !important;
    }
    .right_col {
      background: #f4f7f5 !important;
      padding: 26px 24px 24px !important;
      min-height: calc(100vh - 60px) !important;
      overflow-x: hidden !important;
      max-width: 100% !important;
    }

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
      font-family: inherit;
    }
    .report-title-content p {
      font-size: 13px;
      color: #6b8f7e;
      margin: 0;
      line-height: 1.4;
    }


    /* ── CARD GLOBAL ── */
    .card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    /* ── FILTER SECTION INSIDE CONTROL CARD ── */
    .card-filter-section {
      padding: 16px 24px 18px;
      background: #fafcfb;
    }
    .filter-row {
      display: flex;
      align-items: flex-end;
      gap: 16px;
      flex-wrap: wrap;
      margin: 0;
    }
    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      flex: 1;
      min-width: 180px;
    }
    .filter-group label {
      font-size: 11.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #5a7d6d;
      margin: 0;
    }
    .input-wrap {
      position: relative;
    }
    .input-wrap svg {
      position: absolute;
      left: 11px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ab5a8;
      pointer-events: none;
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
      cursor: pointer;
      font-family: inherit;
    }
    .filter-input:focus {
      border-color: #2e7d4f;
    }
    .btn-tampilkan {
      background: #1e3a2f;
      color: #fff !important;
      border: none;
      border-radius: 8px;
      padding: 0 22px;
      height: 38px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      text-decoration: none !important;
    }
    .btn-tampilkan:hover {
      background: #2d5540;
      color: #fff !important;
    }
    .btn-reset-filter {
      background: #fff;
      color: #6b8f7e !important;
      border: 1px solid #d6e6dc;
      border-radius: 8px;
      padding: 0 16px;
      height: 38px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.15s;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      text-decoration: none !important;
    }
    .btn-reset-filter:hover {
      background: #f4f7f5;
      color: #1e3a2f !important;
      border-color: #b0cfc0;
    }

    /* ── STAT CARDS ── */
    .stat-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 22px;
    }
    @media (max-width: 992px) {
      .stat-cards { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 540px) {
      .stat-cards { grid-template-columns: 1fr; }
    }
    .stat-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      padding: 18px 22px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .stat-label {
      font-size: 11.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #7a9e8e;
      margin-bottom: 8px;
    }
    .stat-value {
      font-size: 28px;
      font-weight: 700;
      line-height: 1.1;
      color: #1e3a2f;
      margin-bottom: 6px;
    }
    .stat-value.green {
      color: #2e7d4f;
    }
    .stat-sub {
      font-size: 12px;
      color: #6b8f7e;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
    }
    .stat-pill {
      display: inline-block;
      padding: 2px 7px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      background: #edf8f2;
      color: #1e6b3c;
    }

    /* ── CHART ROW & CARDS ── */
    .chart-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 340px;
      gap: 20px;
      margin-bottom: 22px;
      width: 100%;
      max-width: 100%;
    }
    .chart-row-equal {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      gap: 20px;
      margin-bottom: 22px;
      width: 100%;
      max-width: 100%;
    }
    @media (max-width: 1080px) {
      .chart-row, .chart-row-equal { grid-template-columns: minmax(0, 1fr); }
    }
    .chart-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      padding: 20px 22px;
      display: flex;
      flex-direction: column;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
      min-width: 0;
      max-width: 100%;
      box-sizing: border-box;
      overflow: hidden;
    }
    .chart-card-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 16px;
    }
    .chart-title {
      font-size: 15.5px;
      font-weight: 700;
      color: #1e3a2f;
      margin: 0;
    }
    .chart-subtitle {
      font-size: 12px;
      color: #7a9e8e;
      margin-top: 3px;
    }
    .metric-toggle-group {
      display: inline-flex;
      align-items: center;
      background: #f4f8f5;
      border: 1px solid #e0ece5;
      border-radius: 8px;
      padding: 3px;
      gap: 2px;
    }
    .btn-metric {
      border: none;
      background: transparent;
      color: #5a7d6d;
      font-size: 11.5px;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-metric.active {
      background: #1e3a2f;
      color: #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .chart-canvas-wrap {
      position: relative;
      height: 340px;
      width: 100%;
      min-width: 0;
      max-width: 100%;
      overflow: hidden;
      flex: 1;
    }
    .chart-canvas-wrap canvas {
      max-width: 100% !important;
    }

    /* Donut Center Label & List */
    .donut-wrap {
      position: relative;
      height: 195px;
      width: 100%;
      min-width: 0;
      max-width: 100%;
      margin: 4px 0 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .donut-wrap canvas {
      position: relative;
      z-index: 2;
      max-width: 100% !important;
    }
    .donut-center-info {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      pointer-events: none;
      z-index: 1;
    }
    .donut-total {
      display: block;
      font-size: 24px;
      font-weight: 800;
      color: #1e3a2f;
      line-height: 1;
    }
    .donut-label {
      display: block;
      font-size: 10px;
      color: #7a9e8e;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-top: 4px;
    }
    .donut-legend-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      padding-top: 14px;
      border-top: 1px solid #f0f5f2;
    }
    .donut-legend-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12.5px;
    }
    .donut-legend-left {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #3a5c4c;
      font-weight: 500;
    }
    .status-dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .dot-manca { background: #10b981; }
    .dot-dom   { background: #1e3a2f; }
    .donut-legend-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .status-val {
      font-weight: 700;
      color: #1e3a2f;
    }
    .status-pct {
      font-size: 11px;
      font-weight: 600;
      color: #6b8f7e;
      min-width: 40px;
      text-align: right;
      background: #f2f7f4;
      padding: 2px 6px;
      border-radius: 4px;
    }
    .sub-meal-breakdown {
      margin-top: 14px;
      padding-top: 12px;
      border-top: 1px solid #f0f5f2;
      display: flex;
      justify-content: space-around;
      text-align: center;
    }
    .meal-item-label {
      font-size: 10.5px;
      text-transform: uppercase;
      color: #7a9e8e;
      font-weight: 600;
      letter-spacing: 0.03em;
    }
    .meal-item-val {
      font-size: 14.5px;
      font-weight: 700;
      color: #1e3a2f;
      margin-top: 2px;
    }

    /* ── RANKING TABLE CARD ── */
    .table-card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
      margin-bottom: 24px;
    }
    .table-card-header {
      padding: 14px 20px 12px;
      border-bottom: 1px solid #f0f5f2;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }
    .ranking-tabs {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
      padding: 0;
      list-style: none;
    }
    .ranking-tab-btn {
      border: 1px solid #d6e6dc;
      background: #fff;
      color: #4a6e5c;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .ranking-tab-btn:hover {
      background: #f4fbf6;
      border-color: #2e7d4f;
      color: #2e7d4f;
    }
    .ranking-tab-btn.active {
      background: #1e3a2f;
      border-color: #1e3a2f;
      color: #fff;
    }
    .table-ranking {
      width: 100%;
      border-collapse: collapse;
    }
    .table-ranking thead th {
      padding: 11px 16px;
      background: #f8fbf9;
      color: #5a7d6d;
      font-size: 11.5px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      border-bottom: 1px solid #e8f0ec;
      white-space: nowrap;
      text-align: left;
    }
    .table-ranking tbody tr {
      border-bottom: 1px solid #f0f5f2;
      transition: background 0.15s;
    }
    .table-ranking tbody tr:hover {
      background: #fafcfa;
    }
    .table-ranking tbody td {
      padding: 11px 16px;
      font-size: 13px;
      color: #1e3a2f;
      vertical-align: middle;
    }
    .rank-badge {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 11.5px;
      font-weight: 700;
    }
    .rank-1 { background: #fef3c7; color: #b45309; border: 1.5px solid #f59e0b; }
    .rank-2 { background: #e2e8f0; color: #475569; border: 1.5px solid #94a3b8; }
    .rank-3 { background: #fed7aa; color: #c2410c; border: 1.5px solid #fb923c; }
    .rank-other { background: #f1f5f9; color: #64748b; }
    .progress-track {
      background: #eef4f0;
      border-radius: 999px;
      height: 6px;
      overflow: hidden;
      width: 100%;
      margin-top: 4px;
    }
    .progress-bar-forest {
      background: linear-gradient(90deg, #10b981, #059669);
      height: 100%;
      border-radius: 999px;
    }
    .progress-bar-purple {
      background: linear-gradient(90deg, #8b5cf6, #7c3aed);
      height: 100%;
      border-radius: 999px;
    }

    /* Print Optimization */
    @media print {
      .left_col, .top_nav, footer, .card-filter-section, .metric-toggle-group, .ranking-tabs {
        display: none !important;
      }
      .report-control-card {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 12px !important;
      }
      .card-header-report {
        padding: 0 0 10px 0 !important;
        border-bottom: 2px solid #1e3a2f !important;
      }
      .right_col {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
      }
      .chart-card, .stat-card, .table-card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
        break-inside: avoid;
      }
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
        <div class="">

          <!-- Header & Filter Unified in 1 Card -->
          <div class="card report-control-card">
            <div class="card-header-report">
              <div class="report-title-content">
                <h1>Analitik Data Pengunjung &amp; Wisata</h1>
                <p>Visualisasi interaktif tren kunjungan, paket wisata terpopuler, dan sebaran wisatawan Desa Candirejo</p>
              </div>
            </div>

            <div class="card-filter-section">
              <form method="GET" action="analitik.php" id="filterForm">
                <div class="filter-row">
                  
                  <div class="filter-group">
                    <label>Tahun Kunjungan</label>
                    <div class="input-wrap">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                      </svg>
                      <select name="tahun" class="filter-input">
                        <option value="all" <?php echo ($filter_tahun === 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                        <?php foreach ($available_years as $thn): ?>
                          <option value="<?php echo $thn; ?>" <?php echo ($filter_tahun == $thn) ? 'selected' : ''; ?>>
                            Tahun <?php echo $thn; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="filter-group">
                    <label>Bulan Kunjungan</label>
                    <div class="input-wrap">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                      </svg>
                      <select name="bulan" class="filter-input">
                        <option value="all" <?php echo ($filter_bulan === 'all') ? 'selected' : ''; ?>>Semua Bulan (1 Tahun)</option>
                        <?php 
                        $nama_bln = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                        foreach ($nama_bln as $num => $nama): 
                        ?>
                          <option value="<?php echo $num; ?>" <?php echo ($filter_bulan == $num) ? 'selected' : ''; ?>>
                            <?php echo $nama; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="filter-group">
                    <label>Kategori Wisatawan</label>
                    <div class="input-wrap">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                      </svg>
                      <select name="jenis" class="filter-input">
                        <option value="all" <?php echo ($filter_jenis === 'all') ? 'selected' : ''; ?>>Semua Kategori (Domestik &amp; Mancanegara)</option>
                        <option value="Mancanegara" <?php echo ($filter_jenis === 'Mancanegara') ? 'selected' : ''; ?>>Hanya Mancanegara (Luar Negeri)</option>
                        <option value="Domestik" <?php echo ($filter_jenis === 'Domestik') ? 'selected' : ''; ?>>Hanya Domestik (Nusantara)</option>
                      </select>
                    </div>
                  </div>

                  <div style="display:flex; gap:8px;">
                    <button type="submit" class="btn-tampilkan">Terapkan Filter</button>
                    <a href="analitik.php" class="btn-reset-filter" title="Reset filter">Reset</a>
                  </div>

                </div>
              </form>
            </div>
          </div>

          <!-- Stat Cards Row -->
          <div class="stat-cards">
            
            <!-- Card 1: Total Kunjungan -->
            <div class="stat-card">
              <div>
                <div class="stat-label">Total Kunjungan (Sesi)</div>
                <div class="stat-value"><?php echo number_format($total_kunjungan, 0, ',', '.'); ?></div>
              </div>
              <p class="stat-sub">
                <span class="stat-pill">Transaksi</span>
                <span><?php echo ($filter_tahun !== 'all') ? 'Tahun ' . htmlspecialchars($filter_tahun) : 'Semua Periode'; ?></span>
              </p>
            </div>

            <!-- Card 2: Total Pax Wisatawan -->
            <div class="stat-card">
              <div>
                <div class="stat-label">Total Wisatawan (Pax)</div>
                <div class="stat-value green"><?php echo number_format($total_pax, 0, ',', '.'); ?></div>
              </div>
              <p class="stat-sub">
                <span class="stat-pill"><?php echo $pct_manca; ?>% Manca</span>
                <span><?php echo number_format($pax_manca, 0, ',', '.'); ?> Manca &bull; <?php echo number_format($pax_domestik, 0, ',', '.'); ?> Dom</span>
              </p>
            </div>

            <!-- Card 3: Paket Paling Populer -->
            <div class="stat-card">
              <div>
                <div class="stat-label">Paket Terpopuler (#1)</div>
                <div class="stat-value" style="font-size:18px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo htmlspecialchars($top_paket_name); ?>">
                  <?php echo htmlspecialchars($top_paket_name); ?>
                </div>
              </div>
              <p class="stat-sub">
                <span class="stat-pill"><?php echo number_format($top_paket_pax, 0, ',', '.'); ?> Pax</span>
                <span>Paling diminati</span>
              </p>
            </div>

            <!-- Card 4: Negara Mancanegara Terbanyak -->
            <div class="stat-card">
              <div>
                <div class="stat-label">Asal Mancanegara (#1)</div>
                <div class="stat-value" style="font-size:22px;">
                  <?php echo htmlspecialchars($top_negara_name); ?>
                </div>
              </div>
              <p class="stat-sub">
                <span class="stat-pill"><?php echo number_format($top_negara_pax, 0, ',', '.'); ?> Pax</span>
                <span>Turis terbanyak</span>
              </p>
            </div>

          </div>

          <!-- Chart Row 1: Ranking Paket Wisata & Komposisi Wisatawan -->
          <div class="chart-row">
            
            <!-- Left: Ranking Paket Wisata Bar Chart -->
            <div class="card chart-card">
              <div class="chart-card-header">
                <div>
                  <h3 class="chart-title">Ranking Paket Wisata Candirejo</h3>
                  <p class="chart-subtitle">Urutan paket wisata dari yang terpopuler hingga paling sedikit peminat</p>
                </div>
                <div class="metric-toggle-group">
                  <button type="button" class="btn-metric active" id="btnPaketPax" onclick="togglePaketMetric('pax')">Berdasarkan Pax</button>
                  <button type="button" class="btn-metric" id="btnPaketSesi" onclick="togglePaketMetric('sesi')">Berdasarkan Sesi</button>
                </div>
              </div>

              <div class="chart-canvas-wrap">
                <canvas id="chartPaket"></canvas>
              </div>
            </div>

            <!-- Right: Komposisi Wisatawan Donut Chart -->
            <div class="card chart-card">
              <div class="chart-card-header">
                <div>
                  <h3 class="chart-title">Komposisi Wisatawan</h3>
                  <p class="chart-subtitle">Proporsi Mancanegara vs Domestik</p>
                </div>
              </div>

              <div class="donut-wrap">
                <canvas id="chartKomposisi"></canvas>
                <div class="donut-center-info">
                  <span class="donut-total"><?php echo number_format($total_pax, 0, ',', '.'); ?></span>
                  <span class="donut-label">Wisatawan</span>
                </div>
              </div>

              <div class="donut-legend-list">
                <div class="donut-legend-item">
                  <div class="donut-legend-left">
                    <span class="status-dot dot-manca"></span>
                    <span>Mancanegara</span>
                  </div>
                  <div class="donut-legend-right">
                    <span class="status-val"><?php echo number_format($pax_manca, 0, ',', '.'); ?></span>
                    <span class="status-pct"><?php echo $pct_manca; ?>%</span>
                  </div>
                </div>
                <div class="donut-legend-item">
                  <div class="donut-legend-left">
                    <span class="status-dot dot-dom"></span>
                    <span>Domestik</span>
                  </div>
                  <div class="donut-legend-right">
                    <span class="status-val"><?php echo number_format($pax_domestik, 0, ',', '.'); ?></span>
                    <span class="status-pct"><?php echo $pct_dom; ?>%</span>
                  </div>
                </div>
              </div>

              <div class="sub-meal-breakdown">
                <div>
                  <div class="meal-item-label">With Lunch</div>
                  <div class="meal-item-val"><?php echo number_format($pax_with_lunch, 0, ',', '.'); ?> <span style="font-size:11px;font-weight:normal;color:#7a9e8e;">pax</span></div>
                </div>
                <div style="border-left: 1px solid #eef4f0;"></div>
                <div>
                  <div class="meal-item-label">Without Lunch</div>
                  <div class="meal-item-val"><?php echo number_format($pax_without_lunch, 0, ',', '.'); ?> <span style="font-size:11px;font-weight:normal;color:#7a9e8e;">pax</span></div>
                </div>
              </div>

            </div>

          </div>

          <!-- Chart Row 2: Sebaran Negara & Tren Bulanan -->
          <div class="chart-row-equal">
            
            <!-- Left: Ranking Negara Mancanegara -->
            <div class="card chart-card">
              <div class="chart-card-header">
                <div>
                  <h3 class="chart-title">Sebaran Negara Asal Mancanegara</h3>
                  <p class="chart-subtitle">Ranking negara wisatawan asing yang paling sering berkunjung ke Candirejo</p>
                </div>
              </div>

              <div class="chart-canvas-wrap">
                <canvas id="chartNegara"></canvas>
              </div>
            </div>

            <!-- Right: Tren Fluktuasi Kunjungan Bulanan -->
            <div class="card chart-card">
              <div class="chart-card-header">
                <div>
                  <h3 class="chart-title">Tren Fluktuasi Kunjungan Bulanan</h3>
                  <p class="chart-subtitle">Pola musim kunjungan (High Season / Low Season) per bulan</p>
                </div>
              </div>

              <div class="chart-canvas-wrap">
                <canvas id="chartTren"></canvas>
              </div>
            </div>

          </div>

          <!-- Ranking Tables Card -->
          <div class="table-card">
            <div class="table-card-header">
              <ul class="ranking-tabs" role="tablist">
                <li>
                  <button type="button" class="ranking-tab-btn active" id="tabBtnPaket" onclick="switchRankingTab('paket')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Ranking Paket Wisata (<?php echo count($paket_list); ?> Paket)
                  </button>
                </li>
                <li>
                  <button type="button" class="ranking-tab-btn" id="tabBtnNegara" onclick="switchRankingTab('negara')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    Ranking Negara Mancanegara (<?php echo count($negara_aggregated); ?> Negara)
                  </button>
                </li>
              </ul>
            </div>

            <!-- Tab Content Paket -->
            <div id="panelRankingPaket" style="overflow-x:auto;">
              <table class="table-ranking">
                <thead>
                  <tr>
                    <th style="width: 60px; text-align: center;">Rank</th>
                    <th>Nama Paket Wisata</th>
                    <th style="width: 140px; text-align: center;">Total Sesi</th>
                    <th style="width: 150px; text-align: center;">Total Wisatawan</th>
                    <th style="width: 130px; text-align: center;">Rata-rata/Sesi</th>
                    <th style="width: 200px;">Kontribusi (%)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($paket_list as $idx => $p): 
                    $rank = $idx + 1;
                    $rankClass = ($rank == 1) ? 'rank-1' : (($rank == 2) ? 'rank-2' : (($rank == 3) ? 'rank-3' : 'rank-other'));
                  ?>
                  <tr>
                    <td style="text-align: center;">
                      <span class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank; ?></span>
                    </td>
                    <td>
                      <strong style="color: #1e3a2f;"><?php echo htmlspecialchars($p['label']); ?></strong>
                      <div style="font-size: 11px; color: #7a9e8e; font-family: monospace;"><?php echo htmlspecialchars($p['code']); ?></div>
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                      <?php echo number_format($p['jml_kunjungan'], 0, ',', '.'); ?> sesi
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #2e7d4f;">
                      <?php echo number_format($p['total_pax'], 0, ',', '.'); ?> pax
                    </td>
                    <td style="text-align: center; color: #6b8f7e;">
                      <?php echo $p['avg_pax']; ?> pax/sesi
                    </td>
                    <td>
                      <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 11.5px; color: #5a7d6d; margin-bottom: 2px;">
                        <span><?php echo $p['percentage']; ?>%</span>
                      </div>
                      <div class="progress-track">
                        <div class="progress-bar-forest" style="width: <?php echo min(100, $p['percentage']); ?>%;"></div>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Tab Content Negara -->
            <div id="panelRankingNegara" style="display:none; overflow-x:auto;">
              <table class="table-ranking">
                <thead>
                  <tr>
                    <th style="width: 60px; text-align: center;">Rank</th>
                    <th>Negara Asal Wisatawan</th>
                    <th style="width: 140px; text-align: center;">Total Sesi</th>
                    <th style="width: 150px; text-align: center;">Total Wisatawan</th>
                    <th style="width: 130px; text-align: center;">Rata-rata/Sesi</th>
                    <th style="width: 200px;">Pangsa Mancanegara (%)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($negara_aggregated as $idx => $neg): 
                    $rank = $idx + 1;
                    $rankClass = ($rank == 1) ? 'rank-1' : (($rank == 2) ? 'rank-2' : (($rank == 3) ? 'rank-3' : 'rank-other'));
                  ?>
                  <tr>
                    <td style="text-align: center;">
                      <span class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank; ?></span>
                    </td>
                    <td>
                      <strong style="color: #1e3a2f;"><?php echo htmlspecialchars($neg['negara']); ?></strong>
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                      <?php echo number_format($neg['jml_kunjungan'], 0, ',', '.'); ?> sesi
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #1e3a2f;">
                      <?php echo number_format($neg['total_pax'], 0, ',', '.'); ?> pax
                    </td>
                    <td style="text-align: center; color: #6b8f7e;">
                      <?php echo $neg['avg_pax']; ?> pax/sesi
                    </td>
                    <td>
                      <div style="display: flex; justify-content: space-between; font-weight: 600; font-size: 11.5px; color: #5a7d6d; margin-bottom: 2px;">
                        <span><?php echo $neg['percentage']; ?>%</span>
                      </div>
                      <div class="progress-track">
                        <div class="progress-bar-purple" style="width: <?php echo min(100, $neg['percentage']); ?>%;"></div>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          </div>

        </div>
      </div>
      <!-- /Page Content -->

      <!-- Footer -->
      <footer>
        <div class="pull-right">
          Sistem Informasi Desa Wisata Candirejo &bull; Supported by DRTPM
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /Footer -->

    </div>
  </div>

  <!-- jQuery & Bootstrap -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>

  <!-- Modern Chart.js v4 -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    // Compatibility shim for Gentelella custom.min.js legacy Chart.defaults.global access
    if (typeof Chart !== 'undefined') {
      Chart.defaults = Chart.defaults || {};
      Chart.defaults.global = Chart.defaults.global || {};
    }
  </script>

  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- Chart.js Scripts & Interactivity -->
  <script>
    // Data PHP to JavaScript
    const paketLabels = <?php echo json_encode($chart_paket_labels); ?>;
    const paketPax    = <?php echo json_encode($chart_paket_pax); ?>;
    const paketSesi   = <?php echo json_encode($chart_paket_sesi); ?>;

    const negaraLabels = <?php echo json_encode($chart_negara_labels); ?>;
    const negaraPax    = <?php echo json_encode($chart_negara_pax); ?>;
    const negaraSesi   = <?php echo json_encode($chart_negara_sesi); ?>;

    const trenLabels = <?php echo json_encode($bulan_labels); ?>;
    const trenManca  = <?php echo json_encode(array_values($tren_manca)); ?>;
    const trenDom    = <?php echo json_encode(array_values($tren_dom)); ?>;
    const trenTotal  = <?php echo json_encode(array_values($tren_total)); ?>;

    let chartPaketInstance = null;
    // Registry semua chart instance untuk resize saat sidebar toggle
    window._analyticsCharts = [];

    // 1. Chart Ranking Paket Wisata (Horizontal Bar Chart)
    function initChartPaket(metric) {
      const ctx = document.getElementById('chartPaket').getContext('2d');
      const isPax = (metric === 'pax');
      const dataValues = isPax ? paketPax : paketSesi;
      const labelName = isPax ? 'Total Wisatawan (Pax)' : 'Jumlah Kunjungan (Sesi)';

      if (chartPaketInstance) {
        chartPaketInstance.destroy();
      }

      // Gradient Hijau Candirejo
      const grad = ctx.createLinearGradient(0, 0, 400, 0);
      grad.addColorStop(0, '#10b981');
      grad.addColorStop(1, '#059669');

      chartPaketInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: paketLabels,
          datasets: [{
            label: labelName,
            data: dataValues,
            backgroundColor: grad,
            hoverBackgroundColor: '#047857',
            borderRadius: 6,
            barThickness: 15
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e3a2f',
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              padding: 10,
              cornerRadius: 8,
              callbacks: {
                label: function(context) {
                  const idx = context.dataIndex;
                  return [
                    ` Wisatawan: ${paketPax[idx].toLocaleString('id-ID')} pax`,
                    ` Kunjungan: ${paketSesi[idx].toLocaleString('id-ID')} sesi`
                  ];
                }
              }
            }
          },
          scales: {
            x: {
              grid: { color: '#f0f5f2', drawBorder: false },
              ticks: { color: '#7a9e8e', font: { size: 11 } }
            },
            y: {
              grid: { display: false, drawBorder: false },
              ticks: { color: '#1e3a2f', font: { size: 12, weight: '500' } }
            }
          }
        }
      });
    }

    function togglePaketMetric(metric) {
      $('#btnPaketPax').removeClass('active');
      $('#btnPaketSesi').removeClass('active');
      if (metric === 'pax') {
        $('#btnPaketPax').addClass('active');
      } else {
        $('#btnPaketSesi').addClass('active');
      }
      initChartPaket(metric);
    }

    // 2. Chart Komposisi Wisatawan (Donut ala Laporan Booking)
    let chartKomposisiInstance = null;
    function initChartKomposisi() {
      const ctx = document.getElementById('chartKomposisi').getContext('2d');
      if (chartKomposisiInstance) chartKomposisiInstance.destroy();
      chartKomposisiInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Mancanegara', 'Domestik'],
          datasets: [{
            data: [<?php echo $pax_manca; ?>, <?php echo $pax_domestik; ?>],
            backgroundColor: ['#10b981', '#1e3a2f'],
            hoverBackgroundColor: ['#059669', '#142c23'],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '74%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e3a2f',
              cornerRadius: 8,
              padding: 10,
              callbacks: {
                label: function(context) {
                  const val = context.parsed;
                  const total = <?php echo ($total_pax > 0) ? $total_pax : 1; ?>;
                  const pct = ((val / total) * 100).toFixed(1);
                  return ` ${context.label}: ${val.toLocaleString('id-ID')} pax (${pct}%)`;
                }
              }
            }
          }
        }
      });
    }

    // 3. Chart Sebaran Negara Asal Mancanegara (Horizontal Bar)
    let chartNegaraInstance = null;
    function initChartNegara() {
      const ctx = document.getElementById('chartNegara').getContext('2d');
      if (chartNegaraInstance) chartNegaraInstance.destroy();
      const gradPurple = ctx.createLinearGradient(0, 0, 400, 0);
      gradPurple.addColorStop(0, '#8b5cf6');
      gradPurple.addColorStop(1, '#7c3aed');

      chartNegaraInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: negaraLabels,
          datasets: [{
            label: 'Total Wisatawan (Pax)',
            data: negaraPax,
            backgroundColor: gradPurple,
            hoverBackgroundColor: '#6d28d9',
            borderRadius: 6,
            barThickness: 15
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e3a2f',
              padding: 10,
              cornerRadius: 8,
              callbacks: {
                label: function(context) {
                  const idx = context.dataIndex;
                  return [
                    ` Wisatawan: ${negaraPax[idx].toLocaleString('id-ID')} pax`,
                    ` Sesi Kunjungan: ${negaraSesi[idx].toLocaleString('id-ID')} kali`
                  ];
                }
              }
            }
          },
          scales: {
            x: {
              grid: { color: '#f0f5f2', drawBorder: false },
              ticks: { color: '#7a9e8e', font: { size: 11 } }
            },
            y: {
              grid: { display: false, drawBorder: false },
              ticks: { color: '#1e3a2f', font: { size: 12, weight: '500' } }
            }
          }
        }
      });
    }

    // 4. Chart Tren Kunjungan Bulanan (Line Chart)
    let chartTrenInstance = null;
    function initChartTren() {
      const ctx = document.getElementById('chartTren').getContext('2d');
      if (chartTrenInstance) chartTrenInstance.destroy();
      chartTrenInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: trenLabels,
          datasets: [
            {
              label: 'Mancanegara',
              data: trenManca,
              borderColor: '#8b5cf6',
              backgroundColor: 'rgba(139, 92, 246, 0.07)',
              borderWidth: 2.2,
              tension: 0.35,
              fill: true,
              pointRadius: 3.5,
              pointHoverRadius: 6,
              pointBackgroundColor: '#8b5cf6'
            },
            {
              label: 'Domestik',
              data: trenDom,
              borderColor: '#10b981',
              backgroundColor: 'rgba(16, 185, 129, 0.07)',
              borderWidth: 2.2,
              tension: 0.35,
              fill: true,
              pointRadius: 3.5,
              pointHoverRadius: 6,
              pointBackgroundColor: '#10b981'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: {
              position: 'top',
              align: 'end',
              labels: {
                boxWidth: 10,
                font: { size: 11.5, weight: '600' },
                color: '#5a7d6d'
              }
            },
            tooltip: {
              backgroundColor: '#1e3a2f',
              cornerRadius: 8,
              padding: 10
            }
          },
          scales: {
            x: {
              grid: { color: '#f0f5f2', drawBorder: false },
              ticks: { color: '#7a9e8e', font: { size: 11, weight: '600' } }
            },
            y: {
              grid: { color: '#f0f5f2', drawBorder: false },
              ticks: { color: '#7a9e8e', font: { size: 11 } }
            }
          }
        }
      });
    }

    // 5. Switch Ranking Tabs
    function switchRankingTab(tab) {
      $('#tabBtnPaket').removeClass('active');
      $('#tabBtnNegara').removeClass('active');
      if (tab === 'paket') {
        $('#tabBtnPaket').addClass('active');
        $('#panelRankingPaket').show();
        $('#panelRankingNegara').hide();
      } else {
        $('#tabBtnNegara').addClass('active');
        $('#panelRankingNegara').show();
        $('#panelRankingPaket').hide();
      }
    }

    $(document).ready(function() {
      initChartPaket('pax');
      initChartKomposisi();
      initChartNegara();
      initChartTren();
    });

    // Fungsi global untuk recreate & resize semua chart — dipanggil saat sidebar toggle atau window resize
    window._resizeAllCharts = function() {
      const curMetric = ($('#btnPaketSesi').hasClass('active')) ? 'sesi' : 'pax';

      // 1. Destroy semua instance chart agar melepaskan binding canvas
      if (chartPaketInstance) { chartPaketInstance.destroy(); chartPaketInstance = null; }
      if (chartKomposisiInstance) { chartKomposisiInstance.destroy(); chartKomposisiInstance = null; }
      if (chartNegaraInstance) { chartNegaraInstance.destroy(); chartNegaraInstance = null; }
      if (chartTrenInstance) { chartTrenInstance.destroy(); chartTrenInstance = null; }

      // 2. Bersihkan atribut inline width & height dari canvas agar tidak memaksakan lebar lama
      ['chartPaket', 'chartKomposisi', 'chartNegara', 'chartTren'].forEach(function(id) {
        const c = document.getElementById(id);
        if (c) {
          c.removeAttribute('width');
          c.removeAttribute('height');
          c.style.width = '';
          c.style.height = '';
        }
      });

      // 3. Re-initialize setiap chart dengan container width yang baru
      initChartPaket(curMetric);
      initChartKomposisi();
      initChartNegara();
      initChartTren();
    };

    // Dengarkan saat animasi margin-left .right_col selesai (transition 0.25s)
    document.addEventListener('DOMContentLoaded', function() {
      const rightCol = document.querySelector('.right_col');
      if (rightCol) {
        rightCol.addEventListener('transitionend', function(e) {
          if (e.propertyName === 'margin-left') {
            if (typeof window._resizeAllCharts === 'function') {
              window._resizeAllCharts();
            }
          }
        });
      }
    });

    // Debounced window resize handler
    let _winResizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(_winResizeTimer);
      _winResizeTimer = setTimeout(function() {
        if (typeof window._resizeAllCharts === 'function') {
          window._resizeAllCharts();
        }
      }, 200);
    });
  </script>

</body>
</html>
