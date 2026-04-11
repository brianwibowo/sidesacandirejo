<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

function tglIndo($date) {
    global $bulan_id;
    if (!$date) return '-';
    $ts = strtotime($date);
    return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : null;
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : null;

if (!$tgl_mulai || !$tgl_akhir) {
    die('Parameter tidak lengkap.');
}

$tgl_mulai_esc = mysqli_real_escape_string($db, $tgl_mulai);
$tgl_akhir_esc = mysqli_real_escape_string($db, $tgl_akhir);

/* ── Stat total ── */
$total_booking = $total_checkin = $total_tidak = $total_pax = 0;
$q = mysqli_query($db,
    "SELECT status, COUNT(*) as jml, SUM(pax) as total_pax
     FROM tb_booking
     WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
     GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) {
    $total_booking += $r['jml'];
    $total_pax     += $r['total_pax'];
    if ($r['status'] === 'checkin')     $total_checkin += $r['jml'];
    if ($r['status'] === 'tidak_hadir') $total_tidak   += $r['jml'];
}

/* ── Rekap per hari ── */
$rekap = [];
$q2 = mysqli_query($db,
    "SELECT tanggal_kunjungan,
            COUNT(*) as total,
            SUM(CASE WHEN status='checkin'     THEN 1 ELSE 0 END) as jml_checkin,
            SUM(CASE WHEN status='tidak_hadir' THEN 1 ELSE 0 END) as jml_tidak,
            SUM(pax) as total_pax
     FROM tb_booking
     WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
     GROUP BY tanggal_kunjungan
     ORDER BY tanggal_kunjungan ASC");
while ($r = mysqli_fetch_assoc($q2)) $rekap[] = $r;

/* ── Detail semua booking ── */
$detail = [];
$q3 = mysqli_query($db,
    "SELECT nama, tanggal_kunjungan, pax, pilihan_paket_wisata, status
     FROM tb_booking
     WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
     ORDER BY tanggal_kunjungan ASC, id ASC");
while ($r = mysqli_fetch_assoc($q3)) $detail[] = $r;

$tgl_cetak = date('d/m/Y H:i');

/* ── Build HTML ── */
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e3a2f; }
  .header-pdf { text-align: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #1e3a2f; }
  .header-pdf h1 { font-size: 18px; font-weight: bold; color: #1e3a2f; }
  .header-pdf p  { font-size: 11px; color: #4a6e5c; margin-top: 4px; }
  .meta { font-size: 10px; color: #6b8f7e; margin-top: 6px; }

  .stat-row { display: table; width: 100%; margin-bottom: 18px; border-spacing: 8px; }
  .stat-box { display: table-cell; width: 25%; background: #f4f7f5; border: 1px solid #d6e6dc; border-radius: 6px; padding: 10px 14px; text-align: center; }
  .stat-box .label { font-size: 10px; color: #7a9e8e; margin-bottom: 4px; }
  .stat-box .val   { font-size: 20px; font-weight: bold; color: #1e3a2f; }
  .stat-box .val.green { color: #2e7d4f; }
  .stat-box .val.red   { color: #c0392b; }

  .section-title { font-size: 13px; font-weight: bold; color: #1e3a2f; margin: 18px 0 8px; }

  table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
  thead th { background: #1e3a2f; color: #fff; padding: 7px 10px; text-align: left; font-size: 10.5px; }
  tbody tr:nth-child(even) { background: #f8fbf9; }
  tbody td { padding: 7px 10px; font-size: 10.5px; border-bottom: 1px solid #e8f0ec; }
  .green { color: #2e7d4f; font-weight: bold; }
  .red   { color: #c0392b; font-weight: bold; }

  .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
  .badge-pending     { background: #fdf0e0; color: #c0742a; }
  .badge-checkin     { background: #e4f5ec; color: #2e7d4f; }
  .badge-tidak_hadir { background: #fceaea; color: #c0392b; }

  .footer-pdf { margin-top: 24px; padding-top: 10px; border-top: 1px solid #e5ede8; text-align: center; font-size: 9px; color: #9ab5a8; }
</style>
</head>
<body>

<div class="header-pdf">
  <h1>Laporan Booking</h1>
  <p>Desa Wisata Candirejo</p>
  <p class="meta">
    Periode: <?php echo tglIndo($tgl_mulai); ?> — <?php echo tglIndo($tgl_akhir); ?> &nbsp;|&nbsp; Dicetak: <?php echo $tgl_cetak; ?>
  </p>
</div>

<!-- Stat boxes -->
<div class="stat-row">
  <div class="stat-box"><div class="label">Total Booking</div><div class="val"><?php echo $total_booking; ?></div></div>
  <div class="stat-box"><div class="label">Total Check-in</div><div class="val green"><?php echo $total_checkin; ?></div></div>
  <div class="stat-box"><div class="label">Total Tidak Datang</div><div class="val red"><?php echo $total_tidak; ?></div></div>
  <div class="stat-box"><div class="label">Total Pax</div><div class="val"><?php echo $total_pax; ?></div></div>
</div>

<!-- Rekap per hari -->
<div class="section-title">Rekap Data per Hari</div>
<table>
  <thead>
    <tr>
      <th>Tanggal</th>
      <th>Total Booking</th>
      <th>Check-in</th>
      <th>Tidak Datang</th>
      <th>Total Pax</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rekap)): ?>
    <tr><td colspan="5" style="text-align:center;color:#9ab5a8;">Tidak ada data</td></tr>
    <?php else: foreach ($rekap as $r): ?>
    <tr>
      <td><?php echo tglIndo($r['tanggal_kunjungan']); ?></td>
      <td><?php echo (int)$r['total']; ?></td>
      <td class="green"><?php echo (int)$r['jml_checkin']; ?></td>
      <td class="red"><?php echo (int)$r['jml_tidak']; ?></td>
      <td><?php echo (int)$r['total_pax']; ?></td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<!-- Detail semua booking -->
<div class="section-title">Detail Semua Booking</div>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Tanggal Kunjungan</th>
      <th>Paket</th>
      <th>Pax</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($detail)): ?>
    <tr><td colspan="6" style="text-align:center;color:#9ab5a8;">Tidak ada data</td></tr>
    <?php else:
      $no = 1;
      $paket_map = [
        'meal_only'=>'Breakfast/Lunch/Dinner Only','studi_banding'=>'Studi Banding',
        'fun_game'=>'Paket Fun Game','pelajar_live_in'=>'Pelajar – Live In',
        'pelajar_field_trip_one_day'=>'Pelajar – Field Trip One Day',
        'pelajar_field_trip_half_day'=>'Pelajar – Field Trip Half Day',
        'cycling_tour'=>'Cycling Village Tour','traditional_dance'=>'Traditional Dance',
        'walking_tour'=>'Walking Around Village','homestay'=>'Homestay',
        'serenade'=>'Serenade Menoreh','cooking_lesson'=>'Cooking Lesson',
        'gamelan_class'=>'Gamelan Class','village_experience'=>'Village Experience',
        'dokar_tour'=>'Dokar Village Tour','inspection'=>'Inspection','lainnya'=>'Lainnya',
      ];
      foreach ($detail as $d):
        $st = $d['status'];
        $bl = $st === 'checkin' ? 'Check-in' : ($st === 'pending' ? 'Pending' : 'Tidak Datang');
        $bc = 'badge-' . ($st === 'tidak_hadir' ? 'tidak_hadir' : $st);
        $pk = isset($paket_map[$d['pilihan_paket_wisata']]) ? $paket_map[$d['pilihan_paket_wisata']] : ucwords(str_replace('_',' ',$d['pilihan_paket_wisata']));
    ?>
    <tr>
      <td><?php echo $no++; ?></td>
      <td><?php echo htmlspecialchars($d['nama']); ?></td>
      <td><?php echo tglIndo($d['tanggal_kunjungan']); ?></td>
      <td><?php echo htmlspecialchars($pk); ?></td>
      <td><?php echo (int)$d['pax']; ?></td>
      <td><span class="badge <?php echo $bc; ?>"><?php echo $bl; ?></span></td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<div class="footer-pdf">Apriansyah Wibowo &mdash; Sistem Booking Desa Wisata Candirejo</div>

</body>
</html>
<?php
$html = ob_get_clean();

/* ── Render PDF ── */
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'Laporan_Booking_' . $tgl_mulai . '_sd_' . $tgl_akhir . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
exit;