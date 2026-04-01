<?php
include '../../koneksi/koneksi.php';

$id = $_POST['id'];
$produk = $_POST['produk'];
$jumlah = $_POST['jumlah'];
$harga = $_POST['harga'];


$sql = "UPDATE tb_data_penjualan_usaha SET  produk = '$produk', jumlah = '$jumlah', harga = '$harga' WHERE id = '$id'";

if ($db->query($sql) === TRUE) {
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
            text: 'Data berhasil diedit'
        }).then(() => {
            window.location = '../datapenjualanusaha.php';
        });
        </script>
        </body>
        </html>
        ";
} else {
  $error = $db->error;

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
        text: " . json_encode("Error updating record: " . $error) . "
    }).then(() => {
        window.history.back();
    });
    </script>
    </body>
    </html>
    ";
}
