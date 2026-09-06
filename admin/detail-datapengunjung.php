<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

function getFotoList($db, $id_pengunjung, $kolom_foto) {
    if (!empty($kolom_foto)) {
        $decoded = json_decode($kolom_foto, true);
        if (is_array($decoded) && count($decoded) > 0) return $decoded;
        if (!empty(trim($kolom_foto))) return [trim($kolom_foto)];
    }
    $pid = (int)$id_pengunjung;
    $res = mysqli_query($db, "SELECT nama_file FROM tb_foto_pengunjung WHERE id_pengunjung = $pid ORDER BY id_foto ASC");
    $list = [];
    while ($r = mysqli_fetch_assoc($res)) {
        if (!empty($r['nama_file'])) $list[] = $r['nama_file'];
    }
    return $list;
}

function formatPaket($raw) {
    $map = [
        'meal_only'                   => 'Breakfast/Lunch/Dinner Only',
        'studi_banding'               => 'Studi Banding',
        'fun_game'                    => 'Paket Fun Game',
        'pelajar_live_in'             => 'Paket Pelajar - Live In Candirejo',
        'pelajar_field_trip_one_day'  => 'Paket Pelajar – Field Trip One Day',
        'pelajar_field_trip_half_day' => 'Paket Pelajar – Field Trip Half Day',
        'cycling_tour'                => 'Cycling Village Tour',
        'traditional_dance'           => 'Traditional Dance',
        'walking_tour'                => 'Walking Around Village',
        'homestay'                    => 'Stay At Local House (Homestay)',
        'serenade'                    => 'Serenade Foot Of Menoreh Hill',
        'cooking_lesson'              => 'Cooking Lesson',
        'gamelan_class'               => 'Gamelan Class',
        'village_experience'          => 'Village Experience',
        'dokar_tour'                  => 'Dokar Village Tour',
        'inspection'                  => 'Inspection Tour',
        'lainnya'                     => 'Paket Lainnya'
    ];
    return $map[$raw] ?? ucwords(str_replace('_', ' ', (string)$raw));
}

function tgl_indo($tgl) {
    if (empty($tgl) || $tgl === '0000-00-00') return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tgl)));
    return (int)$pecahkan[2] . ' ' . ($bulan[(int)$pecahkan[1]] ?? '') . ' ' . $pecahkan[0];
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '';
if (empty($id)) {
    header("Location: datapengunjung.php");
    exit();
}

$sql   = "SELECT * FROM tb_data_pengunjung WHERE id='$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: datapengunjung.php");
    exit();
}

$foto_semua  = getFotoList($db, $data['id'], $data['foto'] ?? null);
$foto_avatar = !empty($foto_semua) ? $foto_semua[0] : null;

$is_manca = (strtolower($data['jenis_wisatawan'] ?? '') === 'mancanegara');
$asal     = $is_manca ? ($data['negara'] ?? '-') : ($data['kota'] ?? '-');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Pengunjung - <?php echo htmlspecialchars($data['nama']); ?> - Arsip Candirejo</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendors -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">
  <link href="css/detail_modern.css?v=1.0" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="detail-page-wrapper">

          <!-- Card Header Page Title -->
          <div class="card page-title-card">
            <div class="detail-header-bar">
              <div class="detail-header-left">
                <h1>Detail Data Pengunjung</h1>
                <p>Informasi registrasi kunjungan wisatawan Desa Candirejo</p>
              </div>
              <div class="detail-header-actions">
                <a href="datapengunjung.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editpengunjung.php?id=<?php echo urlencode($data['id']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Data
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['nama']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                  Kode: <strong><?php echo htmlspecialchars($data['kode_data'] ?? '-'); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  Kunjungan: <?php echo tgl_indo($data['tanggal_kunjungan']); ?>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  Peserta: <strong><?php echo (int)$data['pax']; ?> Pax</strong>
                </span>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <?php if ($is_manca): ?>
                <span class="badge-pill badge-pill-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                  Mancanegara (<?php echo htmlspecialchars($asal); ?>)
                </span>
              <?php else: ?>
                <span class="badge-pill badge-pill-green">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  Domestik (<?php echo htmlspecialchars($asal); ?>)
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Grid Layout -->
          <div class="detail-grid">

            <!-- Left Column: Informasi Rinci Pengunjung & Wisata -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  Rincian Data Pengunjung &amp; Paket
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <tr>
                      <td class="col-label">Kode Data</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['kode_data'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Tanggal Kunjungan</td>
                      <td class="col-value"><?php echo tgl_indo($data['tanggal_kunjungan']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nama Pengunjung / Rombongan</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Pilihan Paket Wisata</td>
                      <td class="col-value"><?php echo formatPaket($data['pilihan_paket_wisata'] ?? ''); ?></td>
                    </tr>
                    <?php if (!empty($data['opsi_makan_tour'])): ?>
                      <tr>
                        <td class="col-label">Opsi Makan Tour</td>
                        <td class="col-value"><?php echo ucwords(str_replace('_', ' ', $data['opsi_makan_tour'])); ?></td>
                      </tr>
                    <?php endif; ?>
                    <?php if (!empty($data['jenis_makanan_paket'])): ?>
                      <tr>
                        <td class="col-label">Jenis Makanan</td>
                        <td class="col-value"><?php echo ucfirst($data['jenis_makanan_paket']); ?></td>
                      </tr>
                    <?php endif; ?>
                    <?php if (!empty($data['opsi_cooking_lesson'])): ?>
                      <tr>
                        <td class="col-label">Opsi Cooking Lesson</td>
                        <td class="col-value"><?php echo ucwords(str_replace('_', ' ', $data['opsi_cooking_lesson'])); ?></td>
                      </tr>
                    <?php endif; ?>
                    <?php if (!empty($data['opsi_gamelan'])): ?>
                      <tr>
                        <td class="col-label">Opsi Gamelan Class</td>
                        <td class="col-value"><?php echo ucwords(str_replace('_', ' ', $data['opsi_gamelan'])); ?></td>
                      </tr>
                    <?php endif; ?>
                    <tr>
                      <td class="col-label">Kategori Wisatawan</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['jenis_wisatawan'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Asal Daerah / Negara</td>
                      <td class="col-value"><?php echo htmlspecialchars($asal); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Jumlah Wisatawan</td>
                      <td class="col-value"><?php echo (int)$data['pax']; ?> Orang (Pax)</td>
                    </tr>
                    <tr>
                      <td class="col-label">Agen Wisata</td>
                      <td class="col-value"><?php echo !empty($data['agen_wisata']) ? htmlspecialchars($data['agen_wisata']) : '-'; ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Driver / Agent Guide</td>
                      <td class="col-value"><?php echo !empty($data['driver_agent_guide']) ? htmlspecialchars($data['driver_agent_guide']) : '-'; ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Local Guide (Desa)</td>
                      <td class="col-value"><?php echo !empty($data['local_guide']) ? htmlspecialchars($data['local_guide']) : '-'; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Galeri Foto Kunjungan -->
            <div>
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Dokumentasi Foto Kunjungan
                  </h3>
                  <?php if (!empty($foto_semua)): ?>
                    <span class="badge-pill badge-pill-gray"><?php echo count($foto_semua); ?> Foto</span>
                  <?php endif; ?>
                </div>
                <div class="detail-card-body">
                  <?php if (!empty($foto_semua)): ?>
                    <!-- Foto Utama -->
                    <?php if ($foto_avatar): ?>
                      <div style="margin-bottom:14px;border-radius:10px;overflow:hidden;border:1.5px solid #d6e6dc;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <a href="uploads/pengunjung/<?php echo htmlspecialchars($foto_avatar); ?>" target="_blank" title="Buka foto utama">
                          <img src="uploads/pengunjung/<?php echo htmlspecialchars($foto_avatar); ?>" alt="Foto Utama" style="width:100%;max-height:260px;object-fit:cover;display:block;">
                        </a>
                      </div>
                    <?php endif; ?>

                    <!-- Foto Lainnya (Jika ada) -->
                    <?php if (count($foto_semua) > 1): ?>
                      <div style="font-size:12px;font-weight:600;color:#5a7d6d;margin-bottom:8px;">Foto Tambahan:</div>
                      <div class="gallery-grid">
                        <?php foreach (array_slice($foto_semua, 1) as $idx => $f): ?>
                          <a href="uploads/pengunjung/<?php echo htmlspecialchars($f); ?>" target="_blank" class="gallery-thumb-item" title="Lihat Foto <?php echo $idx + 2; ?>">
                            <img src="uploads/pengunjung/<?php echo htmlspecialchars($f); ?>" alt="Foto <?php echo $idx + 2; ?>">
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div style="text-align:center;padding:28px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-camera" style="font-size:32px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Tidak ada foto dokumentasi kunjungan.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>

      <footer>
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>