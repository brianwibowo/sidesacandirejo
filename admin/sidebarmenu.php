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
  <div class="left_col scroll-view">
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
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Kategori</h3>
        <ul class="nav side-menu">

          <!-- Buat Surat -->
          <li class="<?php echo $current_page == 'inputbuatsurat.php' ? 'active' : ''; ?>">
            <a href="#" style="pointer-events:none; color:gray;">
              <i class="fa fa-plus-square"></i> Buat Surat
            </a>
          </li>

          <!-- Kategori Surat -->
          <li class="<?php echo $is_kategori_surat_active ? 'active' : ''; ?>">
            <a href="#">
              <i class="fa fa-file-text"></i> Kategori Surat <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="<?php echo $is_kategori_surat_active ? 'display:block;' : 'display:none;'; ?>">
              <li class="<?php echo $current_page == 'datasuratmasuk.php' ? 'active' : ''; ?>">
                <a href="datasuratmasuk.php"><i class="fa fa-inbox"></i> Arsip Surat Masuk</a>
              </li>
              <li class="<?php echo $current_page == 'datasuratkeluar.php' ? 'active' : ''; ?>">
                <a href="datasuratkeluar.php"><i class="fa fa-send"></i> Arsip Surat Keluar</a>
              </li>
            </ul>
          </li>

          <!-- Kategori Data -->
          <li class="<?php echo $is_kategori_data_active ? 'active' : ''; ?>">
            <a href="#">
              <i class="fa fa-users"></i> Kategori Data <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="<?php echo $is_kategori_data_active ? 'display:block;' : 'display:none;'; ?>">
              <li class="<?php echo $current_page == 'datamitra.php' ? 'active' : ''; ?>">
                <a href="datamitra.php"><i class="fa fa-inbox"></i> Data Mitra</a>
              </li>
              <li class="<?php echo $current_page == 'datapengunjung.php' ? 'active' : ''; ?>">
                <a href="datapengunjung.php"><i class="fa fa-inbox"></i> Data Pengunjung</a>
              </li>
              <li class="<?php echo $current_page == 'datapenjualanusaha.php' ? 'active' : ''; ?>">
                <a href="datapenjualanusaha.php"><i class="fa fa-inbox"></i> Data Penjualan Usaha</a>
              </li>
              <li class="<?php echo $current_page == 'datapengurus.php' ? 'active' : ''; ?>">
                <a href="datapengurus.php"><i class="fa fa-users"></i> Data Pengurus</a>
              </li>
            </ul>
          </li>

          <!-- Manajemen Admin (superadmin only) -->
          <?php if ($_SESSION['role'] == 'superadmin') { ?>
            <li class="<?php echo $current_page == 'manajemen_admin.php' ? 'active' : ''; ?>">
              <a href="manajemen_admin.php"><i class="fa fa-user"></i> Manajemen Admin</a>
            </li>
          <?php } ?>

        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->
  </div>
</div>