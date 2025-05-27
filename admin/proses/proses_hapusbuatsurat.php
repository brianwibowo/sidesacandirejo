<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['nomor_surat'])) {
    $nomor_surat = $_GET['nomor_surat'];

    $sql = "SELECT * FROM buat_surat WHERE nomor_surat='$nomor_surat'";
    $query = mysqli_query($db, $sql);
    $data = mysqli_fetch_array($query);

    if (mysqli_num_rows($query) == 0) {
        echo '<script>alert("Data tidak ditemukan!"); window.history.back();</script>';
    } else {
        $sql = "DELETE FROM buat_surat WHERE nomor_surat='$nomor_surat'";
        if (mysqli_query($db, $sql)) {
            unlink("../kop_surat/" . $data['kop_surat']);
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Surat berhasil dihapus",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasurat.php";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal menghapus surat",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasurat.php";
                    });
                  </script>';
        }
    }
}
?>
