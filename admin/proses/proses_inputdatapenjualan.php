<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_produk = $_POST['produk'];
    $pilihan_paket_wisata = $jenis_produk == 'Paket Wisata' ? $_POST['pilihan_paket_wisata'] : null;
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $total = $jumlah * $harga;
    $query = "INSERT INTO tb_data_penjualan_usaha (produk, paket_wisata, jumlah, harga) 
              VALUES ('$jenis_produk', '$pilihan_paket_wisata', '$jumlah', '$harga')";

    if (mysqli_query($db, $query)) {
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
            text: 'Data penjualan berhasil ditambahkan'
        }).then(() => {
            window.location = '../datapenjualanusaha.php';
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
                text: " . json_encode("Terjadi kesalahan, gagal input data: " . mysqli_error($db)) . "
            }).then(() => {
                window.location = '../datapenjualanusaha.php';
            });
        </>
        </body>
        </html>
        ";
    }
} else {
    $_SESSION['error'] = "Permintaan tidak valid.";
    header("Location: ../datapenjualanusaha.php");
}

mysqli_close($db);
