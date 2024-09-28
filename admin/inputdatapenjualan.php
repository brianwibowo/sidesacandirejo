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
                  <h2>Input Data Penjualan</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_inputdatapenjualan.php" name="forminputdatapenjualan" method="post"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_wisatawan">Jenis Produk<span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="jenis_produk" name="produk" required="required"
                          class="form-control col-md-7 col-xs-12">
                          <option value="Listrik">Listrik</option>
                          <option value="Pulsa">Pulsa</option>
                          <option value="Paket Wisata">Paket Wisata</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="pilihan_paket_wisata" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilihan_paket_wisata">Pilihan Paket
                        Wisata<span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="pilihan_paket_wisata" name="pilihan_paket_wisata"
                          class="form-control col-md-7 col-xs-12">
                          <option value="">--</option>
                          <option value="Paket Fun Game">Paket Fun Game</option>
                          <option value="Paket Pelajar - Live In Candirejo">Paket Pelajar - Live In Candirejo</option>
                          <option value="Paket Pelajar – Field Trip One Day">Paket Pelajar – Field Trip One Day</option>
                          <option value="Paket Pelajar – Field Trip Half Day">Paket Pelajar – Field Trip Half Day
                          </option>
                          <option value="Cycling Village Tour Candirejo">Cycling Village Tour Candirejo</option>
                          <option value="Traditional Dance">Traditional Dance</option>
                          <option value="Walking Around Village">Walking Around Village</option>
                          <option value="Stay At Local House In Candirejo Village (Homestay)">Stay At Local House In
                            Candirejo Village (Homestay)</option>
                          <option value="Serenade At The Foot Of Menoreh Hill">Serenade At The Foot Of Menoreh Hill
                          </option>
                          <option value="Cooking Lesson">Cooking Lesson</option>
                          <option value="Village Experience">Village Experience</option>
                          <option value="Dokar Village Tour Candirejo">Dokar Village Tour Candirejo</option>
                        </select>
                      </div>
                    </div>

                    <script>
                    document.getElementById('jenis_produk').addEventListener('change', function() {
                      var pilihanPaketWisata = document.getElementById('pilihan_paket_wisata');

                      if (this.value === 'Paket Wisata') {
                        pilihanPaketWisata.style.display = 'block';
                      } else {
                        pilihanPaketWisata.style.display = 'none';
                      }
                    });
                    </script>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah">Jumlah<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="jumlah" name="jumlah" required="required" maxlength="100"
                          placeholder="Masukkan Jumlah Pembelian" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="harga">Harga<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="harga" name="harga" required="required" maxlength="100"
                          placeholder="Masukkan Harga" class="form-control col-md-7 col-xs-12">
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
    $('#jenis_wisatawan').change(function() {
      if ($(this).val() == 'Domestik') {
        $('#kota-group').show();
        $('#negara-group').hide();
      } else if ($(this).val() == 'Mancanegara') {
        $('#kota-group').hide();
        $('#negara-group').show();
      } else {
        $('#kota-group').hide();
        $('#negara-group').hide();
      }
    });
  });
  </script>
</body>

</html>