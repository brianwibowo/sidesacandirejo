<?php
include '../koneksi/koneksi.php';
$sql = "SELECT * FROM tb_admin WHERE id_admin='" . $_SESSION['id'] . "'";
$query = mysqli_query($db, $sql);
$admin_login = mysqli_fetch_array($query);

// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
$kategori_surat_pages = ['datasuratmasuk.php', 'datasuratkeluar.php'];
$kategori_data_pages  = ['datamitra.php', 'datapengunjung.php', 'datapenjualanusaha.php', 'datapengurus.php'];
$is_kategori_surat_active = in_array($current_page, $kategori_surat_pages);
$is_kategori_data_active  = in_array($current_page, $kategori_data_pages);
?>
<div class="col-md-3 left_col">
  <div class="left_col scroll-view" style="display: flex; flex-direction: column; height: 100vh;">
    <div class="navbar nav_title" style="border: 0;">
      <a href="index.php" class="site_title"><i class="fa fa-institution"></i> <span>Arsip Surat</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="images/<?php echo htmlspecialchars($admin_login['gambar']); ?>" height="70" width="85" alt="" class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Selamat Datang,</span>
        <h2><?php echo htmlspecialchars($_SESSION['nama']); ?></h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br />
    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="flex: 1;">
      <div class="menu_section">
        <h3>Kategori</h3>
        <ul class="nav side-menu">

          <!-- Kategori Data -->
          <li class="<?php echo $is_kategori_data_active ? 'active' : ''; ?>">
            <a href="#">
              <i class="fa fa-database"></i> Kategori Data <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="<?php echo $is_kategori_data_active ? 'display:block;' : 'display:none;'; ?>">
              <li class="<?php echo $current_page == 'datapengunjung.php' ? 'active' : ''; ?>">
                <a href="datapengunjung.php"><i class="fa fa-users"></i> Data Pengunjung</a>
              </li>
              <li class="<?php echo $current_page == 'datamitra.php' ? 'active' : ''; ?>">
                <a href="datamitra.php"><i class="fa fa-exchange"></i> Data Mitra</a>
              </li>
              <li class="<?php echo $current_page == 'datapengurus.php' ? 'active' : ''; ?>">
                <a href="datapengurus.php"><i class="fa fa-briefcase"></i> Data Pengurus</a>
              </li>
              <li class="<?php echo $current_page == 'datapenjualanusaha.php' ? 'active' : ''; ?>">
                <a href="datapenjualanusaha.php"><i class="fa fa-line-chart"></i> Data Penjualan Usaha</a>
              </li>
            </ul>
          </li>

          <!-- Kategori Surat -->
          <li class="<?php echo $is_kategori_surat_active ? 'active' : ''; ?>">
            <a href="#">
              <i class="fa fa-envelope"></i> Kategori Surat <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="<?php echo $is_kategori_surat_active ? 'display:block;' : 'display:none;'; ?>">
              <li class="<?php echo $current_page == 'datasuratmasuk.php' ? 'active' : ''; ?>">
                <a href="datasuratmasuk.php"><i class="fa fa-envelope-o"></i> Arsip Surat Masuk</a>
              </li>
              <li class="<?php echo $current_page == 'datasuratkeluar.php' ? 'active' : ''; ?>">
                <a href="datasuratkeluar.php"><i class="fa fa-paper-plane"></i> Arsip Surat Keluar</a>
              </li>
            </ul>
          </li>

          <!-- Manajemen Admin (superadmin only) -->
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') { ?>
            <li class="<?php echo $current_page == 'manajemen_admin.php' ? 'active' : ''; ?>">
              <a href="manajemen_admin.php"><i class="fa fa-user"></i> Manajemen Admin</a>
            </li>
          <?php } ?>

        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->

    <!-- Switch ke Booking -->
    <div class="sidebar-switch-booking">
      <a href="../booking/booking_dashboard.php" target="_blank" class="switch-btn-booking">
        <span class="switch-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </span>
        <span class="switch-text">
          <span class="switch-label">Buka Sistem Booking</span>
          <span class="switch-sub">Desa Wisata Candirejo</span>
        </span>
        <span class="switch-arrow">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M7 17L17 7M7 7h10v10"/>
          </svg>
        </span>
      </a>
    </div>

  </div>
</div>

<style>
.left_col.scroll-view {
  overflow: hidden !important;
}
.sidebar-switch-booking {
  padding: 12px 14px 16px;
  border-top: 1px solid rgba(255,255,255,0.08);
  flex-shrink: 0;
}
.switch-btn-booking {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.13);
  border-radius: 10px;
  text-decoration: none;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
  width: 100%;
}
.switch-btn-booking:hover {
  background: rgba(255,255,255,0.12);
  border-color: rgba(255,255,255,0.25);
  transform: translateY(-1px);
  text-decoration: none;
}
.switch-icon {
  width: 32px; height: 32px;
  background: rgba(255,255,255,0.08);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: #b0bec5;
  flex-shrink: 0;
}
.switch-text {
  display: flex; flex-direction: column; flex: 1; gap: 1px;
}
.switch-label {
  color: #eceff1;
  font-size: 12.5px;
  font-weight: 600;
  line-height: 1.2;
}
.switch-sub {
  color: #78909c;
  font-size: 11px;
  line-height: 1.2;
}
.switch-arrow {
  color: #90a4ae;
  display: flex; align-items: center;
  flex-shrink: 0;
  opacity: 0.6;
}
.switch-btn-booking:hover .switch-arrow { opacity: 1; }
</style>