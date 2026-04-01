<?php
include '../koneksi/koneksi.php';
$sql      = "SELECT * FROM tb_admin where id_admin='" . $_SESSION['id'] . "'";
$query    = mysqli_query($db, $sql);
$data     = mysqli_fetch_array($query);
?>
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="images/<?php echo $data['gambar']; ?>" alt=""><?php echo $_SESSION['nama']; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="profile.php"><i class="fa fa-user pull-right"></i> Profil</a></li>
            <li>
              <a href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fa fa-sign-out pull-right"></i> Keluar
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><b>Konfirmasi Keluar</b></h4>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin keluar dari sistem ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
        <a href="../koneksi/proses_logout.php" class="btn btn-danger" onclick="$('.modal').modal('hide');">Ya, Keluar</a>
      </div>
    </div>
  </div>
</div>