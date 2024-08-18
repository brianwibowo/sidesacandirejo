<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id_suratmasuk'])) {
    $id = $_GET['id_suratmasuk'];

    $sql2 = "SELECT * FROM tb_suratmasuk WHERE id_suratmasuk='".$id."'";                        
    $query2 = mysqli_query($db, $sql2);
    $data2 = mysqli_fetch_array($query2);
    $total = mysqli_num_rows($query2);

    if ($total == 0) {
        echo '<script>alert("Data tidak ditemukan!"); window.history.back();</script>';
    } else {
        $sql = "DELETE FROM tb_suratmasuk WHERE id_suratmasuk='".$id."'";                        
        $query = mysqli_query($db, $sql);

        if ($query) {
            unlink("../surat_masuk/".$data2['file_suratmasuk']);
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Data Surat Masuk telah Dihapus",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasuratmasuk.php";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal Menghapus Data Surat Masuk",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datasuratmasuk.php";
                    });
                  </script>';
        }
    }
}
?>
