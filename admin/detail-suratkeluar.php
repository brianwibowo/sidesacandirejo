<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '';
if (empty($id)) {
    header("Location: datasuratkeluar.php");
    exit();
}

$sql   = "SELECT * FROM tb_arsip_surat_keluar WHERE No = '$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: datasuratkeluar.php");
    exit();
}

// Parse lampiran arrays
$absensi_files     = json_decode($data['lampiran_absensi']  ?? '[]', true);
if (!is_array($absensi_files))     $absensi_files     = [];
$notulen_files     = json_decode($data['lampiran_notulen']  ?? '[]', true);
if (!is_array($notulen_files))     $notulen_files     = [];
$dokumentasi_files = json_decode($data['dokumentasi_foto']  ?? '[]', true);
if (!is_array($dokumentasi_files)) $dokumentasi_files = [];

// Format tanggal
function tgl_indo($tgl) {
    if (empty($tgl) || $tgl === '0000-00-00') return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tgl)));
    return (int)$pecahkan[2] . ' ' . ($bulan[(int)$pecahkan[1]] ?? '') . ' ' . $pecahkan[0];
}

$jenis_surat = strtolower($data['jenis_surat'] ?? 'keterangan');
$is_undangan = ($jenis_surat === 'undangan');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Surat Keluar #<?php echo htmlspecialchars($data['nomor_surat']); ?> - Arsip Candirejo</title>

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
                <h1>Detail Surat Keluar</h1>
                <p>Informasi lengkap arsip surat keluar dan lampiran dokumen kegiatan</p>
              </div>
              <div class="detail-header-actions">
                <a href="datasuratkeluar.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editsuratkeluar.php?id=<?php echo urlencode($data['No']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Surat
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['perihal']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                  No. Urut: <strong>#<?php echo htmlspecialchars($data['No']); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  No. Surat: <strong><?php echo htmlspecialchars($data['nomor_surat']); ?></strong>
                </span>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  Tanggal Keluar: <?php echo tgl_indo($data['tanggal_keluar']); ?>
                </span>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
              <?php if ($is_undangan): ?>
                <span class="badge-pill badge-pill-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                  Surat Undangan
                </span>
              <?php else: ?>
                <span class="badge-pill badge-pill-blue">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Surat Keterangan
                </span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Grid Layout -->
          <div class="detail-grid">

            <!-- Left Column: Informasi Lengkap Surat -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Rincian Informasi Surat
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <tr>
                      <td class="col-label">Nomor Urut Arsip</td>
                      <td class="col-value">#<?php echo htmlspecialchars($data['No']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nomor Surat</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nomor_surat']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Tanggal Keluar</td>
                      <td class="col-value"><?php echo tgl_indo($data['tanggal_keluar']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Tujuan / Penerima</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['penerima']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Jenis Surat</td>
                      <td class="col-value"><?php echo ucfirst($jenis_surat); ?></td>
                    </tr>
                    <?php if ($is_undangan): ?>
                      <tr>
                        <td class="col-label">Tempat Acara</td>
                        <td class="col-value"><?php echo !empty($data['tempat_acara']) ? htmlspecialchars($data['tempat_acara']) : '-'; ?></td>
                      </tr>
                      <tr>
                        <td class="col-label">Tanggal Kegiatan</td>
                        <td class="col-value"><?php echo tgl_indo($data['tanggal_kegiatan']); ?></td>
                      </tr>
                    <?php endif; ?>
                    <tr>
                      <td class="col-label">Perihal</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['perihal']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Keterangan Tambahan</td>
                      <td class="col-value">
                        <?php if (!empty($data['keterangan'])): ?>
                          <div style="background:#f8faf9;border:1px solid #e2ede6;border-radius:8px;padding:10px 14px;font-weight:500;color:#2a4535;line-height:1.5;">
                            <?php echo nl2br(htmlspecialchars($data['keterangan'])); ?>
                          </div>
                        <?php else: ?>
                          <span style="color:#9ab5a8;">- Tidak ada keterangan tambahan -</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Lampiran & Berkas -->
            <div>
              <!-- Berkas Utama -->
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    File Berkas Surat
                  </h3>
                </div>
                <div class="detail-card-body">
                  <?php if (!empty($data['file_surat']) && file_exists(__DIR__ . '/uploads/' . $data['file_surat'])): ?>
                    <div class="file-card-box">
                      <div class="file-card-info">
                        <div class="file-badge-icon file-badge-pdf">
                          <i class="fa fa-file-pdf-o"></i>
                        </div>
                        <div class="file-meta-text">
                          <div class="file-name-label" title="<?php echo htmlspecialchars($data['file_surat']); ?>">
                            <?php echo htmlspecialchars($data['file_surat']); ?>
                          </div>
                          <div class="file-sub-label">Berkas Surat Resmi</div>
                        </div>
                      </div>
                      <a href="uploads/<?php echo htmlspecialchars($data['file_surat']); ?>" target="_blank" class="btn-file-action">
                        <i class="fa fa-external-link"></i> Buka File
                      </a>
                    </div>
                  <?php else: ?>
                    <div style="text-align:center;padding:24px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-file-o" style="font-size:28px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Tidak ada berkas file surat yang diunggah.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Lampiran Absensi & Notulen (Khusus Undangan) -->
              <?php if ($is_undangan): ?>
                <div class="detail-card">
                  <div class="detail-card-header">
                    <h3 class="detail-card-title">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                      Lampiran Absensi &amp; Notulen
                    </h3>
                  </div>
                  <div class="detail-card-body">
                    <!-- Absensi -->
                    <div style="margin-bottom:14px;">
                      <div style="font-size:12px;font-weight:600;color:#4a6a57;margin-bottom:8px;">Berkas Absensi:</div>
                      <?php if (!empty($absensi_files)): ?>
                        <?php foreach ($absensi_files as $af): ?>
                          <div class="file-card-box">
                            <div class="file-card-info">
                              <div class="file-badge-icon file-badge-pdf"><i class="fa fa-file-text-o"></i></div>
                              <div class="file-meta-text">
                                <div class="file-name-label"><?php echo htmlspecialchars($af); ?></div>
                                <div class="file-sub-label">Lampiran Absensi</div>
                              </div>
                            </div>
                            <a href="uploads/<?php echo htmlspecialchars($af); ?>" target="_blank" class="btn-file-action">Unduh</a>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div style="font-size:12px;color:#9ab5a8;font-style:italic;">Tidak ada lampiran absensi.</div>
                      <?php endif; ?>
                    </div>

                    <!-- Notulen -->
                    <div>
                      <div style="font-size:12px;font-weight:600;color:#4a6a57;margin-bottom:8px;">Berkas Notulen:</div>
                      <?php if (!empty($notulen_files)): ?>
                        <?php foreach ($notulen_files as $nf): ?>
                          <div class="file-card-box">
                            <div class="file-card-info">
                              <div class="file-badge-icon file-badge-pdf"><i class="fa fa-file-text-o"></i></div>
                              <div class="file-meta-text">
                                <div class="file-name-label"><?php echo htmlspecialchars($nf); ?></div>
                                <div class="file-sub-label">Lampiran Notulen</div>
                              </div>
                            </div>
                            <a href="uploads/<?php echo htmlspecialchars($nf); ?>" target="_blank" class="btn-file-action">Unduh</a>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div style="font-size:12px;color:#9ab5a8;font-style:italic;">Tidak ada lampiran notulen.</div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Dokumentasi Foto -->
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Dokumentasi Foto
                  </h3>
                  <?php if (!empty($dokumentasi_files)): ?>
                    <span class="badge-pill badge-pill-gray"><?php echo count($dokumentasi_files); ?> Foto</span>
                  <?php endif; ?>
                </div>
                <div class="detail-card-body">
                  <?php if (!empty($dokumentasi_files)): ?>
                    <div class="gallery-grid">
                      <?php foreach ($dokumentasi_files as $idx => $df): 
                        $fpath = 'uploads/' . $df;
                      ?>
                        <a href="<?php echo htmlspecialchars($fpath); ?>" target="_blank" class="gallery-thumb-item" title="Lihat Foto <?php echo $idx + 1; ?>">
                          <img src="<?php echo htmlspecialchars($fpath); ?>" alt="Dokumentasi <?php echo $idx + 1; ?>" onerror="this.src='../img/default-avatar.png';">
                        </a>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div style="text-align:center;padding:24px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-picture-o" style="font-size:28px;margin-bottom:8px;display:block;"></i>
                      <div style="font-size:12.5px;">Tidak ada dokumentasi foto untuk surat ini.</div>
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