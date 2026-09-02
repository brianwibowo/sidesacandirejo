<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_POST['submit'])) {
    $nomor_surat = mysqli_real_escape_string($db, $_POST['nomor_surat']);
    $lampiran = mysqli_real_escape_string($db, $_POST['lampiran']);
    $perihal = mysqli_real_escape_string($db, $_POST['perihal']);
    $tanggal = mysqli_real_escape_string($db, $_POST['tanggal']);
    $kepada = mysqli_real_escape_string($db, $_POST['kepada']);
    $pembuka = mysqli_real_escape_string($db, $_POST['pembuka']);
    $isi = mysqli_real_escape_string($db, $_POST['isi']);
    $penutup = mysqli_real_escape_string($db, $_POST['penutup']);
    $penandatangan_surat = mysqli_real_escape_string($db, $_POST['penandatangan_surat']);

    $kop_surat = $_FILES['kop_surat']['name'];
    $tipe_file = $_FILES['kop_surat']['type'];
    $ukuran_file = $_FILES['kop_surat']['size'];
    $tmp_file = $_FILES['kop_surat']['tmp_name'];

    $sql = "SELECT * FROM buat_surat WHERE nomor_surat='$nomor_surat'";
    $query = mysqli_query($db, $sql);
    $data = mysqli_fetch_array($query);

    if ($kop_surat == '') {
        $sql = "UPDATE buat_surat SET 
                    lampiran = '$lampiran', 
                    perihal = '$perihal', 
                    tanggal = '$tanggal', 
                    kepada = '$kepada', 
                    pembuka = '$pembuka', 
                    isi = '$isi', 
                    penutup = '$penutup', 
                    penandatangan_surat = '$penandatangan_surat' 
                WHERE nomor_surat = '$nomor_surat'";
    } else {
        if (($tipe_file == "application/pdf") && ($ukuran_file <= 10340000)) {
            unlink("../kop_surat/" . $data['kop_surat']);
            $path = "../kop_surat/" . $kop_surat;
            move_uploaded_file($tmp_file, $path);

            $sql = "UPDATE buat_surat SET 
                        kop_surat = '$kop_surat', 
                        lampiran = '$lampiran', 
                        perihal = '$perihal', 
                        tanggal = '$tanggal', 
                        kepada = '$kepada', 
                        pembuka = '$pembuka', 
                        isi = '$isi', 
                        penutup = '$penutup', 
                        penandatangan_surat = '$penandatangan_surat' 
                    WHERE nomor_surat = '$nomor_surat'";
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "File tidak sesuai ketentuan",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../editsurat.php?nomor_surat=' . $nomor_surat . '";
                    });
                  </script>';
            exit;
        }
    }

    if (mysqli_query($db, $sql)) {
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: "Surat berhasil diubah",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../datasurat.php";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Gagal mengubah surat",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = "../editsurat.php?nomor_surat=' . $nomor_surat . '";
                });
              </script>';
    }
}
?>
