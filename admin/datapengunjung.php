<?php
session_start();
include "login/ceksession.php";
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Data Pengunjung Wisata Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Datatables -->
  <link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* Override agresif agar card scroll tidak diblokir template */
    #card-area-wrap { display: block !important; width: 100% !important; float: none !important; }
    #card-scroll-inner {
      overflow-x: auto !important;
      overflow-y: visible !important;
      -webkit-overflow-scrolling: touch !important;
      display: block !important;
      width: 100% !important;
    }

    /* ===== CARD TOTAL KUNJUNGAN ===== */
    .card-total-kunjungan {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 14px 18px 32px 18px;
      color: #fff;
      box-shadow: 0 4px 18px rgba(102, 126, 234, 0.30);
      display: flex;
      align-items: flex-start;
      gap: 14px;
      margin-bottom: 4px;
      position: relative;
      overflow: hidden;
      height: 120px;
      box-sizing: border-box;
    }
    .card-total-kunjungan::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 110px;
      height: 110px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
      pointer-events: none;
    }
    .card-total-kunjungan::after {
      content: '';
      position: absolute;
      bottom: -40px;
      right: 40px;
      width: 150px;
      height: 150px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
      pointer-events: none;
    }
    .card-total-kunjungan .icon-wrap {
      background: rgba(255,255,255,0.18);
      border-radius: 50%;
      width: 46px;
      height: 46px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 20px;
      color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .card-total-kunjungan .info-wrap .label-text {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      opacity: 0.88;
      margin-bottom: 2px;
      text-transform: uppercase;
    }
    .card-total-kunjungan .info-wrap .count-number {
      font-size: 28px;
      font-weight: 700;
      line-height: 1;
      letter-spacing: -0.5px;
    }
    .card-total-kunjungan .info-wrap .count-number span {
      font-size: 13px;
      font-weight: 400;
      opacity: 0.85;
      margin-left: 4px;
    }
    .card-total-kunjungan .info-wrap .sub-text {
      font-size: 10px;
      opacity: 0.75;
      margin-top: 3px;
    }

    /* ===== CARD JENIS WISATAWAN (LAYOUT NYAMPING) ===== */
    .card-jenis-wisatawan {
      background: linear-gradient(135deg, #1a6b4a 0%, #2e9e6e 100%);
      border-radius: 12px;
      padding: 14px 18px;
      color: #fff;
      box-shadow: 0 4px 18px rgba(30, 120, 80, 0.28);
      position: relative;
      overflow: hidden;
      min-width: 280px;
      display: flex;
      flex-direction: column;
      height: 120px;
      box-sizing: border-box;
    }
    .card-jenis-wisatawan::before {
      content: '';
      position: absolute;
      top: -30px; right: -30px;
      width: 110px; height: 110px;
      background: rgba(255,255,255,0.07);
      border-radius: 50%;
      pointer-events: none;
    }
    .card-jenis-wisatawan::after {
      content: '';
      position: absolute;
      bottom: -40px; right: 40px;
      width: 150px; height: 150px;
      background: rgba(255,255,255,0.04);
      border-radius: 50%;
      pointer-events: none;
    }
    .card-jenis-wisatawan .card-jenis-header {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      opacity: 0.85;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
      flex-shrink: 0;
    }
    .card-jenis-wisatawan .jenis-cols {
      display: flex;
      gap: 10px;
      flex: 1;
      min-height: 0;
    }
    .card-jenis-wisatawan .jenis-col {
      flex: 1;
      background: rgba(255,255,255,0.10);
      border-radius: 8px;
      padding: 6px 10px;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .card-jenis-wisatawan .jenis-icon {
      width: 22px; height: 22px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px;
      background: rgba(255,255,255,0.20);
      margin-bottom: 2px;
      flex-shrink: 0;
    }
    .card-jenis-wisatawan .jenis-label {
      font-size: 9px;
      font-weight: 700;
      opacity: 0.80;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      line-height: 1;
    }
    .card-jenis-wisatawan .jenis-count {
      font-size: 17px;
      font-weight: 700;
      line-height: 1;
      letter-spacing: -0.5px;
    }
    .card-jenis-wisatawan .jenis-count span {
      font-size: 10px;
      font-weight: 400;
      opacity: 0.80;
      margin-left: 2px;
    }
    .card-jenis-wisatawan .jenis-sub {
      font-size: 9px;
      opacity: 0.70;
    }
    .card-jenis-wisatawan .jenis-bar-wrap {
      margin-top: 3px;
      height: 3px;
      background: rgba(255,255,255,0.20);
      border-radius: 4px;
      overflow: hidden;
    }
    .card-jenis-wisatawan .jenis-bar-fill {
      height: 100%;
      border-radius: 4px;
      background: rgba(255,255,255,0.75);
    }
    .card-jenis-wisatawan .jenis-pct {
      font-size: 15px;
      font-weight: 800;
      opacity: 0.95;
      margin-top: 2px;
      letter-spacing: -0.3px;
    }

    /* ===== TOGGLE SWITCH ===== */
    .toggle-mode-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 6px;
    }
    .toggle-label {
      font-size: 10px;
      opacity: 0.85;
      font-weight: 500;
    }
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 38px;
      height: 20px;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(255,255,255,0.3);
      border-radius: 20px;
      transition: 0.3s;
    }
    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 14px; width: 14px;
      left: 3px; bottom: 3px;
      background-color: white;
      border-radius: 50%;
      transition: 0.3s;
    }
    .toggle-switch input:checked + .toggle-slider {
      background-color: rgba(255,255,255,0.55);
    }
    .toggle-switch input:checked + .toggle-slider:before {
      transform: translateX(18px);
    }

    /* ===== BADGE JENIS WISATAWAN ===== */
    .badge-jenis {
      display: inline-block;
      padding: 1px 7px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.2px;
      white-space: nowrap;
      line-height: 1.4;
    }
    .badge-mancanegara {
      background-color: #27ae60;
      color: #fff;
    }
    .badge-domestik {
      background-color: #2980b9;
      color: #fff;
    }
    .badge-pelajar {
      background-color: #e67e22;
      color: #fff;
    }
    .badge-lainnya {
      background-color: #95a5a6;
      color: #fff;
    }

    /* ===== BADGE PAX ===== */
    .badge-pax {
      display: inline-block;
      padding: 1px 8px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      background-color: #555;
      color: #fff;
      white-space: nowrap;
      line-height: 1.4;
    }

    /* ===== FILTER PANEL ===== */
    .filter-panel {
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 10px;
      padding: 16px 20px;
      margin-bottom: 20px;
    }
    .filter-panel .filter-title {
      font-size: 14px;
      font-weight: 600;
      color: #555;
      margin-bottom: 12px;
    }
    .filter-panel .filter-title i {
      margin-right: 6px;
      color: #667eea;
    }

    /* ===== TABLE AREA ===== */
    .x_content {
      overflow: visible !important;
    }
    .table-scroll-wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      width: 100%;
    }
    #datatable thead tr th:last-child,
    #datatable tbody tr td:last-child {
      position: sticky;
      right: 0;
      background-color: #fff;
      z-index: 2;
      box-shadow: -3px 0 6px rgba(0,0,0,0.08);
    }
    #datatable thead tr th:last-child {
      background-color: #f5f5f5;
      z-index: 3;
    }
    #datatable thead tr th:nth-child(2),
    #datatable tbody tr td:nth-child(2) {
      max-width: 130px;
      min-width: 100px;
      white-space: normal;
      word-break: break-word;
    }
    #datatable thead tr th:nth-child(9),
    #datatable tbody tr td:nth-child(9) {
      max-width: 100px;
      min-width: 80px;
      white-space: normal;
      word-break: break-word;
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

          <?php
          include '../koneksi/koneksi.php';

          /**
           * getFotoList: ambil daftar nama file foto pengunjung.
           * Data lama → foto ada di tb_foto_pengunjung (kolom nama_file).
           * Data baru → foto ada di kolom foto (JSON) di tb_data_pengunjung.
           * Return array of filename strings.
           */
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

          // Tahun card default 2026
          $tahun_card = isset($_GET['tahun_card']) && is_numeric($_GET['tahun_card']) ? (int)$_GET['tahun_card'] : 2026;

          // Daftar tahun dari DB
          $res_years = mysqli_query($db, "SELECT DISTINCT YEAR(tanggal_kunjungan) as thn FROM tb_data_pengunjung ORDER BY thn ASC");
          $available_years = [];
          while ($ry = mysqli_fetch_assoc($res_years)) { $available_years[] = (int)$ry['thn']; }
          if (empty($available_years)) { $available_years = [2026]; }
          if (!in_array($tahun_card, $available_years)) { $available_years[] = $tahun_card; sort($available_years); }

          // Total per tahun card
          $r_pax  = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(pax) as v FROM tb_data_pengunjung WHERE YEAR(tanggal_kunjungan)='$tahun_card'"));
          $r_sesi = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as v FROM tb_data_pengunjung WHERE YEAR(tanggal_kunjungan)='$tahun_card'"));
          $total_pax       = $r_pax['v']  ?? 0;
          $total_kunjungan = $r_sesi['v'] ?? 0;

          // Domestik vs Mancanegara per tahun card
          $r_dom = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(pax) as pax, COUNT(*) as sesi FROM tb_data_pengunjung WHERE YEAR(tanggal_kunjungan)='$tahun_card' AND jenis_wisatawan='Domestik'"));
          $r_mnc = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(pax) as pax, COUNT(*) as sesi FROM tb_data_pengunjung WHERE YEAR(tanggal_kunjungan)='$tahun_card' AND jenis_wisatawan='Mancanegara'"));
          $dom_pax  = (int)($r_dom['pax']  ?? 0);
          $dom_sesi = (int)($r_dom['sesi'] ?? 0);
          $mnc_pax  = (int)($r_mnc['pax']  ?? 0);
          $mnc_sesi = (int)($r_mnc['sesi'] ?? 0);
          $total_jenis_pax = $dom_pax + $mnc_pax;
          $dom_pct = $total_jenis_pax > 0 ? round($dom_pax / $total_jenis_pax * 100) : 0;
          $mnc_pct = $total_jenis_pax > 0 ? round($mnc_pax / $total_jenis_pax * 100) : 0;

          // Filter aktif
          $filter_aktif = !empty($_GET['bulan']) || !empty($_GET['tahun']);
          $total_pax_filter = 0; $total_kunjungan_filter = 0; $label_filter = '';
          if ($filter_aktif) {
            $wf = "WHERE 1=1";
            if (!empty($_GET['bulan'])) { $fb = mysqli_real_escape_string($db, $_GET['bulan']); $wf .= " AND MONTH(tanggal_kunjungan)='$fb'"; }
            if (!empty($_GET['tahun'])) { $ft = mysqli_real_escape_string($db, $_GET['tahun']); $wf .= " AND YEAR(tanggal_kunjungan)='$ft'"; }
            $total_pax_filter       = mysqli_fetch_assoc(mysqli_query($db,"SELECT SUM(pax) as v FROM tb_data_pengunjung $wf"))['v'] ?? 0;
            $total_kunjungan_filter = mysqli_fetch_assoc(mysqli_query($db,"SELECT COUNT(*) as v FROM tb_data_pengunjung $wf"))['v'] ?? 0;
            $bl = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
            $parts = [];
            if (!empty($_GET['bulan'])) $parts[] = $bl[$_GET['bulan']] ?? $_GET['bulan'];
            if (!empty($_GET['tahun']))  $parts[] = $_GET['tahun'];
            $label_filter = implode(' ', $parts);
          }
          ?>

          <!-- CARD AREA -->
          <div id="card-area-wrap" style="width:100%;margin-bottom:16px;">
            <div id="card-scroll-inner" style="overflow-x:auto;overflow-y:visible;-webkit-overflow-scrolling:touch;padding-bottom:6px;">
              <div style="display:table;white-space:nowrap;border-spacing:14px 0;padding:0;">

                <!-- Card Utama: Total Kunjungan -->
                <div style="display:table-cell;vertical-align:top;width:290px;">
                  <div class="card-total-kunjungan">
                    <div id="mode-pax" style="display:flex;align-items:flex-start;gap:14px;width:100%;">
                      <div class="icon-wrap"><i class="fa fa-users"></i></div>
                      <div class="info-wrap" style="flex:1;">
                        <div class="label-text">Total Kunjungan Tahun <?php echo $tahun_card; ?></div>
                        <div class="count-number"><?php echo number_format($total_pax,0,',','.'); ?> <span>orang</span></div>
                        <div class="sub-text"><i class="fa fa-users"></i> Jumlah total pax tahun <?php echo $tahun_card; ?></div>
                      </div>
                    </div>
                    <div id="mode-sesi" style="display:none;align-items:flex-start;gap:14px;width:100%;">
                      <div class="icon-wrap"><i class="fa fa-calendar"></i></div>
                      <div class="info-wrap" style="flex:1;">
                        <div class="label-text">Total Sesi Kunjungan Tahun <?php echo $tahun_card; ?></div>
                        <div class="count-number"><?php echo number_format($total_kunjungan,0,',','.'); ?> <span>sesi</span></div>
                        <div class="sub-text"><i class="fa fa-info-circle"></i> Jumlah kunjungan tanpa dihitung pax</div>
                      </div>
                    </div>
                    <div style="position:absolute;bottom:10px;left:74px;right:14px;">
                      <div class="toggle-mode-wrap">
                        <span class="toggle-label" id="lbl-pax" style="font-weight:700;">Pax</span>
                        <label class="toggle-switch">
                          <input type="checkbox" id="toggleMode" onclick="handleToggleMain(this)">
                          <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label" id="lbl-sesi">Sesi</span>
                        &nbsp;
                        <select onchange="gantiTahunCard(this.value)" style="background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.4);border-radius:6px;color:#fff;font-size:11px;padding:2px 6px;cursor:pointer;outline:none;">
                          <?php foreach ($available_years as $yr): ?>
                            <option value="<?php echo $yr; ?>" <?php echo ($yr==$tahun_card)?'selected':''; ?> style="color:#333;background:#fff;"><?php echo $yr; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <?php if ($filter_aktif): ?>
                <!-- Card Filter -->
                <div style="display:table-cell;vertical-align:top;width:290px;">
                  <div class="card-total-kunjungan" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);box-shadow:0 4px 18px rgba(245,87,108,0.30);">
                    <div id="fmode-pax" style="display:flex;align-items:flex-start;gap:14px;width:100%;">
                      <div class="icon-wrap"><i class="fa fa-filter"></i></div>
                      <div class="info-wrap" style="flex:1;">
                        <div class="label-text">Total Kunjungan <?php echo htmlspecialchars($label_filter); ?></div>
                        <div class="count-number"><?php echo number_format($total_pax_filter,0,',','.'); ?> <span>orang</span></div>
                        <div class="sub-text"><i class="fa fa-users"></i> Hasil filter yang diterapkan</div>
                      </div>
                    </div>
                    <div id="fmode-sesi" style="display:none;align-items:flex-start;gap:14px;width:100%;">
                      <div class="icon-wrap"><i class="fa fa-calendar"></i></div>
                      <div class="info-wrap" style="flex:1;">
                        <div class="label-text">Total Sesi <?php echo htmlspecialchars($label_filter); ?></div>
                        <div class="count-number"><?php echo number_format($total_kunjungan_filter,0,',','.'); ?> <span>sesi</span></div>
                        <div class="sub-text"><i class="fa fa-info-circle"></i> Jumlah sesi kunjungan hasil filter</div>
                      </div>
                    </div>
                    <div style="position:absolute;bottom:10px;left:74px;right:14px;">
                      <div class="toggle-mode-wrap">
                        <span class="toggle-label" id="lbl-f-pax" style="font-weight:700;">Pax</span>
                        <label class="toggle-switch">
                          <input type="checkbox" id="toggleModeFilter" onclick="handleToggleFilter(this)">
                          <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label" id="lbl-f-sesi">Sesi</span>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif; ?>

                <!-- Card Asal Wisatawan (layout 2 kolom nyamping) -->
                <div style="display:table-cell;vertical-align:top;width:280px;">
                  <div class="card-jenis-wisatawan">
                    <div class="card-jenis-header">
                      <i class="fa fa-globe"></i>
                      Asal Wisatawan <?php echo $tahun_card; ?>
                    </div>
                    <div class="jenis-cols">

                      <!-- Mancanegara -->
                      <div class="jenis-col">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                          <div class="jenis-icon"><i class="fa fa-plane"></i></div>
                          <div class="jenis-label">Mancanegara</div>
                        </div>
                        <div class="jenis-count">
                          <?php echo number_format($mnc_pax,0,',','.'); ?>
                          <span>orang</span>
                        </div>
                        <div class="jenis-sub"><?php echo $mnc_sesi; ?> sesi</div>
                      </div>

                      <!-- Domestik -->
                      <div class="jenis-col">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                          <div class="jenis-icon"><i class="fa fa-map-marker"></i></div>
                          <div class="jenis-label">Domestik</div>
                        </div>
                        <div class="jenis-count">
                          <?php echo number_format($dom_pax,0,',','.'); ?>
                          <span>orang</span>
                        </div>
                        <div class="jenis-sub"><?php echo $dom_sesi; ?> sesi</div>
                      </div>

                    </div>
                  </div>
                </div>

              </div><!-- end table -->
            </div><!-- end scroll -->
          </div><!-- end card area -->

          <script>
          function handleToggleMain(el) {
            var isSesi = el.checked;
            document.getElementById('mode-pax').style.display  = isSesi ? 'none' : 'flex';
            document.getElementById('mode-sesi').style.display = isSesi ? 'flex' : 'none';
            document.getElementById('lbl-pax').style.fontWeight  = isSesi ? '400' : '700';
            document.getElementById('lbl-sesi').style.fontWeight = isSesi ? '700' : '400';
          }
          function handleToggleFilter(el) {
            var isSesi = el.checked;
            document.getElementById('fmode-pax').style.display  = isSesi ? 'none' : 'flex';
            document.getElementById('fmode-sesi').style.display = isSesi ? 'flex' : 'none';
            document.getElementById('lbl-f-pax').style.fontWeight  = isSesi ? '400' : '700';
            document.getElementById('lbl-f-sesi').style.fontWeight = isSesi ? '700' : '400';
          }
          function gantiTahunCard(thn) {
            var u = new URL(window.location.href);
            u.searchParams.set('tahun_card', thn);
            window.location.href = u.toString();
          }
          </script>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><i class="fa fa-users"></i> Data Pengunjung Wisata</h2>
                  <div class="clearfix"></div>
                </div>

                <!-- FILTER PANEL -->
                <div class="filter-panel" style="margin: 0 15px 15px 15px;">
                  <div class="filter-title"><i class="fa fa-filter"></i> Filter Data</div>
                  <form action="datapengunjung.php" method="get" class="form-inline">
                    <div class="form-group" style="margin-right:10px;">
                      <label style="margin-right:6px; font-size:13px;">Bulan:</label>
                      <select name="bulan" class="form-control input-sm">
                        <option value="">Pilih Bulan</option>
                        <?php
                        $bulan_list = [
                          '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                          '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                          '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                          '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        foreach ($bulan_list as $val => $nama) {
                          $sel = (isset($_GET['bulan']) && $_GET['bulan'] == $val) ? 'selected' : '';
                          echo '<option value="' . $val . '" ' . $sel . '>' . $nama . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group" style="margin-right:10px;">
                      <label style="margin-right:6px; font-size:13px;">Tahun:</label>
                      <select name="tahun" class="form-control input-sm">
                        <option value="">Pilih Tahun</option>
                        <?php
                        $sql_tahun_min = "SELECT YEAR(MIN(tanggal_kunjungan)) as tahun_min FROM tb_data_pengunjung";
                        $res_tahun_min = mysqli_query($db, $sql_tahun_min);
                        $row_tahun_min = mysqli_fetch_assoc($res_tahun_min);
                        $tahun_min = !empty($row_tahun_min['tahun_min']) ? (int)$row_tahun_min['tahun_min'] : (int)date('Y');
                        $tahun_max = (int)date('Y') + 1;
                        for ($t = $tahun_min; $t <= $tahun_max; $t++) {
                          $sel = (isset($_GET['tahun']) && $_GET['tahun'] == $t) ? 'selected' : '';
                          echo '<option value="' . $t . '" ' . $sel . '>' . $t . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="margin-right:6px;">
                      <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="datapengunjung.php" class="btn btn-warning btn-sm">
                      <i class="fa fa-refresh"></i> Reset Filter
                    </a>
                  </form>
                </div>

                <!-- TOMBOL AKSI -->
                <div style="padding: 0 15px 15px 15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                  <a href="inputdatapengunjung.php">
                    <button type="button" class="btn btn-primary">
                      <i class="fa fa-plus"></i> Tambah Pengunjung
                    </button>
                  </a>
                  <div>
                    <a href="export/export_data_pengunjung.php" class="btn btn-danger" style="margin-right:6px;">
                      <i class="fa fa-file-pdf-o"></i> Unduh PDF
                    </a>
                    <a href="export/exportExcel_data_pengunjung.php" class="btn btn-success">
                      <i class="fa fa-file-excel-o"></i> Unduh Excel
                    </a>
                  </div>
                </div>

                <?php if (!empty($_SESSION['notif_hapus'])): ?>
                <div class="alert alert-<?php echo $_SESSION['notif_hapus'] === 'berhasil' ? 'success' : 'danger'; ?> alert-dismissible" role="alert" style="margin:0 15px 15px 15px;border-radius:8px;">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <?php if ($_SESSION['notif_hapus'] === 'berhasil'): ?>
                    <i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> Data pengunjung berhasil dihapus.
                  <?php else: ?>
                    <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> Terjadi kesalahan saat menghapus data.
                  <?php endif; ?>
                </div>
                <?php unset($_SESSION['notif_hapus']); endif; ?>

                <div class="x_content">
                  <?php
                  $where = "WHERE 1=1";
                  if (!empty($_GET['bulan'])) {
                    $filter_bulan = mysqli_real_escape_string($db, $_GET['bulan']);
                    $where .= " AND MONTH(tanggal_kunjungan) = '$filter_bulan'";
                  }
                  if (!empty($_GET['tahun'])) {
                    $filter_tahun = mysqli_real_escape_string($db, $_GET['tahun']);
                    $where .= " AND YEAR(tanggal_kunjungan) = '$filter_tahun'";
                  }
                  $sql1   = "SELECT * FROM tb_data_pengunjung $where ORDER BY id DESC";
                  $query1 = mysqli_query($db, $sql1);
                  $total  = mysqli_num_rows($query1);
                  if ($total == 0) {
                    echo "<center><h2>Belum Ada Data Pengunjung</h2></center>";
                  } else { ?>
                  <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Paket Wisata</th>
                        <th>Detail</th>
                        <th>Jenis</th>
                        <th>Kota/Negara</th>
                        <th>Nama</th>
                        <th>Pax</th>
                        <th>Agen Wisata</th>
                        <th>Driver/Guide</th>
                        <th>Local Guide</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        while ($data = mysqli_fetch_array($query1)) {
                          $paket_display = '';
                          switch($data['pilihan_paket_wisata']) {
                            case 'meal_only':              $paket_display = 'Breakfast/Lunch/Dinner Only'; break;
                            case 'studi_banding':          $paket_display = 'Studi Banding'; break;
                            case 'fun_game':               $paket_display = 'Paket Fun Game'; break;
                            case 'pelajar_live_in':        $paket_display = 'Paket Pelajar - Live In Candirejo'; break;
                            case 'pelajar_field_trip_one_day':  $paket_display = 'Paket Pelajar – Field Trip One Day'; break;
                            case 'pelajar_field_trip_half_day': $paket_display = 'Paket Pelajar – Field Trip Half Day'; break;
                            case 'cycling_tour':           $paket_display = 'Cycling Village Tour Candirejo'; break;
                            case 'traditional_dance':      $paket_display = 'Traditional Dance'; break;
                            case 'walking_tour':           $paket_display = 'Walking Around Village'; break;
                            case 'homestay':               $paket_display = 'Stay At Local House In Candirejo Village (Homestay)'; break;
                            case 'serenade':               $paket_display = 'Serenade At The Foot Of Menoreh Hill'; break;
                            case 'cooking_lesson':         $paket_display = 'Cooking Lesson'; break;
                            case 'gamelan_class':          $paket_display = 'Gamelan Class'; break;
                            case 'village_experience':     $paket_display = 'Village Experience'; break;
                            case 'dokar_tour':             $paket_display = 'Dokar Village Tour Candirejo'; break;
                            default:                       $paket_display = htmlspecialchars($data['pilihan_paket_wisata']);
                          }

                          $detail_paket = '';
                          $paket_utama  = $data['pilihan_paket_wisata'];
                          if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($data['opsi_makan_tour'])) {
                            $detail_paket = ($data['opsi_makan_tour'] == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                          } elseif ($paket_utama == 'meal_only' && !empty($data['jenis_makanan_paket'])) {
                            $makanan_map  = ['breakfast' => 'Breakfast', 'lunch' => 'Lunch', 'dinner' => 'Dinner'];
                            $detail_paket = $makanan_map[$data['jenis_makanan_paket']] ?? $data['jenis_makanan_paket'];
                          } elseif ($paket_utama == 'cooking_lesson' && !empty($data['opsi_cooking_lesson'])) {
                            $detail_paket = ($data['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'Cooking Lesson + Tour' : 'Cooking Lesson Saja';
                          } elseif ($paket_utama == 'gamelan_class' && !empty($data['opsi_gamelan'])) {
                            $detail_paket = ($data['opsi_gamelan'] == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
                          }
                          if (empty($detail_paket)) $detail_paket = '-';

                          $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];

                          $jenis       = htmlspecialchars($data['jenis_wisatawan']);
                          $jenis_lower = strtolower($jenis);
                          if (strpos($jenis_lower, 'mancanegara') !== false)      $badge_class = 'badge-mancanegara';
                          elseif (strpos($jenis_lower, 'domestik') !== false)     $badge_class = 'badge-domestik';
                          elseif (strpos($jenis_lower, 'pelajar') !== false)      $badge_class = 'badge-pelajar';
                          else                                                     $badge_class = 'badge-lainnya';
                          $jenis_badge = '<span class="badge-jenis ' . $badge_class . '">' . $jenis . '</span>';

                          $pax_val   = htmlspecialchars($data['pax']);
                          $pax_badge = '<span class="badge-pax">' . $pax_val . ' orang</span>';

                          $foto_arr  = getFotoList($db, $data['id'], $data['foto']);
                          $foto_html = '-';
                          if (!empty($foto_arr)) {
                            $foto_html = '';
                            foreach (array_slice($foto_arr, 0, 3) as $idx => $f) {
                              $foto_html .= '<img src="uploads/pengunjung/' . htmlspecialchars($f) . '" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin:1px;" title="Foto ' . ($idx+1) . '">';
                            }
                            if (count($foto_arr) > 3) $foto_html .= '<span style="font-size:11px;color:#888;">+' . (count($foto_arr)-3) . '</span>';
                          }

                          echo '<tr>
                              <td>' . htmlspecialchars($data['tanggal_kunjungan']) . '</td>
                              <td>' . $paket_display . '</td>
                              <td>' . $detail_paket . '</td>
                              <td>' . $jenis_badge . '</td>
                              <td>' . htmlspecialchars($lokasi ?? '-') . '</td>
                              <td>' . htmlspecialchars($data['nama']) . '</td>
                              <td>' . $pax_badge . '</td>
                              <td>' . htmlspecialchars($data['agen_wisata'] ?? '-') . '</td>
                              <td>' . htmlspecialchars($data['driver_agent_guide']) . '</td>
                              <td>' . htmlspecialchars($data['local_guide']) . '</td>
                              <td>' . $foto_html . '</td>
                              <td style="text-align:center;">
                                  <a href="detail-datapengunjung.php?id=' . urlencode($data['id']) . '"><button type="button" title="Detail" class="btn btn-info btn-xs"><i class="fa fa-file-image-o"></i></button></a><br>
                                  <a href="editpengunjung.php?id=' . urlencode($data['id']) . '"><button type="button" title="Edit" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button></a><br>
                                  <button type="button" title="Hapus" onclick="konfirmasiHapus(' . $data['id'] . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                              </td>
                          </tr>';
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
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

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin-top:15%;">
      <div class="modal-content" style="border-radius:12px;overflow:hidden;border:none;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
        <div class="modal-body" style="text-align:center;padding:30px 24px 20px;">
          <div style="width:60px;height:60px;border-radius:50%;background:#fdecea;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;border:3px solid #e74c3c;">
            <i class="fa fa-exclamation" style="font-size:24px;color:#e74c3c;"></i>
          </div>
          <h4 style="font-weight:700;color:#1a1a2e;margin-bottom:8px;">Hapus Data?</h4>
          <p style="color:#555;font-size:14px;margin-bottom:4px;">Data pengunjung ini akan dihapus permanen.</p>
          <p style="color:#e74c3c;font-size:12px;margin-bottom:0;">Tindakan ini tidak dapat dibatalkan!</p>
        </div>
        <div class="modal-footer" style="border:none;justify-content:center;padding:0 24px 24px;display:flex;gap:10px;">
          <button type="button" class="btn" data-dismiss="modal"
            style="background:#f0f0f0;color:#555;border:none;border-radius:8px;padding:9px 22px;font-size:14px;font-weight:600;">
            <i class="fa fa-times"></i> Batal
          </button>
          <a id="btnYaHapus" href="#" class="btn"
            style="background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:14px;font-weight:600;box-shadow:0 4px 12px rgba(231,76,60,0.35);">
            <i class="fa fa-trash"></i> Ya, Hapus!
          </a>
        </div>
      </div>
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
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- Datatables -->
  <script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="../assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="../assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="../assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
  <script src="../assets/vendors/jszip/dist/jszip.min.js"></script>
  <script src="../assets/vendors/pdfmake/build/pdfmake.min.js"></script>
  <script src="../assets/vendors/pdfmake/build/vfs_fonts.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script type="text/javascript">
  function konfirmasiHapus(id) {
    document.getElementById('btnYaHapus').href = 'proses/proses_hapusdatapengunjung.php?id=' + id;
    $('#modalHapus').modal('show');
  }

  $(document).ready(function() {
    if (!$('#datatable').closest('.table-scroll-wrapper').length) {
      $('#datatable').wrap('<div class="table-scroll-wrapper"></div>');
    }

    function adjustContentHeight() {
      var windowH = $(window).height();
      var leftH = $('.left_col').outerHeight() || 0;
      var navH = $('.nav_menu').outerHeight() || $('.top_nav').outerHeight() || 50;
      var footerH = $('footer').outerHeight() || 40;
      var minH = Math.max(windowH, leftH) - navH - footerH;
      $('.right_col').css('min-height', minH > 200 ? minH : windowH);
    }

    if ($.fn.DataTable.isDataTable('#datatable')) {
      $('#datatable').DataTable().destroy();
    }
    $('#datatable').DataTable({
      "order": [[0, "desc"]],
      "columnDefs": [
        { "orderable": false, "targets": 11 }
      ],
      "pageLength": 10,
      "scrollX": false,
      "language": {
        "search": "Cari:",
        "lengthMenu": "Tampilkan _MENU_ data",
        "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
        "infoEmpty": "Tidak ada data",
        "zeroRecords": "Data tidak ditemukan",
        "paginate": {
          "first": "Pertama",
          "last": "Terakhir",
          "next": "Berikutnya",
          "previous": "Sebelumnya"
        }
      },
      "drawCallback": function() {
        adjustContentHeight();
      }
    });

    adjustContentHeight();
    setTimeout(adjustContentHeight, 200);
  });
  </script>

</body>

</html>