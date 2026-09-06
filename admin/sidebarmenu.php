<?php
include '../koneksi/koneksi.php';
$admin_login = null;
if (!empty($_SESSION['id'])) {
    $sql         = "SELECT * FROM tb_admin WHERE id_admin='" . mysqli_real_escape_string($db, $_SESSION['id']) . "'";
    $query       = mysqli_query($db, $sql);
    $admin_login = mysqli_fetch_array($query);
}

$nama_admin = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';
$initials   = strtoupper(substr($nama_admin, 0, 1));

// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);

$kategori_data_pages = [
    'datapengunjung.php', 'detail-datapengunjung.php', 'editpengunjung.php', 'inputdatapengunjung.php',
    'datamitra.php', 'detail-datamitra.php', 'editmitra.php', 'inputdatamitra.php',
    'datapengurus.php', 'detail-datapengurus.php', 'editpengurus.php', 'inputdatapengurus.php',
    'datapenjualanusaha.php', 'detail-datapenjualanusaha.php', 'editpenjualanusaha.php', 'inputdatapenjualanusaha.php',
    'analitik.php'
];

$kategori_surat_pages = [
    'datasuratmasuk.php', 'detail-suratmasuk.php', 'editsuratmasuk.php', 'inputsuratmasuk.php',
    'datasuratkeluar.php', 'detail-suratkeluar.php', 'editsuratkeluar.php', 'inputsuratkeluar.php'
];

$is_kategori_data_active  = in_array($current_page, $kategori_data_pages);
$is_kategori_surat_active = in_array($current_page, $kategori_surat_pages);
?>
<link rel="stylesheet" href="css/modern_admin.css?v=2.3">
<link rel="stylesheet" href="css/tabel_modern.css?v=2.3">
<script>
  if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
    document.documentElement.classList.add('admin-sidebar-is-collapsed');
  }
</script>
<style>
  html.admin-sidebar-is-collapsed .booking-sidebar { width: 60px !important; }
  html.admin-sidebar-is-collapsed .booking-header { padding-left: 84px !important; }
  html.admin-sidebar-is-collapsed .right_col { margin-left: 60px !important; width: calc(100% - 60px) !important; }
  html.admin-sidebar-is-collapsed footer { margin-left: 60px !important; width: calc(100% - 60px) !important; }
  html.admin-sidebar-is-collapsed .booking-sidebar .brand-text,
  html.admin-sidebar-is-collapsed .booking-sidebar .profile-info,
  html.admin-sidebar-is-collapsed .booking-sidebar .nav-label,
  html.admin-sidebar-is-collapsed .booking-sidebar .chevron,
  html.admin-sidebar-is-collapsed .booking-sidebar .submenu,
  html.admin-sidebar-is-collapsed .booking-sidebar .switch-arsip-text,
  html.admin-sidebar-is-collapsed .booking-sidebar .switch-arsip-arrow { display: none !important; opacity: 0 !important; }
</style>

<aside class="booking-sidebar admin-sidebar" id="bookingSidebar">

  <!-- Sidebar Brand -->
  <div class="sidebar-brand">
    <span class="brand-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
      </svg>
    </span>
    <div class="brand-text">
      <div class="brand-title">Arsip Candirejo</div>
      <div class="brand-subtitle">Desa Wisata Candirejo</div>
    </div>
  </div>

  <!-- Sidebar Profile -->
  <div class="sidebar-profile">
    <div class="profile-avatar">
      <?php if (!empty($admin_login['gambar'])): ?>
        <img src="images/<?php echo htmlspecialchars($admin_login['gambar']); ?>" alt="<?php echo htmlspecialchars($nama_admin); ?>">
      <?php else: ?>
        <span class="avatar-initials"><?php echo $initials; ?></span>
      <?php endif; ?>
    </div>
    <div class="profile-info">
      <div class="profile-name"><?php echo htmlspecialchars($nama_admin); ?></div>
      <div class="profile-role">Administrator</div>
    </div>
  </div>

  <!-- Sidebar Navigation Menu -->
  <nav class="sidebar-nav">
    <ul>

      <!-- Dashboard -->
      <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
        <a href="index.php" data-label="Dashboard">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
          </span>
          <span class="nav-label">Dashboard</span>
        </a>
      </li>

      <!-- Kategori Data (Dropdown) -->
      <li class="has-submenu <?php echo $is_kategori_data_active ? 'open active' : ''; ?>">
        <a href="#" class="submenu-toggle" data-label="Kategori Data">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
            </svg>
          </span>
          <span class="nav-label">Kategori Data</span>
          <span class="chevron <?php echo $is_kategori_data_active ? 'rotated' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </span>
        </a>
        <ul class="submenu <?php echo $is_kategori_data_active ? 'open' : ''; ?>">
          <li class="<?php echo in_array($current_page, ['datapengunjung.php', 'detail-datapengunjung.php', 'editpengunjung.php', 'inputdatapengunjung.php']) ? 'active' : ''; ?>">
            <a href="datapengunjung.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></span>
              <span class="nav-label">Data Pengunjung</span>
            </a>
          </li>
          <li class="<?php echo in_array($current_page, ['datamitra.php', 'detail-datamitra.php', 'editmitra.php', 'inputdatamitra.php']) ? 'active' : ''; ?>">
            <a href="datamitra.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></span>
              <span class="nav-label">Data Mitra</span>
            </a>
          </li>
          <li class="<?php echo in_array($current_page, ['datapengurus.php', 'detail-datapengurus.php', 'editpengurus.php', 'inputdatapengurus.php']) ? 'active' : ''; ?>">
            <a href="datapengurus.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg></span>
              <span class="nav-label">Data Pengurus</span>
            </a>
          </li>
          <li class="<?php echo in_array($current_page, ['datapenjualanusaha.php', 'detail-datapenjualanusaha.php', 'editpenjualanusaha.php', 'inputdatapenjualanusaha.php']) ? 'active' : ''; ?>">
            <a href="datapenjualanusaha.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></span>
              <span class="nav-label">Data Penjualan Usaha</span>
            </a>
          </li>
          <li class="<?php echo $current_page == 'analitik.php' ? 'active' : ''; ?>">
            <a href="analitik.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
              <span class="nav-label">Analitik Data</span>
            </a>
          </li>
        </ul>
      </li>

      <!-- Kategori Surat (Dropdown) -->
      <li class="has-submenu <?php echo $is_kategori_surat_active ? 'open active' : ''; ?>">
        <a href="#" class="submenu-toggle" data-label="Kategori Surat">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </span>
          <span class="nav-label">Kategori Surat</span>
          <span class="chevron <?php echo $is_kategori_surat_active ? 'rotated' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </span>
        </a>
        <ul class="submenu <?php echo $is_kategori_surat_active ? 'open' : ''; ?>">
          <li class="<?php echo in_array($current_page, ['datasuratmasuk.php', 'detail-suratmasuk.php', 'editsuratmasuk.php', 'inputsuratmasuk.php']) ? 'active' : ''; ?>">
            <a href="datasuratmasuk.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg></span>
              <span class="nav-label">Arsip Surat Masuk</span>
            </a>
          </li>
          <li class="<?php echo in_array($current_page, ['datasuratkeluar.php', 'detail-suratkeluar.php', 'editsuratkeluar.php', 'inputsuratkeluar.php']) ? 'active' : ''; ?>">
            <a href="datasuratkeluar.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></span>
              <span class="nav-label">Arsip Surat Keluar</span>
            </a>
          </li>
        </ul>
      </li>

      <!-- Manajemen Admin (superadmin only) -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin'): ?>
      <li class="<?php echo $current_page == 'manajemen_admin.php' ? 'active' : ''; ?>">
        <a href="manajemen_admin.php" data-label="Manajemen Admin">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </span>
          <span class="nav-label">Manajemen Admin</span>
        </a>
      </li>
      <?php endif; ?>

    </ul>
  </nav>

  <!-- Switch ke Sistem Booking -->
  <div class="sidebar-switch-arsip">
    <a href="../booking/booking_dashboard.php" target="_blank" class="switch-btn-arsip">
      <span class="switch-arsip-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
          <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
        </svg>
      </span>
      <span class="switch-arsip-text">
        <span class="switch-arsip-label">Buka Sistem Booking</span>
        <span class="switch-arsip-sub">Desa Wisata Candirejo</span>
      </span>
      <span class="switch-arsip-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path d="M7 17L17 7M7 7h10v10"/>
        </svg>
      </span>
    </a>
  </div>

</aside>

<style>
:root {
  --sidebar-bg: #1e3a2f;
  --sidebar-hover: #2a4f3f;
  --sidebar-active: #2d5540;
  --sidebar-text: #c8ddd4;
  --sidebar-text-muted: #7fa892;
  --sidebar-accent: #4caf80;
  --sidebar-width: 240px;
  --sidebar-collapsed: 60px;
}

/* Base Sidebar */
.booking-sidebar {
  width: var(--sidebar-width);
  height: 100vh;
  background: var(--sidebar-bg);
  display: flex;
  flex-direction: column;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
  overflow: hidden;
  transition: width 0.25s ease;
  box-sizing: border-box;
}
.booking-sidebar.collapsed {
  width: var(--sidebar-collapsed);
}

/* Brand */
.sidebar-brand {
  padding: 16px 14px 14px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
  display: flex;
  align-items: center;
  gap: 10px;
  white-space: nowrap;
  min-width: var(--sidebar-width);
  height: 58px;
  box-sizing: border-box;
}
.brand-icon {
  flex-shrink: 0;
  color: var(--sidebar-accent);
  display: flex;
  align-items: center;
}
.brand-text {
  display: flex;
  flex-direction: column;
  gap: 1px;
  transition: opacity 0.15s;
}
.brand-text .brand-title {
  color: #fff;
  font-size: 16px;
  font-weight: 600;
  line-height: 1.2;
}
.brand-text .brand-subtitle {
  color: var(--sidebar-text-muted);
  font-size: 11.5px;
  line-height: 1.2;
}
.booking-sidebar.collapsed .brand-text {
  opacity: 0;
  pointer-events: none;
}

/* Profile */
.sidebar-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 14px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
  white-space: nowrap;
  overflow: hidden;
  min-width: var(--sidebar-width);
  box-sizing: border-box;
}
.profile-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(76,175,128,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  flex-shrink: 0;
}
.profile-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.avatar-initials {
  color: var(--sidebar-accent);
  font-size: 15px;
  font-weight: 600;
}
.profile-info {
  transition: opacity 0.15s;
}
.profile-name {
  color: #fff;
  font-size: 13px;
  font-weight: 500;
}
.profile-role {
  color: var(--sidebar-text-muted);
  font-size: 11px;
  margin-top: 1px;
}
.booking-sidebar.collapsed .profile-info {
  opacity: 0;
  pointer-events: none;
}

/* Navigation */
.sidebar-nav {
  padding: 12px 0;
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
}
.sidebar-nav::-webkit-scrollbar {
  width: 4px;
}
.sidebar-nav::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.15);
  border-radius: 4px;
}
.sidebar-nav ul {
  list-style: none;
  margin: 0;
  padding: 0;
  min-width: var(--sidebar-width);
}

.sidebar-nav > ul > li > a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 20px;
  color: var(--sidebar-text);
  text-decoration: none !important;
  font-size: 13.5px;
  white-space: nowrap;
  transition: background 0.15s, color 0.15s;
  position: relative;
}
.sidebar-nav > ul > li > a:hover {
  background: var(--sidebar-hover);
  color: #fff !important;
}
.sidebar-nav > ul > li.active > a {
  background: var(--sidebar-active);
  color: #fff !important;
  border-left: 3px solid var(--sidebar-accent);
  padding-left: 17px;
}
.sidebar-nav > ul > li.has-submenu.open > a {
  background: var(--sidebar-active);
  color: #fff !important;
  border-left: 3px solid var(--sidebar-accent);
  padding-left: 17px;
}

/* Nav Icon & Label */
.nav-icon {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  opacity: 0.9;
}
.nav-label {
  transition: opacity 0.15s;
}
.booking-sidebar.collapsed .nav-label {
  opacity: 0;
  pointer-events: none;
}

/* Chevron */
.chevron {
  margin-left: auto;
  display: flex;
  align-items: center;
  transition: transform 0.2s;
  color: var(--sidebar-text-muted);
}
.chevron.rotated {
  transform: rotate(180deg);
}
.booking-sidebar.collapsed .chevron {
  display: none;
}

/* Submenu */
.submenu {
  display: none;
  background: rgba(0,0,0,0.15);
  padding: 4px 0;
  list-style: none;
  margin: 0;
}
.submenu.open {
  display: block;
}
.booking-sidebar.collapsed .submenu {
  display: none !important;
}
.submenu li a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 20px 9px 40px;
  color: var(--sidebar-text-muted);
  text-decoration: none !important;
  font-size: 13px;
  white-space: nowrap;
  transition: color 0.15s, background 0.15s;
}
.submenu li a:hover {
  color: #fff !important;
  background: var(--sidebar-hover);
}
.submenu li.active a {
  color: #fff !important;
  background: rgba(76, 175, 128, 0.18);
  border-left: 3px solid var(--sidebar-accent);
  padding-left: 37px;
  font-weight: 500;
}

/* Switch ke Sistem Booking */
.sidebar-switch-arsip {
  padding: 12px 12px 16px;
  border-top: 1px solid rgba(255,255,255,0.07);
  flex-shrink: 0;
  min-width: var(--sidebar-width);
  box-sizing: border-box;
}
.switch-btn-arsip {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 10px;
  text-decoration: none !important;
  width: 100%;
  box-sizing: border-box;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
}
.switch-btn-arsip:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.25);
  transform: translateY(-1px);
}
.switch-arsip-icon {
  width: 32px;
  height: 32px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #a8c8b8;
  flex-shrink: 0;
}
.switch-arsip-text {
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 1px;
}
.switch-arsip-label {
  color: #ddeee6;
  font-size: 12.5px;
  font-weight: 600;
  line-height: 1.2;
}
.switch-arsip-sub {
  color: var(--sidebar-text-muted);
  font-size: 11px;
  line-height: 1.2;
}
.switch-arsip-arrow {
  color: #7fa892;
  display: flex;
  align-items: center;
  flex-shrink: 0;
  opacity: 0.6;
}
.switch-btn-arsip:hover .switch-arsip-arrow {
  opacity: 1;
}
.booking-sidebar.collapsed .sidebar-switch-arsip {
  padding: 10px 8px;
  display: block;
}
.booking-sidebar.collapsed .switch-btn-arsip {
  padding: 8px;
  justify-content: center;
}
.booking-sidebar.collapsed .switch-arsip-text,
.booking-sidebar.collapsed .switch-arsip-arrow {
  display: none;
}

/* Neutralize old Gentelella left column */
.col-md-3.left_col {
  display: none !important;
}

/* ── FLOATING TOOLTIP & FLYOUT SAAT SIDEBAR COLLAPSED ── */
#sidebarFloatingTooltip {
  position: fixed;
  z-index: 99999;
  background: #142a20;
  border: 1px solid #2d5540;
  border-radius: 8px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.38);
  color: #ffffff;
  font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  pointer-events: none;
  opacity: 0;
  visibility: hidden;
  transform: translateX(6px);
  transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s ease;
}
#sidebarFloatingTooltip.show {
  opacity: 1;
  visibility: visible;
  transform: translateX(0);
  pointer-events: auto;
}
#sidebarFloatingTooltip::before {
  content: '';
  position: absolute;
  left: -6px;
  top: var(--arrow-top, 14px);
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-right: 6px solid #142a20;
}

/* Tooltip Pill Mode (Single Item) */
.sft-pill {
  padding: 8px 14px;
  font-size: 13px;
  font-weight: 500;
  white-space: nowrap;
  letter-spacing: 0.01em;
  color: #e5f0ea;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Flyout Popover Mode (Submenu Item) */
.sft-popover {
  min-width: 195px;
  padding: 6px 0;
}
.sft-header {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #7fa892;
  padding: 8px 16px 6px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.sft-menu {
  list-style: none;
  margin: 4px 0 0 0;
  padding: 0;
}
.sft-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 16px;
  color: #c8ddd4;
  text-decoration: none !important;
  font-size: 13px;
  font-weight: 500;
  transition: background 0.12s ease, color 0.12s ease;
}
.sft-item:hover {
  background: #234635;
  color: #ffffff !important;
}
.sft-item.active {
  background: #204b36;
  color: #10b981 !important;
  font-weight: 600;
}
</style>

<script>
(function() {
  function initSidebarTooltips() {
    var sidebar = document.getElementById('bookingSidebar');
    if (!sidebar) return;

    var tooltipEl = document.getElementById('sidebarFloatingTooltip');
    if (!tooltipEl) {
      tooltipEl = document.createElement('div');
      tooltipEl.id = 'sidebarFloatingTooltip';
      document.body.appendChild(tooltipEl);
    }

    var hideTimer = null;

    function hideTooltip() {
      if (tooltipEl) {
        tooltipEl.classList.remove('show');
      }
    }

    function scheduleHide() {
      clearTimeout(hideTimer);
      hideTimer = setTimeout(hideTooltip, 120);
    }

    tooltipEl.addEventListener('mouseenter', function() {
      clearTimeout(hideTimer);
    });
    tooltipEl.addEventListener('mouseleave', function() {
      scheduleHide();
    });

    function showForElement(el, isSubmenu, label, submenuEl) {
      clearTimeout(hideTimer);
      if (!sidebar.classList.contains('collapsed')) {
        hideTooltip();
        return;
      }

      var rect = el.getBoundingClientRect();
      var html = '';

      if (isSubmenu && submenuEl) {
        html = '<div class="sft-popover">';
        html += '<div class="sft-header">' + (label || 'Menu') + '</div>';
        html += '<div class="sft-menu">';
        var links = submenuEl.querySelectorAll('a');
        links.forEach(function(a) {
          var isAct = (a.closest('li') && a.closest('li').classList.contains('active')) || a.classList.contains('active');
          var text = a.querySelector('.nav-label') ? a.querySelector('.nav-label').textContent.trim() : a.textContent.trim();
          html += '<a href="' + a.getAttribute('href') + '" class="sft-item' + (isAct ? ' active' : '') + '">' + text + '</a>';
        });
        html += '</div></div>';
      } else {
        html = '<div class="sft-pill">' + (label || el.getAttribute('data-label') || el.getAttribute('title') || el.textContent.trim()) + '</div>';
      }

      tooltipEl.innerHTML = html;

      // Posisi X persis rapat di samping kanan sidebar ciut (60px + 6px = 66px)
      var sidebarRect = sidebar.getBoundingClientRect();
      var posX = Math.round(sidebarRect.right) + 6;
      tooltipEl.style.left = posX + 'px';

      // Posisi Y presisi sejajar ikon yang disorot
      tooltipEl.classList.add('show');
      var itemCenterY = rect.top + (rect.height / 2);
      var tooltipHeight = tooltipEl.offsetHeight || 36;
      var targetTop = isSubmenu ? rect.top : (itemCenterY - (tooltipHeight / 2));

      // Mencegah tooltip terpotong di batas atas / bawah layar
      var newTop = targetTop;
      if (newTop + tooltipHeight > window.innerHeight - 10) {
        newTop = window.innerHeight - 10 - tooltipHeight;
      }
      if (newTop < 10) {
        newTop = 10;
      }

      tooltipEl.style.top = Math.round(newTop) + 'px';
      var arrowOffset = Math.max(10, Math.min(tooltipHeight - 10, itemCenterY - newTop));
      tooltipEl.style.setProperty('--arrow-top', Math.round(arrowOffset) + 'px');
    }

    // Attach to top-level menu items
    var menuItems = sidebar.querySelectorAll('.sidebar-nav > ul > li');
    menuItems.forEach(function(li) {
      var a = li.querySelector(':scope > a');
      if (!a) return;

      var isSubmenu = li.classList.contains('has-submenu');
      var submenuEl = li.querySelector('.submenu');
      var label = a.getAttribute('data-label') || (a.querySelector('.nav-label') ? a.querySelector('.nav-label').textContent.trim() : '');

      li.addEventListener('mouseenter', function() {
        showForElement(a, isSubmenu, label, submenuEl);
      });
      li.addEventListener('mouseleave', function() {
        scheduleHide();
      });
    });

    // Switch button at bottom
    var switchBtn = sidebar.querySelector('.switch-btn-arsip');
    if (switchBtn) {
      switchBtn.addEventListener('mouseenter', function() {
        var label = switchBtn.getAttribute('data-label') || 'Buka Sistem Booking';
        showForElement(switchBtn, false, label, null);
      });
      switchBtn.addEventListener('mouseleave', function() {
        scheduleHide();
      });
    }

    // Hide on scroll/resize/click outside
    document.addEventListener('click', function(e) {
      if (!tooltipEl.contains(e.target) && !sidebar.contains(e.target)) {
        hideTooltip();
      }
    });
    window.addEventListener('resize', hideTooltip);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarTooltips);
  } else {
    initSidebarTooltips();
  }
})();
</script>