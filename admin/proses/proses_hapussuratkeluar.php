<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['nomor_surat'])) {
    $nomor_surat = mysqli_real_escape_string($db, $_GET['nomor_surat']);

    // Ambil data berdasarkan nomor surat
    $sql2 = "SELECT * FROM arsip_surat_keluar WHERE nomor_surat='$nomor_surat'";
    $query2 = mysqli_query($db, $sql2);
    $data2 = mysqli_fetch_array($query2);
    $total = mysqli_num_rows($query2);

    // Cek apakah data ada
    if ($total == 0) {
        echo '<script>alert("Data tidak ditemukan!"); window.history.back();</script>';
    } else {
        // Hapus data dari database
        $sql = "DELETE FROM arsip_surat_keluar WHERE nomor_surat='$nomor_surat'";
        $query = mysqli_query($db, $sql);

        if ($query) {
            // Hapus file terkait jika ada
            if (file_exists("../surat_keluar/" . $data2['file_surat'])) {
                unlink("../surat_keluar/" . $data2['file_surat']);
            }

            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Data Surat Keluar telah Dihapus",
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
                        text: "Gagal Menghapus Data Surat Keluar",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasuratkeluar.php";
                    });
                  </script>';
        }
    }
}
?>
