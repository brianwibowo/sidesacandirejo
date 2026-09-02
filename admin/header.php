<?php 
include '../koneksi/koneksi.php';
$sql  		= "SELECT * FROM tb_admin where id_admin='".$_SESSION['id']."'";                        
$query  	= mysqli_query($db, $sql);
$admin_login = mysqli_fetch_array($query);
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
                    <img src="images/<?php echo $admin_login['gambar']; ?>" alt=""><?php echo $_SESSION['nama'];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="profile.php"><i class="fa fa-user pull-right"></i> Profil</a></li>
                    <li><a href="../koneksi/proses_logout.php"><i class="fa fa-sign-out pull-right" onclick="return confirm ('Apakah Anda Akan Keluar.?');"></i> Keluar</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>

        <style>
          /* ===== FIX HEADER & SIDEBAR SELALU FIXED ===== */

          /* Top nav fixed di atas */
          .main_container .top_nav {
            position: fixed !important;
            top: 0;
            left: 230px;
            right: 0;
            z-index: 9998;
            margin-left: 0 !important;
            background: #EDEDED;
            border-bottom: 1px solid #D9DEE4;
          }
          .nav-sm .main_container .top_nav {
            left: 70px;
          }

          /* Sidebar fixed, tidak ikut scroll */
          .nav-md .container.body .col-md-3.left_col {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100% !important;
            overflow-y: auto;
            z-index: 9999;
          }
          .nav-sm .container.body .col-md-3.left_col {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100% !important;
            overflow-y: auto;
            z-index: 9999;
          }

          /* Konten utama turun agar tidak ketutup header */
          .right_col {
            margin-top: 57px !important;
          }
        </style>