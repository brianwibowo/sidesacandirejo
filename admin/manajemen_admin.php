<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

// Cek role superadmin
if ($_SESSION['role'] != 'superadmin') {
    header("Location:index.php");
    exit();
}

/* ── Filter & Search Parameters ──────────────────────────────────────────── */
$search = isset($_GET['search']) ? mysqli_real_escape_string($db, trim($_GET['search'])) : '';
$role   = isset($_GET['role'])   ? mysqli_real_escape_string($db, trim($_GET['role']))   : '';

$where_admin = "WHERE 1=1";
if ($search !== '') {
    $where_admin .= " AND (nama_admin LIKE '%$search%' OR username_admin LIKE '%$search%' OR role LIKE '%$search%')";
}
if ($role !== '') {
    $where_admin .= " AND role = '$role'";
}

/* ── Pagination (Default 10 baris, opsi: 10, 15, 25, 50) ────────────────── */
$allowed_per_page = [10, 15, 25, 50];
$per_page = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowed_per_page) ? (int)$_GET['per_page'] : 10;

$count_query = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_admin $where_admin");
$count_row   = mysqli_fetch_assoc($count_query);
$total_data  = (int)($count_row['total'] ?? 0);

$total_pages = max(1, (int)ceil($total_data / $per_page));
$page        = isset($_GET['page']) ? max(1, min($total_pages, (int)$_GET['page'])) : 1;
$offset      = ($page - 1) * $per_page;

$sql_admin   = "SELECT * FROM tb_admin $where_admin ORDER BY id_admin ASC LIMIT $per_page OFFSET $offset";
$query_admin = mysqli_query($db, $sql_admin);
$admins = [];
while ($r = mysqli_fetch_assoc($query_admin)) {
    $admins[] = $r;
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
  <title>Manajemen Admin - Desa Candirejo Borobudur</title>

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
                <h1>Manajemen Admin</h1>
                <p>Kelola hak akses pengguna, administrator, dan status aktivitas sistem</p>
              </div>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="manajemen_admin.php" id="filterFormAdmin">
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
                    placeholder="Cari nama, username..."
                    value="<?php echo htmlspecialchars($search); ?>"
                    autocomplete="off"
                  >
                </div>

                <!-- Filter Role -->
                <select name="role" id="filterRole" class="filter-select" style="min-width:130px;" onchange="document.getElementById('filterFormAdmin').submit()">
                  <option value="">Semua Role</option>
                  <option value="superadmin" <?php echo $role === 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                  <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>

                <!-- Rows per page (Default 10) -->
                <select name="per_page" id="perPage" class="filter-select" style="min-width:115px;" onchange="document.getElementById('filterFormAdmin').submit()">
                  <option value="10" <?php echo $per_page === 10 ? 'selected' : ''; ?>>10 baris</option>
                  <option value="15" <?php echo $per_page === 15 ? 'selected' : ''; ?>>15 baris</option>
                  <option value="25" <?php echo $per_page === 25 ? 'selected' : ''; ?>>25 baris</option>
                  <option value="50" <?php echo $per_page === 50 ? 'selected' : ''; ?>>50 baris</option>
                </select>

                <!-- Submit filter icon -->
                <button type="submit" class="btn-filter" title="Terapkan Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18M7 9h10M11 14h2M13 19h-2"/>
                  </svg>
                </button>

                <?php if ($search !== '' || $role !== ''): ?>
                <a href="manajemen_admin.php" class="btn-reset-filter" title="Reset Filter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                  Reset
                </a>
                <?php endif; ?>

                <!-- Right Action Buttons -->
                <div class="filter-bar-right">
                  <a href="tambah_admin.php" class="btn-tambah">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Admin
                  </a>
                </div>
              </div>
            </form>

            <!-- Table Wrapper -->
            <div class="table-wrapper">
              <table class="tabel-booking" id="datatable-admin">
                <thead>
                  <tr>
                    <th>PENGGUNA / ADMINISTRATOR</th>
                    <th>ROLE</th>
                    <th>STATUS AKTIVITAS</th>
                    <th style="text-align:right; padding-right:16px;">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($admins)): ?>
                  <tr class="empty-row">
                    <td colspan="4" style="text-align:center; padding: 36px 14px; color:#9ab5a8;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:6px; display:inline-block;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                      <div style="font-size:13px;">Tidak ada data admin yang sesuai.</div>
                    </td>
                  </tr>
                  <?php else: foreach ($admins as $data):
                    $online = "Offline";
                    $warna  = "#e74c3c";
                    if (!empty($data['last_active'])) {
                        $last = strtotime($data['last_active']);
                        if ((time() - $last) <= 120) {
                            $online = "Online";
                            $warna  = "#2e7d4f";
                        }
                    }
                    $nama_admin = !empty($data['nama_admin']) ? htmlspecialchars($data['nama_admin']) : '-';
                    $username   = !empty($data['username_admin']) ? htmlspecialchars($data['username_admin']) : '-';
                    $user_role  = strtolower($data['role'] ?? 'admin');
                    $avatar     = !empty($data['gambar']) ? $data['gambar'] : '';
                  ?>
                  <tr id="admin-row-<?php echo $data['id_admin']; ?>">
                    <td>
                      <div style="display: flex; align-items: center; gap: 10px;">
                        <?php if (!empty($avatar)): ?>
                          <img src="images/<?php echo htmlspecialchars($avatar); ?>" style="width: 34px; height: 34px; object-fit: cover; border-radius: 50%; border: 1px solid #d6e6dc; flex-shrink: 0;">
                        <?php else: ?>
                          <div style="width: 34px; height: 34px; border-radius: 50%; background: #eef4f0; color: #2e7d4f; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0;">
                            <?php echo strtoupper(substr($nama_admin, 0, 1)); ?>
                          </div>
                        <?php endif; ?>
                        <div>
                          <div style="font-weight: 600; color: #1e3a2f; font-size: 13px;"><?php echo $nama_admin; ?></div>
                          <div style="font-size: 11px; color: #7a9e8e; margin-top: 1px;">@<?php echo $username; ?></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <?php if ($user_role === 'superadmin'): ?>
                        <span class="badge badge-purple" style="font-size:10.5px; padding:2px 8px;">Superadmin</span>
                      <?php else: ?>
                        <span class="badge badge-blue" style="font-size:10.5px; padding:2px 8px;">Admin</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="status-dot" style="color:<?php echo $warna; ?>; font-size: 14px; line-height: 1;">●</span>
                        <span class="status-text" style="font-size: 12px; font-weight: 600; color: <?php echo $warna; ?>;"><?php echo $online; ?></span>
                      </div>
                      <div style="font-size: 11px; color: #7a9e8e; margin-top: 2px;">
                        Aktif: <span class="last-active"><?php echo htmlspecialchars($data['last_active'] ?? '-'); ?></span>
                      </div>
                    </td>
                    <td style="text-align:right; padding-right:16px;">
                      <div class="btn-action-wrap">
                        <!-- Edit -->
                        <a href="edit_admin.php?id=<?php echo urlencode($data['id_admin']); ?>" class="btn-icon btn-edit" data-tooltip="Edit Data Admin" title="Edit Data">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/><path d="M3 21h18"/></svg>
                        </a>
                        <!-- Reset Password -->
                        <a href="reset_password.php?id=<?php echo urlencode($data['id_admin']); ?>" class="btn-icon btn-key" data-tooltip="Reset Password" title="Reset Password">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </a>
                        <!-- Hapus -->
                        <button type="button" class="btn-icon btn-hapus btn-delete-admin" data-id="<?php echo $data['id_admin']; ?>" data-nama="<?php echo htmlspecialchars($data['nama_admin'], ENT_QUOTES); ?>" data-tooltip="Hapus Admin" title="Hapus Admin">
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
                Menampilkan <strong><?php echo $total_data > 0 ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $per_page, $total_data); ?></strong> dari <strong><?php echo $total_data; ?></strong> data admin
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
      $('#datatable-admin tbody tr').filter(function() {
        if ($(this).hasClass('empty-row')) return;
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
      });
    });

    // Alert session check
    <?php if (isset($_SESSION['alert'])): ?>
      <?php
          $type = $_SESSION['alert']['type'];
          $msg  = $_SESSION['alert']['msg'];
          $icon = ($type == 'success') ? 'success' : (($type == 'danger') ? 'error' : 'warning');
          unset($_SESSION['alert']);
      ?>
      Swal.fire({
          icon: '<?php echo $icon; ?>',
          title: <?php echo $icon == 'success' ? "'Berhasil'" : ($icon == 'error' ? "'Gagal'" : "'Perhatian'"); ?>,
          html: '<?php echo addslashes($msg); ?>',
          timer: 3000,
          timerProgressBar: true,
          showConfirmButton: true,
          confirmButtonText: 'OK'
      });
    <?php endif; ?>

    // Delete admin confirmation with SweetAlert2
    $(document).on('click', '.btn-delete-admin', function() {
      var id   = $(this).data('id');
      var nama = $(this).data('nama');

      Swal.fire({
          icon: 'warning',
          title: 'Hapus Akun Admin?',
          html: 'Apakah Anda yakin ingin menghapus admin <strong>' + (nama || '') + '</strong>?<br><small class="text-muted">Aksi ini tidak dapat dibatalkan.</small>',
          showCancelButton: true,
          confirmButtonColor: '#c0392b',
          cancelButtonColor: '#6b8f7e',
          confirmButtonText: '<i class="fa fa-trash"></i> Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = 'hapus_admin.php?id=' + id;
          }
      });
    });

    adjustContentHeight();
    $(window).on('resize', adjustContentHeight);
    setTimeout(adjustContentHeight, 200);
  });

  // Polling online status
  function updateStatus() {
      fetch('update_status.php', { method: 'GET', credentials: 'same-origin' })
      .then(response => response.text())
      .then(() => {
          fetchAdminStatus();
      })
      .catch(err => console.error('Update status error:', err));
  }

  function fetchAdminStatus() {
      fetch('get_admin_status.php', { credentials: 'same-origin' })
      .then(res => res.json())
      .then(admins => {
          admins.forEach(admin => {
              var $row = $('#datatable-admin tbody tr#admin-row-' + admin.id_admin);
              if ($row.length) {
                  var $dot  = $row.find('.status-dot');
                  var $text = $row.find('.status-text');
                  var $last = $row.find('.last-active');

                  if (admin.online) {
                      $dot.css('color', '#2e7d4f');
                      $text.css('color', '#2e7d4f').text('Online');
                  } else {
                      $dot.css('color', '#e74c3c');
                      $text.css('color', '#e74c3c').text('Offline');
                  }

                  $last.text(admin.last_active || '-');
              }
          });
      })
      .catch(err => console.error('Fetch status error:', err));
  }

  fetchAdminStatus();
  setInterval(updateStatus, 5000);
  </script>

</body>
</html>