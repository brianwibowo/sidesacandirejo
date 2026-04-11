<?php
include '../koneksi/koneksi.php';
$sql         = "SELECT * FROM tb_admin WHERE id_admin='" . $_SESSION['id'] . "'";
$query       = mysqli_query($db, $sql);
$admin_login = mysqli_fetch_array($query);

$nama     = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';
$initials = strtoupper(substr($nama, 0, 1));
?>

<header class="booking-header" id="bookingHeader">

  <div class="header-left">
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
  </div>

  <div class="header-right">

    <!-- User Dropdown -->
    <div class="header-user" id="userDropdownBtn">
      <div class="user-avatar">
        <?php if (!empty($admin_login['gambar'])): ?>
          <img src="../admin/images/<?php echo htmlspecialchars($admin_login['gambar']); ?>" alt="<?php echo htmlspecialchars($nama); ?>">
        <?php else: ?>
          <span class="user-initials"><?php echo $initials; ?></span>
        <?php endif; ?>
      </div>
      <span class="user-name"><?php echo htmlspecialchars($nama); ?></span>
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="user-chevron">
        <path d="M6 9l6 6 6-6"/>
      </svg>

      <div class="user-dropdown" id="userDropdown">
        <a href="../admin/profile.php" class="dropdown-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
          Profil
        </a>
        <div class="dropdown-divider"></div>
        <a href="../koneksi/proses_logout.php" class="dropdown-item dropdown-item-danger"
           onclick="return confirm('Apakah Anda akan keluar?');">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Keluar
        </a>
      </div>
    </div>

  </div>
</header>

<style>
:root {
  --sidebar-width: 240px;
  --sidebar-collapsed: 60px;
}
.booking-header {
  position: fixed; top: 0; left: 240px; right: 0;
  height: 58px; background: #fff;
  border-bottom: 1px solid #e5ede8;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 24px; z-index: 99; transition: left 0.25s;
}
.header-left { display: flex; align-items: center; }
.sidebar-toggle-btn {
  background: none; border: none; cursor: pointer;
  padding: 6px; border-radius: 6px; color: #4a6358;
  display: flex; align-items: center; transition: background 0.15s;
}
.sidebar-toggle-btn:hover { background: #f0f5f2; }
.header-right { display: flex; align-items: center; gap: 6px; }
.header-user {
  display: flex; align-items: center; gap: 8px;
  cursor: pointer; padding: 5px 10px; border-radius: 8px;
  transition: background 0.15s; position: relative;
}
.header-user:hover { background: #f0f5f2; }
.user-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: #1e3a2f; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-initials { color: #fff; font-size: 13px; font-weight: 600; }
.user-name { font-size: 13.5px; font-weight: 500; color: #1e3a2f; }
.user-chevron { color: #7a9e8e; }
.user-dropdown {
  display: none; position: absolute;
  top: calc(100% + 6px); right: 0;
  background: #fff; border: 1px solid #dde8e2;
  border-radius: 10px; min-width: 160px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.09);
  padding: 6px 0; z-index: 200;
}
.user-dropdown.open { display: block; }
.dropdown-item {
  display: flex; align-items: center; gap: 8px;
  padding: 9px 16px; font-size: 13px; color: #2a4535;
  text-decoration: none; transition: background 0.12s;
}
.dropdown-item:hover { background: #f0f5f2; }
.dropdown-item-danger { color: #c0392b; }
.dropdown-item-danger:hover { background: #fdf2f2; }
.dropdown-divider { height: 1px; background: #e5ede8; margin: 4px 0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var userBtn   = document.getElementById('userDropdownBtn');
  var dropdown  = document.getElementById('userDropdown');
  var toggleBtn = document.getElementById('sidebarToggleBtn');
  var sidebar   = document.getElementById('bookingSidebar');
  var header    = document.getElementById('bookingHeader');
  var content   = document.getElementById('bookingContent');
  var collapsed = false;

  if (userBtn && dropdown) {
    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });
    document.addEventListener('click', function () {
      dropdown.classList.remove('open');
    });
  }

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function () {
      collapsed = !collapsed;
      sidebar.classList.toggle('collapsed', collapsed);
      if (header)  header.style.left        = collapsed ? 'var(--sidebar-collapsed)' : 'var(--sidebar-width)';
      if (content) content.classList.toggle('collapsed', collapsed);
    });
  }
});
</script>