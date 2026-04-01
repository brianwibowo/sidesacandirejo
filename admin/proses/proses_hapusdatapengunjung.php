<?php
include '../../koneksi/koneksi.php';

$id = $_GET['id'];

$sql = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";

if (mysqli_query($db, $sql)) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil dihapus',
        showConfirmButton: false,
        timer: 3000
    }).then(() => {
        window.location.href = '../datapengunjung.php';
    });
    </script>
    </body>
    </html>
    ";
} else {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: " . json_encode("Error deleting record: " . mysqli_error($db)) . "
        }).then(() => {
            window.history.back();
        });
    </script>
    </body>
    </html>
    ";
    exit();
}

mysqli_close($db);
