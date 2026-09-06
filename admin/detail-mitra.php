<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($db, trim($_GET['id'])) : '';
if (empty($id)) {
    header("Location: datamitra.php");
    exit();
}

$sql   = "SELECT * FROM tb_data_mitra WHERE id='$id'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

if (!$data) {
    header("Location: datamitra.php");
    exit();
}

// Phone & Legalitas fallback
$no_telepon = !empty($data['nomor_telp']) ? $data['nomor_telp'] : (!empty($data['no_telp']) ? $data['no_telp'] : '');
$legalitas_label = !empty($data['legalitas_usaha']) ? trim($data['legalitas_usaha']) : '-';

// Parse foto kegiatan / foto mitra
$raw_foto = !empty($data['foto_kegiatan']) ? $data['foto_kegiatan'] : (!empty($data['foto_mitra']) ? $data['foto_mitra'] : '');
$fotos = [];
if (!empty($raw_foto)) {
    $decoded = json_decode($raw_foto, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded) > 0) {
        $fotos = $decoded;
    } else {
        $fotos = array_filter(array_map('trim', explode(',', $raw_foto)));
    }
}

// Resolve web URL for each foto
$foto_urls = [];
foreach ($fotos as $f) {
    $clean_f = preg_replace('/^(\.\.\/|\.\/)+/', '', $f);
    $f_name = basename($clean_f);
    if (file_exists(__DIR__ . '/' . $clean_f)) {
        $foto_urls[] = $clean_f;
    } elseif (file_exists(__DIR__ . '/uploads/foto_kegiatan/' . $f_name)) {
        $foto_urls[] = 'uploads/foto_kegiatan/' . $f_name;
    } elseif (file_exists(__DIR__ . '/uploads/' . $f_name)) {
        $foto_urls[] = 'uploads/' . $f_name;
    } elseif (file_exists(__DIR__ . '/uploads/mitra/' . $f_name)) {
        $foto_urls[] = 'uploads/mitra/' . $f_name;
    } else {
        $foto_urls[] = (strpos($clean_f, 'uploads/') === 0) ? $clean_f : 'uploads/foto_kegiatan/' . $clean_f;
    }
}
$foto_utama = !empty($foto_urls) ? $foto_urls[0] : null;

// Parse file bukti legalitas
$bukti_legalitas_raw = !empty($data['bukti_legalitas']) ? trim($data['bukti_legalitas']) : '';
$legalitas_file_url = '';
$legalitas_file_name = '';
$legalitas_file_size = '';

if (!empty($bukti_legalitas_raw)) {
    $clean_rel = preg_replace('/^(\.\.\/|\.\/)+/', '', $bukti_legalitas_raw);
    $legalitas_file_name = basename($clean_rel);

    if (file_exists(__DIR__ . '/' . $clean_rel)) {
        $legalitas_file_url = $clean_rel;
        $size_b = filesize(__DIR__ . '/' . $clean_rel);
        $legalitas_file_size = round($size_b / 1024, 1) . ' KB';
    } elseif (file_exists(__DIR__ . '/uploads/' . $legalitas_file_name)) {
        $legalitas_file_url = 'uploads/' . $legalitas_file_name;
        $size_b = filesize(__DIR__ . '/uploads/' . $legalitas_file_name);
        $legalitas_file_size = round($size_b / 1024, 1) . ' KB';
    } elseif (file_exists(__DIR__ . '/../' . $clean_rel)) {
        $legalitas_file_url = '../' . $clean_rel;
        $size_b = filesize(__DIR__ . '/../' . $clean_rel);
        $legalitas_file_size = round($size_b / 1024, 1) . ' KB';
    } else {
        $legalitas_file_url = (strpos($clean_rel, 'uploads/') === 0) ? $clean_rel : 'uploads/' . $clean_rel;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Mitra - <?php echo htmlspecialchars($data['nama_usaha']); ?> - Arsip Candirejo</title>

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
  <link href="css/detail_modern.css?v=1.1" rel="stylesheet">
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
                <h1>Detail Data Mitra</h1>
                <p>Profil kemitraan pelaku usaha dan UMKM pendukung wisata Desa Candirejo</p>
              </div>
              <div class="detail-header-actions">
                <a href="datamitra.php" class="btn-back-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                  Kembali ke Data
                </a>
                <a href="editmitra.php?id=<?php echo urlencode($data['id']); ?>" class="btn-edit-detail">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                  Edit Mitra
                </a>
              </div>
            </div>
          </div>

          <!-- Summary Card -->
          <div class="detail-summary-card">
            <div class="summary-main-info">
              <h2><?php echo htmlspecialchars($data['nama_usaha']); ?></h2>
              <div class="summary-meta-row">
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  Pemilik: <strong><?php echo htmlspecialchars($data['nama_pemilik']); ?></strong>
                </span>
                <?php if (!empty($data['kode_data'])): ?>
                  <span class="summary-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    Kode: <strong><?php echo htmlspecialchars($data['kode_data']); ?></strong>
                  </span>
                <?php endif; ?>
                <?php if (!empty($no_telepon)): ?>
                  <span class="summary-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <?php echo htmlspecialchars($no_telepon); ?>
                  </span>
                <?php endif; ?>
                <span class="summary-meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Legalitas: <strong><?php echo htmlspecialchars($legalitas_label); ?></strong>
                </span>
              </div>
            </div>
            <div>
              <span class="badge-pill badge-pill-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <?php echo htmlspecialchars($data['kategori_usaha'] ?? 'Mitra Usaha'); ?>
              </span>
            </div>
          </div>

          <!-- Grid Layout -->
          <div class="detail-grid">

            <!-- Left Column: Informasi Rinci Mitra -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3 class="detail-card-title">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                  Informasi Profil Usaha Mitra
                </h3>
              </div>
              <div class="detail-card-body">
                <table class="info-list-table">
                  <tbody>
                    <?php if (!empty($data['kode_data'])): ?>
                    <tr>
                      <td class="col-label">Kode Mitra</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['kode_data']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td class="col-label">Nama Usaha / Brand</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama_usaha']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Nama Pemilik / Pengelola</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['nama_pemilik']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Kategori Usaha</td>
                      <td class="col-value"><?php echo htmlspecialchars($data['kategori_usaha']); ?></td>
                    </tr>
                    <tr>
                      <td class="col-label">Status Legalitas Usaha</td>
                      <td class="col-value">
                        <?php if ($legalitas_label !== '-'): ?>
                          <span class="badge-pill badge-pill-blue" style="font-size:12px;font-weight:600;">
                            <i class="fa fa-certificate"></i> <?php echo htmlspecialchars($legalitas_label); ?>
                          </span>
                        <?php else: ?>
                          <span style="color:#7a9e8e;">Belum Ada / Non-Formal (-)</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-label">Nomor Telepon / WhatsApp</td>
                      <td class="col-value">
                        <?php if (!empty($no_telepon)): ?>
                          <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $no_telepon); ?>" target="_blank" style="color:#2e7d4f;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                            <i class="fa fa-whatsapp" style="font-size:15px;color:#25d366;"></i> <?php echo htmlspecialchars($no_telepon); ?>
                          </a>
                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-label">Alamat Usaha / Lokasi</td>
                      <td class="col-value"><?php echo !empty($data['alamat']) ? htmlspecialchars($data['alamat']) : '-'; ?></td>
                    </tr>
                    <?php if (!empty($data['deskripsi_usaha'])): ?>
                    <tr>
                      <td class="col-label">Deskripsi &amp; Layanan Usaha</td>
                      <td class="col-value">
                        <div style="background:#f8faf9;border:1px solid #e2ede6;border-radius:8px;padding:12px 14px;font-weight:500;color:#2a4535;line-height:1.5;">
                          <?php echo nl2br(htmlspecialchars($data['deskripsi_usaha'])); ?>
                        </div>
                      </td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: 1. Foto Dokumentasi & 2. Dokumen Legalitas -->
            <div>
              <!-- 1. Dokumentasi Foto Usaha -->
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Dokumentasi Foto Usaha
                  </h3>
                  <?php if (!empty($foto_urls)): ?>
                    <span class="badge-pill badge-pill-gray"><?php echo count($foto_urls); ?> Foto</span>
                  <?php endif; ?>
                </div>
                <div class="detail-card-body">
                  <?php if (!empty($foto_urls)): ?>
                    <?php if ($foto_utama): ?>
                      <div style="margin-bottom:14px;border-radius:10px;overflow:hidden;border:1.5px solid #d6e6dc;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <a href="<?php echo htmlspecialchars($foto_utama); ?>" target="_blank" title="Lihat foto utama">
                          <img src="<?php echo htmlspecialchars($foto_utama); ?>" alt="Foto Usaha" style="width:100%;max-height:240px;object-fit:cover;display:block;" onerror="this.src='../img/default-avatar.png';">
                        </a>
                      </div>
                    <?php endif; ?>

                    <?php if (count($foto_urls) > 1): ?>
                      <div style="font-size:12px;font-weight:600;color:#5a7d6d;margin-bottom:8px;">Foto Tambahan:</div>
                      <div class="gallery-grid">
                        <?php foreach (array_slice($foto_urls, 1) as $idx => $f): ?>
                          <a href="<?php echo htmlspecialchars($f); ?>" target="_blank" class="gallery-thumb-item" title="Lihat Foto <?php echo $idx + 2; ?>">
                            <img src="<?php echo htmlspecialchars($f); ?>" alt="Foto <?php echo $idx + 2; ?>" onerror="this.src='../img/default-avatar.png';">
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>

                  <?php else: ?>
                    <div style="text-align:center;padding:26px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-camera" style="font-size:30px;margin-bottom:8px;display:block;color:#b5d5c0;"></i>
                      <div style="font-size:12.5px;">Tidak ada foto kegiatan usaha yang diunggah.</div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- 2. Dokumen Legalitas Usaha -->
              <div class="detail-card" style="margin-top:20px;">
                <div class="detail-card-header">
                  <h3 class="detail-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Dokumen Legalitas Usaha
                  </h3>
                  <?php if (!empty($legalitas_file_url)): ?>
                    <span class="badge-pill badge-pill-green">PDF Tersedia</span>
                  <?php else: ?>
                    <span class="badge-pill badge-pill-gray">Belum Diupload</span>
                  <?php endif; ?>
                </div>
                <div class="detail-card-body">
                  <div style="margin-bottom:12px;font-size:12.5px;color:#4a6a57;background:#f8faf9;border:1px solid #e2ede6;border-radius:8px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;">
                    <span style="color:#7a9e8e;font-weight:500;">Status Legalitas:</span>
                    <strong style="color:#1e3a2f;"><?php echo htmlspecialchars($legalitas_label); ?></strong>
                  </div>

                  <?php if (!empty($legalitas_file_url)): ?>
                    <div class="file-card-box">
                      <div class="file-card-info">
                        <div class="file-badge-icon file-badge-pdf">
                          <i class="fa fa-file-pdf-o"></i>
                        </div>
                        <div class="file-meta-text">
                          <div class="file-name-label" title="<?php echo htmlspecialchars($legalitas_file_name); ?>">
                            <?php echo htmlspecialchars($legalitas_file_name); ?>
                          </div>
                          <div class="file-sub-label">
                            Berkas Bukti Legalitas Usaha<?php echo !empty($legalitas_file_size) ? ' • ' . $legalitas_file_size : ''; ?>
                          </div>
                        </div>
                      </div>
                      <a href="<?php echo htmlspecialchars($legalitas_file_url); ?>" target="_blank" class="btn-file-action">
                        <i class="fa fa-external-link"></i> Buka Dokumen
                      </a>
                    </div>
                  <?php else: ?>
                    <div style="text-align:center;padding:26px 14px;color:#9ab5a8;background:#fafcfb;border-radius:8px;border:1px dashed #d6e6dc;">
                      <i class="fa fa-file-pdf-o" style="font-size:30px;margin-bottom:8px;display:block;color:#b5d5c0;"></i>
                      <div style="font-size:12.5px;">Tidak ada file dokumen legalitas yang dilampirkan.</div>
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