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
  <!-- Select2 -->
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
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
                  <h2>Input Data Pengunjung</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_inputdatapengunjung.php" name="forminputdatapengunjung" method="post"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">


                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kunjungan">Tanggal Kunjungan
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker6'>
                          <input type='text' id="tanggal_kunjungan" name="tanggal_kunjungan" required="required"
                            class="form-control" readonly="readonly" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilihan_paket_wisata">Pilihan Paket Wisata
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" required="required"
                          class="form-control col-md-7 col-xs-12">
                          <option value="">--</option>
                          <option value="meal_only">Breakfast/Lunch/Diner Only</option>
                          <option value="studi_banding">Studi Banding</option>
                          <option value="fun_game">Paket Fun Game</option>
                          <option value="pelajar_live_in">Paket Pelajar - Live In Candirejo</option>
                          <option value="pelajar_field_trip">Paket Pelajar – Field Trip</option>
                          <option value="cycling_tour">Cycling Village Tour with/without Lunch</option>
                          <option value="traditional_dance">Traditional Dance</option>
                          <option value="walking_tour">Walking Around Village with/without Lunch</option>
                          <option value="homestay">Stay At Local House In Candirejo Village (Homestay)</option>
                          <option value="serenade">Serenade At The Foot Of Menoreh Hill</option>
                          <option value="cooking_lesson">Cooking lesson with/without Tour</option>
                          <option value="village_experience">Village Experience</option>
                          <option value="dokar_tour">Dokar Village Tour with/without Lunch</option>
                          <option value="inspection">Inspection</option>
                          <option value="lainnya">Lainnya</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="opsi_makan_tour_group" style="display:none;">
                      <label class="control-label col-md-3" for="opsi_makan_tour">Opsi Makan Tour</label>
                      <div class="col-md-9">
                        <select id="opsi_makan_tour" name="opsi_makan_tour" class="form-control">
                          <option value="">--</option>
                          <option value="without_lunch">Without Lunch</option>
                          <option value="with_lunch">With Lunch</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="jenis_makanan_paket_group" style="display:none;">
                      <label class="control-label col-md-3" for="jenis_makanan_paket">Jenis Makanan</label>
                      <div class="col-md-9">
                        <select id="jenis_makanan_paket" name="jenis_makanan_paket" class="form-control">
                          <option value="">--</option>
                          <option value="breakfast">Breakfast</option>
                          <option value="lunch">Lunch</option>
                          <option value="dinner">Dinner</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="opsi_cooking_lesson_group" style="display:none;">
                      <label class="control-label col-md-3" for="opsi_cooking_lesson">Opsi Cooking Lesson</label>
                      <div class="col-md-9">
                        <select id="opsi_cooking_lesson" name="opsi_cooking_lesson" class="form-control">
                          <option value="">--</option>
                          <option value="lesson_only">Lesson Only</option>
                          <option value="lesson_with_tour">Lesson With Tour</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_wisatawan">Jenis Wisatawan
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="jenis_wisatawan" name="jenis_wisatawan" required="required"
                          class="form-control col-md-7 col-xs-12">
                          <option value="">--</option>
                          <option value="Domestik">Domestik</option>
                          <option value="Mancanegara">Mancanegara</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="kota-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kota" name="kota" maxlength="100" placeholder="Masukkan Kota"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group" id="negara-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="negara">Negara <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="negara" name="negara" maxlength="100" placeholder="Masukkan Negara"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama" name="nama" required="required" maxlength="100"
                          placeholder="Masukkan Nama Pengunjung" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pax">Jumlah Wisatawan (Pax) <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="number" id="pax" name="pax" required="required" min="1" placeholder="Masukkan Jumlah Pax"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agen_wisata">Agen Wisata
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="agen_wisata" name="agen_wisata" maxlength="100"
                          placeholder="Masukkan Agen Wisata (Opsional)" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver_agent_guide">Driver/Agent Guide
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="driver_agent_guide" name="driver_agent_guide" maxlength="100" required="required"
                          placeholder="Masukkan Nama Driver/Agent Guide" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="local_guide">Local Guide
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="local_guide" name="local_guide" maxlength="100" required="required"
                          placeholder="Masukkan Nama Local Guide" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="foto">Foto
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control col-md-7 col-xs-12">
                        <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
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

  <script>
  $(document).ready(function() {
    $('#myDatepicker6').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD'
    });

    $('#pilihan_paket_wisata').change(function() {
        let paket = $(this).val();
        
        // Reset dan hide semua optional fields
        $('#opsi_makan_tour_group').hide();
        $('#jenis_makanan_paket_group').hide();
        $('#opsi_cooking_lesson_group').hide();
        
        // Reset values
        $('#opsi_makan_tour').val('');
        $('#jenis_makanan_paket').val('');
        $('#opsi_cooking_lesson').val('');
        
        // Show relevant fields based on selected package
        if (paket === 'cycling_tour' || paket === 'dokar_tour' || paket === 'walking_tour') {
            $('#opsi_makan_tour_group').show();
        }
        if (paket === 'meal_only') {
            $('#jenis_makanan_paket_group').show();
        }
        if (paket === 'cooking_lesson') {
            $('#opsi_cooking_lesson_group').show();
        }
    });

    $('#jenis_wisatawan').change(function() {
        const jenis = $(this).val();
        
        if (jenis == 'Domestik') {
            $('#kota-group').show();
            $('#negara-group').hide();
            $('#kota').attr('required', true);
            $('#negara').removeAttr('required').val(''); // Clear negara field
        } else if (jenis == 'Mancanegara') {
            $('#kota-group').hide();
            $('#negara-group').show();
            $('#negara').attr('required', true);
            $('#kota').removeAttr('required').val(''); // Clear kota field
        } else {
            $('#kota-group').hide();
            $('#negara-group').hide();
            $('#kota').removeAttr('required').val('');
            $('#negara').removeAttr('required').val('');
        }
    });
  });
  </script>
</body>

</html>