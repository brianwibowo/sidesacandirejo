<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_POST['submit'])) {
    // Collect the data from form input
    $nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_surat']);
    $tanggal_terima = mysqli_real_escape_string($db, $_POST['tanggal_terima']);
    $tanggal_surat = mysqli_real_escape_string($db, $_POST['tanggal_surat']);
    $pengirim = mysqli_real_escape_string($db, $_POST['pengirim']);
    $perihal = mysqli_real_escape_string($db, $_POST['perihal']);
    $kode = mysqli_real_escape_string($db, $_POST['kode']);
    $keterangan = mysqli_real_escape_string($db, $_POST['keterangan']);
    
    date_default_timezone_set('Asia/Jakarta'); 
    $tanggal_entry = date("Y-m-d H:i:s");
    $thnNow = date("Y");

    // Upload file
    $tipe_file = $_FILES['file_surat']['type'];
    $ukuran_file = $_FILES['file_surat']['size'];
    $ext_file = substr($_FILES['file_surat']['name'], strripos($_FILES['file_surat']['name'], '.'));    
    $tmp_file = $_FILES['file_surat']['tmp_name'];
    $nama_baru = $thnNow . '-' . $nomor_surat . $ext_file;
    $path = "/Applications/XAMPP/xamppfiles/htdocs/sidesacandirejo/surat_masuk" . $nama_baru;

    if ($tipe_file == "application/pdf" && $ukuran_file <= 10340000) {
        if (move_uploaded_file($tmp_file, $path)) {
            $sql = "INSERT INTO arsip_surat_masuk (nomor_surat, tanggal_terima, tanggal_surat, pengirim, perihal, kode, keterangan, file_surat, tanggal_entry)
                    VALUES ('$nomor_surat', '$tanggal_terima', '$tanggal_surat', '$pengirim', '$perihal', '$kode', '$keterangan', '$nama_baru', '$tanggal_entry')";
            $execute = mysqli_query($db, $sql);

            if ($execute) {
                echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Sukses",
                            text: "Surat masuk telah dimasukkan",
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
                            text: "Terjadi kesalahan saat memasukkan data",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.href = "../inputsuratmasuk.php";
                        });
                      </script>';
            }
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal mengupload file",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../inputsuratmasuk.php";
                    });
                  </script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Peringatan",
                    text: "Silahkan isi semua kolom dengan benar dan upload file PDF dengan ukuran maksimal 10MB",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../inputsuratmasuk.php";
                });
              </script>';
    }
}
?>
