<?php
session_start();
include '../../koneksi/koneksi.php';

// Helper SweetAlert2 response (sama dengan proses_inputdatapengunjung.php)
function swalResponse($title, $message, $icon, $redirect) {
    $color = $icon == 'success' ? '#4ade80' : ($icon == 'error' ? '#e74c3c' : '#f39c12');
    $timerScript = $icon == 'success' ? '
      didOpen: () => {
        const bar = Swal.getTimerProgressBar();
        if (bar) { bar.style.background = "linear-gradient(90deg,#4ade80,#22d3ee)"; bar.style.height = "5px"; }
      },' : '';
    $confirmBtn  = $icon == 'success' ? 'showConfirmButton: false, timer: 2500, timerProgressBar: true,' : 'confirmButtonText: "Kembali",';
    $willClose   = $icon == 'success'  ? 'willClose: () => { window.location.href = "' . $redirect . '"; }' : '';
    $then        = $icon != 'success'  ? '.then(() => { window.location.href = "' . $redirect . '"; })' : '';

    return '<!DOCTYPE html>
<html lang="id"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Proses</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head><body>
<style>
  body{font-family:\"Plus Jakarta Sans\",sans-serif;background:#f4f6f9;}
  .swal-custom-popup{border-radius:20px!important;padding:30px 20px!important;box-shadow:0 25px 60px rgba(0,0,0,0.25)!important;}
  .swal-custom-title{font-family:\"Outfit\",\"Plus Jakarta Sans\",sans-serif!important;font-weight:700!important;font-size:20px!important;color:#1a1a2e!important;}
</style>
<script>
  Swal.fire({
    title: "' . $title . '",
    html: `<p style="color:#555;font-size:15px;">' . $message . '</p>`,
    icon: "' . $icon . '",
    iconColor: "' . $color . '",
    ' . $confirmBtn . '
    background: "#fff", color: "#1a1a2e",
    customClass: { popup: "swal-custom-popup", title: "swal-custom-title" },
    ' . $timerScript . '
    ' . $willClose . '
  })' . $then . ';
</script>
</body></html>';
}

$allowed_ref = [
    'booking_dashboard.php',
    'booking_semua.php',
    'booking_pending.php',
    'booking_checkin.php',
    'booking_tidakdatang.php',
    'booking_kalender.php',
    'booking_laporan.php',
    'detail_booking.php'
];

$ref = isset($_POST['ref']) ? basename($_POST['ref']) : 'booking_dashboard.php';
if (!in_array($ref, $allowed_ref, true)) {
    $ref = 'booking_dashboard.php';
}
$redirect = '../' . $ref;

// ── Validasi request ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo swalResponse('Permintaan Tidak Valid!', 'Metode tidak diizinkan.', 'error', $redirect);
    exit;
}

$aksi      = isset($_POST['aksi'])     ? $_POST['aksi']     : '';
$id_booking = isset($_POST['id_booking']) ? (int)$_POST['id_booking'] : 0;

if (!in_array($aksi, ['checkin', 'tidak_hadir']) || $id_booking <= 0) {
    echo swalResponse('Permintaan Tidak Valid!', 'Parameter tidak lengkap.', 'error', $redirect);
    exit;
}

// ── Ambil data booking ────────────────────────────────────────────────────────
$stmt = $db->prepare("SELECT * FROM tb_booking WHERE id = ? AND status = 'pending' LIMIT 1");
$stmt->bind_param("i", $id_booking);
$stmt->execute();
$result  = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo swalResponse('Data Tidak Ditemukan!', 'Booking tidak ditemukan atau sudah diproses sebelumnya.', 'warning', $redirect);
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
//  AKSI: TIDAK HADIR
// ════════════════════════════════════════════════════════════════════════════
if ($aksi === 'tidak_hadir') {
    $stmt = $db->prepare("UPDATE tb_booking SET status = 'tidak_hadir', checkin_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id_booking);

    if ($stmt->execute()) {
        $stmt->close();
        echo swalResponse(
            'Ditandai Tidak Hadir',
            'Booking atas nama <strong>' . htmlspecialchars($booking['nama']) . '</strong> ditandai tidak hadir.',
            'success',
            $redirect
        );
    } else {
        $err = htmlspecialchars($stmt->error);
        $stmt->close();
        echo swalResponse('Terjadi Kesalahan!', $err, 'error', $redirect);
    }
    $db->close();
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
//  AKSI: CHECK-IN → salin ke tb_data_pengunjung (foto kosong, admin isi nanti)
// ════════════════════════════════════════════════════════════════════════════
$db->begin_transaction();

try {
    // 1. Insert ke tb_data_pengunjung
    $ins = $db->prepare("
        INSERT INTO tb_data_pengunjung
          (tanggal_kunjungan, pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket,
           opsi_cooking_lesson, opsi_gamelan, jenis_wisatawan, kota, negara, nama, pax,
           agen_wisata, driver_agent_guide, local_guide, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)
    ");

    $ins->bind_param(
        "ssssssssssisss",
        $booking['tanggal_kunjungan'],
        $booking['pilihan_paket_wisata'],
        $booking['opsi_makan_tour'],
        $booking['jenis_makanan_paket'],
        $booking['opsi_cooking_lesson'],
        $booking['opsi_gamelan'],
        $booking['jenis_wisatawan'],
        $booking['kota'],
        $booking['negara'],
        $booking['nama'],
        $booking['pax'],
        $booking['agen_wisata'],
        $booking['driver_agent_guide'],
        $booking['local_guide']
    );

    $ins->execute();
    $id_pengunjung = $db->insert_id;
    $ins->close();

    // 2. Update status booking → checkin, simpan referensi id_pengunjung & waktu
    $upd = $db->prepare("
        UPDATE tb_booking
        SET status = 'checkin', id_pengunjung = ?, checkin_at = NOW()
        WHERE id = ?
    ");
    $upd->bind_param("ii", $id_pengunjung, $id_booking);
    $upd->execute();
    $upd->close();

    $db->commit();

    echo swalResponse(
        'Check-in Berhasil!',
        'Data <strong>' . htmlspecialchars($booking['nama']) . '</strong> berhasil dicatat. '
        . 'Admin dapat menambahkan foto di halaman Data Pengunjung.',
        'success',
        $redirect
    );

} catch (Exception $e) {
    $db->rollback();
    echo swalResponse('Terjadi Kesalahan!', htmlspecialchars($e->getMessage()), 'error', $redirect);
}

$db->close();
?>