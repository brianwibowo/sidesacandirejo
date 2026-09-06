<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

/* ── Bulan & tahun yang ditampilkan ─────────────────────────────────────── */
$today_y = (int)date('Y');
$today_m = (int)date('n');
$today_d = (int)date('j');

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : $today_m;
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : $today_y;

// Batasi agar valid
if ($bulan < 1)  { $bulan = 12; $tahun--; }
if ($bulan > 12) { $bulan = 1;  $tahun++; }

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

/* ── Ambil semua booking di bulan ini ───────────────────────────────────── */
$tgl_awal = sprintf('%04d-%02d-01', $tahun, $bulan);
$tgl_akhir = sprintf('%04d-%02d-%02d', $tahun, $bulan, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun));

$sql = "SELECT id, nama, agen_wisata, pax, status, tanggal_kunjungan, pilihan_paket_wisata
        FROM tb_booking
        WHERE tanggal_kunjungan BETWEEN '$tgl_awal' AND '$tgl_akhir'
        ORDER BY tanggal_kunjungan ASC, id ASC";
$q   = mysqli_query($db, $sql);

/* Kelompokkan per tanggal: ['2026-04-12' => [...rows...]] */
$booking_map = [];
while ($r = mysqli_fetch_assoc($q)) {
    $key = $r['tanggal_kunjungan'];
    $booking_map[$key][] = $r;
}

/* ── Tanggal yang dipilih (klik kalender) ───────────────────────────────── */
$selected_date = isset($_GET['tgl']) ? $_GET['tgl'] : null;
$selected_bookings = [];
if ($selected_date && isset($booking_map[$selected_date])) {
    $selected_bookings = $booking_map[$selected_date];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kalender Booking - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f7f5; color: #1e3a2f; min-height: 100vh;
    }

    /* ── LAYOUT ── */
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 28px 40px; }

    /* ── CARD GLOBAL ── */
    .card {
      background: #fff;
      border: 1px solid #e5ede8;
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    /* ── PAGE TITLE CARD ── */
    .page-title-card {
      margin-bottom: 20px;
      overflow: hidden;
    }
    .card-header-table {
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      background: #fff;
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
    .btn-today {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #f4fbf6;
      color: #1e3a2f;
      border: 1px solid #c8e4d3;
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s;
    }
    .btn-today:hover {
      background: #1e3a2f;
      color: #fff;
      border-color: #1e3a2f;
    }

    /* ── MAIN GRID ── */
    .kalender-layout {
      display: grid;
      grid-template-columns: 1fr 320px;
      gap: 20px;
      align-items: start;
    }

    /* ── KALENDER CARD ── */
    .kalender-card {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      padding: 24px 24px 20px;
    }

    /* Header bulan */
    .kal-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 22px;
    }
    .kal-header h2 { font-size: 18px; font-weight: 600; color: #1e3a2f; }
    .kal-nav { display: flex; gap: 6px; }
    .kal-nav-btn {
      width: 32px; height: 32px; border: 1px solid #d6e6dc; border-radius: 7px;
      background: #fff; cursor: pointer; display: flex; align-items: center;
      justify-content: center; color: #4a6358; transition: background 0.15s;
    }
    .kal-nav-btn:hover { background: #f0f5f2; }

    /* Grid hari */
    .kal-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 6px;
    }
    .kal-day-name {
      text-align: center; font-size: 12px; font-weight: 600;
      color: #7a9e8e; padding: 6px 0; letter-spacing: 0.02em;
    }
    .kal-cell {
      min-height: 80px; border: 1px solid #e8f0ec; border-radius: 8px;
      padding: 8px; cursor: pointer; transition: background 0.12s, border-color 0.12s;
      position: relative; display: flex; flex-direction: column;
    }
    .kal-cell.empty { background: transparent; border-color: transparent; cursor: default; }
    .kal-cell:not(.empty):hover { background: #f4f8f5; border-color: #b8d4c4; }

    /* Today */
    .kal-cell.today { border: 1.5px solid #1e3a2f; background: #fff; }

    /* Selected */
    .kal-cell.selected { background: #1e3a2f !important; border-color: #1e3a2f !important; }
    .kal-cell.selected .kal-date { color: #fff; }
    .kal-cell.selected .kal-booking-count { color: rgba(255,255,255,0.8); }

    .kal-date {
      font-size: 13.5px; font-weight: 500; color: #1e3a2f; line-height: 1;
    }
    .kal-booking-count {
      margin-top: auto; font-size: 11.5px; color: #6b8f7e; font-weight: 500;
    }

    /* Legend */
    .kal-legend {
      display: flex; gap: 18px; margin-top: 16px; padding-top: 14px;
      border-top: 1px solid #f0f5f2;
    }
    .legend-item { display: flex; align-items: center; gap: 7px; font-size: 12.5px; color: #6b8f7e; }
    .legend-box {
      width: 16px; height: 16px; border-radius: 4px; flex-shrink: 0;
    }
    .legend-box.today   { border: 1.5px solid #1e3a2f; background: #fff; }
    .legend-box.selected { background: #1e3a2f; }

    /* ── SIDE PANEL ── */
    .side-panel {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      padding: 20px;
    }
    .side-panel-empty {
      text-align: center; padding: 40px 20px;
      color: #9ab5a8; font-size: 13.5px;
    }
    .side-panel-empty svg { margin-bottom: 10px; color: #c8ddd4; }

    .panel-header {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 18px;
    }
    .panel-title { font-size: 15px; font-weight: 600; color: #1e3a2f; }
    .panel-date  { font-size: 13px; color: #6b8f7e; margin-top: 3px; }
    .panel-close {
      background: none; border: none; cursor: pointer;
      color: #9ab5a8; padding: 2px; transition: color 0.15s;
    }
    .panel-close:hover { color: #1e3a2f; }

    /* Booking item di panel */
    .booking-item {
      padding: 14px 0;
      border-bottom: 1px solid #f0f5f2;
    }
    .booking-item:last-child { border-bottom: none; }

    .booking-item-top {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 4px;
    }
    .booking-item-nama { font-size: 14px; font-weight: 600; color: #1e3a2f; }
    .booking-item-pax  { font-size: 12.5px; color: #7a9e8e; margin-bottom: 10px; }

    /* Badge */
    .badge {
      display: inline-block; padding: 3px 10px; border-radius: 20px;
      font-size: 11.5px; font-weight: 500; white-space: nowrap;
    }
    .badge-pending     { background: #fdf0e0; color: #c0742a; border: 1px solid #f5d9a8; }
    .badge-checkin     { background: #e4f5ec; color: #2e7d4f; border: 1px solid #a8d8bc; }
    .badge-tidak_hadir { background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8; }

    /* Tombol Icon Detail */
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
    .btn-icon::after {
      content: attr(data-tooltip);
      position: absolute; top: calc(100% + 7px); left: 50%; transform: translateX(-50%);
      background: #1e3a2f; color: #fff; font-size: 11px; font-weight: 500;
      padding: 3px 8px; border-radius: 5px; white-space: nowrap;
      opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 999;
    }
    .btn-icon:hover::after { opacity: 1; }

    /* Tombol Check-in di panel */
    .btn-checkin-panel {
      width: 100%; background: #1e3a2f; color: #fff; border: none;
      border-radius: 7px; padding: 9px; font-size: 13px; font-weight: 500;
      cursor: pointer; transition: background 0.15s; margin-top: 8px;
    }
    .btn-checkin-panel:hover { background: #2d5540; }

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

    <!-- Card Header (Judul & Subtitle di dalam Card) -->
    <div class="card page-title-card">
      <div class="card-header-table">
        <div>
          <h1>Kalender Booking</h1>
          <p>Lihat jadwal booking dalam tampilan kalender</p>
        </div>
        <div>
          <a href="?bulan=<?php echo $today_m; ?>&tahun=<?php echo $today_y; ?>&tgl=<?php echo date('Y-m-d'); ?>" class="btn-today" title="Lompat ke tanggal hari ini">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Hari Ini
          </a>
        </div>
      </div>
    </div>

    <div class="kalender-layout">

      <!-- ── KALENDER ── -->
      <div class="kalender-card">

        <div class="kal-header">
          <h2><?php echo $bulan_id[$bulan] . ' ' . $tahun; ?></h2>
          <div class="kal-nav">
            <?php
              $prev_m = $bulan - 1; $prev_y = $tahun;
              if ($prev_m < 1) { $prev_m = 12; $prev_y--; }
              $next_m = $bulan + 1; $next_y = $tahun;
              if ($next_m > 12) { $next_m = 1; $next_y++; }
            ?>
            <a href="?bulan=<?php echo $prev_m; ?>&tahun=<?php echo $prev_y; ?><?php echo $selected_date ? '&tgl='.$selected_date : ''; ?>" class="kal-nav-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
            <a href="?bulan=<?php echo $next_m; ?>&tahun=<?php echo $next_y; ?><?php echo $selected_date ? '&tgl='.$selected_date : ''; ?>" class="kal-nav-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </a>
          </div>
        </div>

        <div class="kal-grid">
          <!-- Nama hari -->
          <?php foreach (['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $h): ?>
            <div class="kal-day-name"><?php echo $h; ?></div>
          <?php endforeach; ?>

          <?php
            $first_dow  = (int)date('w', mktime(0,0,0,$bulan,1,$tahun)); // 0=Min
            $days_total = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            // Sel kosong sebelum hari pertama
            for ($i = 0; $i < $first_dow; $i++):
          ?>
            <div class="kal-cell empty"></div>
          <?php endfor; ?>

          <?php for ($d = 1; $d <= $days_total; $d++):
            $tgl_key  = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $count    = isset($booking_map[$tgl_key]) ? count($booking_map[$tgl_key]) : 0;
            $is_today = ($d == $today_d && $bulan == $today_m && $tahun == $today_y);
            $is_sel   = ($selected_date === $tgl_key);
            $classes  = 'kal-cell';
            if ($is_today)  $classes .= ' today';
            if ($is_sel)    $classes .= ' selected';
            $url = '?bulan='.$bulan.'&tahun='.$tahun.'&tgl='.$tgl_key;
          ?>
            <a href="<?php echo $url; ?>" class="<?php echo $classes; ?>" style="text-decoration:none;">
              <span class="kal-date"><?php echo $d; ?></span>
              <?php if ($count > 0): ?>
                <span class="kal-booking-count"><?php echo $count; ?> booking</span>
              <?php endif; ?>
            </a>
          <?php endfor; ?>
        </div>

        <!-- Legend -->
        <div class="kal-legend">
          <div class="legend-item">
            <div class="legend-box today"></div>
            <span>Hari ini</span>
          </div>
          <div class="legend-item">
            <div class="legend-box selected"></div>
            <span>Tanggal dipilih</span>
          </div>
        </div>

      </div><!-- /.kalender-card -->

      <!-- ── SIDE PANEL ── -->
      <div class="side-panel" id="sidePanel">
        <?php if (!$selected_date || empty($selected_bookings)): ?>
          <?php if ($selected_date && empty($selected_bookings)): ?>
            <!-- Tanggal dipilih tapi tidak ada booking -->
            <div class="panel-header">
              <div>
                <div class="panel-title">Booking pada</div>
                <div class="panel-date">
                  <?php
                    $ts = strtotime($selected_date);
                    echo date('j', $ts) . ' ' . $bulan_id[(int)date('n',$ts)] . ' ' . date('Y',$ts);
                  ?>
                </div>
              </div>
              <a href="?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" class="panel-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
              </a>
            </div>
            <div class="side-panel-empty">Tidak ada booking pada tanggal ini</div>
          <?php else: ?>
            <!-- Belum pilih tanggal -->
            <div class="side-panel-empty">
              <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <p>Pilih tanggal pada kalender<br>untuk melihat booking</p>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <!-- Ada booking -->
          <div class="panel-header">
            <div>
              <div class="panel-title">Booking pada</div>
              <div class="panel-date">
                <?php
                  $ts = strtotime($selected_date);
                  echo date('j', $ts) . ' ' . $bulan_id[(int)date('n',$ts)] . ' ' . date('Y',$ts);
                ?>
              </div>
            </div>
            <a href="?bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>" class="panel-close">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </a>
          </div>

          <?php foreach ($selected_bookings as $b):
            $status        = $b['status'];
            $badgeClass    = 'badge-' . ($status === 'tidak_hadir' ? 'tidak_hadir' : $status);
            $badgeLabel    = $status === 'checkin' ? 'Check-in'
                           : ($status === 'pending' ? 'Pending' : 'Tidak Datang');
            $nama_agen_kal = (!empty(trim((string)($b['agen_wisata'] ?? ''))) && strtolower(trim((string)$b['agen_wisata'])) !== 'null')
                           ? htmlspecialchars($b['agen_wisata'])
                           : '-';
          ?>
          <div class="booking-item">
            <div class="booking-item-top" style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
              <div style="flex:1;min-width:0;">
                <div class="booking-item-nama" style="font-size:13.5px;font-weight:600;word-break:break-word;line-height:1.35;">
                  <?php echo $nama_agen_kal; ?>
                </div>
                <div class="booking-item-pax" style="margin-top:3px;margin-bottom:0;">
                  <?php echo (int)$b['pax']; ?> pax
                </div>
              </div>
              <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
                <a href="detail_booking.php?id=<?php echo $b['id']; ?>&ref=booking_kalender.php" class="btn-icon btn-detail" data-tooltip="Lihat Detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
              </div>
            </div>
            <?php if ($status === 'pending'): ?>
              <button class="btn-checkin-panel" style="margin-top:10px;"
                onclick="konfirmasiCheckin(<?php echo $b['id']; ?>, '<?php echo htmlspecialchars($b['nama'], ENT_QUOTES); ?>')">
                Check-in
              </button>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>

        <?php endif; ?>
      </div><!-- /.side-panel -->

    </div><!-- /.kalender-layout -->

  </div><!-- /.content-inner -->

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<!-- Form tersembunyi untuk proses check-in -->
<form id="formCheckin" action="proses/proses_checkin.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdBooking">
  <input type="hidden" name="aksi" value="checkin">
</form>

<script>
function konfirmasiCheckin(id, nama) {
  Swal.fire({
    title: 'Konfirmasi Check-in',
    html: `<p style="font-size:15px;color:#555;">
      Check-in untuk <strong>${nama}</strong>?<br>
      <small style="color:#9ab5a8;margin-top:6px;display:block;">
        Data otomatis masuk ke Data Pengunjung.
      </small>
    </p>`,
    icon: 'question',
    iconColor: '#2e7d4f',
    showCancelButton: true,
    confirmButtonText: 'Ya, Check-in',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#2e7d4f',
    cancelButtonColor: '#aaa',
  }).then(result => {
    if (result.isConfirmed) {
      document.getElementById('inputIdBooking').value = id;
      document.getElementById('formCheckin').submit();
    }
  });
}
</script>
</body>
</html>