<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_POST['submit'])) {
    // Mengumpulkan data dari input form
    $pilihan_paket_wisata = mysqli_real_escape_string($db, $_POST['pilihan_paket_wisata']);
    $jenis_wisatawan = mysqli_real_escape_string($db, $_POST['jenis_wisatawan']);
    $kota = mysqli_real_escape_string($db, $_POST['kota']);
    $negara = mysqli_real_escape_string($db, $_POST['negara']);
    $nama = mysqli_real_escape_string($db, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($db, $_POST['jenis_kelamin']);
    $usia = mysqli_real_escape_string($db, $_POST['usia']);
    $agen_wisata = mysqli_real_escape_string($db, $_POST['agen_wisata']);

    // Menyusun query untuk memasukkan data ke database
    $sql = "INSERT INTO tb_data_pengunjung (kode_data, pilihan_paket_wisata, jenis_wisatawan, kota, negara, nama, jenis_kelamin, usia, agen_wisata)
            VALUES ('$kode_data_baru', '$pilihan_paket_wisata', '$jenis_wisatawan', '$kota', '$negara', '$nama', '$jenis_kelamin', '$usia', '$agen_wisata')";
    
    $execute = mysqli_query($db, $sql);

    // Menampilkan pesan berdasarkan hasil eksekusi query
    if ($execute) {
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Data pengunjung telah dimasukkan",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../datapengunjung.php";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan saat memasukkan data",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../inputdatapengunjung.php";
                });
              </script>';
    }
}
?>
