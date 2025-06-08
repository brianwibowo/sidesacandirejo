<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
ob_start();

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
$data_pengunjung = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$data_pengunjung) {
  echo "<script>alert('Data tidak ditemukan!'); window.location='datapengunjung.php';</script>";
  exit;
}

?>

<!DOCTYPE html>
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
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                    <!-- Hidden input untuk ID -->
                    <input type="hidden" name="id"
                      value="<?php echo htmlspecialchars($data_pengunjung['id'] ?? $id); ?>">
                    <!-- Debug output to check values -->
                    <?php
                    // Uncomment for debugging
                    // echo "<!-- Debug: ID from URL: $id, ID from data: " . ($data_pengunjung['id'] ?? 'not set') . " -->"; 
                    ?>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kunjungan">Tanggal Kunjungan
                        <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker6'>
                          <input type='text' id="tanggal_kunjungan" name="tanggal_kunjungan" required="required"
                            class="form-control" readonly="readonly"
                            value="<?php echo htmlspecialchars(date('d-m-Y', strtotime($data_pengunjung['tanggal_kunjungan'] ?? ''))); ?>" />
                          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pilihan_paket_wisata">Pilihan Paket
                        Wisata <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" required="required"
                          class="form-control col-md-7 col-xs-12">
                          <option value="">--</option>
                          <option value="meal_only" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'meal_only') ? 'selected' : ''; ?>>Breakfast/Lunch/Dinner Only</option>
                          <option value="studi_banding" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'studi_banding') ? 'selected' : ''; ?>>Studi
                            Banding</option>
                          <option value="fun_game" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'fun_game') ? 'selected' : ''; ?>>Paket Fun Game</option>
                          <option value="pelajar_live_in" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_live_in') ? 'selected' : ''; ?>>Paket
                            Pelajar - Live In Candirejo</option>
                          <option value="pelajar_field_trip_one_day" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_field_trip_one_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip One Day</option>
                          <option value="pelajar_field_trip_half_day" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_field_trip_half_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip Half Day</option>
                          <option value="cycling_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'cycling_tour') ? 'selected' : ''; ?>>Cycling
                            Village Tour with/without Lunch</option>
                          <option value="traditional_dance" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'traditional_dance') ? 'selected' : ''; ?>>
                            Traditional Dance</option>
                          <option value="walking_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'walking_tour') ? 'selected' : ''; ?>>Walking
                            Around Village with/without Lunch</option>
                          <option value="homestay" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'homestay') ? 'selected' : ''; ?>>Stay At Local House In Candirejo Village (Homestay)</option>
                          <option value="serenade" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'serenade') ? 'selected' : ''; ?>>Serenade At The Foot Of Menoreh Hill</option>
                          <option value="cooking_lesson" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'cooking_lesson') ? 'selected' : ''; ?>>Cooking
                            lesson with/without Tour</option>
                          <option value="village_experience" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'village_experience') ? 'selected' : ''; ?>>
                            Village Experience</option>
                          <option value="dokar_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'dokar_tour') ? 'selected' : ''; ?>>Dokar
                            Village
                            Tour with/without Lunch</option>
                        </select>
                      </div>
                    </div>


                    <div class="form-group" id="opsi_makan_tour_group" style="display:block;">
                      <label class="control-label col-md-3" for="opsi_makan_tour">Opsi Makan Tour</label>
                      <div class="col-md-9">
                        <select id="opsi_makan_tour" name="opsi_makan_tour" class="form-control">
                          <option value="">--</option>
                          <option value="without_lunch" <?php echo ($data_pengunjung['opsi_makan_tour'] === 'without_lunch') ? 'selected' : ''; ?>>Without Lunch
                          </option>
                          <option value="with_lunch" <?php echo ($data_pengunjung['opsi_makan_tour'] === 'with_lunch') ? 'selected' : ''; ?>>With Lunch</option>
                        </select>

                      </div>
                    </div>


                    <div class="form-group" id="jenis_makanan_paket_group" style="display:none;">
                      <label class="control-label col-md-3" for="jenis_makanan_paket">Jenis Makanan</label>
                      <div class="col-md-9">
                        <select id="jenis_makanan_paket" name="jenis_makanan_paket" class="form-control">
                          <option value="">--</option>
                          <option value="breakfast" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'breakfast') ? 'selected' : ''; ?>>Breakfast
                          </option>
                          <option value="lunch" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'lunch') ? 'selected' : ''; ?>>Lunch</option>
                          <option value="dinner" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'dinner') ? 'selected' : ''; ?>>Dinner</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="opsi_cooking_lesson_group" style="display:none;">
                      <label class="control-label col-md-3" for="opsi_cooking_lesson">Opsi Cooking Lesson</label>
                      <div class="col-md-9">
                        <select id="opsi_cooking_lesson" name="opsi_cooking_lesson" class="form-control">
                          <option value="">--</option>
                          <option value="lesson_only" <?php echo (isset($data_pengunjung['opsi_cooking_lesson']) && $data_pengunjung['opsi_cooking_lesson'] == 'lesson_only') ? 'selected' : ''; ?>>Lesson Only
                          </option>
                          <option value="lesson_with_tour" <?php echo (isset($data_pengunjung['opsi_cooking_lesson']) && $data_pengunjung['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'selected' : ''; ?>>
                            Lesson With Tour</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jenis_wisatawan">Jenis Wisatawan
                        <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="jenis_wisatawan" name="jenis_wisatawan" required="required"
                          class="form-control col-md-7 col-xs-12">
                          <option value="">--</option>
                          <option value="Domestik" <?php echo (isset($data_pengunjung['jenis_wisatawan']) && $data_pengunjung['jenis_wisatawan'] == 'Domestik') ? 'selected' : ''; ?>>Domestik</option>
                          <option value="Mancanegara" <?php echo (isset($data_pengunjung['jenis_wisatawan']) && $data_pengunjung['jenis_wisatawan'] == 'Mancanegara') ? 'selected' : ''; ?>>Mancanegara
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group" id="kota-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kota">Kota <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="kota" name="kota" maxlength="100" placeholder="Masukkan Kota"
                          class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['kota'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group" id="negara-group" style="display:none;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="negara">Negara <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="negara" name="negara" maxlength="100" placeholder="Masukkan Negara"
                          class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['negara'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama" name="nama" required="required" maxlength="100"
                          placeholder="Masukkan Nama Pengunjung" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['nama'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pax">Jumlah Wisatawan (Pax) <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="number" id="pax" name="pax" required="required" min="1"
                          placeholder="Masukkan Jumlah Pax" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['pax'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agen_wisata">Agen Wisata
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="agen_wisata" name="agen_wisata" maxlength="100"
                          placeholder="Masukkan Agen Wisata (Opsional)" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['agen_wisata'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="driver_agent_guide">Driver/Agent Guide
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="driver_agent_guide" name="driver_agent_guide" maxlength="100"
                          placeholder="Masukkan Nama Driver/Agent Guide (Opsional)" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['driver_agent_guide'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="local_guide">Local Guide
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="local_guide" name="local_guide" maxlength="100"
                          placeholder="Masukkan Nama Local Guide (Opsional)" class="form-control col-md-7 col-xs-12"
                          value="<?php echo htmlspecialchars($data_pengunjung['local_guide'] ?? ''); ?>">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="foto">Foto
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($data_pengunjung['foto'])): ?>
                          <img src="../admin/uploads/pengunjung/<?php echo htmlspecialchars($data_pengunjung['foto']); ?>" 
                               alt="Foto Pengunjung" style="max-width: 200px; margin-bottom: 10px;"><br>
                        <?php endif; ?>
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control col-md-7 col-xs-12">
                        <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB)</small>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="datapengunjung.php" class="btn btn-primary">Kembali</a>
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
  $(document).ready(function () {
    $('#myDatepicker6').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD'
    });
  
    function showHideFields() {
      let paket = $('#pilihan_paket_wisata').val();
  
      // Hide semua optional fields terlebih dahulu
      $('#opsi_makan_tour_group').hide();
      $('#jenis_makanan_paket_group').hide();
      $('#opsi_cooking_lesson_group').hide();
  
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
    }
  
    $('#pilihan_paket_wisata').change(function () {
      let paket = $(this).val();
  
      // Reset values HANYA jika user mengubah pilihan (bukan saat load)
      if ($(this).data('user-changed')) {
        $('#opsi_makan_tour').val('');
        $('#jenis_makanan_paket').val('');
        $('#opsi_cooking_lesson').val('');
      }
  
      showHideFields();
      
      // Set flag bahwa user sudah mengubah pilihan
      $(this).data('user-changed', true);
    });
  
    $('#jenis_wisatawan').change(function () {
      const jenis = $(this).val();
  
      if (jenis == 'Domestik') {
        $('#kota-group').show();
        $('#negara-group').hide();
        $('#kota').attr('required', true);
        $('#negara').removeAttr('required').val('');
      } else if (jenis == 'Mancanegara') {
        $('#kota-group').hide();
        $('#negara-group').show();
        $('#negara').attr('required', true);
        $('#kota').removeAttr('required').val('');
      } else {
        $('#kota-group').hide();
        $('#negara-group').hide();
        $('#kota').removeAttr('required').val('');
        $('#negara').removeAttr('required').val('');
      }
    });
  
    // Initialize pada saat load pertama kali TANPA mereset values
    showHideFields();
    
    const initialJenis = $('#jenis_wisatawan').val();
    if (initialJenis) {
      $('#jenis_wisatawan').trigger('change');
    }
  });
  </script>
</body>

</html>
<?php ob_end_flush(); // Tambahkan ini ?>