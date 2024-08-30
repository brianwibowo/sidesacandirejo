<?php
session_start();
include '../../koneksi/koneksi.php';

// Ambil data dari form
$kode_data = mysqli_real_escape_string($db, $_POST['kode_data']);
$pilihan_paket_wisata = mysqli_real_escape_string($db, $_POST['pilihan_paket_wisata']);
$jenis_wisatawan = mysqli_real_escape_string($db, $_POST['jenis_wisatawan']);
$kota = mysqli_real_escape_string($db, $_POST['kota']);
$negara = mysqli_real_escape_string($db, $_POST['negara']);
$nama = mysqli_real_escape_string($db, $_POST['nama']);
$jenis_kelamin = mysqli_real_escape_string($db, $_POST['jenis_kelamin']);
$usia = mysqli_real_escape_string($db, $_POST['usia']);
$agen_wisata = mysqli_real_escape_string($db, $_POST['agen_wisata']);

// Cek apakah ada file foto yang diupload
if ($_FILES['foto_wisatawan']['name'] == '') {
    // Jika tidak ada file yang diupload, perbarui data tanpa mengubah foto
    $sql = "UPDATE tb_data_pengunjung SET 
                pilihan_paket_wisata = '$pilihan_paket_wisata',
                jenis_wisatawan = '$jenis_wisatawan',
                kota = '$kota',
                negara = '$negara',
                nama = '$nama',
                jenis_kelamin = '$jenis_kelamin',
                usia = '$usia',
                agen_wisata = '$agen_wisata'
            WHERE kode_data = '$kode_data'";
    
    $execute = mysqli_query($db, $sql);            

    if ($execute) {
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Data pengunjung telah diubah",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../detail-datapengunjung.php?kode_data='.$kode_data.'";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Terjadi kesalahan saat mengubah data",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../editdatapengunjung.php?kode_data='.$kode_data.'";
                });
              </script>';
    }
} else {
    // Jika ada file foto yang diupload, proses file tersebut
    $tipe_file = $_FILES['foto_wisatawan']['type'];
    $ukuran_file = $_FILES['foto_wisatawan']['size'];
    $ext_file = substr($_FILES['foto_wisatawan']['name'], strripos($_FILES['foto_wisatawan']['name'], '.'));            
    $tmp_file = $_FILES['foto_wisatawan']['tmp_name'];
    
    $nama_baru = $kode_data . $ext_file;
    $path = "../foto_wisatawan/" . $nama_baru;

    if (($tipe_file == "image/jpeg" || $tipe_file == "image/png") && $ukuran_file <= 10485760) {
        // Hapus file lama jika ada
        $sql_old = "SELECT foto_wisatawan FROM tb_data_pengunjung WHERE kode_data = '$kode_data'";
        $query_old = mysqli_query($db, $sql_old);
        $data_old = mysqli_fetch_array($query_old);

        if (!empty($data_old['foto_wisatawan']) && file_exists("../foto_wisatawan/" . $data_old['foto_wisatawan'])) {
            unlink("../foto_wisatawan/" . $data_old['foto_wisatawan']);
        }

        // Upload file baru
        move_uploaded_file($tmp_file, $path);
        
        $sql = "UPDATE tb_data_pengunjung SET 
                    pilihan_paket_wisata = '$pilihan_paket_wisata',
                    jenis_wisatawan = '$jenis_wisatawan',
                    kota = '$kota',
                    negara = '$negara',
                    nama = '$nama',
                    jenis_kelamin = '$jenis_kelamin',
                    usia = '$usia',
                    agen_wisata = '$agen_wisata',
                    foto_wisatawan = '$nama_baru'
                WHERE kode_data = '$kode_data'";
        
        $execute = mysqli_query($db, $sql);            

        if ($execute) {
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Data pengunjung telah diubah",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../detail-datapengunjung.php?kode_data='.$kode_data.'";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Terjadi kesalahan saat mengubah data",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../editdatapengunjung.php?kode_data='.$kode_data.'";
                    });
                  </script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Peringatan",
                    text: "File yang Anda masukkan tidak sesuai ketentuan. Silahkan ulangi",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../editdatapengunjung.php?kode_data='.$kode_data.'";
                });
              </script>';
    }
}
?>
