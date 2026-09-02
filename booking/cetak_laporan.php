<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$bulan_id = ['','Januari','Februari','Maret','April','Mei','Juni',
             'Juli','Agustus','September','Oktober','November','Desember'];

function tglIndo($date) {
    global $bulan_id;
    if (!$date) return '-';
    $ts = strtotime($date);
    // FIX: index bulan langsung (1-12)
    return date('j', $ts) . ' ' . $bulan_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function namapaket($paket, $sub_makan_tour = '', $jenis_makanan = '', $opsi_cooking = '', $opsi_gamelan = '') {
    $map = [
        'meal_only'                  => 'Breakfast / Lunch / Dinner Only',
        'studi_banding'              => 'Studi Banding',
        'fun_game'                   => 'Paket Fun Game',
        'pelajar_live_in'            => 'Paket Pelajar – Live In Candirejo',
        'pelajar_field_trip_one_day' => 'Paket Pelajar – Field Trip One Day',
        'pelajar_field_trip_half_day'=> 'Paket Pelajar – Field Trip Half Day',
        'cycling_tour'               => 'Cycling Village Tour',
        'traditional_dance'          => 'Traditional Dance',
        'walking_tour'               => 'Walking Around Village',
        'homestay'                   => 'Homestay – Stay At Local House',
        'serenade'                   => 'Serenade At The Foot Of Menoreh Hill',
        'cooking_lesson'             => 'Cooking Lesson',
        'gamelan_class'              => 'Gamelan Class',
        'village_experience'         => 'Village Experience',
        'dokar_tour'                 => 'Dokar Village Tour',
        'inspection'                 => 'Inspection',
        'lainnya'                    => 'Lainnya',
    ];
    $nama = $map[$paket] ?? ucfirst(str_replace('_',' ',$paket));
    $sub  = '';
    if (in_array($paket, ['cycling_tour','dokar_tour','walking_tour']) && $sub_makan_tour)
        $sub = ($sub_makan_tour === 'with_lunch') ? 'With Lunch' : 'Without Lunch';
    elseif ($paket === 'meal_only' && $jenis_makanan)
        $sub = ucfirst($jenis_makanan);
    elseif ($paket === 'cooking_lesson' && $opsi_cooking)
        $sub = ($opsi_cooking === 'lesson_only') ? 'Lesson Only' : 'Lesson with Tour';
    elseif ($paket === 'gamelan_class' && $opsi_gamelan)
        $sub = ($opsi_gamelan === 'with_lunch') ? 'With Lunch' : 'Without Lunch';
    return $sub ? $nama . ' (' . $sub . ')' : $nama;
}

function labelStatusText($s) {
    $map = ['checkin'=>'Check-in','pending'=>'Pending','tidak_hadir'=>'Tidak Datang'];
    return $map[$s] ?? ucfirst($s);
}
function statusColor($s) {
    $map = ['checkin'=>'#1a6e3c','pending'=>'#8a5700','tidak_hadir'=>'#9b1c1c'];
    return $map[$s] ?? '#333';
}
function statusBg($s) {
    $map = ['checkin'=>'#dcfce7','pending'=>'#fef9c3','tidak_hadir'=>'#fee2e2'];
    return $map[$s] ?? '#f3f4f6';
}

/* ── Parameter ── */
$tgl_mulai = isset($_GET['tgl_mulai']) && $_GET['tgl_mulai'] !== '' ? $_GET['tgl_mulai'] : null;
$tgl_akhir = isset($_GET['tgl_akhir']) && $_GET['tgl_akhir'] !== '' ? $_GET['tgl_akhir'] : null;

if (!$tgl_mulai || !$tgl_akhir) {
    echo '<p style="padding:40px;font-family:sans-serif;color:red;">Parameter tanggal tidak lengkap.</p>';
    exit;
}

$tgl_mulai_esc = mysqli_real_escape_string($db, $tgl_mulai);
$tgl_akhir_esc = mysqli_real_escape_string($db, $tgl_akhir);

/* ── Stat ── */
$total_booking = 0; $total_checkin = 0; $total_tidak = 0; $total_pax = 0;
$q = mysqli_query($db,
    "SELECT status, COUNT(*) as jml, SUM(pax) as tp
     FROM tb_booking
     WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
     GROUP BY status");
while ($r = mysqli_fetch_assoc($q)) {
    $total_booking += $r['jml'];
    $total_pax     += $r['tp'];
    if ($r['status'] === 'checkin')     $total_checkin += $r['jml'];
    if ($r['status'] === 'tidak_hadir') $total_tidak   += $r['jml'];
}

/* ── Data detail ── */
$rows = [];
$q2 = mysqli_query($db,
    "SELECT id, tanggal_kunjungan, agen_wisata, nama,
            pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket,
            opsi_cooking_lesson, opsi_gamelan,
            pax, keterangan, status
     FROM tb_booking
     WHERE tanggal_kunjungan BETWEEN '$tgl_mulai_esc' AND '$tgl_akhir_esc'
     ORDER BY tanggal_kunjungan ASC, id ASC");
while ($r = mysqli_fetch_assoc($q2)) $rows[] = $r;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Cetak Laporan Booking – Desa Wisata Candirejo</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      font-size: 11.5px;
      color: #111;
      background: #fff;
      padding: 0;
    }

    /* ── PRINT PAGE ── */
    @page { size: A4 landscape; margin: 14mm 14mm 14mm 14mm; }
    @media print {
      .no-print { display: none !important; }
      body { padding: 0; }
    }

    /* ── Tombol cetak (hanya layar) ── */
    .print-bar {
      background: #1e3a2f; color: #fff;
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 24px; gap: 12px; flex-wrap: wrap;
    }
    .print-bar span { font-size: 14px; font-weight: 600; }
    .btn-print {
      background: #fff; color: #1e3a2f; border: none;
      padding: 9px 22px; border-radius: 7px;
      font-size: 13px; font-weight: 600; cursor: pointer;
      display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-print:hover { background: #e4f5ec; }
    .btn-back-link {
      color: #a8d5ba; text-decoration: none; font-size: 13px;
      display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-back-link:hover { color: #fff; }

    /* ── Dokumen ── */
    .doc { padding: 10px 0; }

    /* ── Header ── */
    .doc-header {
      text-align: center; padding-bottom: 12px;
      border-bottom: 2.5px solid #1e3a2f; margin-bottom: 14px;
    }
    .doc-header .org { font-size: 15px; font-weight: 700; color: #1e3a2f; letter-spacing: .02em; }
    .doc-header .sub { font-size: 11px; color: #444; margin-top: 2px; }
    .doc-header .judul {
      font-size: 14px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .06em; margin-top: 8px; color: #1e3a2f;
    }
    .doc-header .periode { font-size: 11px; color: #555; margin-top: 3px; }

    /* ── Ringkasan ── */
    .ringkasan {
      display: grid; grid-template-columns: repeat(4,1fr); gap: 10px;
      margin-bottom: 14px;
    }
    .rkard {
      border: 1px solid #d4e8dc; border-radius: 7px; padding: 9px 12px;
      text-align: center;
    }
    .rkard .rl { font-size: 10px; color: #5a8a6e; margin-bottom: 4px; }
    .rkard .rv { font-size: 20px; font-weight: 700; color: #1e3a2f; }
    .rkard .rv.green { color: #1a6e3c; }
    .rkard .rv.red   { color: #9b1c1c; }

    /* ── Tabel ── */
    .tbl-title {
      font-size: 12px; font-weight: 700; color: #1e3a2f;
      margin-bottom: 7px; text-transform: uppercase; letter-spacing: .04em;
    }
    table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
    thead th {
      background: #1e3a2f; color: #fff;
      padding: 7px 9px; text-align: left;
      font-size: 10px; font-weight: 700;
      letter-spacing: .04em; border: 1px solid #1e3a2f;
      white-space: nowrap;
    }
    thead th.center { text-align: center; }
    tbody tr:nth-child(even) { background: #f6fbf8; }
    tbody tr:nth-child(odd)  { background: #fff; }
    tbody td {
      padding: 6px 9px; border: 1px solid #d4e8dc;
      vertical-align: middle; color: #1a1a1a;
    }
    .td-no { text-align: center; width: 26px; color: #888; font-size: 10px; }
    .td-agen  { font-weight: 600; min-width: 110px; }
    .td-tamu  { min-width: 110px; }
    .td-tgl   { white-space: nowrap; min-width: 110px; }
    .td-paket { min-width: 160px; }
    .paket-utama { font-weight: 600; }
    .paket-sub   { font-size: 9.5px; color: #3d7a5a; margin-top: 1px; }
    .td-pax   { text-align: center; font-weight: 700; color: #1a6e3c; width: 40px; }
    .td-ket   { min-width: 120px; color: #555; font-size: 10px; }
    .td-status { text-align: center; white-space: nowrap; }
    .status-badge {
      display: inline-block; padding: 2px 9px; border-radius: 20px;
      font-size: 9.5px; font-weight: 700; letter-spacing: .03em;
    }

    /* ── Footer ── */
    .doc-footer {
      margin-top: 18px; padding-top: 10px;
      border-top: 1px solid #d4e8dc;
      display: flex; justify-content: space-between; align-items: flex-end;
      font-size: 10px; color: #777;
    }
    .ttd { text-align: center; }
    .ttd .ttd-nama { font-weight: 700; font-size: 11px; color: #1e3a2f; border-top: 1px solid #555; padding-top: 4px; margin-top: 50px; display: inline-block; min-width: 150px; }
  </style>
</head>
<body>

<!-- Toolbar (hanya layar) -->
<div class="print-bar no-print">
  <div style="display:flex;align-items:center;gap:20px;">
    <a href="booking_laporan.php?tgl_mulai=<?php echo urlencode($tgl_mulai); ?>&tgl_akhir=<?php echo urlencode($tgl_akhir); ?>" class="btn-back-link">
      ← Kembali
    </a>
    <span>Laporan Booking &nbsp;·&nbsp; <?php echo tglIndo($tgl_mulai); ?> – <?php echo tglIndo($tgl_akhir); ?></span>
  </div>
  <button class="btn-print" onclick="window.print()">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
      <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
      <rect x="6" y="13" width="12" height="9" rx="1"/>
    </svg>
    Cetak / Simpan PDF
  </button>
</div>

<!-- Dokumen -->
<div class="doc">

  <!-- Header -->
  <div class="doc-header">
    <div class="org">Desa Wisata Candirejo</div>
    <div class="sub">Jl. Candirejo, Kecamatan Borobudur, Kabupaten Magelang, Jawa Tengah</div>
    <div class="judul">Laporan Data Booking Pengunjung</div>
    <div class="periode">
      Periode: <?php echo tglIndo($tgl_mulai); ?> &ndash; <?php echo tglIndo($tgl_akhir); ?>
      &nbsp;&nbsp;|&nbsp;&nbsp;
      Dicetak: <?php echo tglIndo(date('Y-m-d')); ?>
    </div>
  </div>

  <!-- Ringkasan -->
  <div class="ringkasan">
    <div class="rkard">
      <div class="rl">Total Booking</div>
      <div class="rv"><?php echo $total_booking; ?></div>
    </div>
    <div class="rkard">
      <div class="rl">Check-in</div>
      <div class="rv green"><?php echo $total_checkin; ?></div>
    </div>
    <div class="rkard">
      <div class="rl">Tidak Datang</div>
      <div class="rv red"><?php echo $total_tidak; ?></div>
    </div>
    <div class="rkard">
      <div class="rl">Total Pax</div>
      <div class="rv"><?php echo $total_pax; ?></div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="tbl-title">Daftar Booking Pengunjung</div>
  <table>
    <thead>
      <tr>
        <th class="center">#</th>
        <th>Nama Travel (Agen)</th>
        <th>Nama Tamu</th>
        <th>Tanggal Kunjungan</th>
        <th>Paket</th>
        <th class="center">Pax</th>
        <th>Catatan / Keterangan</th>
        <th class="center">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
      <tr><td colspan="8" style="text-align:center;padding:20px;color:#888;">Tidak ada data</td></tr>
      <?php else: $no = 1; foreach ($rows as $b): ?>
      <?php
        $paket_full   = namapaket(
            $b['pilihan_paket_wisata'],
            $b['opsi_makan_tour']      ?? '',
            $b['jenis_makanan_paket']  ?? '',
            $b['opsi_cooking_lesson']  ?? '',
            $b['opsi_gamelan']         ?? ''
        );
        // Pisah nama utama dan sub dalam kurung
        preg_match('/^(.*?)(?:\s*\(([^)]*)\))?$/', $paket_full, $pm);
        $p_utama = trim($pm[1] ?? $paket_full);
        $p_sub   = trim($pm[2] ?? '');
        $s_label = labelStatusText($b['status']);
        $s_color = statusColor($b['status']);
        $s_bg    = statusBg($b['status']);
      ?>
      <tr>
        <td class="td-no"><?php echo $no++; ?></td>
        <td class="td-agen"><?php echo htmlspecialchars($b['agen_wisata'] ?: '-'); ?></td>
        <td class="td-tamu"><?php echo htmlspecialchars($b['nama']); ?></td>
        <td class="td-tgl"><?php echo tglIndo($b['tanggal_kunjungan']); ?></td>
        <td class="td-paket">
          <div class="paket-utama"><?php echo htmlspecialchars($p_utama); ?></div>
          <?php if ($p_sub): ?>
          <div class="paket-sub"><?php echo htmlspecialchars($p_sub); ?></div>
          <?php endif; ?>
        </td>
        <td class="td-pax"><?php echo (int)$b['pax']; ?></td>
        <td class="td-ket"><?php echo htmlspecialchars($b['keterangan'] ?: '-'); ?></td>
        <td class="td-status">
          <span class="status-badge" style="color:<?php echo $s_color; ?>;background:<?php echo $s_bg; ?>;">
            <?php echo $s_label; ?>
          </span>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>

  <!-- Footer -->
  <div class="doc-footer">
    <div>
      Laporan ini digenerate secara otomatis oleh Sistem Booking Desa Wisata Candirejo<br>
      PTIK INTER UNNES &copy; 2023
    </div>
    <div class="ttd">
      <div style="font-size:10px;color:#555;margin-bottom:2px;">Mengetahui,</div>
      <div style="font-size:10px;color:#555;">Pengelola Desa Wisata Candirejo</div>
      <div class="ttd-nama">( __________________________ )</div>
    </div>
  </div>

</div><!-- /.doc -->
</body>
</html>