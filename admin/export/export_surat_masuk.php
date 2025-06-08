<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';


$sql = "SELECT * FROM tb_arsip_surat_masuk ORDER BY No ASC";
$query = mysqli_query($db, $sql);

// Start buffering output
ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Surat Masuk</title>
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
  <h2 class="title">Laporan Data Surat Masuk</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nomor Surat</th>
        <th>Tanggal Terima</th>
        <th>Tanggal Surat</th>
        <th>Pengirim</th>
        <th>Penerima</th>
        <th>Disposisi</th>
        <th>Perihal</th>
        <th>Kode</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php while($data = mysqli_fetch_array($query)) { ?>
      <tr>
        <td><?php echo $data['No']; ?></td>
        <td><?php echo $data['nomor_surat']; ?></td>
        <td><?php echo $data['tanggal_terima']; ?></td>
        <td><?php echo $data['tanggal_surat']; ?></td>
        <td><?php echo $data['pengirim']; ?></td>
        <td><?php echo $data['penerima_surat']; ?></td>
        <td><?php echo $data['disposisi']; ?></td>
        <td><?php echo $data['perihal']; ?></td>
        <td><?php echo $data['kode']; ?></td>
        <td><?php echo $data['keterangan']; ?></td>
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
$dompdf->stream("laporan_surat_masuk.pdf", ["Attachment" => false]); // Set Attachment to true to force download