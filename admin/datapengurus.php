<?php
session_start();
include "login/ceksession.php";
include "../koneksi/koneksi.php";

/* ── Filter & Search Parameters ──────────────────────────────────────────── */
$search  = isset($_GET['search'])  ? mysqli_real_escape_string($db, trim($_GET['search']))  : '';
$jabatan = isset($_GET['jabatan']) ? mysqli_real_escape_string($db, trim($_GET['jabatan'])) : '';

$where_pengurus = "WHERE 1=1";
if ($search !== '') {
    $where_pengurus .= " AND (nama LIKE '%$search%' OR no_ktp LIKE '%$search%' OR jabatan LIKE '%$search%' OR periode LIKE '%$search%' OR alamat LIKE '%$search%' OR no_telp LIKE '%$search%')";
}
if ($jabatan !== '') {
    $where_pengurus .= " AND jabatan = '$jabatan'";
}

/* ── Ambil list distinct jabatan untuk filter ────────────────────────────── */
$q_jab = mysqli_query($db, "SELECT DISTINCT jabatan FROM tb_data_pengurus WHERE jabatan != '' ORDER BY jabatan ASC");
$jabatan_list = [];
while ($rj = mysqli_fetch_assoc($q_jab)) {
    $jabatan_list[] = $rj['jabatan'];
}

/* ── Pagination (Default 50 baris, opsi: 10, 15, 25, 50, 100) ───────────── */
$allowed_per_page = [10, 15, 25, 50, 100];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowed_per_page) ? (int)$_GET['per_page'] : 50;

$count_query = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_data_pengurus $where_pengurus");
$count_row   = mysqli_fetch_assoc($count_query);
$total_data  = (int)($count_row['total'] ?? 0);

$total_pages = max(1, (int)ceil($total_data / $per_page));
$page        = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset      = ($page - 1) * $per_page;

$sql_pengurus   = "SELECT * FROM tb_data_pengurus $where_pengurus ORDER BY id DESC LIMIT $per_page OFFSET $offset";
$query_pengurus = mysqli_query($db, $sql_pengurus);
$penguruses = [];
while ($r = mysqli_fetch_assoc($query_pengurus)) {
    $penguruses[] = $r;
}

if (!function_exists('getPageUrl')) {
    function getPageUrl($p) {
        $params = $_GET;
        $params['page'] = $p;
        return '?' . http_build_query($params);
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
  <title>Data Pengurus - Desa Candirejo Borobudur</title>

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

          <!-- Card Modern Table Container -->
          <div class="card booking-card">
            <!-- Card Header (Judul & Subtitle di dalam Card Modern) -->
            <div class="card-header-table">
              <div>
                <h1>Data Pengurus Desa</h1>
                <p>Kelola struktur organisasi dan pengurus Desa Wisata Candirejo</p>
              </div>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="datapengurus.php" id="filterFormPengurus">
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
                    placeholder="Cari nama, KTP, jabatan..."
                    value="<?php echo htmlspecialchars($search); ?>"
                    autocomplete="off"
                  >
                </div>

                <!-- Filter Jabatan -->
                <?php if (!empty($jabatan_list)): ?>
                <select name="jabatan" id="filterJabatan" class="filter-select" style="min-width:140px;" onchange="document.getElementById('filterFormPengurus').submit()">
                  <option value="">Semua Jabatan</option>
                  <?php foreach ($jabatan_list as $jl): ?>
                    <option value="<?php echo htmlspecialchars($jl); ?>" <?php echo $jabatan === $jl ? 'selected' : ''; ?>><?php echo htmlspecialchars($jl); ?></option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <!-- Rows per page (Default 50) -->
                <select name="per_page" id="perPage" class="filter-select" style="min-width:115px;" onchange="document.getElementById('filterFormPengurus').submit()">
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

                <?php if ($search !== '' || $jabatan !== ''): ?>
                <a href="datapengurus.php" class="btn-reset-filter" title="Reset Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                  Reset
                </a>
                <?php endif; ?>

                <!-- Right Action Buttons -->
                <div class="filter-bar-right">
                  <a href="export/export_data_pengurus.php<?php echo !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>" class="btn-export-outline" title="Unduh PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                  </a>
                  <a href="export/exportExcel_data_pengurus.php<?php echo !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>" class="btn-export-outline" title="Unduh Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                  </a>
                  <a href="inputdatapengurus.php" class="btn-tambah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Pengurus
                  </a>
                </div>
              </div>
            </form>

            <!-- Table Wrapper -->
            <div class="table-wrapper">
              <table class="tabel-booking" id="tabelPengurus">
                <thead>
                  <tr>
                    <th>PENGURUS</th>
                    <th>JABATAN &amp; PERIODE</th>
                    <th>KONTAK &amp; ALAMAT</th>
                    <th>DOKUMEN KTP</th>
                    <th style="text-align:right; padding-right:16px;">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($penguruses)): ?>
                  <tr class="empty-row">
                    <td colspan="5" style="text-align:center; padding: 36px 14px; color:#9ab5a8;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:6px; display:inline-block;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                      <div style="font-size:13px;">Tidak ada data pengurus yang sesuai.</div>
                    </td>
                  </tr>
                  <?php else: foreach ($penguruses as $data):
                    $nama    = !empty($data['nama']) ? htmlspecialchars($data['nama']) : '-';
                    $no_ktp  = !empty($data['no_ktp']) ? htmlspecialchars($data['no_ktp']) : '-';
                    $jabatan = !empty($data['jabatan']) ? htmlspecialchars($data['jabatan']) : '-';
                    $periode = !empty($data['periode']) ? htmlspecialchars($data['periode']) : '-';
                    $alamat  = !empty($data['alamat']) ? htmlspecialchars($data['alamat']) : '-';
                    $no_telp = !empty($data['no_telp']) ? htmlspecialchars($data['no_telp']) : '-';
                    $pas_foto = !empty($data['pas_foto']) ? $data['pas_foto'] : '';
                    $foto_ktp = !empty($data['foto_ktp']) ? $data['foto_ktp'] : '';
                  ?>
                  <tr>
                    <td>
                      <div style="display: flex; align-items: center; gap: 10px;">
                        <?php if (!empty($pas_foto)): ?>
                          <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($pas_foto); ?>" style="width: 34px; height: 34px; object-fit: cover; border-radius: 50%; border: 1px solid #d6e6dc; flex-shrink: 0;">
                        <?php else: ?>
                          <div style="width: 34px; height: 34px; border-radius: 50%; background: #eef4f0; color: #2e7d4f; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0;">
                            <?php echo strtoupper(substr($nama, 0, 1)); ?>
                          </div>
                        <?php endif; ?>
                        <div>
                          <div style="font-weight: 600; color: #1e3a2f; font-size: 13px;"><?php echo $nama; ?></div>
                          <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">KTP: <?php echo $no_ktp; ?></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="badge badge-green"><?php echo $jabatan; ?></span>
                      <div style="font-size: 11px; color: #7a9e8e; margin-top: 2px;">Periode: <?php echo $periode; ?></div>
                    </td>
                    <td>
                      <div style="font-weight: 500; font-size: 12.5px; color: #1e3a2f;"><i class="fa fa-phone" style="color:#2e7d4f;"></i> <?php echo $no_telp; ?></div>
                      <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px; max-width: 240px;"><?php echo $alamat; ?></div>
                    </td>
                    <td>
                      <?php if (!empty($foto_ktp)): ?>
                        <a href="../admin/uploads/pengurus/<?php echo htmlspecialchars($foto_ktp); ?>" target="_blank" class="badge badge-blue" style="text-decoration:none; font-size:10.5px; padding:3px 8px;" title="Lihat Foto KTP">
                          <i class="fa fa-id-card-o"></i> Foto KTP
                        </a>
                      <?php else: ?>
                        <span style="font-size: 11px; color: #9ab5a8;">-</span>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:right; padding-right:16px;">
                      <div class="btn-action-wrap">
                        <!-- Detail -->
                        <a href="detail-pengurus.php?id=<?php echo urlencode($data['id']); ?>" class="btn-icon btn-detail" data-tooltip="Detail Pengurus" title="Detail Pengurus">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <!-- Edit -->
                        <a href="editpengurus.php?id=<?php echo urlencode($data['id']); ?>" class="btn-icon btn-edit" data-tooltip="Edit Data" title="Edit Data">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                        </a>
                        <!-- Hapus -->
                        <button type="button" class="btn-icon btn-hapus" data-tooltip="Hapus Pengurus" title="Hapus Pengurus" onclick="konfirmasiHapus(<?php echo (int)$data['id']; ?>, '<?php echo htmlspecialchars($data['nama'] ?? '', ENT_QUOTES); ?>')">
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
                Menampilkan <strong><?php echo $total_data > 0 ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $per_page, $total_data); ?></strong> dari <strong><?php echo $total_data; ?></strong> data pengurus
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
  function konfirmasiHapus(id, nama) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Data Pengurus?',
        text: 'Pengurus "' + (nama || '') + '" akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c0392b',
        cancelButtonColor: '#6b8f7e',
        confirmButtonText: '<i class="fa fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'proses/proses_hapuspengurus.php?id=' + id;
        }
      });
    } else {
      if (confirm('Yakin ingin menghapus data pengurus ini?')) {
        window.location.href = 'proses/proses_hapuspengurus.php?id=' + id;
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
      $('#tabelPengurus tbody tr').filter(function() {
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