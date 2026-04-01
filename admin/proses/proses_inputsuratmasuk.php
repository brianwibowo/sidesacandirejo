<?php
// Menghubungkan ke database

include '../../koneksi/koneksi.php';


// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $tanggal_terima = $_POST['tanggal_terima'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $nomor_surat = $_POST['nomor_surat'];
    $pengirim = $_POST['pengirim'];
    $penerima_surat = $_POST['penerima_surat'];
    $disposisi = $_POST['disposisi'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];

    $tanggal = date('Y-m-d', strtotime($tanggal_surat));
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $pengirim)) . "_{$tanggal}_{$jam}.pdf";

    $target_dir = "../uploads/";
    $destination = $target_dir . $nama_file;

    // Handle lampiran foto
    $lampiran_foto = null;
    if (isset($_FILES['lampiran_foto']) && $_FILES['lampiran_foto']['error'] == UPLOAD_ERR_OK) {
        $foto_name = strtolower(str_replace(' ', '_', $pengirim)) . "_foto_{$tanggal}_{$jam}." . pathinfo($_FILES['lampiran_foto']['name'], PATHINFO_EXTENSION);
        $foto_destination = $target_dir . $foto_name;
        if (move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $foto_destination)) {
            $lampiran_foto = $foto_destination;
        }
    }

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        // Get the last No value
        $query_last_no = "SELECT MAX(No) as last_no FROM tb_arsip_surat_masuk";
        $result = mysqli_query($db, $query_last_no);
        $row = mysqli_fetch_assoc($result);
        $next_no = ($row['last_no'] ?? 0) + 1;

        $query = "INSERT INTO tb_arsip_surat_masuk (No, tanggal_terima, tanggal_surat, nomor_surat, pengirim, penerima_surat, disposisi, perihal, kode, keterangan, file_surat, lampiran_foto) 
                  VALUES ('$next_no', '$tanggal_terima', '$tanggal_surat', '$nomor_surat', '$pengirim', '$penerima_surat', '$disposisi', '$perihal', '$kode', '$keterangan', '$destination', '$lampiran_foto')";

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
                text: 'Data berhasil ditambahkan'
            }).then(() => {
                window.location = '../datasuratmasuk.php';
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
                    window.location = '../datasuratmasuk.php';
                });
            </>
            </body>
            </html>
            ";
        }
    } else {
        $_SESSION['error'] = "File gagal diupload!";
        header("Location: ../datasuratmasuk.php");
    }
}


mysqli_close($db);
