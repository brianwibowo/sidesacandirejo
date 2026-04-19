<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

function tglIndo($date) {
    global $bulan_id;
    if (!$date) return '-';
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

/* ── Filter tanggal ──────────────────────────────────────────────────────── */
$tgl_mulai = isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] !== '' ? $_GET['tgl_mulai'] : null;
$tgl_akhir = isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] !== '' ? $_GET['tgl_akhir'] : null;
$show      = ($tgl_mulai && $tgl_akhir);

$total_booking  = 0;
$total_checkin  = 0;
$total_tidakdatang = 0;
$total_pax      = 0;
$rekap_harian   = [];
$chart_labels        = [];
$chart_data          = [];
$chart_data_dom      = [];
$chart_data_manca    = [];
$pie_checkin    = 0;
$pie_pending    = 0;
$pie_tidak      = 0;

if ($show) {
    $tgl_mulai_esc = mysqli_real_escape_string($db, $tgl_mulai);
    $tgl_akhir_esc = mysqli_real_escape_string($db, $tgl_akhir);

    /* ── Stat total ── */
    $q = mysqli_query($db,
        "SELECT status, COUNT(*) as jml, SUM(pax) as total_pax
         FROM tb_booking
         WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
         GROUP BY status");
    while ($r = mysqli_fetch_assoc($q)) {
        $total_booking += $r['jml'];
        $total_pax     += $r['total_pax'];
        if ($r['status'] === 'checkin')      { $total_checkin     += $r['jml']; $pie_checkin = $r['jml']; }
        if ($r['status'] === 'tidak_hadir')  { $total_tidakdatang += $r['jml']; $pie_tidak   = $r['jml']; }
        if ($r['status'] === 'pending')      { $pie_pending        = $r['jml']; }
    }

    /* ── Rekap per hari ── */
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Booking - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

    /* ── PAGE TITLE ROW ── */
    .page-title-row {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 22px; flex-wrap: wrap; gap: 12px;
    }
    .page-title h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title p  { font-size: 13.5px; color: #6b8f7e; }

    .btn-export {
      display: inline-flex; align-items: center; gap: 8px;
      background: #1e3a2f; color: #fff; border: none; border-radius: 8px;
      padding: 11px 20px; font-size: 14px; font-weight: 500;
      cursor: pointer; text-decoration: none; transition: background 0.15s; flex-shrink: 0;
    }
    .btn-export:hover { background: #2d5540; color: #fff; }

    /* ── CARD ── */
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; }

    /* ── FILTER CARD ── */
    .filter-card { padding: 20px 24px; margin-bottom: 20px; }
    .filter-row  { display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap; }
    .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .filter-group label { font-size: 12.5px; font-weight: 500; color: #4a6e5c; }
    .filter-input {
      border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 9px 12px 9px 38px; font-size: 13.5px; color: #1e3a2f;
      background: #fff; outline: none; transition: border-color 0.15s; width: 100%;
    }
    .filter-input:focus { border-color: #2e7d4f; }
    .input-wrap { position: relative; }
    .input-wrap svg {
      position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
      color: #9ab5a8; pointer-events: none;
    }
    .btn-tampilkan {
      background: #1e3a2f; color: #fff; border: none; border-radius: 8px;
      padding: 10px 28px; font-size: 14px; font-weight: 500;
      cursor: pointer; transition: background 0.15s; white-space: nowrap; height: 40px;
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
    .chart-row { display: grid; grid-template-columns: 1fr 320px; gap: 20px; margin-bottom: 20px; }
    .chart-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; padding: 22px 24px; }
    .chart-title { font-size: 15px; font-weight: 600; color: #1e3a2f; margin-bottom: 18px; }
    .chart-canvas-wrap { position: relative; height: 260px; }

    /* ── TABLE ── */
    .table-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: hidden; }
    .table-card-header { padding: 18px 22px 14px; border-bottom: 1px solid #f0f5f2; }
    .table-card-header h2 { font-size: 15px; font-weight: 600; color: #1e3a2f; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8fbf9; }
    thead th {
      padding: 11px 18px; text-align: left;
      font-size: 12.5px; font-weight: 600; color: #4a6e5c;
      letter-spacing: 0.03em; border-bottom: 1px solid #e8f0ec; white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid #f0f5f2; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafcfa; }
    tbody td { padding: 12px 18px; font-size: 13.5px; color: #1e3a2f; vertical-align: middle; }
    .num-green { color: #2e7d4f; font-weight: 500; }
    .num-red   { color: #c0392b; font-weight: 500; }
    .empty-row td { text-align: center; color: #9ab5a8; padding: 36px; font-size: 14px; }

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

    <!-- Title + Export -->
    <div class="page-title-row">
      <div class="page-title">
        <h1>Laporan Booking</h1>
        <p>Analisis dan statistik booking pengunjung</p>
      </div>
      <?php if ($show): ?>
      <a href="cetak_laporan.php?tgl_mulai=<?php echo urlencode($tgl_mulai); ?>&tgl_akhir=<?php echo urlencode($tgl_akhir); ?>" target="_blank" class="btn-export">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export PDF
      </a>
      <?php endif; ?>
    </div>

    <!-- Filter -->
    <div class="card filter-card">
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
        <div class="chart-title">Jumlah Booking per Hari</div>
        <div class="chart-canvas-wrap">
          <canvas id="chartBar"></canvas>
        </div>
      </div>
      <!-- Pie chart -->
      <div class="chart-card">
        <div class="chart-title">Status Booking</div>
        <div class="chart-canvas-wrap">
          <canvas id="chartPie"></canvas>
        </div>
      </div>
    </div>

    <!-- Rekap Tabel -->
    <div class="table-card">
      <div class="table-card-header">
        <h2>Rekap Data per Hari</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Total Booking</th>
            <th>Check-in</th>
            <th>Tidak Datang</th>
            <th>Total Pax</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rekap_harian)): ?>
          <tr class="empty-row"><td colspan="5">Tidak ada data pada rentang tanggal ini</td></tr>
          <?php else: foreach ($rekap_harian as $r): ?>
          <tr>
            <td><?php echo tglIndo($r['tanggal_kunjungan']); ?></td>
            <td><?php echo (int)$r['total']; ?></td>
            <td class="num-green"><?php echo (int)$r['jml_checkin']; ?></td>
            <td class="num-red"><?php echo (int)$r['jml_tidak']; ?></td>
            <td><?php echo (int)$r['total_pax']; ?></td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <?php endif; ?>

  </div><!-- /.content-inner -->

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<?php if ($show): ?>
<script>
/* ── Bar Chart (Domestik vs Mancanegara) ── */
window._rebuildBarChart = function () {
  const ctxBar = document.getElementById('chartBar').getContext('2d');
  window._chartBar = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($chart_labels); ?>,
      datasets: [
        {
          label: 'Wisatawan Domestik',
          data: <?php echo json_encode($chart_data_dom); ?>,
          backgroundColor: '#1e3a2f',
          borderRadius: 4,
          borderSkipped: false,
        },
        {
          label: 'Wisatawan Mancanegara',
          data: <?php echo json_encode($chart_data_manca); ?>,
          backgroundColor: '#5fa87a',
          borderRadius: 4,
          borderSkipped: false,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            color: '#1e3a2f',
            font: { size: 12 },
            padding: 16,
            usePointStyle: true,
            pointStyleWidth: 10,
          }
        },
        tooltip: {
          callbacks: {
            label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y + ' booking'
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: '#7a9e8e', font: { size: 12 } }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#7a9e8e', font: { size: 12 },
            stepSize: 2,
            callback: v => Number.isInteger(v) ? v : ''
          },
          grid: { color: '#f0f5f2' }
        }
      }
    }
  });
};

window._rebuildBarChart();

/* ── Pie Chart ── */
const pieData   = [<?php echo $pie_checkin; ?>, <?php echo $pie_pending; ?>, <?php echo $pie_tidak; ?>];
const pieLabels = ['Check-in: <?php echo $pie_checkin; ?>', 'Pending: <?php echo $pie_pending; ?>', 'Tidak Datang: <?php echo $pie_tidak; ?>'];
const pieColors = ['#2e7d4f', '#f0a500', '#c0392b'];

window._rebuildPieChart = function () {
  const ctxPie = document.getElementById('chartPie').getContext('2d');
  window._chartPie = new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: pieLabels,
      datasets: [{
        data: pieData,
        backgroundColor: pieColors,
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: {
            color: '#1e3a2f',
            font: { size: 12 },
            padding: 14,
            usePointStyle: true,
            pointStyleWidth: 10,
          }
        },
        tooltip: {
          callbacks: {
            label: ctx => ' ' + ctx.label
          }
        }
      }
    }
  });
};

window._rebuildPieChart();
</script>
<?php endif; ?>
</body>
</html>