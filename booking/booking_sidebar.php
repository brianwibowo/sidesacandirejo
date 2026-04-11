<?php
include '../koneksi/koneksi.php';
$sql         = "SELECT * FROM tb_admin WHERE id_admin='" . $_SESSION['id'] . "'";
$query       = mysqli_query($db, $sql);
$admin_login = mysqli_fetch_array($query);

$current_page   = basename($_SERVER['PHP_SELF']);
$booking_pages  = ['booking_semua.php', 'booking_pending.php', 'booking_checkin.php', 'booking_tidakdatang.php'];
$is_booking_active = in_array($current_page, $booking_pages);

$nama     = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';
$initials = strtoupper(substr($nama, 0, 1));
?>

<div class="booking-sidebar" id="bookingSidebar">

  <div class="sidebar-brand">
    <span class="brand-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
        <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
      </svg>
    </span>
    <div class="brand-text">
      <div class="brand-title">Sistem Booking</div>
      <div class="brand-subtitle">Desa Wisata Candirejo</div>
    </div>
  </div>

  <div class="sidebar-profile">
    <div class="profile-avatar">
      <?php if (!empty($admin_login['gambar'])): ?>
        <img src="../admin/images/<?php echo htmlspecialchars($admin_login['gambar']); ?>" alt="<?php echo htmlspecialchars($nama); ?>">
      <?php else: ?>
        <span class="avatar-initials"><?php echo $initials; ?></span>
      <?php endif; ?>
    </div>
    <div class="profile-info">
      <div class="profile-name"><?php echo htmlspecialchars($nama); ?></div>
      <div class="profile-role">Administrator</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <ul>

      <li class="<?php echo $current_page == 'booking_dashboard.php' ? 'active' : ''; ?>">
        <a href="booking_dashboard.php" data-label="Dashboard">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
          </span>
          <span class="nav-label">Dashboard</span>
        </a>
      </li>

      <li class="has-submenu <?php echo $is_booking_active ? 'open' : ''; ?>">
        <a href="#" class="submenu-toggle" data-label="Data Booking">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
            </svg>
          </span>
          <span class="nav-label">Data Booking</span>
          <span class="chevron <?php echo $is_booking_active ? 'rotated' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
          </span>
        </a>
        <ul class="submenu <?php echo $is_booking_active ? 'open' : ''; ?>">
          <li class="<?php echo $current_page == 'booking_semua.php' ? 'active' : ''; ?>">
            <a href="booking_semua.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></span>
              <span class="nav-label">Semua Booking</span>
            </a>
          </li>
          <li class="<?php echo $current_page == 'booking_pending.php' ? 'active' : ''; ?>">
            <a href="booking_pending.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
              <span class="nav-label">Pending</span>
            </a>
          </li>
          <li class="<?php echo $current_page == 'booking_checkin.php' ? 'active' : ''; ?>">
            <a href="booking_checkin.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
              <span class="nav-label">Check-in</span>
            </a>
          </li>
          <li class="<?php echo $current_page == 'booking_tidakdatang.php' ? 'active' : ''; ?>">
            <a href="booking_tidakdatang.php">
              <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
              <span class="nav-label">Tidak Datang</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="<?php echo $current_page == 'booking_kalender.php' ? 'active' : ''; ?>">
        <a href="booking_kalender.php" data-label="Kalender Booking">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </span>
          <span class="nav-label">Kalender Booking</span>
        </a>
      </li>

      <li class="<?php echo $current_page == 'booking_laporan.php' ? 'active' : ''; ?>">
        <a href="booking_laporan.php" data-label="Laporan">
          <span class="nav-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </span>
          <span class="nav-label">Laporan</span>
        </a>
      </li>

    </ul>
  </nav>
</div>

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

/* Sidebar base */
.booking-sidebar {
  width: var(--sidebar-width); min-height: 100vh;
  background: var(--sidebar-bg);
  display: flex; flex-direction: column;
  position: fixed; left: 0; top: 0; z-index: 100;
  overflow: hidden; transition: width 0.25s ease;
}
.booking-sidebar.collapsed { width: var(--sidebar-collapsed); }

/* Brand */
.sidebar-brand {
  padding: 20px 12px 14px; border-bottom: 1px solid rgba(255,255,255,0.07);
  display: flex; align-items: center; gap: 10px;
  white-space: nowrap; min-width: var(--sidebar-width);
}
.brand-icon { flex-shrink: 0; color: var(--sidebar-accent); }
.brand-text .brand-title { color: #fff; font-size: 17px; font-weight: 600; }
.brand-text .brand-subtitle { color: var(--sidebar-text-muted); font-size: 12px; margin-top: 2px; }
.brand-text { transition: opacity 0.15s; }
.collapsed .brand-text { opacity: 0; pointer-events: none; }

/* Profile */
.sidebar-profile {
  display: flex; align-items: center; gap: 10px;
  padding: 16px 12px; border-bottom: 1px solid rgba(255,255,255,0.07);
  white-space: nowrap; overflow: hidden; min-width: var(--sidebar-width);
}
.profile-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: rgba(76,175,128,0.2);
  display: flex; align-items: center; justify-content: center;
  overflow: hidden; flex-shrink: 0;
}
.profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-initials { color: var(--sidebar-accent); font-size: 15px; font-weight: 600; }
.profile-info { transition: opacity 0.15s; }
.profile-name { color: #fff; font-size: 13px; font-weight: 500; }
.profile-role { color: var(--sidebar-text-muted); font-size: 11px; margin-top: 1px; }
.collapsed .profile-info { opacity: 0; pointer-events: none; }

/* Nav */
.sidebar-nav { padding: 12px 0; flex: 1; overflow: hidden; }
.sidebar-nav ul { list-style: none; margin: 0; padding: 0; min-width: var(--sidebar-width); }

.sidebar-nav > ul > li > a {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 20px; color: var(--sidebar-text);
  text-decoration: none; font-size: 13.5px; white-space: nowrap;
  transition: background 0.15s, color 0.15s; position: relative;
}
.sidebar-nav > ul > li > a:hover { background: var(--sidebar-hover); color: #fff; }
.sidebar-nav > ul > li.active > a {
  background: var(--sidebar-active); color: #fff;
  border-left: 3px solid var(--sidebar-accent); padding-left: 17px;
}

/* Tooltip saat collapsed */
.booking-sidebar.collapsed .sidebar-nav > ul > li > a::after {
  content: attr(data-label);
  position: absolute; left: calc(var(--sidebar-collapsed) + 8px); top: 50%;
  transform: translateY(-50%);
  background: #152b22; color: #fff; font-size: 12px; font-weight: 500;
  padding: 5px 10px; border-radius: 6px; white-space: nowrap;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  opacity: 0; pointer-events: none; transition: opacity 0.15s; z-index: 300;
}
.booking-sidebar.collapsed .sidebar-nav > ul > li > a:hover::after { opacity: 1; }

.nav-icon {
  width: 20px; height: 20px; display: flex;
  align-items: center; justify-content: center;
  flex-shrink: 0; opacity: 0.9;
}
.nav-label { transition: opacity 0.15s; }
.collapsed .nav-label { opacity: 0; pointer-events: none; }

/* Chevron */
.chevron { margin-left: auto; display: flex; align-items: center; transition: transform 0.2s; }
.chevron.rotated { transform: rotate(180deg); }
.collapsed .chevron { display: none; }

/* Submenu */
.submenu { display: none; background: rgba(0,0,0,0.15); padding: 4px 0; }
.submenu.open { display: block; }
.collapsed .submenu { display: none !important; }
.submenu li a {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 20px 9px 40px;
  color: var(--sidebar-text-muted); text-decoration: none; font-size: 13px;
  white-space: nowrap; transition: color 0.15s, background 0.15s;
}
.submenu li a:hover { color: #fff; background: var(--sidebar-hover); }
.submenu li.active a { color: var(--sidebar-accent); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  /* Submenu toggle */
  var toggle = document.querySelector('.submenu-toggle');
  if (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      var sidebar = document.getElementById('bookingSidebar');
      if (sidebar && sidebar.classList.contains('collapsed')) return;
      var li      = this.closest('li');
      var submenu = li.querySelector('.submenu');
      var chevron = li.querySelector('.chevron');
      li.classList.toggle('open');
      if (submenu) submenu.classList.toggle('open');
      if (chevron) chevron.classList.toggle('rotated');
    });
  }
});
</script>