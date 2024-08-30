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

$file_surat = $_FILES['file_surat']['name'];
$tipe_file = $_FILES['file_surat']['type'];
$ukuran_file = $_FILES['file_surat']['size'];
$tmp_file = $_FILES['file_surat']['tmp_name'];

$tanggal_surat = date('Y-m-d', strtotime($tanggal));

// Ambil data surat keluar berdasarkan ID
$sql = "SELECT * FROM tb_arsip_surat_keluar WHERE nomor_surat='$id'";
$query = mysqli_query($db, $sql);
$data = mysqli_fetch_array($query);

// Jika file tidak diupload, gunakan nama file lama
if (empty($file_surat)) {
    $ext = substr($data['file_surat'], strripos($data['file_surat'], '.'));
    $nama_b = $thnNow . '-' . substr($nomor_surat, 0, 4) . $ext;
    rename("../surat_keluar/" . $data['file_surat'], "../surat_keluar/" . $nama_b);

    $sql = "UPDATE arsip_surat_keluar SET 
                tanggal = '$tanggal_surat',
                penerima = '$penerima',
                perihal = '$perihal',
                kode = '$kode',
                keterangan = '$keterangan',
                file_surat = '$nama_b'
            WHERE nomor_surat = '$id'";

    $execute = mysqli_query($db, $sql);

    if ($execute) {
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Data surat keluar telah diubah",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../detail-suratkeluar.php?nomor_surat=' . $id . '";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan saat mengubah data",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../editsuratkeluar.php?nomor_surat=' . $id . '";
                });
              </script>';
    }
} else {
    // Validasi file
    if ($tipe_file == "application/pdf" && $ukuran_file <= 10340000) {
        // Hapus file lama
        unlink("../surat_keluar/" . $data['file_surat']);

        $ext_file = substr($file_surat, strripos($file_surat, '.'));
        $nama_baru = $thnNow . '-' . substr($nomor_surat, 0, 4) . $ext_file;
        $path = "../surat_keluar/" . $nama_baru;
        move_uploaded_file($tmp_file, $path);

        $sql = "UPDATE arsip_surat_keluar SET 
                    tanggal = '$tanggal_surat',
                    penerima = '$penerima',
                    perihal = '$perihal',
                    kode = '$kode',
                    keterangan = '$keterangan',
                    file_surat = '$nama_baru'
                WHERE nomor_surat = '$id'";

        $execute = mysqli_query($db, $sql);

        if ($execute) {
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Data surat keluar telah diubah",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../detail-suratkeluar.php?nomor_surat=' . $id . '";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Terjadi kesalahan saat mengubah data",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../editsuratkeluar.php?nomor_surat=' . $id . '";
                    });
                  </script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Peringatan",
                    text: "File yang Anda masukkan tidak sesuai ketentuan. Silahkan ulangi.",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../editsuratkeluar.php?nomor_surat=' . $id . '";
                });
              </script>';
    }
}
?>
