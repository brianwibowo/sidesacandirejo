<?php
session_start();
include '../../koneksi/koneksi.php';

function swalResponse($title, $message, $icon, $redirect)
{
    $color = $icon == 'success' ? '#4ade80' : ($icon == 'error' ? '#e74c3c' : '#f39c12');
    $timerScript = $icon == 'success' ? '
      didOpen: () => {
        const bar = Swal.getTimerProgressBar();
        if (bar) { bar.style.background = "linear-gradient(90deg,#4ade80,#22d3ee)"; bar.style.height = "5px"; }
      },' : '';
    $confirmBtn = $icon == 'success' ? 'showConfirmButton: false, timer: 2200, timerProgressBar: true,' : 'confirmButtonText: "Kembali",';
    $willClose = $icon == 'success' ? 'willClose: () => { window.location.href = "' . $redirect . '"; }' : '';
    $then = $icon != 'success' ? '.then(() => { window.location.href = "' . $redirect . '"; })' : '';

    return '<!DOCTYPE html>
<html lang="id"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Proses</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head><body>
<style>
  body{font-family:"Poppins",sans-serif;background:#f4f6f9;}
  .swal-custom-popup{border-radius:20px!important;padding:30px 20px!important;box-shadow:0 25px 60px rgba(0,0,0,0.25)!important;}
  .swal-custom-title{font-family:"Poppins",sans-serif!important;font-weight:700!important;font-size:20px!important;color:#1a1a2e!important;}
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

$ref = isset($_POST['ref']) ? basename($_POST['ref']) : 'booking_semua.php';
if (!in_array($ref, $allowed_ref, true)) {
    $ref = 'booking_semua.php';
}
$redirect = '../' . $ref;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo swalResponse('Permintaan Tidak Valid!', 'Metode tidak diizinkan.', 'error', $redirect);
    exit;
}

$id_booking = isset($_POST['id_booking']) ? (int)$_POST['id_booking'] : 0;
if ($id_booking <= 0) {
    echo swalResponse('Permintaan Tidak Valid!', 'ID booking tidak valid.', 'error', $redirect);
    exit;
}

$stmt = $db->prepare('SELECT id, nama, status FROM tb_booking WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id_booking);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    echo swalResponse('Data Tidak Ditemukan!', 'Booking tidak ditemukan.', 'warning', $redirect);
    exit;
}

if (!in_array($booking['status'], ['pending', 'tidak_hadir'], true)) {
    echo swalResponse(
        'Hapus Ditolak!',
        'Hanya booking dengan status Pending atau Tidak Hadir yang boleh dihapus.',
        'warning',
        $redirect
    );
    exit;
}

$del = $db->prepare('DELETE FROM tb_booking WHERE id = ? LIMIT 1');
$del->bind_param('i', $id_booking);
if ($del->execute()) {
    $del->close();
    echo swalResponse(
        'Berhasil Dihapus!',
        'Booking atas nama <strong>' . htmlspecialchars($booking['nama']) . '</strong> berhasil dihapus.',
        'success',
        $redirect
    );
} else {
    $err = htmlspecialchars($del->error);
    $del->close();
    echo swalResponse('Terjadi Kesalahan!', $err, 'error', $redirect);
}

$db->close();
