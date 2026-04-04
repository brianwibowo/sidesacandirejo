<?php
session_start();
include "../koneksi/koneksi.php";
include "login/ceksession.php";

// Cek role superadmin
if($_SESSION['role'] != 'superadmin'){
    header("Location:index.php");
    exit();
}

// Ambil data admin
$sql = "SELECT * FROM tb_admin ORDER BY id_admin ASC";
$query = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Arsip Surat Desa Candirejo Borobudur</title>

<!-- Bootstrap -->
<link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- DataTables -->
<link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="../assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="../assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="../assets/build/css/custom.min.css" rel="stylesheet">
<link rel="shortcut icon" href="../img/icon.ico">
</head>
<body class="nav-md">
<div class="container body">
  <div class="main_container">

    <!-- Sidebar -->
    <?php include("sidebarmenu.php"); ?>
    <!-- /Sidebar -->

    <!-- Top navigation -->
    <?php include("header.php"); ?>
    <!-- /Top navigation -->

    <!-- Page content -->
    <div class="right_col" role="main">
      <?php
        if(isset($_SESSION['alert'])){
            $type = $_SESSION['alert']['type']; // success, danger, warning
            $msg = $_SESSION['alert']['msg'];

            // Map type ke class Bootstrap 3
            $bsClass = ($type == 'success' ? 'alert-success' : ($type == 'danger' ? 'alert-danger' : 'alert-warning'));

            echo "<div class='alert $bsClass alert-dismissible fade in' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    $msg
                  </div>";

            unset($_SESSION['alert']);
        }
        ?>
      <div class="row">
        <div class="col-md-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Manajemen Admin</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <a href="tambah_admin.php" class="btn btn-success" style="margin-bottom:15px;">
                <i class="fa fa-plus"></i> Tambah Admin
              </a>
              <table id="datatable-admin" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Last Active</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM tb_admin ORDER BY id_admin ASC";
                    $query = mysqli_query($db, $sql); // query baru
                    if (!$query) die(mysqli_error($db)); // cek error
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($query)) {
                        $online = "Offline";
                        $warna = "red";

                        if($data['last_active'] != NULL){

                            $last = strtotime($data['last_active']);
                            $now = time();

                            if(($now - $last) <= 120){
                                $online = "Online";
                                $warna = "green";
                            }
                        }
                        echo "<tr id='admin-row-" . $data['id_admin'] . "'>
                            <td>" . $no++ . "</td>
                            <td>" . htmlspecialchars($data['nama_admin']) . "</td>
                            <td>" . htmlspecialchars($data['username_admin']) . "</td>
                            <td>" . htmlspecialchars($data['role']) . "</td>
                            <td>
                            <span style='color:$warna;' class='status-dot'>●</span> $online <br>
                            <small class='last-active'>" . htmlspecialchars($data['last_active'] ?? '-') . "</small>
                            </td>
                            <td>
                                <a href='edit_admin.php?id={$data['id_admin']}' class='btn btn-primary btn-sm'><i class='fa fa-edit'></i> Edit</a>
                                <a href='reset_password.php?id={$data['id_admin']}' class='btn btn-warning btn-sm'><i class='fa fa-key'></i> Reset Password</a>
                                <button class='btn btn-danger btn-sm btn-delete-admin'
                                        data-id='{$data['id_admin']}'
                                        data-nama='{$data['nama_admin']}'
                                        data-toggle='modal'
                                        data-target='#modalHapus'>
                                    <i class='fa fa-trash'></i> Hapus
                                </button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Modal hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Konfirmasi Hapus</h4>
        </div>

        <div class="modal-body" id="textHapusAdmin">
            Apakah Anda yakin ingin menghapus admin ini?
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <a href="#" id="btnHapusAdmin" class="btn btn-danger">Hapus</a>
        </div>

        </div>
    </div>
    </div>
    <!-- Footer -->
    <footer>
      <div class="pull-right">Apriansyah Wibowo. All Rights Reserved.</div>
      <div class="clearfix"></div>
    </footer>
    <!-- /Footer -->
  </div>
</div>

<!-- jQuery -->
<script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<!-- Custom Theme Scripts -->
<script src="../assets/build/js/custom.min.js"></script>

<script>
$(document).ready(function() {
    $('#datatable-admin').DataTable({
        responsive: true
    });
});
</script>
<!-- <script>
function updateOnlineStatus(){
    fetch("update_status.php", {
        method: "GET",
        credentials: "same-origin"
    })
    .then(response => response.text())
    .then(data => console.log("Status updated"))
    .catch(error => console.log(error));
}

setInterval(updateOnlineStatus, 5000);
</script> -->
<script>
function updateStatus() {
    fetch('update_status.php', { method: 'GET', credentials: 'same-origin' })
    .then(response => response.text())
    .then(data => {
        console.log('Status updated');

        // Ambil data terbaru
        fetch('get_admin_status.php')
        .then(res => res.json())
        .then(admins => {
            admins.forEach(admin => {
                // Gunakan jQuery selector agar aman dengan DataTables
                var $row = $('#datatable-admin tbody tr#admin-row-' + admin.id_admin);
                if($row.length){
                    var $statusSpan = $row.find('.status-dot');
                    var $lastActive = $row.find('.last-active');

                    if(admin.online){
                        $statusSpan.css('color', 'green').text('●');
                    } else {
                        $statusSpan.css('color', 'red').text('●');
                    }

                    $lastActive.text(admin.last_active || '-');
                }
            });
        });
    })
    .catch(err => console.log(err));
}

// Jalankan setiap 5 detik
setInterval(updateStatus, 5000);
</script>
<script>
$(document).ready(function(){
    $('.btn-delete-admin').click(function(){
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        // Isi teks modal
        $('#textHapusAdmin').html("Apakah Anda yakin ingin menghapus admin <strong>"+nama+"</strong>?");

        // Set link tombol Hapus
        $('#btnHapusAdmin').attr('href', 'hapus_admin.php?id='+id);
    });
});
</script>
</body>
</html>