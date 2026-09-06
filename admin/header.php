<?php 
include '../koneksi/koneksi.php';
$admin_login = null;
if (!empty($_SESSION['id'])) {
    $sql         = "SELECT * FROM tb_admin WHERE id_admin='" . mysqli_real_escape_string($db, $_SESSION['id']) . "'";
    $query       = mysqli_query($db, $sql);
    $admin_login = mysqli_fetch_array($query);
}
$nama_admin  = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';
$initials    = strtoupper(substr($nama_admin, 0, 1));
?>

<header class="booking-header admin-header" id="bookingHeader">

  <!-- Left: Sidebar Collapse Toggle Button (Identik Web Booking) -->
  <div class="header-left">
    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar" type="button">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
  </div>

  <!-- Right: User Avatar + Dropdown Menu -->
  <div class="header-right">
    <div class="header-user" id="adminUserDropdownBtn">
      <div class="user-avatar">
        <?php if (!empty($admin_login['gambar'])): ?>
          <img src="images/<?php echo htmlspecialchars($admin_login['gambar']); ?>" alt="<?php echo htmlspecialchars($nama_admin); ?>">
        <?php else: ?>
          <span class="user-initials"><?php echo $initials; ?></span>
        <?php endif; ?>
      </div>
      <span class="user-name"><?php echo htmlspecialchars($nama_admin); ?></span>
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="user-chevron">
        <path d="M6 9l6 6 6-6"/>
      </svg>

      <!-- Dropdown Popup -->
      <div class="user-dropdown" id="adminUserDropdown">
        <a href="profile.php" class="dropdown-item">
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
/* ── HEADER — FULL WIDTH, SIDEBAR DI ATASNYA ── */
.booking-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 58px;
  background: #ffffff;
  border-bottom: 1px solid #e5ede8;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px 0 266px; /* 240px sidebar + 26px gap kiri */
  z-index: 998; /* di bawah sidebar (1000) */
  transition: padding-left 0.25s ease;
  box-sizing: border-box;
  overflow: visible;
}
.booking-header.collapsed {
  padding-left: 84px; /* 60px sidebar + 24px gap */
}

.header-left {
  display: flex;
  align-items: center;
}
.sidebar-toggle-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  color: #4a6358;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s, color 0.15s;
}
.sidebar-toggle-btn:hover {
  background: #f0f5f2;
  color: #1e3a2f;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 6px;
}
.header-user {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 8px;
  transition: background 0.15s;
  position: relative;
  user-select: none;
}
.header-user:hover {
  background: #f0f5f2;
}
.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #1e3a2f;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.user-initials {
  color: #fff;
  font-size: 13px;
  font-weight: 600;
}
.user-name {
  font-size: 13.5px;
  font-weight: 500;
  color: #1e3a2f;
}
.user-chevron {
  color: #7a9e8e;
  transition: transform 0.2s;
}
.header-user.active .user-chevron {
  transform: rotate(180deg);
}

.user-dropdown {
  display: none;
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  background: #fff;
  border: 1px solid #dde8e2;
  border-radius: 10px;
  min-width: 160px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.09);
  padding: 6px 0;
  z-index: 3000;
}
.user-dropdown.open {
  display: block;
}
.dropdown-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 16px;
  font-size: 13px;
  color: #2a4535;
  text-decoration: none !important;
  transition: background 0.12s;
}
.dropdown-item:hover {
  background: #f0f5f2;
  color: #1e3a2f !important;
}
.dropdown-item-danger {
  color: #c0392b !important;
}
.dropdown-item-danger:hover {
  background: #fdf2f2;
  color: #c0392b !important;
}
.dropdown-divider {
  height: 1px;
  background: #e5ede8;
  margin: 4px 0;
}

/* ── KONTEN UTAMA & FOOTER MENGIKUTI PERUBAHAN SIDEBAR ── */
/* Pastikan container Gentelella tidak clip header fixed */
.right_col {
  margin-left: 240px !important;
  margin-top: 58px !important;
  padding: 26px 24px 24px !important;
  background: #f4f7f5 !important;
  min-height: 100vh !important;
  transition: margin-left 0.25s ease !important;
  box-sizing: border-box !important;
  overflow-x: hidden !important;
}
.right_col.collapsed {
  margin-left: 60px !important;
}

footer {
  margin-left: 240px !important;
  transition: margin-left 0.25s ease !important;
  background: #ffffff !important;
  border-top: 1px solid #e5ede8 !important;
  padding: 14px 24px !important;
  box-sizing: border-box !important;
}
footer.collapsed {
  margin-left: 60px !important;
}

/* Sembunyikan top_nav lama Gentelella */
.top_nav {
  display: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var userBtn   = document.getElementById('adminUserDropdownBtn');
  var dropdown  = document.getElementById('adminUserDropdown');
  var toggleBtn = document.getElementById('sidebarToggleBtn');
  var sidebar   = document.getElementById('bookingSidebar');
  var header    = document.getElementById('bookingHeader');
  var rightCol  = document.querySelector('.right_col');
  var footer    = document.querySelector('footer');

  // ── PINDAHKAN HEADER KE DIRECT CHILD OF BODY ──
  // Ini memastikan position:fixed bekerja murni terhadap viewport,
  // terlepas dari container Gentelella (.container.body > .main_container)
  if (header && header.parentElement !== document.body) {
    document.body.insertBefore(header, document.body.firstChild);
  }

  // Load state sidebar dari localStorage
  var isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';

  function applyCollapse(collapsed) {
    if (sidebar) sidebar.classList.toggle('collapsed', collapsed);
    if (header)  header.classList.toggle('collapsed', collapsed);
    if (rightCol) rightCol.classList.toggle('collapsed', collapsed);
    if (footer)  footer.classList.toggle('collapsed', collapsed);
    if (collapsed) {
      document.documentElement.classList.add('admin-sidebar-is-collapsed');
    } else {
      document.documentElement.classList.remove('admin-sidebar-is-collapsed');
    }
    localStorage.setItem('admin_sidebar_collapsed', collapsed ? 'true' : 'false');
  }

  // Terapkan saat inisialisasi
  applyCollapse(isCollapsed);

  // Event listener tombol toggle sidebar di header
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      var willCollapse = !sidebar.classList.contains('collapsed');
      applyCollapse(willCollapse);
      // Resize chart setelah animasi sidebar selesai (250ms CSS transition)
      setTimeout(function () {
        if (typeof window._resizeAllCharts === 'function') {
          window._resizeAllCharts();
        }
      }, 280);
    });
  }

  // Event listener User Dropdown
  if (userBtn && dropdown) {
    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
      userBtn.classList.toggle('active');
    });
    document.addEventListener('click', function () {
      dropdown.classList.remove('open');
      userBtn.classList.remove('active');
    });
  }

  // Event listener Submenu dropdown toggle
  var subToggles = document.querySelectorAll('.submenu-toggle');
  subToggles.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      // Jika sidebar sedang collapsed, otomatis buka dulu agar nyaman
      if (sidebar && sidebar.classList.contains('collapsed')) {
        applyCollapse(false);
      }
      var li      = this.closest('li');
      var submenu = li.querySelector('.submenu');
      var chevron = li.querySelector('.chevron');
      li.classList.toggle('open');
      if (submenu) submenu.classList.toggle('open');
      if (chevron) chevron.classList.toggle('rotated');
    });
  });
});
</script>
