<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
ob_start();

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
  echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>body{font-family:"Poppins",sans-serif;background:#f4f6f9;}.swal-custom-popup{border-radius:20px!important;padding:30px 20px!important;font-family:"Poppins",sans-serif!important;}</style>
  </head><body>
  <script>
  Swal.fire({title:"ID Tidak Valid!",html:"<p style=\'color:#555;font-size:15px;\'>Parameter ID tidak ditemukan atau tidak valid.</p>",icon:"error",iconColor:"#e74c3c",confirmButtonText:"Kembali",background:"#fff",color:"#1a1a2e",customClass:{popup:"swal-custom-popup"}}).then(()=>{window.location.href="datapengunjung.php";});
  </script></body></html>';
  exit;
}

// Ambil data pengunjung berdasarkan ID
$query = "SELECT * FROM tb_data_pengunjung WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_pengunjung = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$data_pengunjung) {
  echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>body{font-family:"Poppins",sans-serif;background:#f4f6f9;}.swal-custom-popup{border-radius:20px!important;padding:30px 20px!important;font-family:"Poppins",sans-serif!important;}</style>
  </head><body>
  <script>
  Swal.fire({title:"Data Tidak Ditemukan!",html:"<p style=\'color:#555;font-size:15px;\'>Data pengunjung yang dicari tidak ditemukan di sistem.</p>",icon:"error",iconColor:"#e74c3c",confirmButtonText:"Kembali",background:"#fff",color:"#1a1a2e",customClass:{popup:"swal-custom-popup"}}).then(()=>{window.location.href="datapengunjung.php";});
  </script></body></html>';
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
  <link rel="icon" type="image/x-icon" href="../img/icon.ico">
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <style>
    .foto-thumb {
      position: relative; display: inline-block;
    }
    .foto-thumb img {
      width: 80px; height: 80px; object-fit: cover;
      border-radius: 8px; border: 1.5px solid #b5d5c0;
    }
    .foto-thumb .nama-file {
      font-size: 9px; text-align: center; color: #6b8f7e;
      max-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .foto-thumb .btn-remove {
      position: absolute; top: -6px; right: -6px;
      background: #e74c3c; color: white; border: none;
      border-radius: 50%; width: 20px; height: 20px;
      font-size: 11px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }
    .flatpickr-input { background: #fff !important; }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Data Pengunjung</h1>
            <p>Perbarui data kunjungan wisatawan Desa Wisata Candirejo</p>
          </div>
          <a href="datapengunjung.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Pengunjung
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <h2>Edit Data Pengunjung</h2>
            </div>
          </div>

          <div class="form-card-body">
            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Anda sedang mengedit data pengunjung: <strong><?php echo htmlspecialchars($data_pengunjung['nama'] ?? ''); ?></strong>
            </div>

            <form action="proses/proses_editdatapengunjung.php" name="formeditdatapengunjung" method="post"
              id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

              <!-- Hidden input untuk ID -->
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($data_pengunjung['id'] ?? $id); ?>">

              <!-- INFORMASI KUNJUNGAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                Informasi Kunjungan
              </div>

              <!-- Tanggal Kunjungan -->
              <div class="form-row">
                <label class="form-label">Tanggal Kunjungan <span class="req">*</span>
                  <small>Waktu kedatangan wisatawan</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_kunjungan" name="tanggal_kunjungan" required
                      class="form-input datepicker" placeholder="Pilih Tanggal"
                      value="<?php echo htmlspecialchars($data_pengunjung['tanggal_kunjungan'] ?? ''); ?>"
                      autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_kunjungan')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Pilihan Paket Wisata -->
              <div class="form-row">
                <label class="form-label">Pilihan Paket Wisata <span class="req">*</span>
                  <small>Pilih program wisata</small>
                </label>
                <div>
                  <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" required class="form-select">
                    <option value="">--</option>
                    <option value="meal_only" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'meal_only') ? 'selected' : ''; ?>>Breakfast/Lunch/Dinner Only</option>
                    <option value="studi_banding" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'studi_banding') ? 'selected' : ''; ?>>Studi Banding</option>
                    <option value="fun_game" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'fun_game') ? 'selected' : ''; ?>>Paket Fun Game</option>
                    <option value="pelajar_live_in" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_live_in') ? 'selected' : ''; ?>>Paket Pelajar - Live In Candirejo</option>
                    <option value="pelajar_field_trip_one_day" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_field_trip_one_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip One Day</option>
                    <option value="pelajar_field_trip_half_day" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'pelajar_field_trip_half_day') ? 'selected' : ''; ?>>Paket Pelajar – Field Trip Half Day</option>
                    <option value="cycling_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'cycling_tour') ? 'selected' : ''; ?>>Cycling Village Tour with/without Lunch</option>
                    <option value="traditional_dance" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'traditional_dance') ? 'selected' : ''; ?>>Traditional Dance</option>
                    <option value="walking_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'walking_tour') ? 'selected' : ''; ?>>Walking Around Village with/without Lunch</option>
                    <option value="homestay" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'homestay') ? 'selected' : ''; ?>>Stay At Local House In Candirejo Village (Homestay)</option>
                    <option value="serenade" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'serenade') ? 'selected' : ''; ?>>Serenade At The Foot Of Menoreh Hill</option>
                    <option value="cooking_lesson" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'cooking_lesson') ? 'selected' : ''; ?>>Cooking Lesson with/without Tour</option>
                    <option value="gamelan_class" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'gamelan_class') ? 'selected' : ''; ?>>Gamelan Class with/without Lunch</option>
                    <option value="village_experience" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'village_experience') ? 'selected' : ''; ?>>Village Experience</option>
                    <option value="dokar_tour" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'dokar_tour') ? 'selected' : ''; ?>>Dokar Village Tour with/without Lunch</option>
                    <option value="inspection" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'inspection') ? 'selected' : ''; ?>>Inspection</option>
                    <option value="lainnya" <?php echo ($data_pengunjung['pilihan_paket_wisata'] === 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                  </select>
                </div>
              </div>

              <!-- Sub opsi paket -->
              <div class="form-row" id="opsi_makan_tour_group" style="display:block;">
                <label class="form-label">Opsi Makan Tour
                  <small>Dengan atau tanpa makan</small>
                </label>
                <div>
                  <select id="opsi_makan_tour" name="opsi_makan_tour" class="form-select input-md">
                    <option value="">--</option>
                    <option value="without_lunch" <?php echo ($data_pengunjung['opsi_makan_tour'] === 'without_lunch') ? 'selected' : ''; ?>>Without Lunch</option>
                    <option value="with_lunch" <?php echo ($data_pengunjung['opsi_makan_tour'] === 'with_lunch') ? 'selected' : ''; ?>>With Lunch</option>
                  </select>
                </div>
              </div>

              <div class="form-row" id="jenis_makanan_paket_group" style="display:none;">
                <label class="form-label">Jenis Makanan
                  <small>Waktu penyajian makanan</small>
                </label>
                <div>
                  <select id="jenis_makanan_paket" name="jenis_makanan_paket" class="form-select input-md">
                    <option value="">--</option>
                    <option value="breakfast" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'breakfast') ? 'selected' : ''; ?>>Breakfast</option>
                    <option value="lunch" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'lunch') ? 'selected' : ''; ?>>Lunch</option>
                    <option value="dinner" <?php echo (isset($data_pengunjung['jenis_makanan_paket']) && $data_pengunjung['jenis_makanan_paket'] == 'dinner') ? 'selected' : ''; ?>>Dinner</option>
                  </select>
                </div>
              </div>

              <div class="form-row" id="opsi_cooking_lesson_group" style="display:none;">
                <label class="form-label">Opsi Cooking Lesson
                  <small>Pilihan paket memasak</small>
                </label>
                <div>
                  <select id="opsi_cooking_lesson" name="opsi_cooking_lesson" class="form-select input-md">
                    <option value="">--</option>
                    <option value="lesson_only" <?php echo (isset($data_pengunjung['opsi_cooking_lesson']) && $data_pengunjung['opsi_cooking_lesson'] == 'lesson_only') ? 'selected' : ''; ?>>Lesson Only</option>
                    <option value="lesson_with_tour" <?php echo (isset($data_pengunjung['opsi_cooking_lesson']) && $data_pengunjung['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'selected' : ''; ?>>Lesson With Tour</option>
                  </select>
                </div>
              </div>

              <div class="form-row" id="opsi_gamelan_group" style="display:none;">
                <label class="form-label">Opsi Gamelan Class
                  <small>Pilihan paket gamelan</small>
                </label>
                <div>
                  <select id="opsi_gamelan" name="opsi_gamelan" class="form-select input-md">
                    <option value="">--</option>
                    <option value="without_lunch" <?php echo (isset($data_pengunjung['opsi_gamelan']) && $data_pengunjung['opsi_gamelan'] == 'without_lunch') ? 'selected' : ''; ?>>Without Lunch</option>
                    <option value="with_lunch" <?php echo (isset($data_pengunjung['opsi_gamelan']) && $data_pengunjung['opsi_gamelan'] == 'with_lunch') ? 'selected' : ''; ?>>With Lunch</option>
                  </select>
                </div>
              </div>

              <!-- DATA WISATAWAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Data Wisatawan
              </div>

              <!-- Jenis Wisatawan -->
              <div class="form-row">
                <label class="form-label">Jenis Wisatawan <span class="req">*</span>
                  <small>Domestik atau Mancanegara</small>
                </label>
                <div>
                  <select id="jenis_wisatawan" name="jenis_wisatawan" required class="form-select input-md">
                    <option value="">--</option>
                    <option value="Domestik" <?php echo (isset($data_pengunjung['jenis_wisatawan']) && $data_pengunjung['jenis_wisatawan'] == 'Domestik') ? 'selected' : ''; ?>>Domestik</option>
                    <option value="Mancanegara" <?php echo (isset($data_pengunjung['jenis_wisatawan']) && $data_pengunjung['jenis_wisatawan'] == 'Mancanegara') ? 'selected' : ''; ?>>Mancanegara</option>
                  </select>
                </div>
              </div>

              <!-- Kota (Domestik) -->
              <div class="form-row" id="kota-group" style="display:none;">
                <label class="form-label">Kota Asal <span class="req">*</span>
                  <small>Kota asal wisatawan domestik</small>
                </label>
                <div>
                  <input type="text" id="kota" name="kota" maxlength="100" placeholder="Contoh: Yogyakarta, Jakarta"
                    class="form-input" value="<?php echo htmlspecialchars($data_pengunjung['kota'] ?? ''); ?>">
                </div>
              </div>

              <!-- Negara (Mancanegara) -->
              <div class="form-row" id="negara-group" style="display:none;">
                <label class="form-label">Negara Asal <span class="req">*</span>
                  <small>Negara asal wisatawan mancanegara</small>
                </label>
                <div>
                  <input type="text" id="negara" name="negara" maxlength="100" placeholder="Contoh: Netherlands, Australia"
                    class="form-input" value="<?php echo htmlspecialchars($data_pengunjung['negara'] ?? ''); ?>">
                </div>
              </div>

              <!-- Nama -->
              <div class="form-row">
                <label class="form-label">Nama Pengunjung <span class="req">*</span>
                  <small>Nama atau nama grup</small>
                </label>
                <div>
                  <input type="text" id="nama" name="nama" required maxlength="100"
                    placeholder="Masukkan Nama Pengunjung" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengunjung['nama'] ?? ''); ?>">
                </div>
              </div>

              <!-- Pax -->
              <div class="form-row">
                <label class="form-label">Jumlah Wisatawan (Pax) <span class="req">*</span>
                  <small>Total orang dalam rombongan</small>
                </label>
                <div>
                  <input type="number" id="pax" name="pax" required min="1"
                    placeholder="Masukkan Jumlah Pax" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengunjung['pax'] ?? ''); ?>">
                </div>
              </div>

              <!-- Agen Wisata -->
              <div class="form-row">
                <label class="form-label">Agen Wisata
                  <small>Nama agen (opsional)</small>
                </label>
                <div>
                  <input type="text" id="agen_wisata" name="agen_wisata" maxlength="100"
                    placeholder="Masukkan Agen Wisata" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengunjung['agen_wisata'] ?? ''); ?>">
                </div>
              </div>

              <!-- Driver/Agent Guide -->
              <div class="form-row">
                <label class="form-label">Driver/Agent Guide
                  <small>Nama driver atau agent guide</small>
                </label>
                <div>
                  <input type="text" id="driver_agent_guide" name="driver_agent_guide" maxlength="100"
                    placeholder="Masukkan Nama Driver/Agent Guide" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengunjung['driver_agent_guide'] ?? ''); ?>">
                </div>
              </div>

              <!-- Local Guide -->
              <div class="form-row">
                <label class="form-label">Local Guide
                  <small>Nama pemandu lokal</small>
                </label>
                <div>
                  <input type="text" id="local_guide" name="local_guide" maxlength="100"
                    placeholder="Masukkan Nama Local Guide" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengunjung['local_guide'] ?? ''); ?>">
                </div>
              </div>

              <!-- Foto -->
              <div class="form-row">
                <label class="form-label">Foto Kunjungan
                  <small>Foto pendukung (opsional)</small>
                </label>
                <div>
                  <?php
                  $foto_existing = [];
                  if (!empty($data_pengunjung['foto'])) {
                    $decoded = json_decode($data_pengunjung['foto'], true);
                    $foto_existing = is_array($decoded) ? $decoded : [$data_pengunjung['foto']];
                  }
                  $foto_lama = [];
                  $stmt_foto = mysqli_prepare($db, "SELECT nama_file FROM tb_foto_pengunjung WHERE id_pengunjung = ?");
                  mysqli_stmt_bind_param($stmt_foto, "i", $id);
                  mysqli_stmt_execute($stmt_foto);
                  $result_foto = mysqli_stmt_get_result($stmt_foto);
                  while ($row_foto = mysqli_fetch_assoc($result_foto)) {
                    $foto_lama[] = $row_foto['nama_file'];
                  }
                  $semua_foto = array_merge($foto_existing, $foto_lama);
                  ?>

                  <?php if (!empty($semua_foto)): ?>
                    <input type="hidden" name="existing_foto_sent" value="1">
                    <p class="form-hint" style="margin-bottom:8px;">Foto saat ini <small>(klik ✕ untuk hapus)</small>:</p>
                    <div id="existing-foto-container" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
                    <?php foreach($semua_foto as $f): ?>
                      <div class="foto-thumb" id="existing-<?php echo htmlspecialchars(md5($f)); ?>">
                        <img src="../admin/uploads/pengunjung/<?php echo htmlspecialchars($f); ?>" alt="Foto">
                        <div class="nama-file"><?php echo htmlspecialchars($f); ?></div>
                        <input type="hidden" name="existing_foto[]" value="<?php echo htmlspecialchars($f); ?>" id="input-<?php echo htmlspecialchars(md5($f)); ?>">
                        <button type="button" class="btn-remove"
                          onclick="removeExistingFoto('<?php echo htmlspecialchars(md5($f)); ?>')"
                          title="Hapus foto">✕</button>
                      </div>
                    <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <div class="upload-area-modern" onclick="document.getElementById('foto').click()">
                    <div class="upload-icon"><i class="fa fa-image"></i></div>
                    <div class="upload-text-main">Klik untuk tambah foto baru</div>
                    <div class="upload-text-sub">Format: JPG, PNG, JPEG (Maks 2MB per foto)</div>
                  </div>
                  <input type="file" id="foto" name="foto[]" accept="image/*" multiple class="hidden-file-input">
                  <div id="foto-preview" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <a href="datapengunjung.php" class="btn-cancel">
                  <i class="fa fa-times"></i> Batal
                </a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  // Flatpickr
  flatpickr(".datepicker", {
    locale: "id",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d F Y",
    allowInput: false,
    disableMobile: false
  });

  $(document).ready(function () {
    function showHideFields() {
      let paket = $('#pilihan_paket_wisata').val();
      $('#opsi_makan_tour_group').hide();
      $('#jenis_makanan_paket_group').hide();
      $('#opsi_cooking_lesson_group').hide();
      $('#opsi_gamelan_group').hide();
      if (paket === 'cycling_tour' || paket === 'dokar_tour' || paket === 'walking_tour') {
        $('#opsi_makan_tour_group').show();
      }
      if (paket === 'meal_only') { $('#jenis_makanan_paket_group').show(); }
      if (paket === 'cooking_lesson') { $('#opsi_cooking_lesson_group').show(); }
      if (paket === 'gamelan_class') { $('#opsi_gamelan_group').show(); }
    }

    $('#pilihan_paket_wisata').change(function () {
      if ($(this).data('user-changed')) {
        $('#opsi_makan_tour').val('');
        $('#jenis_makanan_paket').val('');
        $('#opsi_cooking_lesson').val('');
        $('#opsi_gamelan').val('');
      }
      showHideFields();
      $(this).data('user-changed', true);
    });

    // Init tampilan berdasar nilai saat ini
    showHideFields();

    // Jenis wisatawan
    function updateWisatawanFields() {
      const jenis = $('#jenis_wisatawan').val();
      if (jenis == 'Domestik') {
        $('#kota-group').show(); $('#negara-group').hide();
        $('#kota').attr('required', true); $('#negara').removeAttr('required');
      } else if (jenis == 'Mancanegara') {
        $('#kota-group').hide(); $('#negara-group').show();
        $('#negara').attr('required', true); $('#kota').removeAttr('required');
      } else {
        $('#kota-group').hide(); $('#negara-group').hide();
        $('#kota').removeAttr('required'); $('#negara').removeAttr('required');
      }
    }
    $('#jenis_wisatawan').change(updateWisatawanFields);
    updateWisatawanFields();

    // === MULTI-FILE UPLOAD ===
    var selectedFiles = [];

    function renderFotoPreview() {
      var preview = $('#foto-preview');
      preview.empty();
      selectedFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = (function(f, i) {
          return function(e) {
            preview.append(
              '<div class="foto-thumb" id="fpreview-' + i + '">' +
              '<img src="' + e.target.result + '">' +
              '<div class="nama-file">' + f.name + '</div>' +
              '<button type="button" class="btn-remove" onclick="removeSelectedFile(' + i + ')">✕</button>' +
              '</div>'
            );
          };
        })(file, idx);
        reader.readAsDataURL(file);
      });
      var dt = new DataTransfer();
      selectedFiles.forEach(function(f) { dt.items.add(f); });
      document.getElementById('foto').files = dt.files;
    }

    window.removeSelectedFile = function(index) {
      selectedFiles.splice(index, 1);
      renderFotoPreview();
    };

    window.removeExistingFoto = function(hash) {
      var wrapper = document.getElementById('existing-' + hash);
      var hiddenInput = document.getElementById('input-' + hash);
      if (wrapper) wrapper.style.display = 'none';
      if (hiddenInput) hiddenInput.disabled = true;
    };

    $('#foto').on('change', function() {
      var newFiles = Array.from(this.files);
      var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
      var maxSize = 2 * 1024 * 1024;
      newFiles.forEach(function(newFile) {
        if (!allowedTypes.includes(newFile.type) || newFile.size > maxSize) return;
        var isDuplicate = selectedFiles.some(function(f) { return f.name === newFile.name && f.size === newFile.size; });
        if (!isDuplicate) selectedFiles.push(newFile);
      });
      renderFotoPreview();
    });

    $('button[type="reset"]').on('click', function() {
      selectedFiles = [];
      $('#foto-preview').empty();
    });
  });
  </script>
</body>
</html>
<?php ob_end_flush(); ?>
