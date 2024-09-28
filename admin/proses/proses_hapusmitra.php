<?php 
include '../../koneksi/koneksi.php';

$id = $_GET['id'];

$sql_select = "SELECT bukti_legalitas FROM tb_data_mitra WHERE id = '$id'";
$result = mysqli_query($db, $sql_select);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $file_to_delete = $row['bukti_legalitas'];
    $sql_delete = "DELETE FROM tb_data_mitra WHERE id = '$id'";

    if (mysqli_query($db, $sql_delete)) {
        if (!empty($file_to_delete) && file_exists($file_to_delete)) {
            unlink($file_to_delete); 
        }
        echo "<script>
            alert('Hapus Data Berhasil');
            window.location.href = '../datamitra.php';
            </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($db);
    }
} else {
    echo "Error retrieving file: " . mysqli_error($db);
}

mysqli_close($db);