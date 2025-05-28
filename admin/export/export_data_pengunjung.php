<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

// Ambil data surat keluar dari database
$sql = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Start buffering output
ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Data Pengunjung</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    font-size: 12px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  table,
  th,
  td {
    border: 1px solid black;
    padding: 5px;
  }

  th {
    background-color: #f2f2f2;
  }

  .title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
  }
  </style>
</head>

<body>
  <h2 class="title">Laporan Data Pengunjung</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Pax (Jumlah Wisatawan)</th>
        <th>Pilihan Paket Wisata</th>
        <th>Jenis Wisatawan</th>
        <th>Kota/Negara</th>
        <th>Agen Wisata</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; 
      while($data = mysqli_fetch_array($query)) { 
        $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];
        ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['nama']; ?></td>
        <td><?php echo $data['pax']; ?></td>
        <td><?php echo $data['pilihan_paket_wisata']; ?></td>
        <td><?php echo $data['jenis_wisatawan']; ?></td>
        <td><?php echo $lokasi ?></td>
        <td><?php echo $data['agen_wisata']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</body>

</html>

<?php
$html = ob_get_clean();

// Initialize DomPDF and load the HTML content
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation (optional)
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF
$dompdf->stream("laporan_data_pengunjung.pdf", ["Attachment" => false]); // Set Attachment to true to force download