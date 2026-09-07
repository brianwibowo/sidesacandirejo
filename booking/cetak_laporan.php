<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

if (!function_exists('tglIndo')) {
    function tglIndo($date) {
        global $bulan_id;
        if (!$date) return '-';
        $ts = strtotime($date);
        return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

if (!function_exists('hariIndo')) {
    function hariIndo($date) {
        if (!$date) return '';
        $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return $days[(int)date('w', strtotime($date))];
    }
}

$tgl_mulai = isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] !== '' ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] !== '' ? $_GET['tgl_akhir'] : date('Y-m-t');

$tgl_mulai_esc = mysqli_real_escape_string($db, $tgl_mulai);
$tgl_akhir_esc = mysqli_real_escape_string($db, $tgl_akhir);

/* ── Stat total ── */
$total_booking = $total_checkin = $total_tidak = $total_pax = 0;
try {
    $q = mysqli_query($db,
        "SELECT status, COUNT(*) as jml, SUM(pax) as total_pax
         FROM tb_booking
         WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
         GROUP BY status");
    if ($q) {
        while ($r = mysqli_fetch_assoc($q)) {
            $total_booking += (int)$r['jml'];
            $total_pax     += (int)$r['total_pax'];
            if ($r['status'] === 'checkin')     $total_checkin += (int)$r['jml'];
            if ($r['status'] === 'tidak_hadir') $total_tidak   += (int)$r['jml'];
        }
    }
} catch (Throwable $e) {}

/* ── Rekap per hari ── */
$rekap = [];
try {
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
    if ($q2) {
        while ($r = mysqli_fetch_assoc($q2)) $rekap[] = $r;
    }
} catch (Throwable $e) {}

/* ── Detail semua booking ── */
$detail = [];
try {
    $q3 = mysqli_query($db,
        "SELECT id, nama, agen_wisata, tanggal_kunjungan, pax, pilihan_paket_wisata, opsi_makan_tour, status, driver_agent_guide, local_guide, catatan
         FROM tb_booking
         WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
         ORDER BY tanggal_kunjungan ASC, id ASC");
    if ($q3) {
        while ($r = mysqli_fetch_assoc($q3)) $detail[] = $r;
    }
} catch (Throwable $e) {
    $q3 = @mysqli_query($db,
        "SELECT id, nama, agen_wisata, tanggal_kunjungan, pax, pilihan_paket_wisata, opsi_makan_tour, status, driver_agent_guide, local_guide, '' AS catatan
         FROM tb_booking
         WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
         ORDER BY tanggal_kunjungan ASC, id ASC");
    if ($q3) {
        while ($r = mysqli_fetch_assoc($q3)) $detail[] = $r;
    }
}

$tgl_cetak = date('d/m/Y H:i');

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

/* ── Build HTML ── */
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 26px 24px 34px 24px; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 8.5px; color: #1e3a2f; line-height: 1.3; }

  /* ── KOP SURAT RESMI ── */
  .kop { text-align: center; padding-bottom: 7px; border-bottom: 2.5px solid #1e3a2f; position: relative; }
  .kop .instansi { font-size: 13.5px; font-weight: bold; color: #153e2a; text-transform: uppercase; letter-spacing: 0.8px; }
  .kop .unit { font-size: 10.5px; font-weight: bold; color: #2d5a42; margin-top: 2px; text-transform: uppercase; }
  .kop .alamat { font-size: 8px; color: #5a7d6d; margin-top: 2px; }
  .kop-subline { border-top: 1px solid #7a9e8e; margin-top: 2px; }

  /* ── BANNER JUDUL ── */
  .title-banner { background: #edf5f0; border: 1px solid #cce3d5; border-radius: 4px; padding: 6px 12px; margin: 10px 0 10px; text-align: center; }
  .title-banner h2 { font-size: 11px; font-weight: bold; color: #153e2a; text-transform: uppercase; letter-spacing: 0.5px; }
  .title-banner .meta { font-size: 8px; color: #527863; margin-top: 2px; }

  /* ── STAT SUMMARY BOXES ── */
  .stat-table { width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 10px; }
  .stat-card { background: #ffffff; border: 1px solid #d4e5dc; border-radius: 4px; padding: 6px 8px; text-align: center; }
  .stat-card.c-green { background: #f2f9f5; border-color: #bce3cc; }
  .stat-card.c-red   { background: #fdf5f5; border-color: #f3cbcb; }
  .stat-card.c-blue  { background: #f4f8fa; border-color: #cde1ec; }
  .stat-label { font-size: 7.5px; color: #6b8f7e; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px; }
  .stat-val   { font-size: 15px; font-weight: bold; color: #1e3a2f; line-height: 1.1; }
  .stat-val.green { color: #2e7d4f; }
  .stat-val.red   { color: #c0392b; }

  /* ── SECTION HEADINGS ── */
  .section-head { font-size: 9.5px; font-weight: bold; color: #153e2a; margin: 12px 0 5px; text-transform: uppercase; letter-spacing: 0.3px; border-left: 3px solid #2e7d4f; padding-left: 6px; }

  /* ── DATA TABLES ── */
  table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
  table.data-table thead th { background: #1e3a2f; color: #ffffff; padding: 5px 6px; font-size: 8px; font-weight: bold; text-align: left; text-transform: uppercase; letter-spacing: 0.3px; }
  table.data-table thead th.center { text-align: center; }
  table.data-table tbody tr:nth-child(even) { background: #f9fbf9; }
  table.data-table tbody td { padding: 4.5px 6px; font-size: 8px; border-bottom: 1px solid #e5ede8; vertical-align: top; }
  table.data-table tbody td.center { text-align: center; }

  /* ── TFOOT SUMMARY ROW ── */
  table.data-table tfoot td { background: #eaf2ed; border-top: 1.5px solid #1e3a2f; border-bottom: 1px solid #1e3a2f; padding: 5px 6px; font-size: 8px; font-weight: bold; color: #153e2a; }
  table.data-table tfoot td.center { text-align: center; }

  /* ── BADGES & CALLOUTS ── */
  .badge { display: inline-block; padding: 1.5px 6px; border-radius: 3px; font-size: 7.5px; font-weight: bold; text-transform: uppercase; white-space: nowrap; }
  .badge-pending     { background: #fef3e2; color: #b46d1b; border: 0.5px solid #f9d8a7; }
  .badge-checkin     { background: #e6f6ee; color: #227c49; border: 0.5px solid #aee4c5; }
  .badge-tidak_hadir { background: #fdeaea; color: #c0392b; border: 0.5px solid #f8bebe; }

  .day-tag { font-size: 7px; color: #3b6b52; background: #eaf3ee; padding: 1px 4px; border-radius: 3px; font-weight: bold; display: inline-block; margin-left: 3px; }

  .callout-note { margin-top: 2px; padding: 2.5px 5px; background: #fffdf5; border-left: 2px solid #d4a843; font-size: 7.5px; color: #72531a; font-style: italic; }

  /* ── SIGNATURE BLOCK ── */
  .sig-table { width: 100%; margin-top: 16px; border-collapse: collapse; page-break-inside: avoid; }
  .sig-table td { vertical-align: top; border: none; font-size: 8px; }

  /* ── FOOTER TEXT ── */
  .footer-bar { margin-top: 12px; padding-top: 5px; border-top: 1px solid #e0ebe3; text-align: center; font-size: 7.5px; color: #8faea0; }
</style>
</head>
<body>

<!-- Kop Surat Resmi -->
<div class="kop">
  <div class="instansi">Pemerintah Desa Candirejo</div>
  <div class="unit">Pengelola Desa Wisata Candirejo (Candirejo Eco-Tourism)</div>
  <div class="alamat">Jl. Medang Kamulan No. 1, Desa Candirejo, Kec. Borobudur, Kab. Magelang, Jawa Tengah 56553</div>
</div>
<div class="kop-subline"></div>

<!-- Title Banner -->
<div class="title-banner">
  <h2>Laporan Rekapitulasi &amp; Detail Reservasi Booking</h2>
  <div class="meta">
    Periode Kunjungan: <strong><?php echo tglIndo($tgl_mulai); ?></strong> s/d <strong><?php echo tglIndo($tgl_akhir); ?></strong>
    &nbsp;&bull;&nbsp; Tanggal Cetak: <?php echo $tgl_cetak; ?> WIB
  </div>
</div>

<!-- Stat Boxes -->
<table class="stat-table">
  <tr>
    <td class="stat-card" style="width: 25%;">
      <div class="stat-label">Total Booking</div>
      <div class="stat-val"><?php echo $total_booking; ?></div>
    </td>
    <td class="stat-card c-green" style="width: 25%;">
      <div class="stat-label">Total Check-In</div>
      <div class="stat-val green"><?php echo $total_checkin; ?></div>
    </td>
    <td class="stat-card c-red" style="width: 25%;">
      <div class="stat-label">Total Tidak Hadir</div>
      <div class="stat-val red"><?php echo $total_tidak; ?></div>
    </td>
    <td class="stat-card c-blue" style="width: 25%;">
      <div class="stat-label">Total Pengunjung (Pax)</div>
      <div class="stat-val"><?php echo $total_pax; ?></div>
    </td>
  </tr>
</table>

<!-- Bagian I: Rekap Harian -->
<div class="section-head">I. Rekapitulasi Booking per Hari (<?php echo count($rekap); ?> Hari Kunjungan)</div>
<table class="data-table">
  <thead>
    <tr>
      <th style="width: 32%;">Tanggal &amp; Hari</th>
      <th class="center" style="width: 17%;">Total Booking</th>
      <th class="center" style="width: 17%;">Check-In</th>
      <th class="center" style="width: 17%;">Tidak Hadir</th>
      <th class="center" style="width: 17%;">Total Pax</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rekap)): ?>
    <tr><td colspan="5" class="center" style="padding: 10px; color: #8faea0;">Tidak ada data reservasi pada rentang tanggal ini</td></tr>
    <?php else: foreach ($rekap as $r): ?>
    <tr>
      <td>
        <strong><?php echo tglIndo($r['tanggal_kunjungan']); ?></strong>
        <span class="day-tag"><?php echo hariIndo($r['tanggal_kunjungan']); ?></span>
      </td>
      <td class="center"><strong><?php echo (int)$r['total']; ?></strong></td>
      <td class="center" style="color: <?php echo $r['jml_checkin'] > 0 ? '#227c49' : '#8faea0'; ?>; font-weight: <?php echo $r['jml_checkin'] > 0 ? 'bold' : 'normal'; ?>;">
        <?php echo (int)$r['jml_checkin']; ?>
      </td>
      <td class="center" style="color: <?php echo $r['jml_tidak'] > 0 ? '#c0392b' : '#8faea0'; ?>; font-weight: <?php echo $r['jml_tidak'] > 0 ? 'bold' : 'normal'; ?>;">
        <?php echo (int)$r['jml_tidak']; ?>
      </td>
      <td class="center"><strong><?php echo (int)$r['total_pax']; ?></strong> pax</td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <td>TOTAL KESELURUHAN</td>
      <td class="center"><?php echo $total_booking; ?></td>
      <td class="center" style="color: #227c49;"><?php echo $total_checkin; ?></td>
      <td class="center" style="color: #c0392b;"><?php echo $total_tidak; ?></td>
      <td class="center"><?php echo $total_pax; ?> pax</td>
    </tr>
  </tfoot>
</table>

<!-- Bagian II: Detail Booking -->
<div class="section-head">II. Detail Reservasi Booking (<?php echo count($detail); ?> Data)</div>
<table class="data-table">
  <thead>
    <tr>
      <th class="center" style="width: 20px;">No</th>
      <th style="width: 115px;">Agen Wisata / Tamu</th>
      <th style="width: 72px;">Tgl Kunjungan</th>
      <th style="width: 110px;">Paket &amp; Layanan</th>
      <th>Driver, Guide &amp; Catatan</th>
      <th class="center" style="width: 30px;">Pax</th>
      <th class="center" style="width: 58px;">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($detail)): ?>
    <tr><td colspan="7" class="center" style="padding: 10px; color: #8faea0;">Tidak ada data reservasi pada rentang tanggal ini</td></tr>
    <?php else:
      $no = 1;
      foreach ($detail as $d):
        $st = $d['status'];
        $bl = $st === 'checkin' ? 'Check-in' : ($st === 'pending' ? 'Pending' : 'Tidak Hadir');
        $bc = 'badge-' . ($st === 'tidak_hadir' ? 'tidak_hadir' : $st);
        $pk = isset($paket_map[$d['pilihan_paket_wisata']]) ? $paket_map[$d['pilihan_paket_wisata']] : ucwords(str_replace('_',' ',$d['pilihan_paket_wisata']));
        $nama_agen = !empty($d['agen_wisata']) ? $d['agen_wisata'] : (!empty($d['nama']) ? $d['nama'] : '-');
        $driver_txt = $d['driver_agent_guide'] ?: 'Belum Ada';
        $guide_txt = $d['local_guide'] ?: 'Belum Ada';
    ?>
    <tr>
      <td class="center" style="color: #7a9e8e;"><?php echo $no++; ?></td>
      <td>
        <strong style="color: #153e2a;"><?php echo htmlspecialchars($nama_agen); ?></strong>
        <?php if (!empty($d['agen_wisata']) && !empty($d['nama']) && $d['nama'] !== $d['agen_wisata'] && $d['nama'] !== '-'): ?>
          <div style="font-size: 7.5px; color: #6b8f7e; margin-top: 1px;">Tamu: <?php echo htmlspecialchars($d['nama']); ?></div>
        <?php endif; ?>
      </td>
      <td>
        <div><?php echo tglIndo($d['tanggal_kunjungan']); ?></div>
      </td>
      <td>
        <div style="font-weight: bold; color: #1e3a2f;"><?php echo htmlspecialchars($pk); ?></div>
        <?php 
          if ($d['opsi_makan_tour'] === 'with_lunch') {
            echo '<div style="font-size: 7.5px; color: #2e7d4f; font-weight: bold; margin-top: 1px;">(With Lunch)</div>';
          } elseif ($d['opsi_makan_tour'] === 'without_lunch') {
            echo '<div style="font-size: 7.5px; color: #7a9e8e; margin-top: 1px;">(Without Lunch)</div>';
          }
        ?>
      </td>
      <td>
        <div><strong style="color: #3b6b52;">Driver:</strong> <?php echo htmlspecialchars($driver_txt); ?></div>
        <div style="margin-top: 1px;"><strong style="color: #3b6b52;">Guide:</strong> <?php echo htmlspecialchars($guide_txt); ?></div>
        <?php if (!empty($d['catatan'])): ?>
          <div class="callout-note">
            <strong>Catatan:</strong> <?php echo nl2br(htmlspecialchars($d['catatan'])); ?>
          </div>
        <?php endif; ?>
      </td>
      <td class="center"><strong><?php echo (int)$d['pax']; ?></strong></td>
      <td class="center"><span class="badge <?php echo $bc; ?>"><?php echo $bl; ?></span></td>
    </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<!-- Lembar Pengesahan -->
<table class="sig-table">
  <tr>
    <td style="width: 60%;">
      <div style="font-size: 7.5px; color: #7a9e8e; margin-top: 8px;">
        * Dokumen ini diterbitkan secara otomatis melalui Sistem Informasi Desa Wisata Candirejo.<br>
        * Seluruh data operasional reservasi tersimpan dan terverifikasi secara digital.
      </div>
    </td>
    <td style="width: 40%; text-align: center;">
      <div>Candirejo, <?php echo date('j') . ' ' . $bulan_id[(int)date('n')] . ' ' . date('Y'); ?></div>
      <div style="font-weight: bold; color: #153e2a; margin-top: 3px;">Pengelola Desa Wisata Candirejo</div>
      <div style="height: 44px;"></div>
      <div style="font-weight: bold; text-decoration: underline; color: #153e2a;">( ADMINISTRATOR SISTEM )</div>
      <div style="font-size: 7.5px; color: #5a7d6d; margin-top: 2px;">Sistem Booking Terpadu</div>
    </td>
  </tr>
</table>

<div class="footer-bar">
  Desa Wisata Candirejo &bull; Portal Sistem Informasi Desa &bull; Dokumen Resmi
</div>

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

// Running page number at bottom right
$canvas = $dompdf->getCanvas();
$canvas->page_text(515, 820, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", null, 7.5, [0.45, 0.6, 0.52]);

$filename = 'Laporan_Booking_' . $tgl_mulai . '_sd_' . $tgl_akhir . '.pdf';
$attachment = isset($_GET['download']) && $_GET['download'] == '1';
$dompdf->stream($filename, ['Attachment' => $attachment]);
exit;