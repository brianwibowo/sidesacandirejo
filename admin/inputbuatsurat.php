<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
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
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Buat Surat</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_buatsurat.php" name="formbuatsurat" method="post"
                    enctype="multipart/form-data" id="demo-form2" data-parsley-validate
                    class="form-horizontal form-label-left">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_surat">Nomor Surat <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nomor_surat" name="nomor_surat" required="required" maxlength="50"
                          placeholder="Masukkan Nomor Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>



                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lampiran">Lampiran
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="lampiran" name="lampiran" class="form-control" rows="3"
                          placeholder='Masukkan Lampiran (Opsional)'></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="perihal">Perihal <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="perihal" name="perihal" required="required" maxlength="200"
                          placeholder="Masukkan Perihal Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker4'>
                          <input type='date' id="tanggal" name="tanggal" required="required" class="form-control" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kepada">Kepada <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kepada" name="kepada" required="required" maxlength="100"
                          placeholder="Masukkan Nama Penerima" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kepada">Lokasi Penerima <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kepada" name="lokasi" required="required" maxlength="100"
                          placeholder="Masukkan Nama Penerima" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pembuka">Pembuka <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="pembuka" name="pembuka" required="required" class="form-control" rows="3"
                          placeholder='Masukkan Pembuka Surat'></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="isi">Isi <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="isi" name="isi" required="required" class="form-control" rows="5"
                          placeholder='Masukkan Isi Surat'></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penutup">Penutup <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="penutup" name="penutup" required="required" class="form-control" rows="3"
                          placeholder='Masukkan Penutup Surat'></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penandatangan_surat">Penandatangan
                        Surat <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea type="text" id="penandatangan_surat" rows="3" name="penandatangan_surat"
                          required="required" maxlength="100" placeholder="Masukkan Nama Penandatangan Surat"
                          class="form-control col-md-7 col-xs-12"></textarea>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-primary">Reset</button>
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
          Arsip Surat Desa Candirejo Borobudur
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
  <!-- bootstrap-datetimepicker -->
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- Initialize datetimepicker -->
  <script>
  $('#myDatepicker4').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  </script>
</body>

</html>