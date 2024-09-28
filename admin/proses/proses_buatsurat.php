<?php
session_start();
require '../../dompdf/autoload.inc.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['tanggal']) && isset($_POST['kepada']) && isset($_POST['pembuka']) && isset($_POST['isi']) && isset($_POST['penutup']) && isset($_POST['penandatangan_surat'])) {
    
    // Direktori untuk menyimpan file gambar
    $target_dir = "../uploads/";
    
    // Periksa apakah file gambar berhasil diupload
    if (isset($_FILES['kop_surat']) && $_FILES['kop_surat']['error'] === UPLOAD_ERR_OK) {
        // Buat nama file unik menggunakan uniqid() dan ekstensinya
        $file_extension = pathinfo($_FILES["kop_surat"]["name"], PATHINFO_EXTENSION);
        $unique_filename = uniqid('kop_surat_', true) . '.' . $file_extension; // Menambahkan prefix dan memastikan ekstensi file
        $target_file = $target_dir . $unique_filename;

        // Validasi apakah file gambar sudah diupload
        if (move_uploaded_file($_FILES["kop_surat"]["tmp_name"], $target_file)) {
            // Baca file gambar dan konversi ke Base64
            $image_data = file_get_contents($target_file);
            $base64_image = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);
        } else {
            die("Error: Gagal mengupload gambar kop surat.");
        }
    } else {
        die("Error: Tidak ada file kop surat atau terjadi masalah pada upload.");
    }

    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']); // Escaping input
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $kepada = htmlspecialchars($_POST['kepada']);
    $pembuka = htmlspecialchars($_POST['pembuka']);
    $isi = htmlspecialchars($_POST['isi']);
    $penutup = htmlspecialchars($_POST['penutup']);
    $penandatangan_surat = htmlspecialchars($_POST['penandatangan_surat']);
    $lampiran = isset($_POST['lampiran']) ? htmlspecialchars($_POST['lampiran']) : '-';  // Optional field

    // Konfigurasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Enable to load images from local server
    $dompdf = new Dompdf($options);

    // Buat konten HTML untuk PDF dengan gambar base64
    $html = "
    <!DOCTYPE html>
<html lang='id'>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background-color: #ffffff; /* White background for print */
            color: #000; /* Black text for print */
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            border: none; /* Remove border for print */
            padding: 0; /* Remove padding for print */
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat img {
            width: 100vw; /* 100% dari lebar viewport */
            height: auto; /* Menjaga rasio aspek */
            max-width: none; /* Hapus batasan lebar maksimum */
            margin: 0; /* Hapus margin untuk memenuhi layar */
            position: relative; /* Menjaga posisi relatif */
            left: 50%; /* Mengatur posisi gambar */
            transform: translateX(-50%); /* Menggeser gambar ke kiri untuk memenuhi layar */
        }
        .content {
            margin: 20px 0;
        }
        h5 {
            margin: 5px 0;
            font-weight: bold;
        }
             .kop-surat img {
                width: 100%;
                height: auto;
                max-width: 800px;
            }
        p {
            margin: 10px 0;
        }
        .signature {
            margin-top: 80px;
            text-align: right; /* Align signature to the right */
        }
        .isi {
        margin-left:30px;
        }

    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image' alt='Kop Surat'><br>
            <h5>Nomor Surat: $nomor_surat</h5>
        </div>
        <div class='content'>
            <p><strong>Tanggal:</strong> $tanggal</p>
            <p><strong>Kepada:</strong> $kepada</p>
            <p><strong>Lampiran:</strong> $lampiran</p>
            <hr>
            <p class='pembuka'>".nl2br($pembuka)."</p>
            <p class='isi'>".nl2br($isi)."</p>
            <p class='penutup'>".nl2br($penutup)."</p>
        </div>
        <div class='signature'>
            <p>Hormat kami,</p>
            <p><strong>$penandatangan_surat</strong></p>
        </div>
    </div>
</body>
</html>

    ";

    // Load konten HTML ke DomPDF
    $dompdf->loadHtml($html);

    // Set ukuran dan orientasi kertas (opsional)
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF
    $dompdf->render();
    // Header tambahan untuk memastikan file dikenali sebagai PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Surat_' . $nomor_surat . '.pdf"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');

    // Kirim file PDF ke browser (download otomatis)
    $dompdf->stream("Surat_$nomor_surat.pdf", ["Attachment" => false]);
}
else {
    echo "Data tidak lengkap!";
}
?>