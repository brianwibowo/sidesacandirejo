<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

// Ambil data surat keluar dari database
$sql = "SELECT * FROM tb_data_penjualan_usaha ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Start buffering output
ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Data Penjualan Usaha</title>
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
  <h2 class="title">Laporan Data Penjualan Usaha</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; 
      while($data = mysqli_fetch_array($query)) { 
        ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['produk']; ?></td>
        <td><?php echo $data['jumlah']; ?></td>
        <td><?php echo $data['harga']; ?></td>
        <td><?php echo $data['total']; ?></td>
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
$dompdf->stream("laporan_data_penjualan_usaha.pdf", ["Attachment" => false]); // Set Attachment to true to force download