<?php
session_start();
include "login/ceksession.php";
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
  <!-- Flatpickr Date Picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Input Data Pengunjung</h1>
            <p>Pencatatan data kunjungan wisatawan baru di Desa Wisata Candirejo</p>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <h2>Formulir Data Pengunjung Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputdatapengunjung.php" name="forminputdatapengunjung" method="post"
              id="demo-form2" data-parsley-validate enctype="multipart/form-data">

              <!-- INFORMASI KUNJUNGAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                Informasi Kunjungan
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="tanggal_kunjungan">Tanggal Kunjungan <span class="req">*</span>
                  <small>Waktu kedatangan wisatawan</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_kunjungan" name="tanggal_kunjungan" required="required"
                      class="form-input datepicker" placeholder="Pilih Tanggal Kunjungan" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_kunjungan')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="pilihan_paket_wisata">Pilihan Paket Wisata <span class="req">*</span>
                  <small>Pilih program wisata yang diambil</small>
                </label>
                <div>
                  <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" required="required" class="form-select">
                    <option value="">-- Pilih Paket Wisata --</option>
                    <option value="meal_only">Breakfast/Lunch/Dinner Only</option>
                    <option value="studi_banding">Studi Banding</option>
                    <option value="fun_game">Paket Fun Game</option>
                    <option value="pelajar_live_in">Paket Pelajar - Live In Candirejo</option>
                    <option value="pelajar_field_trip_one_day">Paket Pelajar – Field Trip One Day</option>
                    <option value="pelajar_field_trip_half_day">Paket Pelajar – Field Trip Half Day</option>
                    <option value="cycling_tour">Cycling Village Tour with/without Lunch</option>
                    <option value="traditional_dance">Traditional Dance</option>
                    <option value="walking_tour">Walking Around Village with/without Lunch</option>
                    <option value="homestay">Stay At Local House In Candirejo Village (Homestay)</option>
                    <option value="serenade">Serenade At The Foot Of Menoreh Hill</option>
                    <option value="cooking_lesson">Cooking Lesson with/without Tour</option>
                    <option value="gamelan_class">Gamelan Class with/without Lunch</option>
                    <option value="village_experience">Village Experience</option>
                    <option value="dokar_tour">Dokar Village Tour with/without Lunch</option>
                    <option value="inspection">Inspection</option>
                    <option value="lainnya">Lainnya</option>
                  </select>
                </div>
              </div>

              <!-- Sub opsi paket -->
              <div class="form-row form-group" id="opsi_makan_tour_group" style="display:none;">
                <label class="form-label" for="opsi_makan_tour">Opsi Makan Tour
                  <small>Dengan atau tanpa makan siang</small>
                </label>
                <div>
                  <select id="opsi_makan_tour" name="opsi_makan_tour" class="form-select input-md">
                    <option value="">-- Pilih Opsi Makan --</option>
                    <option value="without_lunch">Without Lunch</option>
                    <option value="with_lunch">With Lunch</option>
                  </select>
                </div>
              </div>

              <div class="form-row form-group" id="jenis_makanan_paket_group" style="display:none;">
                <label class="form-label" for="jenis_makanan_paket">Jenis Makanan
                  <small>Waktu penyajian makanan</small>
                </label>
                <div>
                  <select id="jenis_makanan_paket" name="jenis_makanan_paket" class="form-select input-md">
                    <option value="">-- Pilih Jenis Makanan --</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                  </select>
                </div>
              </div>

              <div class="form-row form-group" id="opsi_cooking_lesson_group" style="display:none;">
                <label class="form-label" for="opsi_cooking_lesson">Opsi Cooking Lesson
                  <small>Pilihan paket memasak</small>
                </label>
                <div>
                  <select id="opsi_cooking_lesson" name="opsi_cooking_lesson" class="form-select input-md">
                    <option value="">-- Pilih Opsi Cooking --</option>
                    <option value="lesson_only">Lesson Only</option>
                    <option value="lesson_with_tour">Lesson With Tour</option>
                  </select>
                </div>
              </div>

              <div class="form-row form-group" id="opsi_gamelan_group" style="display:none;">
                <label class="form-label" for="opsi_gamelan">Opsi Gamelan Class
                  <small>Pilihan paket gamelan</small>
                </label>
                <div>
                  <select id="opsi_gamelan" name="opsi_gamelan" class="form-select input-md">
                    <option value="">-- Pilih Opsi Gamelan --</option>
                    <option value="without_lunch">Without Lunch</option>
                    <option value="with_lunch">With Lunch</option>
                  </select>
                </div>
              </div>

              <!-- DATA WISATAWAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Data Wisatawan
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="jenis_wisatawan">Jenis Wisatawan <span class="req">*</span>
                  <small>Domestik atau Mancanegara</small>
                </label>
                <div>
                  <select id="jenis_wisatawan" name="jenis_wisatawan" required="required" class="form-select input-md">
                    <option value="">-- Pilih Jenis Wisatawan --</option>
                    <option value="Domestik">Domestik</option>
                    <option value="Mancanegara">Mancanegara</option>
                  </select>
                </div>
              </div>

              <div class="form-row form-group" id="kota-group" style="display:none;">
                <label class="form-label" for="kota">Kota Asal <span class="req">*</span>
                  <small>Kota asal wisatawan domestik</small>
                </label>
                <div>
                  <input type="text" id="kota" name="kota" maxlength="100" placeholder="Contoh: Yogyakarta, Jakarta, Semarang"
                    class="form-input">
                </div>
              </div>

              <div class="form-row form-group" id="negara-group" style="display:none;">
                <label class="form-label" for="negara">Negara Asal <span class="req">*</span>
                  <small>Negara asal wisatawan mancanegara</small>
                </label>
                <div>
                  <input type="text" id="negara" name="negara" maxlength="100" placeholder="Contoh: Netherlands, Australia, Japan"
                    class="form-input">
                </div>
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="nama">Nama Pengunjung <span class="req">*</span>
                  <small>Nama pengunjung atau nama grup</small>
                </label>
                <div>
                  <input type="text" id="nama" name="nama" required="required" maxlength="100"
                    placeholder="Masukkan nama pengunjung" class="form-input">
                </div>
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="pax">Jumlah Wisatawan (Pax) <span class="req">*</span>
                  <small>Jumlah orang / peserta</small>
                </label>
                <div>
                  <input type="number" id="pax" name="pax" required="required" min="1" placeholder="0"
                    class="form-input">
                </div>
              </div>

              <!-- AGEN & PEMANDU -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/></svg>
                Agen &amp; Pemandu
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="agen_wisata">Agen Wisata
                  <small>Opsional jika menggunakan agen</small>
                </label>
                <div>
                  <input type="text" id="agen_wisata" name="agen_wisata" maxlength="100"
                    placeholder="Nama agen wisata (opsional)" class="form-input">
                </div>
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="driver_agent_guide">Driver / Agent Guide <span class="req">*</span>
                  <small>Default: Belum Ada</small>
                </label>
                <div>
                  <input type="text" id="driver_agent_guide" name="driver_agent_guide" maxlength="100" required="required"
                    value="Belum Ada" placeholder="Nama Driver / Agent Guide" class="form-input">
                </div>
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="local_guide">Local Guide <span class="req">*</span>
                  <small>Default: Belum Ada</small>
                </label>
                <div>
                  <input type="text" id="local_guide" name="local_guide" maxlength="100" required="required"
                    value="Belum Ada" placeholder="Nama Local Guide" class="form-input">
                </div>
              </div>

              <!-- DOKUMENTASI FOTO -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Dokumentasi Foto
              </div>

              <div class="form-row form-group">
                <label class="form-label" for="foto">Upload Foto Kunjungan
                  <small>Dokumentasi foto kunjungan (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('foto').click()">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Klik untuk memilih foto kunjungan</div>
                    <div class="upload-text-sub">Format: JPG, JPEG, PNG (Maks. 2 MB per foto, bisa pilih lebih dari 1)</div>
                  </div>
                  <input type="file" id="foto" name="foto[]" accept="image/*" multiple class="hidden-file-input">
                  <div id="foto-preview" class="file-list-preview"></div>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                  Simpan Data Pengunjung
                </button>
                <button type="reset" class="btn-reset">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                  Reset
                </button>
                <a href="datapengunjung.php" class="btn-cancel" style="margin-left:auto;">
                  Batal
                </a>
              </div>

            </form>
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
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  // Flatpickr for date fields
  flatpickr(".datepicker", {
    locale: "id",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d F Y",
    allowInput: false,
    disableMobile: false
  });

  $(document).ready(function() {

    $('#pilihan_paket_wisata').change(function() {
        let paket = $(this).val();
        
        // Reset dan hide semua optional fields
        $('#opsi_makan_tour_group').hide();
        $('#jenis_makanan_paket_group').hide();
        $('#opsi_cooking_lesson_group').hide();
        $('#opsi_gamelan_group').hide();
        
        // Reset values
        $('#opsi_makan_tour').val('');
        $('#jenis_makanan_paket').val('');
        $('#opsi_cooking_lesson').val('');
        $('#opsi_gamelan').val('');
        
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
        if (paket === 'gamelan_class') {
            $('#opsi_gamelan_group').show();
        }
    });

    // === MULTI-FILE UPLOAD: akumulasi file dari beberapa kali buka file manager ===
    var selectedFiles = [];

    function renderFotoPreview() {
        var preview = $('#foto-preview');
        preview.empty();
        selectedFiles.forEach(function(file, idx) {
            var reader = new FileReader();
            reader.onload = (function(f, i) {
                return function(e) {
                    preview.append(
                        '<div style="position:relative;display:inline-block;margin:4px;" id="fpreview-' + i + '">' +
                        '<img src="' + e.target.result + '" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">' +
                        '<div style="font-size:10px;text-align:center;color:#555;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + f.name + '</div>' +
                        '<button type="button" onclick="removeSelectedFile(' + i + ')" ' +
                        'style="position:absolute;top:-6px;right:-6px;background:#e74c3c;color:white;border:none;border-radius:50%;width:18px;height:18px;font-size:11px;cursor:pointer;line-height:17px;padding:0;text-align:center;">✕</button>' +
                        '</div>'
                    );
                };
            })(file, idx);
            reader.readAsDataURL(file);
        });

        // Sync ke input file via DataTransfer supaya ter-submit
        var dt = new DataTransfer();
        selectedFiles.forEach(function(f) { dt.items.add(f); });
        document.getElementById('foto').files = dt.files;
    }

    window.removeSelectedFile = function(index) {
        selectedFiles.splice(index, 1);
        renderFotoPreview();
    };

    $('#foto').on('change', function() {
        var newFiles = Array.from(this.files);
        var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        var maxSize = 2 * 1024 * 1024; // 2 MB
        var rejectedType = [];
        var rejectedSize = [];

        newFiles.forEach(function(newFile) {
            // Validasi tipe
            if (!allowedTypes.includes(newFile.type)) {
                rejectedType.push(newFile.name);
                return;
            }
            // Validasi ukuran
            if (newFile.size > maxSize) {
                rejectedSize.push(newFile.name);
                return;
            }
            // Hindari duplikat
            var isDuplicate = selectedFiles.some(function(f) {
                return f.name === newFile.name && f.size === newFile.size;
            });
            if (!isDuplicate) {
                selectedFiles.push(newFile);
            }
        });

        // Tampilkan peringatan jika ada yang ditolak
        if (rejectedType.length > 0 || rejectedSize.length > 0) {
            var msg = '';
            if (rejectedType.length > 0) {
                msg += '<b>Format tidak didukung</b> (harus JPG/JPEG/PNG):<br>' + rejectedType.map(function(n){ return '• ' + n; }).join('<br>') + '<br><br>';
            }
            if (rejectedSize.length > 0) {
                msg += '<b>Melebihi batas 2 MB:</b><br>' + rejectedSize.map(function(n){ return '• ' + n; }).join('<br>');
            }
            Swal.fire({
                title: 'Foto Tidak Valid',
                html: '<div style="font-family:\'Poppins\',sans-serif;text-align:left;font-size:14px;color:#555;">' + msg + '</div>',
                icon: 'warning',
                iconColor: '#f39c12',
                confirmButtonText: 'Mengerti',
                background: '#fff',
                color: '#1a1a2e',
                customClass: { popup: 'swal-custom-popup', title: 'swal-custom-title' }
            });
        }

        renderFotoPreview();
    });

    // Reset selectedFiles saat tombol Reset diklik
    $('button[type="reset"]').on('click', function() {
        selectedFiles = [];
        $('#foto-preview').empty();
    });
    // === END MULTI-FILE UPLOAD ===

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
