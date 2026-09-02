<?php
// Kalau belum login sama sekali, tendang ke landing page
if (!isset($_SESSION['r3su'])) {
    header('Location: ../index.php');
    exit();
}

// Kalau sudah login, arahkan ke halaman yang sesuai rolenya
if ($_SESSION['r3su'] == 'bgn') {
    header('location:bagian/');
    exit();
} else if ($_SESSION['r3su'] == 'dmn') {
    // sudah di halaman yang benar, lanjut
}
?>