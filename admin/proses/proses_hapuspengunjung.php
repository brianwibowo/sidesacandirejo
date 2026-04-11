<?php 
include '../../koneksi/koneksi.php';

$id = $_GET['id'];

$sql = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";

if (mysqli_query($db, $sql)) {
  echo '<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hapus Data</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<style>
  body { font-family: "Poppins", sans-serif; background: #f4f6f9; }
  .swal-custom-popup {
    border-radius: 20px !important;
    padding: 30px 20px !important;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25) !important;
    font-family: "Poppins", sans-serif !important;
    animation: swalPopIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
  }
  @keyframes swalPopIn {
    from { transform: scale(0.7); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
  }
  .swal-custom-title {
    font-family: "Poppins", sans-serif !important;
    font-weight: 700 !important;
    font-size: 20px !important;
    color: #1a1a2e !important;
  }
</style>
<script>
  Swal.fire({
    title: "Berhasil Dihapus!",
    html: `<div style="font-family:\'Poppins\',sans-serif;">
      <p style="color:#555;font-size:15px;margin-bottom:6px;">Data pengunjung berhasil dihapus.</p>
      <p style="color:#888;font-size:13px;">Mengalihkan ke halaman data pengunjung...</p>
    </div>`,
    icon: "success",
    iconColor: "#4ade80",
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    background: "#fff",
    color: "#1a1a2e",
    customClass: { popup: "swal-custom-popup", title: "swal-custom-title" },
    didOpen: () => {
      const bar = Swal.getTimerProgressBar();
      if (bar) { bar.style.background = "linear-gradient(90deg,#4ade80,#22d3ee)"; bar.style.height = "5px"; }
    },
    willClose: () => { window.location.href = "../datapengunjung.php"; }
  });
</script>
</body></html>';
} else {
  echo '<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hapus Data</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<style>
  body { font-family: "Poppins", sans-serif; background: #f4f6f9; }
  .swal-custom-popup { border-radius: 20px !important; padding: 30px 20px !important; font-family: "Poppins", sans-serif !important; }
</style>
<script>
  Swal.fire({
    title: "Gagal Menghapus!",
    html: `<p style="color:#555;font-size:15px;">Terjadi kesalahan saat menghapus data.</p>`,
    icon: "error",
    iconColor: "#e74c3c",
    confirmButtonText: "Kembali",
    background: "#fff",
    color: "#1a1a2e",
    customClass: { popup: "swal-custom-popup" },
  }).then(() => { window.location.href = "../datapengunjung.php"; });
</script>
</body></html>';
}

mysqli_close($db);