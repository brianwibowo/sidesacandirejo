<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

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

/* ── Ambil data booking pending ─────────────────────────────────────────── */
$search = isset($_GET['search']) ? mysqli_real_escape_string($db, trim($_GET['search'])) : '';

$where = "WHERE status = 'pending'";
if ($search !== '') {
    $where .= " AND (nama LIKE '%$search%' OR pilihan_paket_wisata LIKE '%$search%')";
}

$sql_all  = "SELECT * FROM tb_booking $where ORDER BY tanggal_kunjungan ASC";
$q_all    = mysqli_query($db, $sql_all);
$bookings = [];
while ($r = mysqli_fetch_assoc($q_all)) $bookings[] = $r;

$bulan_id = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
function tglIndo($date) {
    global $bulan_id;
    if (!$date) return '-';
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Pending - Sistem Booking Desa Wisata Candirejo</title>
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

    /* ── PAGE TITLE ── */
    .page-title { margin-bottom: 22px; }
    .page-title h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title p  { font-size: 13.5px; color: #6b8f7e; }

    /* ── CARD ── */
    .card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: visible; }

    /* ── FILTER BAR ── */
    .filter-bar {
      display: flex; align-items: center; gap: 10px;
      padding: 16px 20px; border-bottom: 1px solid #f0f5f2;
      flex-wrap: wrap;
    }
    .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 380px; }
    .search-wrap svg {
      position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
      color: #9ab5a8; pointer-events: none;
    }
    .search-input {
      width: 100%; border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 9px 14px 9px 36px; font-size: 13.5px; color: #1e3a2f;
      background: #fff; outline: none; transition: border-color 0.15s;
    }
    .search-input::placeholder { color: #aec9b8; }
    .search-input:focus { border-color: #2e7d4f; }

    .filter-date {
      flex: 1; min-width: 160px;
      border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 9px 14px; font-size: 13.5px; color: #1e3a2f;
      background: #fff; outline: none; transition: border-color 0.15s;
    }
    .filter-date:focus { border-color: #2e7d4f; }
    .btn-tambah {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      height: 38px; padding: 0 14px;
      border: 1px solid #1e3a2f; border-radius: 8px;
      background: #1e3a2f; color: #fff; text-decoration: none;
      font-size: 13px; font-weight: 600; white-space: nowrap;
      transition: background 0.15s, border-color 0.15s;
    }
    .btn-tambah:hover { background: #2d5540; border-color: #2d5540; color: #fff; }

    /* ── TABLE ── */
    .table-wrapper { overflow-x: auto; border-radius: 0 0 12px 12px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f8fbf9; }
    thead th {
      padding: 11px 16px; text-align: left;
      font-size: 12.5px; font-weight: 600; color: #4a6e5c;
      letter-spacing: 0.03em; border-bottom: 1px solid #e8f0ec;
      white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid #f0f5f2; transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafcfa; }
    tbody td {
      padding: 12px 16px; font-size: 13.5px; color: #1e3a2f;
      vertical-align: middle;
    }
    .empty-row td { text-align: center; color: #9ab5a8; padding: 36px; font-size: 14px; }

    /* ── BADGE ── */
    .badge {
      display: inline-block; padding: 4px 11px; border-radius: 20px;
      font-size: 12px; font-weight: 500; white-space: nowrap;
    }
    .badge-pending { background: #fdf0e0; color: #c0742a; border: 1px solid #f5d9a8; }

    /* ── AKSI ── */
    .aksi-cell { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .link-detail {
      font-size: 13px; font-weight: 500; color: #4a6e5c;
      text-decoration: none; transition: color 0.15s;
    }
    .link-detail:hover { color: #1e3a2f; text-decoration: underline; }
    .btn-checkin {
      display: inline-flex; align-items: center; gap: 5px;
      background: #1e3a2f; color: #fff; border: none;
      border-radius: 7px; padding: 6px 14px;
      font-size: 13px; font-weight: 500; cursor: pointer;
      transition: background 0.15s; white-space: nowrap;
    }
    .btn-checkin:hover { background: #2d5540; }
    .btn-tidakhadir {
      display: inline-flex; align-items: center; gap: 5px;
      background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8;
      border-radius: 7px; padding: 6px 12px;
      font-size: 13px; font-weight: 500; cursor: pointer;
      transition: background 0.15s, color 0.15s; white-space: nowrap;
    }
    .btn-tidakhadir:hover { background: #c0392b; color: #fff; border-color: #c0392b; }

    /* ── TABLE FOOTER ── */
    .table-footer {
      padding: 12px 20px; border-top: 1px solid #f0f5f2;
      font-size: 12.5px; color: #7a9e8e;
    }

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

    <div class="page-title">
      <h1>Booking Pending</h1>
      <p>Daftar booking yang menunggu kedatangan</p>
    </div>

    <div class="card">

      <!-- Filter bar -->
      <form method="GET" action="" id="filterForm">
        <div class="filter-bar">
          <div class="search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input
              type="text"
              name="search"
              id="searchInput"
              class="search-input"
              placeholder="Cari nama pengunjung..."
              value="<?php echo htmlspecialchars($search); ?>"
              autocomplete="off"
            >
          </div>
          <input
            type="date"
            name="tanggal"
            id="filterTanggal"
            class="filter-date"
            value="<?php echo isset($_GET['tanggal']) ? htmlspecialchars($_GET['tanggal']) : ''; ?>"
            onchange="document.getElementById('filterForm').submit()"
          >
          <a href="tambah_booking.php" class="btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Booking
          </a>
        </div>
      </form>

      <!-- Table -->
      <div class="table-wrapper">
        <table id="tabelPending">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Tanggal Booking</th>
              <th>Pax</th>
              <th>Paket</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($bookings)): ?>
            <tr class="empty-row">
              <td colspan="6">Tidak ada booking pending saat ini</td>
            </tr>
            <?php else: foreach ($bookings as $row):
              $tgl_display = tglIndo($row['tanggal_kunjungan'] ?? $row['created_at'] ?? null);
            ?>
            <tr class="data-row">
              <td><?php echo htmlspecialchars($row['nama']); ?></td>
              <td><?php echo $tgl_display; ?></td>
              <td><?php echo (int)$row['pax']; ?></td>
              <td><?php echo htmlspecialchars(labelPaket($row['pilihan_paket_wisata'])); ?></td>
              <td><span class="badge badge-pending">Pending</span></td>
              <td>
                <div class="aksi-cell">
                  <a href="detail_booking.php?id=<?php echo $row['id']; ?>&ref=booking_pending.php" class="link-detail">Detail</a>
                  <button
                    type="button"
                    class="btn-checkin"
                    onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'checkin', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Check-in
                  </button>
                  <button
                    type="button"
                    class="btn-tidakhadir"
                    onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'tidak_hadir', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Tidak Hadir
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($bookings)): ?>
      <div class="table-footer">
        Menampilkan <strong><?php echo count($bookings); ?></strong> data booking pending
      </div>
      <?php endif; ?>

    </div><!-- /.card -->

  </div><!-- /.content-inner -->

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<!-- Form tersembunyi untuk proses aksi booking -->
<form id="formAksi" action="proses/proses_checkin.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdBooking">
  <input type="hidden" name="aksi" id="inputAksi">
</form>

<script>
document.getElementById('searchInput').addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    document.getElementById('filterForm').submit();
  }
});

function konfirmasiAksi(id, aksi, nama) {
  const isCheckin = aksi === 'checkin';
  Swal.fire({
    title: isCheckin ? 'Konfirmasi Check-in' : 'Tandai Tidak Hadir',
    html: `<p style="font-size:15px;color:#555;">
      ${isCheckin
        ? 'Check-in untuk <strong>' + nama + '</strong>?<br><small style="color:#9ab5a8;margin-top:6px;display:block;">Data otomatis masuk ke Data Pengunjung.</small>'
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
      document.getElementById('inputAksi').value = aksi;
      document.getElementById('formAksi').submit();
    }
  });
}
</script>
</body>
</html>