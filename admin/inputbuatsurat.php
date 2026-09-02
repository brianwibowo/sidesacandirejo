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
                  
                  <!-- Dropdown Pemilihan Jenis Surat -->
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_surat">Jenis Surat <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select id="jenis_surat" name="jenis_surat" class="form-control" onchange="showForm()">
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="undangan">Surat Undangan</option>
                        <option value="keterangan">Surat Keterangan</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="clearfix"></div>
                  <br />

                  <!-- Form Surat Undangan -->
                  <div id="form_undangan" style="display:none;">
                    <form action="proses/proses_buatsurat_undangan.php" name="formbuatsurat" method="post" target="_blank"
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
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="hidden" id="perihal" name="perihal" required="required" maxlength="200"
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lokasi">Lokasi Penerima <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="lokasi" name="lokasi" required="required" maxlength="100"
                            placeholder="Masukkan Lokasi Penerima" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_acara">Tanggal Acara<span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class='input-group date' id='myDatepicker5'>
                            <input type='date' id="tanggal_acara" name="tanggal_acara" required="required"
                              class="form-control" />
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_acara">Waktu Acara<span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="waktu_acara" name="waktu_acara" required="required" maxlength="100"
                            placeholder="Masukkan Waktu Acara" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tempat_acara">Tempat Acara<span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="tempat_acara" name="tempat_acara" required="required" maxlength="100"
                            placeholder="Masukkan Tempat Acara" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan">Keperluan Acara<span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="keperluan" name="keperluan" required="required" maxlength="100"
                            placeholder="Masukkan Keperluan Acara" class="form-control col-md-7 col-xs-12">
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

                  <!-- Form Surat Keterangan -->
                  <div id="form_keterangan" style="display:none;">
                    <form action="proses/proses_buatsurat_keterangan.php" name="formbuatketerangan" method="post" target="_blank"
                      enctype="multipart/form-data" id="demo-form3" data-parsley-validate
                      class="form-horizontal form-label-left">
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_surat_keterangan">Nomor Surat <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="nomor_surat_keterangan" name="nomor_surat" required="required" maxlength="50"
                            placeholder="Masukkan Nomor Surat" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="nama" name="nama" required="required" maxlength="100"
                            placeholder="Masukkan Nama Lengkap" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_keterangan_pendukung">Jenis Keterangan Pendukung <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select id="jenis_keterangan_pendukung" name="jenis_keterangan_pendukung" class="form-control" required="required">
                            <option value="">-- Pilih Jenis Keterangan Pendukung --</option>
                            <option value="NIM">NIM</option>
                            <option value="NUPTK">NUPTK</option>
                            <option value="NIDN">NIDN</option>
                            <option value="NIP">NIP</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan_pendukung">Nomor Keterangan Pendukung <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="keterangan_pendukung" name="keterangan_pendukung" required="required" maxlength="50"
                            placeholder="Masukkan Nomor NIM/NUPTK/NIDN/NIP" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_lahir">Tanggal<span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class='input-group date' id='myDatepicker7'>
                            <input type='date' id="tanggal_lahir" name="tanggal" required="required" class="form-control" />
                            <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan <span
                            class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <textarea id="keterangan" name="keterangan" class="form-control" rows="4" required="required"
                            placeholder="Masukkan Keterangan"></textarea>
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

  <!-- JavaScript untuk mengontrol tampilan form -->
  <script>
    function showForm() {
      var jenisSurat = document.getElementById('jenis_surat').value;
      var formUndangan = document.getElementById('form_undangan');
      var formKeterangan = document.getElementById('form_keterangan');
      
      // Sembunyikan semua form
      formUndangan.style.display = 'none';
      formKeterangan.style.display = 'none';
      
      // Tampilkan form sesuai pilihan
      if (jenisSurat === 'undangan') {
        formUndangan.style.display = 'block';
      } else if (jenisSurat === 'keterangan') {
        formKeterangan.style.display = 'block';
      }
    }

    // Initialize datetimepicker
    $(document).ready(function() {
      $('#myDatepicker4').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#myDatepicker5').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#myDatepicker6').datetimepicker({
        format: 'YYYY-MM-DD'
      });
      $('#myDatepicker7').datetimepicker({
        format: 'YYYY-MM-DD'
      });
    });
  </script>
</body>

</html>