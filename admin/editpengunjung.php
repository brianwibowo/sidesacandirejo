<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    echo "<script>alert('ID tidak valid!'); window.location='datapengunjung.php';</script>";
    exit;
}

// Ambil data pengunjung berdasarkan ID dengan prepared statement untuk keamanan
$query = "SELECT * FROM tb_data_pengunjung WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_array($result);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='datapengunjung.php';</script>";
    exit;
}
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Pengunjung - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Edit Data Pengunjung</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_editdatapengunjung.php" name="formeditdatapengunjung" method="post"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                    <!-- Hidden input untuk ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kunjungan">Tanggal Kunjungan <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker6'>
                          <input type='text' id="tanggal_kunjungan" name="tanggal_kunjungan" required="required" class="form-control" readonly="readonly" value="<?php echo htmlspecialchars($data['tanggal_kunjungan']); ?>" />
                          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilihan_paket_wisata">Pilihan Paket Wisata <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" required="required" class="form-control col-md-7 col-xs-12">
                          <option value="">-- Pilih Paket Wisata --</option>
                          <option value="meal_only" <?php echo ($data['pilihan_paket_wisata'] == 'meal_only') ? 'selected' : ''; ?>>Breakfast/Lunch/Dinner Only</option>
                          <option value="studi_banding" <?php echo ($data['pilihan_paket_wisata'] == 'studi_banding') ? 'selected' : ''; ?>>Studi Banding</option>
                          <option value="fun_game" <?php echo ($data['pilihan_paket_wisata'] == 'fun_game') ? 'selected' : ''; ?>>Paket Fun Game</option>
                          <option value="pelajar_live_in" <?php echo ($data['pilihan_paket_wisata'] == 'pelajar_live_in') ? 'selected' : ''; ?>>Paket Pelajar - Live In Candirejo</option>
                          <option value="pelajar_field_trip_one_day" <?php echo ($data['pilihan_paket_wisata'] == 'pelajar_field_trip_one_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip One Day</option>
                          <option value="pelajar_field_trip_half_day" <?php echo ($data['pilihan_paket_wisata'] == 'pelajar_field_trip_half_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip Half Day</option>
                          <option value="cycling_tour" <?php echo ($data['pilihan_paket_wisata'] == 'cycling_tour') ? 'selected' : ''; ?>>Cycling Village Tour Candirejo</option>
                          <option value="traditional_dance" <?php echo ($data['pilihan_paket_wisata'] == 'traditional_dance') ? 'selected' : ''; ?>>Traditional Dance</option>
                          <option value="walking_tour" <?php echo ($data['pilihan_paket_wisata'] == 'walking_tour') ? 'selected' : ''; ?>>Walking Around Village</option>
                          <option value="homestay" <?php echo ($data['pilihan_paket_wisata'] == 'homestay') ? 'selected' : ''; ?>>Stay At Local House In Candirejo Village (Homestay)</option>
                          <option value="serenade" <?php echo ($data['pilihan_paket_wisata'] == 'serenade') ? 'selected' : ''; ?>>Serenade At The Foot Of Menoreh Hill</option>
                          <option value="cooking_lesson" <?php echo ($data['pilihan_paket_wisata'] == 'cooking_lesson') ? 'selected' : ''; ?>>Cooking Lesson</option>
                          <option value="village_experience" <?php echo ($data['pilihan_paket_wisata'] == 'village_experience') ? 'selected' : ''; ?>>Village Experience</option>
                          <option value="dokar_tour" <?php echo ($data['pilihan_paket_wisata'] == 'dokar_tour') ? 'selected' : ''; ?>>Dokar Village Tour Candirejo</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="opsi_makan_tour_group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="opsi_makan_tour">Opsi Makan Siang</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="opsi_makan_tour" name="opsi_makan_tour" class="form-control col-md-7 col-xs-12">
                          <option value="">-- Pilih Opsi --</option>
                          <option value="without_lunch" <?php echo ($data['opsi_makan_tour'] == 'without_lunch') ? 'selected' : ''; ?>>Without Lunch</option>
                          <option value="with_lunch" <?php echo ($data['opsi_makan_tour'] == 'with_lunch') ? 'selected' : ''; ?>>With Lunch</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group" id="opsi_jenis_makanan_group" style="display:none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_makanan">Pilih Jenis Makanan <span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select id="jenis_makanan" name="jenis_makanan" class="form-control col-md-7 col-xs-12">
                                <option value="">-- Pilih Jenis Makanan --</option>
                                <option value="breakfast" <?php echo ($data['jenis_makanan_paket'] == 'breakfast') ? 'selected' : ''; ?>>Breakfast Only</option>
                                <option value="lunch" <?php echo ($data['jenis_makanan_paket'] == 'lunch') ? 'selected' : ''; ?>>Lunch Only</option>
                                <option value="dinner" <?php echo ($data['jenis_makanan_paket'] == 'dinner') ? 'selected' : ''; ?>>Dinner Only</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="opsi_cooking_lesson_group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="opsi_cooking_tour">Opsi Tambahan (Cooking)</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="opsi_cooking_tour" name="opsi_cooking_tour" class="form-control col-md-7 col-xs-12">
                          <option value="">-- Pilih Opsi --</option>
                          <option value="lesson_only" <?php echo ($data['opsi_cooking_lesson'] == 'lesson_only') ? 'selected' : ''; ?>>Cooking Lesson Only</option>
                          <option value="lesson_with_tour" <?php echo ($data['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'selected' : ''; ?>>Cooking Lesson with Tour</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_wisatawan">Jenis Wisatawan <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="jenis_wisatawan" name="jenis_wisatawan" required="required" class="form-control col-md-7 col-xs-12">
                          <option value="">-- Pilih Jenis Wisatawan --</option>
                          <option value="Domestik" <?php echo ($data['jenis_wisatawan'] == 'Domestik') ? 'selected' : ''; ?>>Domestik</option>
                          <option value="Mancanegara" <?php echo ($data['jenis_wisatawan'] == 'Mancanegara') ? 'selected' : ''; ?>>Mancanegara</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group" id="kota-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kota" name="kota" maxlength="100" placeholder="Masukkan Kota" class="form-control col-md-7 col-xs-12" value="<?php echo htmlspecialchars($data['kota'] ?? ''); ?>">
                      </div>
                    </div>
                    
                    <div class="form-group" id="negara-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="negara">Negara <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="negara" name="negara" maxlength="100" placeholder="Masukkan Negara" class="form-control col-md-7 col-xs-12" value="<?php echo htmlspecialchars($data['negara'] ?? ''); ?>">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Kontak/Perwakilan <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama" name="nama" required="required" maxlength="100" placeholder="Masukkan Nama Kontak Pengunjung" class="form-control col-md-7 col-xs-12" value="<?php echo htmlspecialchars($data['nama']); ?>">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pax">Jumlah Wisatawan (Pax) <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="number" id="pax" name="pax" required="required" min="1" placeholder="Masukkan Jumlah Pax" class="form-control col-md-7 col-xs-12" value="<?php echo htmlspecialchars($data['pax']); ?>">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agen_wisata">Agen Wisata</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="agen_wisata" name="agen_wisata" maxlength="100" placeholder="Masukkan Agen Wisata (Opsional)" class="form-control col-md-7 col-xs-12" value="<?php echo htmlspecialchars($data['agen_wisata'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Update Data</button>
                        <a href="datapengunjung.php" class="btn btn-secondary">Kembali</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include("footer.php"); ?>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  $(document).ready(function() {
    // Inisialisasi Datepicker
    $('#myDatepicker6').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD'
    });

    // Fungsi untuk mereset semua opsi tambahan
    function resetExtraOptions() {
        $('#opsi_makan_tour_group').hide();
        $('#opsi_makan_tour').prop('required', false);

        $('#opsi_jenis_makanan_group').hide();
        $('#jenis_makanan').prop('required', false);

        $('#opsi_cooking_lesson_group').hide();
        $('#opsi_cooking_tour').prop('required', false);
    }
    
    // Fungsi untuk menampilkan opsi berdasarkan paket yang dipilih
    function showOptionsForPackage(selectedPaket) {
      resetExtraOptions();

      if (selectedPaket === 'cycling_tour' || selectedPaket === 'dokar_tour' || selectedPaket === 'walking_tour') {
        $('#opsi_makan_tour_group').show();
      } else if (selectedPaket === 'meal_only') {
        $('#opsi_jenis_makanan_group').show();
        $('#jenis_makanan').prop('required', true);
      } else if (selectedPaket === 'cooking_lesson') {
        $('#opsi_cooking_lesson_group').show();
      }
    }
    
    // Logika untuk menampilkan/menyembunyikan opsi tambahan berdasarkan pilihan paket utama
    $('#pilihan_paket_wisata').change(function() {
      var selectedPaket = $(this).val();
      showOptionsForPackage(selectedPaket);
    });

    // Logika untuk jenis wisatawan (Kota/Negara)
    $('#jenis_wisatawan').change(function() {
      var jenis = $(this).val();
      $('#kota-group').hide();
      $('#kota').prop('required', false);
      $('#negara-group').hide();
      $('#negara').prop('required', false);

      if (jenis == 'Domestik') {
        $('#kota-group').show();
        $('#kota').prop('required', true);
      } else if (jenis == 'Mancanegara') {
        $('#negara-group').show();
        $('#negara').prop('required', true);
      }
    });

    // Inisialisasi tampilan berdasarkan data yang sudah ada
    var currentPaket = $('#pilihan_paket_wisata').val();
    if (currentPaket) {
      showOptionsForPackage(currentPaket);
    }
    
    var currentJenis = $('#jenis_wisatawan').val();
    if (currentJenis) {
      $('#jenis_wisatawan').trigger('change');
    }
  });
  </script>
</body>
</html>