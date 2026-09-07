<?php
session_start();
include "login/ceksession.php";
include "../koneksi/koneksi.php";

/* ── Filter & Search Parameters ──────────────────────────────────────────── */
$search = isset($_GET['search']) ? mysqli_real_escape_string($db, trim($_GET['search'])) : '';
$bulan  = isset($_GET['bulan'])  ? mysqli_real_escape_string($db, trim($_GET['bulan']))  : '';
$tahun  = isset($_GET['tahun'])  ? mysqli_real_escape_string($db, trim($_GET['tahun']))  : '';

$where_sk = "WHERE 1=1";
if ($search !== '') {
    $where_sk .= " AND (nomor_surat LIKE '%$search%' OR penerima LIKE '%$search%' OR perihal LIKE '%$search%' OR jenis_surat LIKE '%$search%' OR tempat_acara LIKE '%$search%' OR keterangan LIKE '%$search%')";
}
if (!empty($bulan)) {
    $where_sk .= " AND MONTH(tanggal_keluar) = '$bulan'";
}
if (!empty($tahun)) {
    $where_sk .= " AND YEAR(tanggal_keluar) = '$tahun'";
}

/* ── Pagination (Default 50 baris, opsi: 10, 15, 25, 50, 100) ───────────── */
$allowed_per_page = [10, 15, 25, 50, 100];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowed_per_page) ? (int)$_GET['per_page'] : 50;

$count_query = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_arsip_surat_keluar $where_sk");
$count_row   = mysqli_fetch_assoc($count_query);
$total_data  = (int)($count_row['total'] ?? 0);

$total_pages = max(1, (int)ceil($total_data / $per_page));
$page        = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset      = ($page - 1) * $per_page;

$sql_sk   = "SELECT * FROM tb_arsip_surat_keluar $where_sk ORDER BY tanggal_keluar DESC, No DESC LIMIT $per_page OFFSET $offset";
$query_sk = mysqli_query($db, $sql_sk);
$surat_keluars = [];
while ($r = mysqli_fetch_assoc($query_sk)) {
    $surat_keluars[] = $r;
}

if (!function_exists('getPageUrl')) {
    function getPageUrl($p) {
        $params = $_GET;
        $params['page'] = $p;
        return '?' . http_build_query($params);
    }
}

$bulan_list_sk = [
    '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
    '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
    '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
];

$row_tmin_sk = mysqli_fetch_assoc(mysqli_query($db, "SELECT YEAR(MIN(tanggal_keluar)) as tmin FROM tb_arsip_surat_keluar"));
$tmin_sk = !empty($row_tmin_sk['tmin']) ? (int)$row_tmin_sk['tmin'] : 2020;
$tmax_sk = (int)date('Y') + 1;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Arsip Surat Keluar - Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <!-- Modern Admin Design System -->
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">
  <link href="css/tabel_modern.css?v=2.3" rel="stylesheet">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebar menu -->
      <?php include("sidebarmenu.php"); ?>
      <!-- /Profile and Sidebar menu -->

      <!-- top navigation -->
      <?php include("header.php"); ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>

          <!-- Notification status -->
          <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
              <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom:14px; border-radius:8px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> Operasi berhasil dilakukan.
              </div>
            <?php elseif ($_GET['status'] === 'deleted'): ?>
              <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom:14px; border-radius:8px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-check-circle"></i> <strong>Dihapus!</strong> Data surat keluar berhasil dihapus.
              </div>
            <?php elseif ($_GET['status'] === 'error'): ?>
              <div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom:14px; border-radius:8px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> <?php echo htmlspecialchars($_GET['msg'] ?? 'Terjadi kesalahan.'); ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Card Modern Table Container -->
          <div class="card booking-card">
            <!-- Card Header (Judul & Subtitle di dalam Card Modern) -->
            <div class="card-header-table">
              <div>
                <h1>Arsip Surat Keluar</h1>
                <p>Kelola data arsip surat keluar, kegiatan, dan notulensi Pemerintah Desa Candirejo</p>
              </div>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="datasuratkeluar.php" id="filterFormSuratKeluar">
              <div class="filter-bar">
                <!-- Search Live Input -->
                <div class="search-wrap">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                  </svg>
                  <input
                    type="text"
                    name="search"
                    id="searchInput"
                    class="search-input"
                    placeholder="Cari nomor, penerima, perihal..."
                    value="<?php echo htmlspecialchars($search); ?>"
                    autocomplete="off"
                  >
                </div>

                <!-- Filter Bulan -->
                <select name="bulan" id="filterBulan" class="filter-select" style="min-width:130px;" onchange="document.getElementById('filterFormSuratKeluar').submit()">
                  <option value="">Semua Bulan</option>
                  <?php foreach ($bulan_list_sk as $val => $nama): ?>
                    <option value="<?php echo $val; ?>" <?php echo $bulan === $val ? 'selected' : ''; ?>><?php echo $nama; ?></option>
                  <?php endforeach; ?>
                </select>

                <!-- Filter Tahun -->
                <select name="tahun" id="filterTahun" class="filter-select" style="min-width:110px;" onchange="document.getElementById('filterFormSuratKeluar').submit()">
                  <option value="">Semua Tahun</option>
                  <?php for ($t = $tmin_sk; $t <= $tmax_sk; $t++): ?>
                    <option value="<?php echo $t; ?>" <?php echo $tahun == $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
                  <?php endfor; ?>
                </select>

                <!-- Rows per page (Default 50) -->
                <select name="per_page" id="perPage" class="filter-select" style="min-width:115px;" onchange="document.getElementById('filterFormSuratKeluar').submit()">
                  <option value="10" <?php echo $per_page === 10 ? 'selected' : ''; ?>>10 baris</option>
                  <option value="15" <?php echo $per_page === 15 ? 'selected' : ''; ?>>15 baris</option>
                  <option value="25" <?php echo $per_page === 25 ? 'selected' : ''; ?>>25 baris</option>
                  <option value="50" <?php echo $per_page === 50 ? 'selected' : ''; ?>>50 baris</option>
                  <option value="100" <?php echo $per_page === 100 ? 'selected' : ''; ?>>100 baris</option>
                </select>

                <!-- Submit filter icon -->
                <button type="submit" class="btn-filter" title="Terapkan Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18M7 9h10M11 14h2M13 19h-2"/>
                  </svg>
                </button>

                <?php if ($search !== '' || !empty($bulan) || !empty($tahun)): ?>
                <a href="datasuratkeluar.php" class="btn-reset-filter" title="Reset Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                  Reset
                </a>
                <?php endif; ?>

                <!-- Right Action Buttons -->
                <div class="filter-bar-right">
                  <a href="export/export_surat_keluar.php<?php echo !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>" class="btn-export-outline" title="Unduh PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                  </a>
                  <a href="export/exportExcel_surat_keluar.php<?php echo !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>" class="btn-export-outline" title="Unduh Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                  </a>
                  <a href="inputsuratkeluar.php" class="btn-tambah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Surat Keluar
                  </a>
                </div>
              </div>
            </form>

            <!-- Table Wrapper -->
            <div class="table-wrapper">
              <table class="tabel-booking" id="tabelSuratKeluar">
                <thead>
                  <tr>
                    <th>NOMOR &amp; TGL SURAT</th>
                    <th>PENERIMA &amp; JENIS</th>
                    <th>PERIHAL &amp; KEGIATAN</th>
                    <th>LAMPIRAN &amp; DOKUMENTASI</th>
                    <th>KETERANGAN</th>
                    <th style="text-align:right; padding-right:16px;">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($surat_keluars)): ?>
                  <tr class="empty-row">
                    <td colspan="6" style="text-align:center; padding: 36px 14px; color:#9ab5a8;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:6px; display:inline-block;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                      <div style="font-size:13px;">Tidak ada data surat keluar yang sesuai.</div>
                    </td>
                  </tr>
                  <?php else: foreach ($surat_keluars as $data):
                    $no_surat = !empty($data['nomor_surat']) ? htmlspecialchars($data['nomor_surat']) : '-';
                    $tgl_keluar = !empty($data['tanggal_keluar']) && $data['tanggal_keluar'] !== '0000-00-00' ? htmlspecialchars($data['tanggal_keluar']) : '-';
                    $penerima = !empty($data['penerima']) ? htmlspecialchars($data['penerima']) : '-';
                    $jenis_surat = ucfirst($data['jenis_surat'] ?? 'Keterangan');
                    $perihal = !empty($data['perihal']) ? htmlspecialchars($data['perihal']) : '-';
                    $tempat = !empty($data['tempat_acara']) ? htmlspecialchars($data['tempat_acara']) : '';
                    $tgl_kegiatan = !empty($data['tanggal_kegiatan']) && $data['tanggal_kegiatan'] !== '0000-00-00' ? htmlspecialchars($data['tanggal_kegiatan']) : '';
                    $jam_kegiatan = !empty($data['jam_kegiatan']) ? htmlspecialchars(substr($data['jam_kegiatan'], 0, 5)) : '';
                    $keterangan = !empty($data['keterangan']) ? htmlspecialchars($data['keterangan']) : '-';

                    // Parse attachments
                    $absensi_files = json_decode($data['lampiran_absensi'] ?? '[]', true);
                    if (!is_array($absensi_files)) $absensi_files = [];
                    $notulen_files = json_decode($data['lampiran_notulen'] ?? '[]', true);
                    if (!is_array($notulen_files)) $notulen_files = [];
                    $dok_files     = json_decode($data['dokumentasi_foto'] ?? '[]', true);
                    if (!is_array($dok_files)) $dok_files = [];
                  ?>
                  <tr>
                    <td>
                      <div style="font-weight: 600; color: #1e3a2f; font-size: 13px;"><?php echo $no_surat; ?></div>
                      <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">Tgl: <?php echo $tgl_keluar; ?></div>
                    </td>
                    <td>
                      <div style="font-weight: 500; font-size: 12.5px; color: #1e3a2f;"><?php echo $penerima; ?></div>
                      <span class="badge badge-blue" style="margin-top: 3px; font-size: 10px; padding: 2px 7px;"><?php echo $jenis_surat; ?></span>
                    </td>
                    <td>
                      <div style="font-size: 12.5px; font-weight: 500; color: #1e3a2f; max-width: 280px;"><?php echo $perihal; ?></div>
                      <?php if ($tempat || $tgl_kegiatan): ?>
                        <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">
                          <?php if ($tempat) echo '<i class="fa fa-map-marker"></i> ' . $tempat . ' '; ?>
                          <?php if ($tgl_kegiatan) echo ' • ' . $tgl_kegiatan . ($jam_kegiatan ? ' (' . $jam_kegiatan . ')' : ''); ?>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                        <?php if (!empty($data['file_surat'])): ?>
                          <a href="uploads/<?php echo htmlspecialchars(basename($data['file_surat'])); ?>" target="_blank" class="btn-icon btn-download" data-tooltip="Unduh Surat Keluar" title="Unduh Surat">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                          </a>
                        <?php endif; ?>
                        <?php if (!empty($absensi_files)): ?>
                          <a href="uploads/<?php echo rawurlencode($absensi_files[0]); ?>" target="_blank" class="badge badge-green" style="font-size: 10px; padding: 3px 7px; text-decoration: none;" title="Absensi">
                            Absensi
                          </a>
                        <?php endif; ?>
                        <?php if (!empty($notulen_files)): ?>
                          <a href="uploads/<?php echo rawurlencode($notulen_files[0]); ?>" target="_blank" class="badge badge-amber" style="font-size: 10px; padding: 3px 7px; text-decoration: none;" title="Notulen">
                            Notulen
                          </a>
                        <?php endif; ?>
                        <?php if (!empty($dok_files)): ?>
                          <div style="display: flex; align-items: center; gap: 2px;">
                            <?php foreach (array_slice($dok_files, 0, 2) as $df): ?>
                              <a href="uploads/<?php echo rawurlencode($df); ?>" target="_blank" title="Lihat Dokumentasi">
                                <img src="uploads/<?php echo rawurlencode($df); ?>" style="width: 20px; height: 20px; object-fit: cover; border-radius: 4px; border: 1px solid #d6e6dc;">
                              </a>
                            <?php endforeach; ?>
                            <?php if (count($dok_files) > 2): ?>
                              <span style="font-size: 10px; color: #7a9e8e; font-weight: 600;">+<?php echo count($dok_files) - 2; ?></span>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                        <?php if (empty($data['file_surat']) && empty($absensi_files) && empty($notulen_files) && empty($dok_files)): ?>
                          <span style="font-size: 11px; color: #9ab5a8;">-</span>
                        <?php endif; ?>
                      </div>
                    </td>
                    <td>
                      <div style="font-size: 12px; color: #2a4535; max-width: 180px;"><?php echo $keterangan; ?></div>
                    </td>
                    <td style="text-align:right; padding-right:16px;">
                      <div class="btn-action-wrap">
                        <!-- Detail -->
                        <a href="detail-suratkeluar.php?id=<?php echo urlencode($data['No']); ?>" class="btn-icon btn-detail" data-tooltip="Detail Surat" title="Detail Surat">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <!-- Edit -->
                        <a href="editsuratkeluar.php?id=<?php echo urlencode($data['No']); ?>" class="btn-icon btn-edit" data-tooltip="Edit Data" title="Edit Data">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                        </a>
                        <!-- Hapus -->
                        <button type="button" class="btn-icon btn-hapus" data-tooltip="Hapus Surat" title="Hapus Surat" onclick="konfirmasiHapus(<?php echo (int)$data['No']; ?>, '<?php echo htmlspecialchars($data['nomor_surat'] ?? '', ENT_QUOTES); ?>')">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>

            <!-- Footer & Pagination -->
            <div class="table-footer">
              <div>
                Menampilkan <strong><?php echo $total_data > 0 ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $per_page, $total_data); ?></strong> dari <strong><?php echo $total_data; ?></strong> data surat keluar
              </div>

              <?php if ($total_pages > 1): ?>
              <ul class="pagination">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                  <a class="page-link" href="<?php echo getPageUrl($page - 1); ?>" title="Sebelumnya">&laquo;</a>
                </li>
                <?php
                  $start_p = max(1, $page - 2);
                  $end_p   = min($total_pages, $page + 2);
                  if ($start_p > 1):
                ?>
                  <li class="page-item"><a class="page-link" href="<?php echo getPageUrl(1); ?>">1</a></li>
                  <?php if ($start_p > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start_p; $i <= $end_p; $i++): ?>
                  <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo getPageUrl($i); ?>"><?php echo $i; ?></a>
                  </li>
                <?php endfor; ?>

                <?php if ($end_p < $total_pages): ?>
                  <?php if ($end_p < $total_pages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                  <li class="page-item"><a class="page-link" href="<?php echo getPageUrl($total_pages); ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                  <a class="page-link" href="<?php echo getPageUrl($page + 1); ?>" title="Berikutnya">&raquo;</a>
                </li>
              </ul>
              <?php endif; ?>
            </div>

          </div><!-- /.card -->

        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-left text-muted" style="font-size:12px;">
          © <?php echo date("Y"); ?> Desa Wisata Candirejo Borobudur
        </div>
        <div class="pull-right text-muted" style="font-size:12px;">
          Supported by DRTPM KEMDIKBUDRISTEK
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- jQuery -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script type="text/javascript">
  function konfirmasiHapus(id, nomor) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Surat Keluar?',
        text: 'Surat keluar "' + (nomor || '') + '" akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c0392b',
        cancelButtonColor: '#6b8f7e',
        confirmButtonText: '<i class="fa fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'proses/proses_hapussuratkeluar.php?id=' + id;
        }
      });
    } else {
      if (confirm('Yakin ingin menghapus data surat keluar ini?')) {
        window.location.href = 'proses/proses_hapussuratkeluar.php?id=' + id;
      }
    }
  }

  $(document).ready(function() {
    function adjustContentHeight() {
      var windowH = $(window).height();
      var leftH = $('.left_col').outerHeight() || 0;
      var navH = $('.nav_menu').outerHeight() || $('.top_nav').outerHeight() || 50;
      var footerH = $('footer').outerHeight() || 40;
      var minH = Math.max(windowH, leftH) - navH - footerH;
      $('.right_col').css('min-height', minH > 200 ? minH : windowH);
    }

    // Instant client-side live filter while typing
    $('#searchInput').on('input', function() {
      var val = $(this).val().toLowerCase();
      $('#tabelSuratKeluar tbody tr').filter(function() {
        if ($(this).hasClass('empty-row')) return;
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
      });
    });

    adjustContentHeight();
    $(window).on('resize', adjustContentHeight);
    setTimeout(adjustContentHeight, 200);
  });
  </script>

</body>
</html>