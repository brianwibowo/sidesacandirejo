<?php
session_start();
include '../../koneksi/koneksi.php';

$nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_surat']);
$tanggal = mysqli_real_escape_string($db, $_POST['tanggal_keluar']);
$penerima = mysqli_real_escape_string($db, $_POST['penerima']);
$perihal = mysqli_real_escape_string($db, $_POST['perihal']);
$kode = mysqli_real_escape_string($db, $_POST['kode']);
$keterangan = mysqli_real_escape_string($db, $_POST['keterangan']);

date_default_timezone_set('Asia/Jakarta'); 
$tanggal_entry = date("Y-m-d H:i:s");
$thnNow = date("Y");

$nama_file_lengkap = $_FILES['file_surat']['name'];
$nama_file = substr($nama_file_lengkap, 0, strripos($nama_file_lengkap, '.'));
$ext_file = substr($nama_file_lengkap, strripos($nama_file_lengkap, '.'));
$tipe_file = $_FILES['file_surat']['type'];
$ukuran_file = $_FILES['file_surat']['size'];
$tmp_file = $_FILES['file_surat']['tmp_name'];

$tanggal_surat = date('Y-m-d', strtotime($tanggal));
$ambilnomor = substr($nomor_surat, 0, 4);

if (!empty($tanggal) && !empty($kode) && !empty($nomor_surat) && !empty($penerima) && !empty($tanggal_surat) && !empty($perihal) && ($tipe_file == "application/pdf") && ($ukuran_file <= 10340000)) {

    $nama_baru = $thnNow . '-' . $ambilnomor . $ext_file;
    $path = "../surat_keluar/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        $sql = "INSERT INTO arsip_surat_keluar (nomor_surat, tanggal, penerima, perihal, kode, keterangan, file_surat)
                VALUES ('$nomor_surat', '$tanggal_surat', '$penerima', '$perihal', '$kode', '$keterangan', '$nama_baru')";
        $execute = mysqli_query($db, $sql);

        if ($execute) {
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Surat keluar telah dimasukkan",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasuratkeluar.php";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Terjadi kesalahan saat memasukkan data",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../inputsuratkeluar.php";
                    });
                  </script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal mengupload file",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../inputsuratkeluar.php";
                });
              </script>';
    }
} else {
    echo '<script>
            Swal.fire({
                icon: "warning",
                title: "Peringatan",
                text: "Silahkan isi semua kolom dengan benar dan upload file PDF dengan ukuran maksimal 10MB",
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = "../inputsuratkeluar.php";
            });
          </script>';
}
?>
