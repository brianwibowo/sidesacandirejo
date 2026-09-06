<?php
session_start();
include "login/ceksession.php";
?>
<!DOCTYPE html>

<html lang="en">

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
  <!-- Flatpickr Date Picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <style>
    .upload-area {
      border: 2px dashed #3498db;
      border-radius: 8px;
      padding: 30px 20px;
      text-align: center;
      cursor: pointer;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
    }

    .upload-area:hover {
      background-color: #ecf0f1;
      border-color: #2980b9;
    }

    .upload-area.dragover {
      background-color: #d4e8f5;
      border-color: #2980b9;
      box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
    }

    .upload-area.required-error {
      border-color: #e74c3c;
      background-color: #fdecea;
      box-shadow: 0 0 10px rgba(231, 76, 60, 0.25);
    }

    .upload-icon {
      font-size: 32px;
      color: #3498db;
      margin-bottom: 10px;
    }

    .file-list-preview {
      margin-top: 15px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .file-item {
      position: relative;
      display: inline-block;
      background: white;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .file-item.image {
      width: 80px;
      height: 80px;
      padding: 4px;
    }

    .file-item.image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 3px;
    }

    .file-item.pdf,
    .file-item.doc {
      padding: 10px;
      min-width: 120px;
    }

    .file-remove {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #e74c3c;
      color: white;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
    }

    .file-remove:hover {
      background: #c0392b;
    }

    .hidden-file-input {
      display: none;
    }

    .file-type-badge {
      display: inline-block;
      background: #3498db;
      color: white;
      padding: 3px 6px;
      border-radius: 3px;
      font-size: 10px;
      margin-top: 4px;
    }

    .upload-required-message {
      display: none;
      color: #e74c3c;
      font-weight: 600;
      margin-top: 8px;
    }

    .upload-required-message.show {
      display: block;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebarmenu -->
      <?php include("sidebarmenu.php"); ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php include("header.php"); ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Tambah Surat Keluar</h1>
            <p>Isi formulir pencatatan arsip surat keluar baru Desa Wisata Candirejo</p>
          </div>
          <a href="datasuratkeluar.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Surat Keluar
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              </div>
              <h2>Formulir Surat Keluar Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputsuratkeluar.php" name="formsuratkeluar" method="post"
              enctype="multipart/form-data" id="demo-form2" novalidate>
              <?php
              include '../koneksi/koneksi.php';
              $query = "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar";
              $result = mysqli_query($db, $query);
              $row = mysqli_fetch_assoc($result);
              $next_no = ($row['last_no'] ?? 0) + 1;
              ?>

              <!-- INFORMASI SURAT -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Informasi Surat
              </div>

              <!-- Nomor Urut -->
              <div class="form-row form-group">
                <label class="form-label">Nomor Urut <span class="req">*</span>
                  <small>Nomor urut otomatis</small>
                </label>
                <div>
                  <input type="text" value="<?php echo $next_no; ?>" id="No" name="No" required="required" maxlength="4"
                    placeholder="Nomor Urut" class="form-input input-sm" readonly>
                </div>
              </div>

              <!-- Jenis Surat -->
              <div class="form-row form-group">
                <label class="form-label">Jenis Surat <span class="req">*</span>
                  <small>Keterangan atau Undangan</small>
                </label>
                <div>
                  <select id="jenis_surat" name="jenis_surat" required="required" class="form-select input-md">
                    <option value="">-- Pilih Jenis Surat --</option>
                    <option value="keterangan">Surat Keterangan</option>
                    <option value="undangan">Surat Undangan</option>
                  </select>
                </div>
              </div>

              <!-- Tanggal Keluar -->
              <div class="form-row form-group">
                <label class="form-label">Tanggal Keluar <span class="req">*</span>
                  <small>Tanggal penerbitan surat keluar</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_keluar" name="tanggal_keluar" required="required"
                      class="form-input datepicker" placeholder="Pilih Tanggal Keluar" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_keluar')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Nomor Surat -->
              <div class="form-row form-group">
                <label class="form-label">Nomor Surat <span class="req">*</span>
                  <small>Nomor resmi surat keluar</small>
                </label>
                <div>
                  <input type="text" id="nomor_surat" name="nomor_surat" required="required" maxlength="50"
                    placeholder="Masukkan Nomor Surat" class="form-input">
                </div>
              </div>

              <!-- Penerima -->
              <div class="form-row form-group">
                <label class="form-label">Penerima <span class="req">*</span>
                  <small>Tujuan pihak atau instansi penerima</small>
                </label>
                <div>
                  <input type="text" id="penerima" name="penerima" required="required"
                    placeholder="Masukkan Nama Penerima" class="form-input">
                </div>
              </div>

              <!-- DETAIL KEGIATAN (UNDANGAN ONLY) -->
              <div class="form-row form-group undangan-field" style="display:none;">
                <label class="form-label">Tempat Acara <span class="req">*</span>
                  <small>Lokasi pelaksanaan acara/undangan</small>
                </label>
                <div>
                  <input type="text" id="tempat_acara" name="tempat_acara" maxlength="150"
                    placeholder="Masukkan Tempat Acara" class="form-input">
                </div>
              </div>

              <div class="form-row form-group undangan-field" style="display:none;">
                <label class="form-label">Tanggal Kegiatan <span class="req">*</span>
                  <small>Tanggal waktu pelaksanaan acara</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_kegiatan" name="tanggal_kegiatan"
                      class="form-input datepicker" placeholder="Pilih Tanggal Kegiatan" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_kegiatan')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-row form-group undangan-field" style="display:none;">
                <label class="form-label">Jam Kegiatan <span class="req">*</span>
                  <small>Waktu mulai kegiatan</small>
                </label>
                <div>
                  <input type="time" id="jam_kegiatan" name="jam_kegiatan" class="form-input input-sm">
                </div>
              </div>

              <!-- PERIHAL & KETERANGAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Perihal &amp; Keterangan
              </div>

              <!-- Perihal -->
              <div class="form-row form-group">
                <label class="form-label">Perihal <span class="req">*</span>
                  <small>Ringkasan maksud isi surat</small>
                </label>
                <div>
                  <input type="text" id="perihal" name="perihal" required="required"
                    placeholder="Masukkan Perihal Surat" class="form-input">
                </div>
              </div>

              <!-- Keterangan -->
              <div class="form-row form-group">
                <label class="form-label">Keterangan
                  <small>Catatan tambahan (opsional)</small>
                </label>
                <div>
                  <textarea id="keterangan" name="keterangan" class="form-input" rows="3"
                    style="resize:vertical;min-height:75px;" placeholder="Masukkan Keterangan Surat"></textarea>
                </div>
              </div>

              <!-- BERKAS & LAMPIRAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas &amp; Dokumen Lampiran
              </div>

              <!-- Upload File Surat -->
              <div class="form-row form-group">
                <label class="form-label">Upload File Surat <span class="req">*</span>
                  <small>Berkas surat keluar (PDF wajib)</small>
                </label>
                <div>
                  <div class="upload-area-modern" id="upload-area-surat" data-field="file_surat">
                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                    <div class="upload-text-main">Drag file PDF ke sini atau klik untuk memilih</div>
                    <div class="upload-text-sub">Format PDF, maksimal ukuran 10 MB (1 file wajib)</div>
                  </div>
                  <input type="file" name="file_surat" id="file_surat" accept="application/pdf" class="hidden-file-input" />
                  <div class="file-list-preview" id="preview-surat"></div>
                  <div id="file-surat-required" class="upload-required-message">File Surat wajib diisi.</div>
                </div>
              </div>

              <!-- Upload Absensi (undangan only) -->
              <div class="form-row form-group undangan-field" style="display:none;">
                <label class="form-label">Upload Absensi
                  <small>Daftar hadir kegiatan (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" id="upload-area-absensi" data-field="file_absensi">
                    <div class="upload-icon"><i class="fa fa-list-alt"></i></div>
                    <div class="upload-text-main">Drag berkas absensi ke sini atau klik untuk memilih</div>
                    <div class="upload-text-sub">PDF, JPG, PNG (Bisa lebih dari 1 file)</div>
                  </div>
                  <input type="file" name="file_absensi[]" id="file_absensi" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div class="file-list-preview" id="preview-absensi"></div>
                </div>
              </div>

              <!-- Upload Notulen (undangan only) -->
              <div class="form-row form-group undangan-field" style="display:none;">
                <label class="form-label">Upload Notulen
                  <small>Notulensi hasil pertemuan (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" id="upload-area-notulen" data-field="file_notulen">
                    <div class="upload-icon"><i class="fa fa-file-text-o"></i></div>
                    <div class="upload-text-main">Drag berkas notulen ke sini atau klik untuk memilih</div>
                    <div class="upload-text-sub">PDF, JPG, PNG (Bisa lebih dari 1 file)</div>
                  </div>
                  <input type="file" name="file_notulen[]" id="file_notulen" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div class="file-list-preview" id="preview-notulen"></div>
                </div>
              </div>

              <!-- Upload Foto/Dokumentasi -->
              <div class="form-row form-group">
                <label class="form-label">Upload Foto / Dokumentasi
                  <small>Dokumentasi kegiatan (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" id="upload-area-dokumentasi" data-field="file_dokumentasi">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Drag foto ke sini atau klik untuk memilih</div>
                    <div class="upload-text-sub">JPG, PNG, WebP, GIF (Maks. 2 MB/foto, bisa lebih dari 1 file)</div>
                  </div>
                  <input type="file" name="file_dokumentasi[]" id="file_dokumentasi" accept="image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div class="file-list-preview" id="preview-dokumentasi"></div>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                  Simpan Surat
                </button>
                <button type="reset" class="btn-reset">
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                  Reset
                </button>
                <button type="button" class="btn-cancel" onclick="kembaliPage()" style="margin-left:auto;">
                  Batal
                </button>
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
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
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

      // Multi-file upload handler dengan preview dan drag-drop
      const uploadFields = ['file_surat', 'file_absensi', 'file_notulen', 'file_dokumentasi'];
      const singleFileFields = ['file_surat'];
      const fileStorage = {
        file_surat: [],
        file_absensi: [],
        file_notulen: [],
        file_dokumentasi: []
      };
      const jenisSuratSelect = $('#jenis_surat');
      const undanganFieldGroups = $('.undangan-field');
      const tempatAcaraInput = $('#tempat_acara');
      const tanggalKegiatanInput = $('#tanggal_kegiatan');
      const jamKegiatanInput = $('#jam_kegiatan');
      const fileSuratArea = $('#upload-area-surat');
      const fileSuratRequiredMsg = $('#file-surat-required');

      function clearUndanganFile(fieldName) {
        fileStorage[fieldName] = [];
        updateFileInput(fieldName);
        const previewContainer = $('#preview-' + fieldName.replace('file_', ''));
        updatePreview(fieldName, previewContainer);
      }

      function toggleJenisSuratFields() {
        const isUndangan = jenisSuratSelect.val() === 'undangan';

        undanganFieldGroups.toggle(isUndangan);

        tempatAcaraInput.prop('required', isUndangan);
        tanggalKegiatanInput.prop('required', isUndangan);
        jamKegiatanInput.prop('required', isUndangan);

        if (!isUndangan) {
          tempatAcaraInput.val('');
          tanggalKegiatanInput.val('');
          jamKegiatanInput.val('');
          clearUndanganFile('file_absensi');
          clearUndanganFile('file_notulen');
        }

        [tempatAcaraInput, tanggalKegiatanInput, jamKegiatanInput].forEach(function($input) {
          $input.closest('.form-group').removeClass('has-error');
          $input.closest('.form-group').find('.custom-error-msg').remove();
        });
      }

      function showFileSuratRequiredError() {
        fileSuratArea.addClass('required-error');
        fileSuratRequiredMsg.addClass('show');
        $('html, body').animate({
          scrollTop: fileSuratArea.offset().top - 120
        }, 400);
      }

      function clearFileSuratRequiredError() {
        fileSuratArea.removeClass('required-error');
        fileSuratRequiredMsg.removeClass('show');
      }

      uploadFields.forEach(fieldName => {
        const uploadArea = $('[data-field="' + fieldName + '"]');
        const fileInput = $('#' + fieldName);
        const previewContainer = $('#preview-' + fieldName.replace('file_', ''));

        // Drag-drop events
        uploadArea.on('dragover dragenter', function(e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).addClass('dragover');
        });

        uploadArea.on('dragleave', function(e) {
          $(this).removeClass('dragover');
        });

        uploadArea.on('drop', function(e) {
          e.preventDefault();
          e.stopPropagation();
          $(this).removeClass('dragover');
          const files = e.originalEvent.dataTransfer.files;
          // JANGAN set fileInput files langsung, biarkan handleFileSelect yang manage
          handleFileSelect(files, fieldName, previewContainer);
        });

        // Click to select
        uploadArea.on('click', function() {
          if (fieldName === 'file_surat') {
            clearFileSuratRequiredError();
          }
          fileInput.click();
        });

        // File input change
        fileInput.on('change', function() {
          handleFileSelect(this.files, fieldName, previewContainer);
        });
      });

      function handleFileSelect(files, fieldName, previewContainer) {
        const selectedFiles = Array.from(files);

        if (singleFileFields.includes(fieldName)) {
          // Field single file: ganti file lama dengan file terbaru.
          fileStorage[fieldName] = selectedFiles.length > 0 ? [selectedFiles[0]] : [];
        } else {
          // Field multi file: append, bukan replace.
          fileStorage[fieldName] = fileStorage[fieldName].concat(selectedFiles);
        }

        updatePreview(fieldName, previewContainer);
        updateFileInput(fieldName);

        if (fieldName === 'file_surat' && fileStorage.file_surat.length > 0) {
          clearFileSuratRequiredError();
        }
      }

      toggleJenisSuratFields();
      jenisSuratSelect.on('change', toggleJenisSuratFields);

      function updateFileInput(fieldName) {
        // Update file input dengan semua files yang ada di fileStorage
        const fileInput = $('#' + fieldName);
        const dataTransfer = new DataTransfer();
        fileStorage[fieldName].forEach(file => dataTransfer.items.add(file));
        fileInput[0].files = dataTransfer.files;
      }

      function updatePreview(fieldName, previewContainer) {
        previewContainer.empty();
        const files = fileStorage[fieldName];

        files.forEach((file, index) => {
          const ext = file.name.split('.').pop().toLowerCase();
          const isImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);
          const isPdf = ext === 'pdf';

          let fileItemHtml = '<div class="file-item ' + (isImage ? 'image' : 'pdf') + '" data-index="' + index + '">';
          fileItemHtml += '<div class="file-remove" onclick="removeFileByIndex(\'' + fieldName + '\', ' + index + ')" title="Hapus file ini">×</div>';

          if (isImage) {
            fileItemHtml += '<img src="" alt="preview" />';
          } else if (isPdf) {
            fileItemHtml += '<div style="padding: 15px;"><span class="fa fa-file-pdf-o" style="font-size: 32px; color: #e74c3c;"></span>';
            fileItemHtml += '<div style="font-size: 11px; margin-top: 5px; word-break: break-word;">' + file.name.substring(0, 15) + (file.name.length > 15 ? '...' : '') + '</div>';
            fileItemHtml += '<span class="file-type-badge">PDF</span></div>';
          } else {
            fileItemHtml += '<div style="padding: 10px;"><span class="fa fa-file" style="font-size: 24px; color: #3498db;"></span>';
            fileItemHtml += '<div style="font-size: 11px; margin-top: 5px; word-break: break-word;">' + file.name.substring(0, 15) + (file.name.length > 15 ? '...' : '') + '</div>';
            fileItemHtml += '<span class="file-type-badge">' + ext.toUpperCase() + '</span></div>';
          }

          fileItemHtml += '</div>';
          const $item = $(fileItemHtml);
          previewContainer.append($item);

          // Load image preview untuk foto
          if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
              $item.find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
          }
        });
      }

      window.removeFileByIndex = function(fieldName, index) {
        fileStorage[fieldName].splice(index, 1);
        const fileInput = $('#' + fieldName);
        const dataTransfer = new DataTransfer();
        fileStorage[fieldName].forEach(file => dataTransfer.items.add(file));
        fileInput[0].files = dataTransfer.files;
        const previewContainer = $('#preview-' + fieldName.replace('file_', ''));
        updatePreview(fieldName, previewContainer);
      };

      // Backward-compatible alias.
      window.removeFile = window.removeFileByIndex;

      // ── Validasi required fields (ganti Parsley/browser native) ────────
      function validateField($input) {
        var val = $input.val() ? $input.val().trim() : '';
        if ($input.attr('required') && val === '') {
          $input.closest('.form-group').addClass('has-error');
          // Untuk field di dalam input-group (datepicker), taruh error SETELAH input-group bukan di dalam
          var $container = $input.closest('.input-group').length
            ? $input.closest('.input-group')
            : $input;
          if ($input.closest('.form-group').find('.custom-error-msg').length === 0) {
            $container.after('<span class="help-block custom-error-msg" style="color:#e74c3c;font-weight:600;">Field ini wajib diisi.</span>');
          }
          return false;
        } else {
          $input.closest('.form-group').removeClass('has-error');
          $input.closest('.form-group').find('.custom-error-msg').remove();
          return true;
        }
      }

      // Live clear error saat diisi
      $('#demo-form2').on('input change', '[required]', function() {
        validateField($(this));
      });

      $('#demo-form2').on('submit', function(e) {
        var valid = true;
        var $firstError = null;

        // Validasi semua required field teks/textarea
        $(this).find('[required]').each(function() {
          if (!validateField($(this))) {
            valid = false;
            if (!$firstError) $firstError = $(this);
          }
        });

        // Validasi file surat
        if (fileStorage.file_surat.length === 0) {
          valid = false;
          showFileSuratRequiredError();
          if (!$firstError) $firstError = $('#upload-area-surat');
        }

        if (!valid) {
          e.preventDefault();
          if ($firstError) {
            $('html, body').animate({ scrollTop: $firstError.offset().top - 120 }, 400);
          }
          return false;
        }
      });

      // ── Tombol Kembali — langsung navigasi, tidak ada validasi ──────
      window.kembaliPage = function() {
        clearFileSuratRequiredError();
        window.location.href = 'datasuratkeluar.php';
      };

      $('#demo-form2').on('reset', function() {
        const $form = $(this);
        const currentScrollTop = $(window).scrollTop();

        // Lepas fokus aktif agar browser tidak melompat ke field tanggal keluar.
        if (document.activeElement) {
          $(document.activeElement).blur();
        }

        // Tunggu reset native selesai, lalu bersihkan state custom.
        setTimeout(function() {
          uploadFields.forEach(fieldName => {
            fileStorage[fieldName] = [];
            updateFileInput(fieldName);
            const previewContainer = $('#preview-' + fieldName.replace('file_', ''));
            updatePreview(fieldName, previewContainer);
          });

          clearFileSuratRequiredError();

          // Bersihkan error custom validasi
          $form.find('.has-error').removeClass('has-error');
          $form.find('.custom-error-msg').remove();

          // Pastikan tidak ada fokus tersisa di field tanggal, lalu kembalikan posisi scroll.
          $('#tanggal_keluar, #tanggal_kegiatan').blur();
          $(window).scrollTop(currentScrollTop);
        }, 0);
      });
    });

    <?php if (isset($_GET['status'])): ?>
      window.addEventListener('DOMContentLoaded', function() {
        <?php if ($_GET['status'] === 'success'): ?>
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data surat keluar berhasil disimpan.',
            confirmButtonColor: '#26B99A'
          });
        <?php elseif ($_GET['status'] === 'error'): ?>
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= htmlspecialchars($_GET["msg"] ?? "Terjadi kesalahan.") ?>',
            confirmButtonColor: '#e74c3c'
          });
        <?php endif; ?>
      });
    <?php endif; ?>
  </script>
</body>

</html>