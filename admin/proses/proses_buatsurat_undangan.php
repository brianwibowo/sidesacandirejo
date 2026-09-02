<?php
session_start();

require '../../dompdf/autoload.inc.php';
include '../../koneksi/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['tanggal']) && isset($_POST['kepada'])) {

    $pdf_dir = '../uploads/';
    $pdf_filename = "Surat_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";
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
    
    // Tanda tangan sekretaris
    $file_extension = pathinfo('../images/ttd_sekretaris.png', PATHINFO_EXTENSION);
    $target_file = "../images/ttd_sekretaris.png";
    $image_data = file_get_contents($target_file);
    $base64_image_sekretaris = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $kepada = htmlspecialchars($_POST['kepada']);
    $lokasi_penerima = htmlspecialchars($_POST['lokasi']);
    $tanggal_acara = htmlspecialchars($_POST['tanggal_acara']);
    $waktu_acara = htmlspecialchars($_POST['waktu_acara']);
    $tempat_acara = htmlspecialchars($_POST['tempat_acara']);
    $keperluan = htmlspecialchars($_POST['keperluan']);
    $perihal = htmlspecialchars($_POST['perihal']);
    $lampiran = isset($_POST['lampiran']) ? htmlspecialchars($_POST['lampiran']) : '-';

    // Format tanggal Indonesia
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    // Format tanggal surat
    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    $bulan_angka = (int) $date->format('n');
    $tanggal_format = $date->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('Y');

    // Format tanggal acara
    $tanggal_acara_input = DateTime::createFromFormat('Y-m-d', $tanggal_acara);
    $bulan_acara = (int) $tanggal_acara_input->format('n');
    $tanggal_acara_format = $tanggal_acara_input->format('d') . ' ' . $bulan[$bulan_acara] . ' ' . $tanggal_acara_input->format('Y');

    // Konfigurasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Buat konten HTML untuk PDF - Layout sesuai surat keterangan
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
        .tanggal-surat {
            text-align: right;
            margin-bottom: 20px;
            padding-right: 40px;
        }
        .header-surat {
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .header-label {
            width: 80px;
            font-weight: normal;
        }
        .header-colon {
            width: 20px;
            text-align: left;
        }
        .header-value {
            width: auto;
            font-weight: normal;
        }
        .penerima-section {
            margin: 20px 0;
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
        .isi-undangan {
            margin: 15px 0;
            text-indent: 30px;
            text-align: justify;
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
            width: 120px;
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
        .penutup {
            margin: 20px 0;
            text-indent: 30px;
            text-align: justify;
        }
        .signature {
            margin-top: 40px;
            text-align: center;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .signature-table td {
            width: 50%;
            padding: 10px;
            vertical-align: top;
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
        .signature-content p {
            margin: 5px 0;
            line-height: 1.15;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'>
        </div>
        
        <div class='tanggal-surat'>
            Candirejo, $tanggal_format
        </div>
        
        <div class='header-surat'>
            <table class='header-table'>
                <tr>
                    <td class='header-label'>Nomor</td>
                    <td class='header-colon'>:</td>
                    <td class='header-value'>$nomor_surat</td>
                </tr>
                <tr>
                    <td class='header-label'>Lampiran</td>
                    <td class='header-colon'>:</td>
                    <td class='header-value'>$lampiran</td>
                </tr>
                <tr>
                    <td class='header-label'>Perihal</td>
                    <td class='header-colon'>:</td>
                    <td class='header-value'><strong>Undangan</strong></td>
                </tr>
            </table>
        </div>
        
        <div class='penerima-section'>
            <p><em>Kepada Yth.</em></p>
            <p><strong>Bapak/Ibu $kepada</strong></p>
            <p><strong>di $lokasi_penerima</strong></p>
        </div>
        
        <div class='content'>
            <div class='pembuka'>
                Puji syukur kepada Allah SWT atas limpahan rahmat-Nya. Sholawat dan salam selalu tercurah kepada junjungan kita, Nabi Muhammad SAW.
            </div>
            
            <div class='isi-undangan'>
                Dengan ini mengharapkan kepada Bapak/Ibu/Saudara, untuk dapat hadir pada:
            </div>
            
            <div class='data-section'>
                <table class='data-table'>
                    <tr>
                        <td class='data-label'>Hari/Tanggal</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$tanggal_acara_format</td>
                    </tr>
                    <tr>
                        <td class='data-label'>Waktu</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$waktu_acara</td>
                    </tr>
                    <tr>
                        <td class='data-label'>Tempat</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$tempat_acara</td>
                    </tr>
                    <tr>
                        <td class='data-label'>Acara</td>
                        <td class='data-colon'>:</td>
                        <td class='data-value'>$keperluan</td>
                    </tr>
                </table>
            </div>
            
            <div class='penutup'>
                Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami sampaikan terima kasih.
            </div>
        </div>
        
        <div class='signature'>
            <div class='signature-title'>
                Pengurus<br>
                Koperasi Desa Wisata Candirejo
            </div>
            
            <table class='signature-table'>
                <tr>
                    <td>
                        <div class='signature-content'>
                            <div class='ttd-img'>
                                <img src='$base64_image_kepala_desa' alt='Tanda Tangan Ketua'>
                            </div>
                            <p class='nama-ttd'>Ersyidik</p>
                            <p class='jabatan-ttd'>Ketua</p>
                        </div>
                    </td>
                    <td>
                        <div class='signature-content'>
                            <div class='ttd-img'>
                                <img src='$base64_image_sekretaris' alt='Tanda Tangan Sekretaris'>
                            </div>
                            <p class='nama-ttd'>Rifa</p>
                            <p class='jabatan-ttd'>Sekretaris</p>
                        </div>
                    </td>
                </tr>
            </table>
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
              VALUES ('$tanggal', '$nomor_surat', '$kepada', '$perihal', '-', 'Dibuat dari fitur Buat surat', '$pdf_path')";

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