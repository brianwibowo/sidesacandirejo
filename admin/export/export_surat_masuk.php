<?php
require '../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

$sql   = "SELECT * FROM tb_arsip_surat_masuk ORDER BY No ASC";
$query = mysqli_query($db, $sql);

// Helper format tanggal Indonesia
function tgl_id($tgl) {
    if (empty($tgl)) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($tgl);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Laporan Surat Masuk</title>
  <style>
    body      { font-family: Arial, sans-serif; font-size: 11px; }
    h2        { text-align: center; font-size: 15px; margin-bottom: 4px; }
    p.sub     { text-align: center; font-size: 10px; color: #555; margin: 0 0 12px; }
    table     { width: 100%; border-collapse: collapse; }
    th, td    { border: 1px solid #333; padding: 5px 6px; vertical-align: top; }
    th        { background-color: #dce6f1; text-align: center; }
    tr:nth-child(even) td { background-color: #f7f7f7; }
    td.center { text-align: center; }
  </style>
</head>
<body>
  <h2>Laporan Data Surat Masuk</h2>
  <p class="sub">Desa Candirejo Borobudur &mdash; Dicetak pada <?php echo tgl_id(date('Y-m-d')); ?></p>
  <table>
    <thead>
      <tr>
        <th width="4%">No</th>
        <th width="12%">Nomor Surat</th>
        <th width="10%">Tgl Terima</th>
        <th width="10%">Tgl Surat</th>
        <th width="12%">Pengirim</th>
        <th width="12%">Penerima</th>
        <th width="10%">Disposisi</th>
        <th width="15%">Perihal</th>
        <th width="15%">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($data = mysqli_fetch_assoc($query)): ?>
      <tr>
        <td class="center"><?php echo htmlspecialchars($data['No']); ?></td>
        <td><?php echo htmlspecialchars($data['nomor_surat']); ?></td>
        <td class="center"><?php echo tgl_id($data['tanggal_terima']); ?></td>
        <td class="center"><?php echo tgl_id($data['tanggal_surat']); ?></td>
        <td><?php echo htmlspecialchars($data['pengirim']); ?></td>
        <td><?php echo htmlspecialchars($data['penerima_surat']); ?></td>
        <td><?php echo htmlspecialchars($data['disposisi']); ?></td>
        <td><?php echo htmlspecialchars($data['perihal']); ?></td>
        <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_surat_masuk.pdf", ["Attachment" => false]);