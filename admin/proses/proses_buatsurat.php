<?php
session_start();
require '../../dompdf/autoload.inc.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;
include '../../koneksi/koneksi.php';
if (isset($_POST['nomor_surat']) && isset($_POST['tanggal']) && isset($_POST['kepada']) && isset($_POST['pembuka']) && isset($_POST['isi']) && isset($_POST['penutup']) && isset($_POST['penandatangan_surat'])) {
    
    $pdf_dir = '../uploads/'; // Ensure this directory exists and is writable
    $pdf_filename = "Surat_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";

    // Prepare to save PDF in the directory
    $pdf_path = $pdf_dir . $pdf_filename;
    // Direktori untuk menyimpan file gambar
    $target_dir = "../images/";
    
    $file_extension = pathinfo('../images/kopsurat.jpg', PATHINFO_EXTENSION);
    $target_file = "../images/kopsurat.jpg";
    $image_data = file_get_contents($target_file);
    $base64_image = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);
    // Periksa apakah file gambar berhasil diupload


    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']); // Escaping input
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $tanggal_input = htmlspecialchars($_POST['tanggal']); 
    $date = DateTime::createFromFormat('Y-m-d', $tanggal_input);
    
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $bulan_angka = (int)$date->format('n'); 
    $tanggal_format = $date->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('Y'); 

    $kepada = htmlspecialchars($_POST['kepada']);
    $lokasi_penerima = htmlspecialchars($_POST['lokasi']);
    $pembuka = htmlspecialchars($_POST['pembuka']);
    $isi = htmlspecialchars($_POST['isi']);
    $penutup = htmlspecialchars($_POST['penutup']);
    $perihal = htmlspecialchars($_POST['perihal']);
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
            font-family: Times New Romans
            line-height: 1.6;
            font-size : 16 px;
            margin: 1cm;
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
            text-align:justify;
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
            margin-top: 60px;
        }
        .isi {
        margin : 20px 0;
        }

    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image' alt='Kop Surat'><br>
        </div>
        <div class='content'>
            <p>$tanggal_format</p>
            <br>
            <p><span style='padding-right: 60px;'>No</span>: $nomor_surat <br>
            <span style='padding-right: 17px;'>Lampiran</span>: $lampiran <br>
            <span style='padding-right: 34px;'>Perihal</span>: <strong>$perihal</strong></p>
            <br>
            <p> 
            Kepada Yth.<br>
            <b>$kepada</b><br>
            <b>$lokasi_penerima</b>
            </p><br>
            <p class='pembuka'>".nl2br($pembuka)."</p>
            <p class='isi'>".nl2br($isi)."</p>
            <p class='penutup'>".nl2br($penutup)."</p>
        </div>
        <div class='signature'>
            <p>Hormat kami,</p>
            <p style='margin-top:90px'>".nl2br($penandatangan_surat)."</p>
        </div>
    </div>
</body>
</html>

    ";
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Save the file locally
    file_put_contents($pdf_path, $dompdf->output());

    // Insert record into the database
    $query = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
                  VALUES ('$tanggal', '$nomor_surat', '$kepada', '$perihal', '-','Dibuat dari fitur Buat surat' , '$pdf_path')";
    
    if (mysqli_query($db, $query)) {
        // Header for downloading PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdf_filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        // Output PDF to browser
        $dompdf->stream($pdf_filename, ["Attachment" => false]);
}else {
    echo "Error: " . mysqli_error($db);
}
}
else {
    echo "Data tidak lengkap!";
}