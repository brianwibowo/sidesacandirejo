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

/* ── Ambil semua data booking ────────────────────────────────────────────── */
$search  = isset($_GET['search'])  ? mysqli_real_escape_string($db, trim($_GET['search']))  : '';
$filter  = isset($_GET['filter'])  ? mysqli_real_escape_string($db, trim($_GET['filter']))  : '';

$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND (nama LIKE '%$search%' OR pilihan_paket_wisata LIKE '%$search%')";
}
if ($filter !== '' && in_array($filter, ['pending','checkin','tidak_hadir'])) {
    $where .= " AND status = '$filter'";
}

$sql_all = "SELECT * FROM tb_booking $where ORDER BY id DESC";
$q_all   = mysqli_query($db, $sql_all);
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
  <title>Semua Booking - Sistem Booking Desa Wisata Candirejo</title>
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
    .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 340px; }
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

    .filter-select {
      border: 1px solid #d6e6dc; border-radius: 8px;
      padding: 9px 14px; font-size: 13.5px; color: #1e3a2f;
      background: #fff; outline: none; cursor: pointer;
      transition: border-color 0.15s; min-width: 180px;
    }
    .filter-select:focus { border-color: #2e7d4f; }

    .btn-filter {
      display: inline-flex; align-items: center; justify-content: center;
      width: 38px; height: 38px; border: 1px solid #d6e6dc;
      border-radius: 8px; background: #fff; cursor: pointer;
      color: #6b8f7e; transition: background 0.15s, border-color 0.15s;
      flex-shrink: 0;
    }
    .btn-filter:hover { background: #f0f5f2; border-color: #b0cfc0; }
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
    .badge-pending     { background: #fdf0e0; color: #c0742a; border: 1px solid #f5d9a8; }
    .badge-checkin     { background: #e4f5ec; color: #2e7d4f; border: 1px solid #a8d8bc; }
    .badge-tidak_hadir { background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8; }

    /* ── AKSI LINKS ── */
    .aksi-cell { display: flex; align-items: center; gap: 6px; white-space: nowrap; }
    .link-detail {
      font-size: 13px; font-weight: 500; color: #4a6e5c;
      text-decoration: none; transition: color 0.15s;
    }
    .link-detail:hover { color: #1e3a2f; text-decoration: underline; }
    .link-edit {
      font-size: 13px; font-weight: 500; color: #3b6fd4;
      text-decoration: none; transition: color 0.15s;
    }
    .link-edit:hover { color: #2a55b0; text-decoration: underline; }
    .link-checkin {
      font-size: 13px; font-weight: 500; color: #2e7d4f;
      text-decoration: none; cursor: pointer; background: none; border: none;
      padding: 0; transition: color 0.15s;
    }
    .link-checkin:hover { color: #1e5c38; text-decoration: underline; }
    .link-tidakhadir {
      font-size: 13px; font-weight: 500; color: #c0392b;
      text-decoration: none; cursor: pointer; background: none; border: none;
      padding: 0; transition: color 0.15s;
    }
    .link-tidakhadir:hover { color: #962d22; text-decoration: underline; }
    .link-hapus {
      font-size: 13px; font-weight: 500; color: #b71c1c;
      text-decoration: none; cursor: pointer; background: none; border: none;
      padding: 0; transition: color 0.15s;
    }
    .link-hapus:hover { color: #7f1010; text-decoration: underline; }
    .aksi-sep { color: #c8ddd4; font-size: 12px; }

    /* ── TABLE FOOTER ── */
    .table-footer {
      padding: 12px 20px; border-top: 1px solid #f0f5f2;
      font-size: 12.5px; color: #7a9e8e;
    }

    /* ── FOOTER ── */
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
      <h1>Semua Booking</h1>
      <p>Kelola semua data booking pengunjung</p>
    </div>

    <div class="card">

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
              placeholder="Cari nama pengunjung..."
              value="<?php echo htmlspecialchars($search); ?>"
              autocomplete="off"
            >
          </div>

          <!-- Filter status -->
          <select name="filter" id="filterStatus" class="filter-select" onchange="document.getElementById('filterForm').submit()">
            <option value="" <?php echo $filter === '' ? 'selected' : ''; ?>>Semua Status</option>
            <option value="pending"      <?php echo $filter === 'pending'      ? 'selected' : ''; ?>>Pending</option>
            <option value="checkin"      <?php echo $filter === 'checkin'      ? 'selected' : ''; ?>>Check-in</option>
            <option value="tidak_hadir"  <?php echo $filter === 'tidak_hadir'  ? 'selected' : ''; ?>>Tidak Datang</option>
          </select>

          <!-- Submit/filter icon button -->
          <button type="submit" class="btn-filter" title="Filter">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M3 4h18M7 9h10M11 14h2M13 19h-2"/>
            </svg>
          </button>

          <a href="tambah_booking.php" class="btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Booking
          </a>
        </div>
      </form>

      <!-- Table -->
      <div class="table-wrapper">
        <table id="tabelSemua">
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
              <td colspan="6">Tidak ada data booking ditemukan</td>
            </tr>
            <?php else: foreach ($bookings as $row):
              $status     = $row['status'];
              $badgeClass = 'badge-' . ($status === 'tidak_hadir' ? 'tidak_hadir' : $status);
              $badgeLabel = $status === 'checkin' ? 'Check-in'
                          : ($status === 'pending' ? 'Pending' : 'Tidak Datang');
              $tgl_display = tglIndo($row['tanggal_kunjungan'] ?? $row['created_at'] ?? null);
            ?>
            <tr class="data-row">
              <td><?php echo htmlspecialchars($row['nama']); ?></td>
              <td><?php echo $tgl_display; ?></td>
              <td><?php echo (int)$row['pax']; ?></td>
              <td><?php echo htmlspecialchars(labelPaket($row['pilihan_paket_wisata'])); ?></td>
              <td>
                <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
              </td>
              <td>
                <div class="aksi-cell">
                  <a href="detail_booking.php?id=<?php echo $row['id']; ?>&ref=booking_semua.php" class="link-detail">Detail</a>
                  <span class="aksi-sep">|</span>
                  <a href="edit_booking.php?id=<?php echo $row['id']; ?>" class="link-edit">Edit</a>
                  <?php if ($status === 'pending'): ?>
                  <span class="aksi-sep">|</span>
                  <button
                    type="button"
                    class="link-checkin"
                    onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'checkin', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Check-in
                  </button>
                  <span class="aksi-sep">|</span>
                  <button
                    type="button"
                    class="link-tidakhadir"
                    onclick="konfirmasiAksi(<?php echo $row['id']; ?>, 'tidak_hadir', '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Tidak Hadir
                  </button>
                  <span class="aksi-sep">|</span>
                  <button
                    type="button"
                    class="link-hapus"
                    onclick="konfirmasiHapus(<?php echo (int)$row['id']; ?>, '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Hapus
                  </button>
                  <?php elseif ($status === 'tidak_hadir'): ?>
                  <span class="aksi-sep">|</span>
                  <button
                    type="button"
                    class="link-hapus"
                    onclick="konfirmasiHapus(<?php echo (int)$row['id']; ?>, '<?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                    Hapus
                  </button>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($bookings)): ?>
      <div class="table-footer">
        Menampilkan <strong><?php echo count($bookings); ?></strong> data booking
        <?php if ($search !== '' || $filter !== ''): ?>
          (difilter dari total seluruh data)
        <?php endif; ?>
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
<form id="formHapus" action="proses/proses_hapus_booking.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdHapus">
  <input type="hidden" name="ref" value="booking_semua.php">
</form>

<script>
/* Search live filter */
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

function konfirmasiHapus(id, nama) {
  Swal.fire({
    title: 'Hapus Booking',
    html: `<p style="font-size:15px;color:#555;">Hapus booking <strong>${nama}</strong>?</p><small style="color:#9ab5a8;">Aksi ini tidak bisa dibatalkan.</small>`,
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
</script>
</body>
</html>