<?php
session_start();

require '../../dompdf/autoload.inc.php';
include '../../koneksi/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['nama']) && isset($_POST['jenis_keterangan_pendukung'])) {

    $pdf_dir = '../uploads/';
    $pdf_filename = "Surat_Keterangan_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";
    $pdf_path = $pdf_dir . $pdf_filename;
    
    // Load dan encode gambar
    $target_dir = "../images/";
    
    // Kop surat
    $file_extension = pathinfo('../images/kopsurat.jpg', PATHINFO_EXTENSION);
    $target_file = "../images/kopsurat.jpg";
    $image_data = file_get_contents($target_file);
    $base64_image_kopsurat = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);
    
    // Tanda tangan kepala desa
    $file_extension = pathinfo('../images/ttd_kepala_desa.png', PATHINFO_EXTENSION);
    $target_file = "../images/ttd_kepala_desa.png";
    $image_data = file_get_contents($target_file);
    $base64_image_kepala_desa = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_keterangan_pendukung = htmlspecialchars($_POST['jenis_keterangan_pendukung']);
    $keterangan_pendukung = htmlspecialchars($_POST['keterangan_pendukung']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal = htmlspecialchars($_POST['tanggal']);

    // Format tanggal Indonesia
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    $bulan_angka = (int) $date->format('n');
    $tanggal_format = $date->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('Y');

    // Konfigurasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Buat konten HTML untuk PDF - CSS yang diperbaiki
    $html = "
    <!DOCTYPE html>
<html lang='id'>
<head>
    <style>
        * {
            box-sizing: border-box;
        }
      
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.15;
            font-size: 14px;
            margin: 0.5cm;
            background-color: #ffffff;
            color: #000;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            border: none;
            padding: 0;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .kop-surat img {
            width: 100%;
            height: auto;
            max-width: 800px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .nomor {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .content {
            margin: 20px 0;
            text-align: justify;
            line-height: 1.15;
        }
        .pembuka {
            margin-bottom: 10px;
            text-indent: 30px;
        }
        .data-section {
            margin: 15px 0;
            padding-left: 40px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .data-label {
            width: 150px;
            font-weight: normal;
        }
        .data-colon {
            width: 20px;
            text-align: left;
        }
        .data-value {
            width: auto;
            font-weight: normal;
        }
        .isi-keterangan {
            text-align: justify;
            margin: 15px 0;
            text-indent: 30px;
            line-height: 1.15;
        }
        .penutup {
            margin: 20px 0;
            text-indent: 30px;
            text-align: justify;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 80px;
        }
        .signature-content {
            display: inline-block;
            text-align: center;
        }
        .signature-content p {
            margin: 5px 0;
            line-height: 1.15;
        }
        .ttd-img {
            margin: 20px 0;
        }
        .ttd-img img {
            height: 80px;
            width: auto;
        }
        .nama-ttd {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
        }
        .jabatan-ttd {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'>
        </div>
        
        <div class='judul'>
            SURAT KETERANGAN
        </div>
        
        <div class='nomor'>
            No. $nomor_surat
        </div>
        
        <div class='content'>
            <div class='pembuka'>
                Yang bertanda tangan dibawah ini,
            </div>
            
            <div class='data-section'>
                <table class='data-table'>
                    <tr>
                        <td class='data-label'>Nama</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>Tatak Sariawan</td>
                    </tr>
                    <tr>
                        <td class='data-label'>Jabatan</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>Ketua Koperasi Desa Wisata Candirejo</td>
                    </tr>
                    <tr>
                        <td class='data-label'>Alamat</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>Mangundadi Candirejo Borobudur – Magelang</td>
                    </tr>
                </table>
            </div>
            
            <div class='pembuka'>
                Menerangkan bahwa,
            </div>
            
            <div class='data-section'>
                <table class='data-table'>
                    <tr>
                        <td class='data-label'>Nama</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$nama</td>
                    </tr>
                    <tr>
                        <td class='data-label'>$jenis_keterangan_pendukung</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$keterangan_pendukung</td>
                    </tr>
                </table>
            </div>
            
            <div class='isi-keterangan'>
                $keterangan
            </div>
            
            <div class='penutup'>
                Demikian surat keterangan ini kami buat untuk digunakan sebagaimana mestinya.
            </div>
        </div>
        
        <div class='signature'>
            <div class='signature-content'>
                <p>Candirejo, $tanggal_format</p>
                <p><strong>Pengurus</strong></p>
                <p><strong>Koperasi Desa Wisata Candirejo</strong></p>
                <div class='ttd-img'>
                    <img src='$base64_image_kepala_desa' alt='Tanda Tangan'>
                </div>
                <p class='nama-ttd'>Tatak Sariawan</p>
                <p class='jabatan-ttd'>Ketua</p>
            </div>
        </div>
    </div>
</body>
</html>";

    // Generate PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Simpan file PDF
    file_put_contents($pdf_path, $dompdf->output());

    // Insert ke database
    $query = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
              VALUES ('$tanggal', '$nomor_surat', '$nama', 'Surat Keterangan', '-', 'Dibuat dari fitur Buat surat keterangan', '$pdf_path')";

    if (mysqli_query($db, $query)) {
        // Header untuk download PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdf_filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        // Output PDF ke browser
        $dompdf->stream($pdf_filename, ["Attachment" => false]);
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Data tidak lengkap! Pastikan semua field required telah diisi.";
}
?>