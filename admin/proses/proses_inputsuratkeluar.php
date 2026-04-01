<?php
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tgl_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = $_POST['nomor_surat'];
    $penerima = $_POST['penerima'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];

    $tanggal = date('Y-m-d', strtotime($tgl_keluar));
    $jam = date('H-i-s');
    $nama_file = strtolower(str_replace(' ', '_', $penerima)) . "_{$tanggal}_{$jam}.pdf";

    $target_dir = '../uploads/';
    $destination = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $destination)) {
        // Get the last No value
        $query_last_no = "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar";
        $result = mysqli_query($db, $query_last_no);
        $row = mysqli_fetch_assoc($result);
        $next_no = ($row['last_no'] ?? 0) + 1;

        $query = "INSERT INTO tb_arsip_surat_keluar (No, tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
                  VALUES ('$next_no', '$tgl_keluar', '$nomor_surat', '$penerima', '$perihal', '$kode', '$keterangan', '$destination')";

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
                window.location = '../datasuratkeluar.php';
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
                    window.location = '../datasuratkeluar.php';
                });
            </>
            </body>
            </html>
            ";
        }
    } else {
        $_SESSION['error'] = "File gagal diupload!";
        header("Location: ../datasuratkeluar.php");
    }
}
