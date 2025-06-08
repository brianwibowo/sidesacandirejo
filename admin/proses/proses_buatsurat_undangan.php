<?php
session_start();

require '../../dompdf/autoload.inc.php';
include '../../koneksi/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['tanggal']) && isset($_POST['kepada'])) {

    $pdf_dir = '../uploads/'; // Ensure this directory exists and is writable
    $pdf_filename = "Surat_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";

    // Prepare to save PDF in the directory
    $pdf_path = $pdf_dir . $pdf_filename;
    // Direktori untuk menyimpan file gambar
    $target_dir = "../images/";

    $file_extension = pathinfo('../images/kopsurat.jpg', PATHINFO_EXTENSION);
    $target_file = "../images/kopsurat.jpg";
    $image_data = file_get_contents($target_file);
    $base64_image_kopsurat = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    $file_extension = pathinfo('../images/ttd_kepala_desa.png', PATHINFO_EXTENSION);
    $target_file = "../images/ttd_kepala_desa.png";
    $image_data = file_get_contents($target_file);
    $base64_image_kepala_desa = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    $file_extension = pathinfo('../images/ttd_sekretaris.png', PATHINFO_EXTENSION);
    $target_file = "../images/ttd_sekretaris.png";
    $image_data = file_get_contents($target_file);
    $base64_image_sekretaris = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']); // Escaping input
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $tanggal_input = htmlspecialchars($_POST['tanggal']);
    $date = DateTime::createFromFormat('Y-m-d', $tanggal_input);

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $bulan_angka = (int) $date->format('n');
    $tanggal_format = $date->format('Y') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('d');

    $kepada = htmlspecialchars($_POST['kepada']);
    $lokasi_penerima = htmlspecialchars($_POST['lokasi']);
    $tanggal_acara = htmlspecialchars($_POST['tanggal_acara']);
    $waktu_acara = htmlspecialchars($_POST['waktu_acara']);
    $tempat_acara = htmlspecialchars($_POST['tempat_acara']);
    $keperluan = htmlspecialchars($_POST['keperluan']);
    $perihal = htmlspecialchars($_POST['perihal']);
    $lampiran = isset($_POST['lampiran']) ? htmlspecialchars($_POST['lampiran']) : '-';  // Optional field

    $tanggal_acara_input = DateTime::createFromFormat('Y-m-d', $tanggal_acara);
    $tanggal_acara_format = $tanggal_acara_input ? $tanggal_acara_input->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $tanggal_acara_input->format('Y') : '-';

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
        * {
            box-sizing: border-box;
        }
      
        body {
            font-family: Times New Romans
            line-height: 1.6;
            font-size : 16 px;
            margin: 0.5cm;
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
            margin-top: 10px;
        }
        .content {
            margin-left: 20px;
        }

    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'><br>
        </div>
        <div class='content'>
            <p>$tanggal_format</p>
            <br>
            <p><span style='padding-right: 34px;'>Nomor</span>: $nomor_surat <br>
            <span style='padding-right: 17px;'>Lampiran</span>: $lampiran <br>
            <span style='padding-right: 34px;'>Perihal</span>: <strong>Undangan</strong></p>
            <br>
            <p> 
            <i>Kepada Yth.<i><br>
            <b>Bapak/Ibu $kepada</b><br>
            <b>di $lokasi_penerima</b>
            </p><br>
            <p class='pembuka'>Puji syukur kepada Allah SWT atas limpahan rahmat_Nya.
            Sholawat dan salam selalu tercurah kepada junjungan kita, Nabi Muhammad SAW.</p>
            <p class='isi'>Dengan ini mengharapkan kepada Bapak/Ibu/Saudara, Besok pada:</p>
            <div class='content'>
                <p>Hari/Tanggal&nbsp;&nbsp;: $tanggal_acara_format</p>
                <p>Waktu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $waktu_acara</p>
                <p>Tempat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $tempat_acara</p>
                <p>Acara&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $keperluan</p>
            </div>
            <p class='penutup'>Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami sampaikan terima kasih.</p>
        </div>
        <div class='signature'> 
        <p style='text-align:center; font-weight: bold;'>Pengurus <br> Koperasi Desa Wisata Candirejo</p><br>
        <table style='width:100%; text-align: center;'>
            <tr>
                <td style='width: 50%; padding: 10px;'>
                    <img src='$base64_image_kepala_desa' alt='Penandatangan Surat' height='90px'><br>
                    <p>Ersyidik</p>
                    <p>Ketua</p>
                </td>
                <td style='width: 50%; padding: 10px;'>
                    <img src='$base64_image_sekretaris' alt='Penandatangan Surat' height='90px'><br>
                    <p>Rifa</p>
                    <p>Sekretaris</p>
                </td>
            </tr>
        </table>
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
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Data tidak lengkap!";
}