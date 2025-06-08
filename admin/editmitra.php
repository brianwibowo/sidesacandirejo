<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
<html lang="en">
<?php
$kategori = [
  "NIB", "PIRT"
];

?>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Arsip Surat Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebarmenu -->
      <?php
        include("sidebarmenu.php");
        ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
        include("header.php");
        ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Data Mitra</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data Mitra<small>Edit Data Mitra</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_editmitra.php" method="post" enctype="multipart/form-data" id="demo-form2"
                    data-parsley-validate class="form-horizontal form-label-left">
                    <?php include '../koneksi/koneksi.php';
                            $id			= mysqli_real_escape_string($db,$_GET['id']);
                            $sql  		= "SELECT * FROM tb_data_mitra where id='".$id."'";                        
                            $query  	= mysqli_query($db, $sql);
                            $data 		= mysqli_fetch_array($query);
                            $selected_value = $data['legalitas_usaha'];
                          ?>

                    <input type=hidden name="id_suratkeluar" value="<?php echo $id;?>">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nama Pemilik <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['nama_pemilik'];?>" type="text" id="nama_pemilikkeluar"
                          name="nama_pemilik" required="required" maxlength="35" placeholder="Masukkan Nomor Surat"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nama Usaha <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['nama_usaha'];?>" type="text" id="nama_usaha"
                          name="nama_usaha" required="required" maxlength="100" placeholder="Masukkan Nama Usaha"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori_usaha">Kategori Usaha<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="kategori_usaha" name="kategori_usaha" required="required" class="form-control col-md-7 col-xs-12">
                          <option value="">Pilih Kategori Usaha</option>
                          <option value="UMKM" <?php echo ($data['kategori_usaha'] == 'UMKM') ? 'selected' : ''; ?>>UMKM</option>
                          <option value="Local Guide" <?php echo ($data['kategori_usaha'] == 'Local Guide') ? 'selected' : ''; ?>>Local Guide</option>
                          <option value="Catering" <?php echo ($data['kategori_usaha'] == 'Catering') ? 'selected' : ''; ?>>Catering</option>
                          <option value="Dokar" <?php echo ($data['kategori_usaha'] == 'Dokar') ? 'selected' : ''; ?>>Dokar</option>
                          <option value="Homestay" <?php echo ($data['kategori_usaha'] == 'Homestay') ? 'selected' : ''; ?>>Homestay</option>
                          <option value="Kerajinan" <?php echo ($data['kategori_usaha'] == 'Kerajinan') ? 'selected' : ''; ?>>Kerajinan</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="alamat" name="alamat" required="required" class="form-control" rows="3" placeholder="Masukkan Alamat Lengkap"><?php echo $data['alamat'];?></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_telp">Nomor Telepon<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['nomor_telp'];?>" type="text" id="nomor_telp" name="nomor_telp" required="required" maxlength="20"
                          placeholder="Masukkan Nomor Telepon" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="legalitas_usaha">Legalitas
                        Usaha<span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['legalitas_usaha'];?>" type="text" id="legalitas_usaha"
                          name="legalitas_usaha" required="required" placeholder="Masukkan Legalitas Usaha"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">File Bukti Legalitas
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input name="bukti_legalitas" accept="application/pdf" type="file" id="bukti_legalitas"
                          class="form-control" autocomplete="off" />
                        <?php if (!empty($data['bukti_legalitas'])): ?>
                        <?php 
                        $bukti_legalitas = str_replace('../', '', $data['bukti_legalitas']);
                        ?>
                        <a href="<?php echo $bukti_legalitas; ?>" target="_blank"><b>Lihat File Sebelumnya</b></a>
                        <?php endif; ?>
                        (Maksimal 10 MB)
                      </div>
                      </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto Kegiatan Usaha
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input name="foto_kegiatan[]" accept="image/*" type="file" id="foto_kegiatan"
                          class="form-control" multiple />
                        <?php if (!empty($data['foto_kegiatan'])): ?>
                        <div class="mt-2">
                          <b>Foto Sebelumnya:</b><br>
                          <?php 
                          $fotos = explode(',', $data['foto_kegiatan']);
                          foreach($fotos as $foto): 
                            $foto = str_replace('../', '', $foto);
                          ?>
                            <a href="<?php echo $foto; ?>" target="_blank">
                              <img src="<?php echo $foto; ?>" alt="Foto Kegiatan" style="max-width: 100px; margin: 5px;">
                            </a>
                          <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <small class="text-muted">Pilih satu atau lebih foto (Maksimal 2MB per foto)</small>
                      </div>
                    </div>

                    <input type="hidden" value="<?= $data['id']?>" name="id">
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="datamitra.php" class="btn btn-success"><span
                            class="glyphicon glyphicon-arrow-left"></span> Batal</a>
                        <button type="submit" name="update" value="Update" class="btn btn-primary"><i
                            class="glyphicon glyphicon-plus"></i> Simpan</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">
     
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
  <!-- bootstrap-progressbar -->
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-wysiwyg -->
  <script src="../assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
  <script src="../assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="../assets/vendors/google-code-prettify/src/prettify.js"></script>
  <!-- jQuery Tags Input -->
  <script src="../assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
  <!-- Switchery -->
  <script src="../assets/vendors/switchery/dist/switchery.min.js"></script>
  <!-- Select2 -->
  <script src="../assets/vendors/select2/dist/js/select2.full.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-datetimepicker -->
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Parsley -->
  <script src="../assets/vendors/parsleyjs/dist/parsley.min.js"></script>
  <!-- Autosize -->
  <script src="../assets/vendors/autosize/dist/autosize.min.js"></script>
  <!-- jQuery autocomplete -->
  <script src="../assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
  <!-- starrr -->
  <script src="../assets/vendors/starrr/dist/starrr.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
  <!-- Initialize datetimepicker -->
  <script>
  $('#myDatepicker').datetimepicker();

  $('#myDatepicker2').datetimepicker({
    format: 'DD.MM.YYYY'
  });

  $('#myDatepicker3').datetimepicker({
    format: 'hh:mm A'
  });

  $(document).ready(function() {
    $('#myDatepicker4').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY/MM/DD'
    });
  });

  $('#datetimepicker6').datetimepicker();

  $('#datetimepicker7').datetimepicker({
    useCurrent: false
  });

  $("#datetimepicker6").on("dp.change", function(e) {
    $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
  });

  $("#datetimepicker7").on("dp.change", function(e) {
    $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
  });
  </script>
  <script language='javascript'>
  function validAngka(a) {
    if (!/^[0-9.]+$/.test(a.value)) {
      a.value = a.value.substring(0, a.value.length - 1000);
    }
  }
  </script>
</body>

</html>