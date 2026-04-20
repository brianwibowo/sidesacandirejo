<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

function e($v) {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES);
}

function labelPaket($kode) {
    $map = [
        'meal_only'                   => 'Breakfast/Lunch/Dinner Only',
        'studi_banding'               => 'Studi Banding',
        'fun_game'                    => 'Paket Fun Game',
        'pelajar_live_in'             => 'Pelajar - Live In',
        'pelajar_field_trip_one_day'  => 'Pelajar - Field Trip One Day',
        'pelajar_field_trip_half_day' => 'Pelajar - Field Trip Half Day',
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
    return isset($map[$kode]) ? $map[$kode] : ucwords(str_replace('_', ' ', (string)$kode));
}

function labelStatus($status) {
    if ($status === 'checkin') return 'Check-in';
    if ($status === 'pending') return 'Pending';
    return 'Tidak Datang';
}

function badgeClass($status) {
    return 'badge-' . ($status === 'tidak_hadir' ? 'tidak_hadir' : $status);
}

function fmtDateTime($dt) {
    if (!$dt) return '-';
    $ts = strtotime($dt);
    if (!$ts) return '-';
    return date('d M Y H:i', $ts);
}

function displayField($val, $fallback = '-') {
    if ($val === null) return $fallback;
    $val = trim((string)$val);
    if ($val === '' || strtolower($val) === 'null') return $fallback;
    return e($val);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: booking_dashboard.php?msg=gagal&detail=ID booking tidak valid");
    exit;
}

$allowed_ref = [
    'booking_dashboard.php',
    'booking_semua.php',
    'booking_pending.php',
    'booking_checkin.php',
    'booking_tidakdatang.php',
    'booking_kalender.php',
    'booking_laporan.php'
];
$ref = isset($_GET['ref']) ? basename($_GET['ref']) : 'booking_semua.php';
if (!in_array($ref, $allowed_ref, true)) {
    $ref = 'booking_semua.php';
}

$stmt = $db->prepare("SELECT * FROM tb_booking WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$bk = $result->fetch_assoc();
$stmt->close();

if (!$bk) {
    header("Location: $ref?msg=gagal&detail=Data booking tidak ditemukan");
    exit;
}

$pengunjung = null;
if ($bk['status'] === 'checkin' && !empty($bk['id_pengunjung'])) {
    $id_pengunjung = (int)$bk['id_pengunjung'];
    $pstmt = $db->prepare("SELECT id, nama, foto FROM tb_data_pengunjung WHERE id = ? LIMIT 1");
    $pstmt->bind_param("i", $id_pengunjung);
    $pstmt->execute();
    $pengunjung = $pstmt->get_result()->fetch_assoc();
    $pstmt->close();
}

$asal = $bk['jenis_wisatawan'] === 'Domestik' ? displayField($bk['kota']) : displayField($bk['negara']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Booking #<?php echo (int)$bk['id']; ?> - Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f7f5; color: #1e3a2f; min-height: 100vh; }
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 28px 40px; }

    .page-title { margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .page-title h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; }
    .page-title p { font-size: 13.5px; color: #6b8f7e; margin-top: 3px; }

    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      background: #fff; color: #4a6a57; border: 1px solid #d6e6dc;
      border-radius: 8px; padding: 9px 15px; text-decoration: none;
      font-size: 13px; font-weight: 500; transition: all 0.15s;
    }
    .btn-back:hover { background: #eef5f1; color: #1e3a2f; }

    .summary-card {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      padding: 16px 18px; margin-bottom: 18px;
      display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
    }
    .summary-left h2 { font-size: 18px; font-weight: 600; margin-bottom: 4px; }
    .summary-meta { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; font-size: 12.5px; color: #6b8f7e; }

    .badge {
      display: inline-flex; align-items: center;
      padding: 5px 12px; border-radius: 20px;
      font-size: 12px; font-weight: 600; white-space: nowrap;
    }
    .badge-pending { background: #fdf0e0; color: #c0742a; border: 1px solid #f5d9a8; }
    .badge-checkin { background: #e4f5ec; color: #2e7d4f; border: 1px solid #a8d8bc; }
    .badge-tidak_hadir { background: #fceaea; color: #c0392b; border: 1px solid #f5b8b8; }

    .layout-grid { display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 18px; align-items: start; }
    .panel {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px;
      overflow: hidden;
    }
    .panel-header {
      padding: 13px 16px; border-bottom: 1px solid #eef4f1;
      font-size: 14px; font-weight: 600; color: #1e3a2f;
    }
    .panel-body { padding: 14px 16px; }

    .info-list { display: grid; grid-template-columns: 200px 1fr; row-gap: 10px; column-gap: 12px; }
    .info-label { font-size: 12.5px; color: #6b8f7e; }
    .info-value { font-size: 13.5px; color: #1e3a2f; font-weight: 500; word-break: break-word; }

    .stack-panels { display: flex; flex-direction: column; gap: 16px; }

    .action-list { display: flex; flex-direction: column; gap: 9px; }
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      border: none; border-radius: 8px; padding: 10px 12px;
      text-decoration: none; cursor: pointer; font-size: 13px; font-weight: 600;
      transition: background 0.15s, color 0.15s;
    }
    .btn-checkin { background: #e4f5ec; color: #2e7d4f; }
    .btn-checkin:hover { background: #2e7d4f; color: #fff; }
    .btn-tidakhadir { background: #fceaea; color: #c0392b; }
    .btn-tidakhadir:hover { background: #c0392b; color: #fff; }
    .btn-hapus { background: #ffe9e9; color: #b71c1c; border: 1px solid #f0b0b0; }
    .btn-hapus:hover { background: #b71c1c; color: #fff; border-color: #b71c1c; }
    .btn-edit { background: #eef3ff; color: #3b6fd4; }
    .btn-edit:hover { background: #3b6fd4; color: #fff; }
    .btn-outline { background: #fff; color: #4a6a57; border: 1px solid #d6e6dc; }
    .btn-outline:hover { background: #eef5f1; color: #1e3a2f; }

    .timeline { display: flex; flex-direction: column; gap: 10px; }
    .timeline-item {
      border: 1px solid #edf3ef; border-radius: 8px; padding: 10px 11px;
      background: #fafcfa;
    }
    .timeline-title { font-size: 12.5px; color: #4a6a57; font-weight: 600; margin-bottom: 2px; }
    .timeline-time { font-size: 12px; color: #7a9e8e; }

    .booking-footer {
      text-align: right; padding: 16px 28px; font-size: 12.5px;
      color: #9ab5a8; border-top: 1px solid #e5ede8; margin-top: 20px;
    }

    @media (max-width: 980px) {
      .layout-grid { grid-template-columns: 1fr; }
      .info-list { grid-template-columns: 1fr; row-gap: 4px; }
      .info-label { margin-top: 6px; }
    }
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>
<?php include 'booking_header.php'; ?>

<main class="booking-content" id="bookingContent">
  <div class="content-inner">

    <div class="page-title">
      <div>
        <h1>Detail Booking</h1>
        <p>Informasi lengkap booking dan status proses</p>
      </div>
      <a href="<?php echo e($ref); ?>" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Kembali
      </a>
    </div>

    <div class="summary-card">
      <div class="summary-left">
        <h2>#<?php echo (int)$bk['id']; ?> - <?php echo e($bk['nama']); ?></h2>
        <div class="summary-meta">
          <span>Tanggal Kunjungan: <strong><?php echo e($bk['tanggal_kunjungan']); ?></strong></span>
          <span>Pax: <strong><?php echo (int)$bk['pax']; ?></strong></span>
          <span>Dibuat: <strong><?php echo e(fmtDateTime($bk['created_at'])); ?></strong></span>
        </div>
      </div>
      <span class="badge <?php echo e(badgeClass($bk['status'])); ?>"><?php echo e(labelStatus($bk['status'])); ?></span>
    </div>

    <div class="layout-grid">

      <div class="stack-panels">

        <section class="panel">
          <div class="panel-header">Informasi Wisatawan</div>
          <div class="panel-body">
            <div class="info-list">
              <div class="info-label">Nama Wisatawan</div><div class="info-value"><?php echo e($bk['nama']); ?></div>
              <div class="info-label">Jenis Wisatawan</div><div class="info-value"><?php echo e($bk['jenis_wisatawan']); ?></div>
              <div class="info-label">Asal</div><div class="info-value"><?php echo $asal; ?></div>
              <div class="info-label">Agen Wisata</div><div class="info-value"><?php echo displayField($bk['agen_wisata']); ?></div>
            </div>
          </div>
        </section>

        <section class="panel">
          <div class="panel-header">Paket & Opsi Layanan</div>
          <div class="panel-body">
            <div class="info-list">
              <div class="info-label">Paket Wisata</div><div class="info-value"><?php echo e(labelPaket($bk['pilihan_paket_wisata'])); ?></div>
              <div class="info-label">Opsi Makan Tour</div><div class="info-value"><?php echo displayField($bk['opsi_makan_tour']); ?></div>
              <div class="info-label">Jenis Makanan</div><div class="info-value"><?php echo displayField($bk['jenis_makanan_paket']); ?></div>
              <div class="info-label">Opsi Cooking Lesson</div><div class="info-value"><?php echo displayField($bk['opsi_cooking_lesson']); ?></div>
              <div class="info-label">Opsi Gamelan</div><div class="info-value"><?php echo displayField($bk['opsi_gamelan']); ?></div>
            </div>
          </div>
        </section>

        <section class="panel">
          <div class="panel-header">Operasional Lapangan</div>
          <div class="panel-body">
            <div class="info-list">
              <div class="info-label">Driver / Agent Guide</div><div class="info-value"><?php echo displayField($bk['driver_agent_guide']); ?></div>
              <div class="info-label">Local Guide</div><div class="info-value"><?php echo displayField($bk['local_guide']); ?></div>
            </div>
          </div>
        </section>

      </div>

      <div class="stack-panels">

        <section class="panel">
          <div class="panel-header">Aksi Booking</div>
          <div class="panel-body">
            <div class="action-list">
              <?php if ($bk['status'] === 'pending'): ?>
                <button type="button" class="btn btn-checkin" onclick="konfirmasiAksi(<?php echo (int)$bk['id']; ?>, 'checkin', '<?php echo e($bk['nama']); ?>')">Check-in Sekarang</button>
                <button type="button" class="btn btn-tidakhadir" onclick="konfirmasiAksi(<?php echo (int)$bk['id']; ?>, 'tidak_hadir', '<?php echo e($bk['nama']); ?>')">Tandai Tidak Hadir</button>
                <button type="button" class="btn btn-hapus" onclick="konfirmasiHapus(<?php echo (int)$bk['id']; ?>, '<?php echo e($bk['nama']); ?>')">Hapus Booking</button>
                <a href="edit_booking.php?id=<?php echo (int)$bk['id']; ?>" class="btn btn-edit">Edit Data Booking</a>
              <?php elseif ($bk['status'] === 'tidak_hadir'): ?>
                <button type="button" class="btn btn-hapus" onclick="konfirmasiHapus(<?php echo (int)$bk['id']; ?>, '<?php echo e($bk['nama']); ?>')">Hapus Booking</button>
              <?php else: ?>
                <div class="info-value" style="font-size:13px;color:#6b8f7e;">
                  Aksi status dinonaktifkan karena booking sudah diproses.
                </div>
              <?php endif; ?>

              <?php if ($bk['status'] === 'checkin' && !empty($bk['id_pengunjung'])): ?>
                <a href="../admin/detail-datapengunjung.php?id=<?php echo (int)$bk['id_pengunjung']; ?>" target="_blank" class="btn btn-outline">Lihat Data Pengunjung</a>
              <?php endif; ?>

              <a href="<?php echo e($ref); ?>" class="btn btn-outline">Kembali ke Daftar</a>
            </div>
          </div>
        </section>

        <section class="panel">
          <div class="panel-header">Timeline Booking</div>
          <div class="panel-body">
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-title">Booking dibuat</div>
                <div class="timeline-time"><?php echo e(fmtDateTime($bk['created_at'])); ?></div>
              </div>

              <?php if ($bk['status'] === 'checkin'): ?>
                <div class="timeline-item">
                  <div class="timeline-title">Status berubah menjadi Check-in</div>
                  <div class="timeline-time"><?php echo e(fmtDateTime($bk['checkin_at'])); ?></div>
                </div>
              <?php elseif ($bk['status'] === 'tidak_hadir'): ?>
                <div class="timeline-item">
                  <div class="timeline-title">Status berubah menjadi Tidak Datang</div>
                  <div class="timeline-time">
                    <?php echo $bk['checkin_at'] ? e(fmtDateTime($bk['checkin_at'])) : 'Sudah diproses dari menu operasional booking.'; ?>
                  </div>
                </div>
              <?php else: ?>
                <div class="timeline-item">
                  <div class="timeline-title">Menunggu kedatangan</div>
                  <div class="timeline-time">Booking masih berstatus pending.</div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </section>

      </div>

    </div>
  </div>

  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<form id="formAksi" action="proses/proses_checkin.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdBooking">
  <input type="hidden" name="aksi" id="inputAksi">
</form>
<form id="formHapus" action="proses/proses_hapus_booking.php" method="POST" style="display:none;">
  <input type="hidden" name="id_booking" id="inputIdHapus">
  <input type="hidden" name="ref" value="<?php echo e($ref); ?>">
</form>

<script>
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