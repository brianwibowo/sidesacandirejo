<?php
session_start();
include '../../koneksi/koneksi.php';

// Helper format tanggal Indonesia
function tgl_id($tgl) {
    if (empty($tgl)) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($tgl);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

$timestamp = date("Ymd_His");
$filename  = "Data_Surat_Masuk_$timestamp.xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

$sql    = "SELECT * FROM tb_arsip_surat_masuk ORDER BY No ASC";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    h2   { text-align: center; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #333; padding: 4px 6px; }
    th { background-color: #dce6f1; text-align: center; }
  </style>
</head>
<body>
  <h2>Data Surat Masuk &mdash; Desa Candirejo Borobudur</h2>
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
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td style="text-align:center;"><?php echo htmlspecialchars($row['No']); ?></td>
          <td><?php echo htmlspecialchars($row['nomor_surat']); ?></td>
          <td style="text-align:center;"><?php echo tgl_id($row['tanggal_terima']); ?></td>
          <td style="text-align:center;"><?php echo tgl_id($row['tanggal_surat']); ?></td>
          <td><?php echo htmlspecialchars($row['pengirim']); ?></td>
          <td><?php echo htmlspecialchars($row['penerima_surat']); ?></td>
          <td><?php echo htmlspecialchars($row['disposisi']); ?></td>
          <td><?php echo htmlspecialchars($row['perihal']); ?></td>
          <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">Tidak ada data</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
<?php mysqli_close($db); ?>