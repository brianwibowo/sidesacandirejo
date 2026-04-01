<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // Delete from database
    $query = "DELETE FROM tb_data_pengunjung WHERE id = '$id'";

    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);

        $query = "UPDATE tb_data_pengunjung SET id = @count:= @count + 1";
        mysqli_query($db, $query);

        $query = "ALTER TABLE tb_data_pengunjung AUTO_INCREMENT = 1";
        mysqli_query($db, $query);

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
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: " . json_encode("Gagal menghapus data! Error: " . mysqli_error($db)) . "
            }).then(() => {
                window.location = '../datapengunjung.php';
            });
        </script>
        </body>
        </html>
        ";
    }
} else {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: ../datapengunjung.php");
}

mysqli_close($db);
