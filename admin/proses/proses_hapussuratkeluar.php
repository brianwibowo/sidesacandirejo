<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['id'])) {
    $No = mysqli_real_escape_string($db, $_GET['id']);

    // Get file name before deleting
    $query = "SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = '$No'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);

    // Delete file if it exists
    $upload_dir = '../uploads/surat_keluar/';
    if (!empty($data['file_surat']) && file_exists($upload_dir . $data['file_surat'])) {
        @unlink($upload_dir . $data['file_surat']);
    }

    // Delete from database
    $query = "DELETE FROM tb_arsip_surat_keluar WHERE No = '$No'";

    if (mysqli_query($db, $query)) {
        // Reorder IDs
        $query = "SET @count = 0";
        mysqli_query($db, $query);

        $query = "UPDATE tb_arsip_surat_keluar SET No = @count:= @count + 1";
        mysqli_query($db, $query);

        $query = "ALTER TABLE tb_arsip_surat_keluar AUTO_INCREMENT = 1";
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
            window.location.href = '../datasuratkeluar.php';
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
                window.location = '../datasuratkeluar.php';
            });
        </>
        </body>
        </html>
        ";
    }
} else {
    $_SESSION['error'] = "Nomor tidak valid!";
    header("Location: ../datasuratkeluar.php");
}

mysqli_close($db);
