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
  <!-- Flatpickr Date Picker -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    /* Upload area drag-drop */
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

    /* Flatpickr override */
    .flatpickr-input {
      background: #fff !important;
    }

    .input-group .flatpickr-input {
      border-radius: 4px 0 0 4px;
    }

    .input-group-addon {
      cursor: pointer;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Tambah Surat Masuk</h1>
            <p>Isi formulir pencatatan arsip surat masuk baru Desa Wisata Candirejo</p>
          </div>
          <a href="datasuratmasuk.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Surat Masuk
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <h2>Formulir Surat Masuk Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputsuratmasuk.php" name="formsuratmasuk" method="post"
              enctype="multipart/form-data" id="demo-form2" novalidate>

              <?php
              include '../koneksi/koneksi.php';
              $query = "SELECT MAX(No) as last_no FROM tb_arsip_surat_masuk";
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
                  <input type="text" value="<?php echo $next_no; ?>" id="No" name="No"
                    required maxlength="4" placeholder="Nomor Urut"
                    class="form-input input-sm" readonly>
                </div>
              </div>

              <!-- Tanggal Terima -->
              <div class="form-row form-group">
                <label class="form-label">Tanggal Terima <span class="req">*</span>
                  <small>Tanggal surat diterima desa</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_terima" name="tanggal_terima" required
                      class="form-input datepicker" placeholder="Pilih Tanggal Terima" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_terima')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Tanggal Surat -->
              <div class="form-row form-group">
                <label class="form-label">Tanggal Surat <span class="req">*</span>
                  <small>Tanggal yang tertera di surat</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_surat" name="tanggal_surat" required
                      class="form-input datepicker" placeholder="Pilih Tanggal Surat" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_surat')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Nomor Surat -->
              <div class="form-row form-group">
                <label class="form-label">Nomor Surat <span class="req">*</span>
                  <small>Nomor resmi surat masuk</small>
                </label>
                <div>
                  <input type="text" id="nomor_surat" name="nomor_surat" required maxlength="35"
                    placeholder="Contoh: 005/123/Desa/2026" class="form-input">
                </div>
              </div>

              <!-- PENGIRIM & PENERIMA -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Pengirim &amp; Penerima
              </div>

              <!-- Pengirim -->
              <div class="form-row form-group">
                <label class="form-label">Pengirim <span class="req">*</span>
                  <small>Instansi atau perorangan pengirim</small>
                </label>
                <div>
                  <input type="text" id="pengirim" name="pengirim" required
                    placeholder="Masukkan nama pengirim / asal instansi" class="form-input">
                </div>
              </div>

              <!-- Penerima -->
              <div class="form-row form-group">
                <label class="form-label">Penerima <span class="req">*</span>
                  <small>Pihak atau bagian penerima surat</small>
                </label>
                <div>
                  <input type="text" id="penerima_surat" name="penerima_surat" required
                    placeholder="Masukkan nama penerima surat" class="form-input">
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-row form-group">
                <label class="form-label">Perihal <span class="req">*</span>
                  <small>Ringkasan inti perihal surat</small>
                </label>
                <div>
                  <input type="text" id="perihal" name="perihal" required
                    placeholder="Masukkan perihal surat" class="form-input">
                </div>
              </div>

              <!-- DISPOSISI & KETERANGAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Disposisi &amp; Keterangan
              </div>

              <!-- Disposisi -->
              <div class="form-row form-group">
                <label class="form-label">Disposisi <span class="req">*</span>
                  <small>Instruksi / arahan tindak lanjut</small>
                </label>
                <div>
                  <textarea id="disposisi" name="disposisi" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan disposisi surat"></textarea>
                </div>
              </div>

              <!-- Keterangan -->
              <div class="form-row form-group">
                <label class="form-label">Keterangan <span class="req">*</span>
                  <small>Keterangan atau catatan tambahan</small>
                </label>
                <div>
                  <textarea id="keterangan" name="keterangan" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan keterangan surat"></textarea>
                </div>
              </div>

              <!-- FILE & LAMPIRAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Lampiran Berkas
              </div>

              <!-- File Surat -->
              <div class="form-row form-group">
                <label class="form-label">Upload File Surat <span class="req">*</span>
                  <small>Berkas surat resmi (PDF wajib)</small>
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

              <!-- Lampiran Foto (multi, opsional) -->
              <div class="form-row form-group">
                <label class="form-label">Upload Lampiran Foto
                  <small>Foto dokumentasi (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" id="upload-area-foto" data-field="lampiran_foto">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Drag foto ke sini atau klik untuk memilih</div>
                    <div class="upload-text-sub">JPG, PNG, GIF, WebP (Maks. 2 MB/foto, bisa lebih dari 1 file)</div>
                  </div>
                  <input type="file" name="lampiran_foto[]" id="lampiran_foto" accept="image/*" multiple class="hidden-file-input" />
                  <div class="file-list-preview" id="preview-foto"></div>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                  Simpan Surat
                </button>
                <button type="button" class="btn-reset" onclick="resetFormInput()">
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

      <footer>
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
        <div class="clearfix"></div>
      </footer>
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
  <!-- Select2 -->
  <script src="../assets/vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
    // ── Flatpickr Date Picker ──────────────────────────────────────────
    flatpickr(".datepicker", {
      locale: "id",
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d F Y",
      allowInput: false,
      disableMobile: false
    });

    // ── Upload drag-drop handler ───────────────────────────────────────
    const uploadFields = ['file_surat', 'lampiran_foto'];
    const singleFileFields = ['file_surat'];
    const fileStorage = { file_surat: [], lampiran_foto: [] };

    const fileSuratArea = $('#upload-area-surat');
    const fileSuratRequiredMsg = $('#file-surat-required');

    function showFileSuratRequiredError() {
      fileSuratArea.addClass('required-error');
      fileSuratRequiredMsg.addClass('show');
      $('html, body').animate({ scrollTop: fileSuratArea.offset().top - 120 }, 400);
    }

    function clearFileSuratRequiredError() {
      fileSuratArea.removeClass('required-error');
      fileSuratRequiredMsg.removeClass('show');
    }

    $(function() {
      uploadFields.forEach(function(fieldName) {
        const uploadArea = $('[data-field="' + fieldName + '"]');
        const fileInput = $('#' + fieldName);
        const previewId = '#preview-' + (fieldName === 'lampiran_foto' ? 'foto' : 'surat');
        const previewContainer = $(previewId);

        uploadArea.on('dragover dragenter', function(e) {
          e.preventDefault(); e.stopPropagation();
          $(this).addClass('dragover');
        });
        uploadArea.on('dragleave', function() { $(this).removeClass('dragover'); });
        uploadArea.on('drop', function(e) {
          e.preventDefault(); e.stopPropagation();
          $(this).removeClass('dragover');
          handleFileSelect(e.originalEvent.dataTransfer.files, fieldName, previewContainer);
        });
        uploadArea.on('click', function() {
          if (fieldName === 'file_surat') clearFileSuratRequiredError();
          fileInput.click();
        });
        fileInput.on('change', function() {
          handleFileSelect(this.files, fieldName, previewContainer);
        });
      });

      function handleFileSelect(files, fieldName, previewContainer) {
        const arr = Array.from(files);
        if (singleFileFields.includes(fieldName)) {
          fileStorage[fieldName] = arr.length > 0 ? [arr[0]] : [];
        } else {
          fileStorage[fieldName] = fileStorage[fieldName].concat(arr);
        }
        updatePreview(fieldName, previewContainer);
        updateFileInput(fieldName);
        if (fieldName === 'file_surat' && fileStorage.file_surat.length > 0) clearFileSuratRequiredError();
      }

      function updateFileInput(fieldName) {
        const dt = new DataTransfer();
        fileStorage[fieldName].forEach(function(f) { dt.items.add(f); });
        document.getElementById(fieldName).files = dt.files;
      }

      function updatePreview(fieldName, previewContainer) {
        previewContainer.empty();
        fileStorage[fieldName].forEach(function(file, index) {
          const ext = file.name.split('.').pop().toLowerCase();
          const isImage = ['jpg','jpeg','png','webp','gif'].includes(ext);
          const isPdf = ext === 'pdf';
          let html = '<div class="file-item ' + (isImage ? 'image' : 'pdf') + '" data-index="' + index + '">';
          html += '<div class="file-remove" onclick="removeFileByIndex(\'' + fieldName + '\', ' + index + ')" title="Hapus">×</div>';
          if (isImage) {
            html += '<img src="" alt="preview" />';
          } else if (isPdf) {
            html += '<div style="padding:15px;"><span class="fa fa-file-pdf-o" style="font-size:32px;color:#e74c3c;"></span>';
            html += '<div style="font-size:11px;margin-top:5px;word-break:break-word;">' + file.name.substring(0,15) + (file.name.length > 15 ? '...' : '') + '</div>';
            html += '<span class="file-type-badge">PDF</span></div>';
          }
          html += '</div>';
          const $item = $(html);
          previewContainer.append($item);
          if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) { $item.find('img').attr('src', e.target.result); };
            reader.readAsDataURL(file);
          }
        });
      }

      window.removeFileByIndex = function(fieldName, index) {
        fileStorage[fieldName].splice(index, 1);
        const previewId = fieldName === 'lampiran_foto' ? '#preview-foto' : '#preview-surat';
        updatePreview(fieldName, $(previewId));
        updateFileInput(fieldName);
      };

      // ── Submit validasi file surat ──────────────────────────────────
      // ── Validasi required fields (ganti browser native) ──────────────
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

      // ── Reset form ──────────────────────────────────────────────────
      window.resetFormInput = function() {
        const currentScrollTop = $(window).scrollTop();
        if (document.activeElement) $(document.activeElement).blur();
        document.getElementById('demo-form2').reset();
        setTimeout(function() {
          uploadFields.forEach(function(fieldName) {
            fileStorage[fieldName] = [];
            updateFileInput(fieldName);
            const previewId = fieldName === 'lampiran_foto' ? '#preview-foto' : '#preview-surat';
            updatePreview(fieldName, $(previewId));
          });
          clearFileSuratRequiredError();
          // Bersihkan error custom validasi
          $('#demo-form2').find('.has-error').removeClass('has-error');
          $('#demo-form2').find('.custom-error-msg').remove();
          document.getElementById('tanggal_terima')._flatpickr.clear();
          document.getElementById('tanggal_surat')._flatpickr.clear();
          $(window).scrollTop(currentScrollTop);
        }, 0);
      };

      // ── Tombol Kembali — langsung navigasi tanpa validasi ──────────
      window.kembaliPage = function() {
        clearFileSuratRequiredError();
        window.location.href = 'datasuratmasuk.php';
      };
    });

    // ── SweetAlert2 — tampilkan pesan dari PHP via URL param ───────────
    <?php if (isset($_GET['status'])): ?>
      window.addEventListener('DOMContentLoaded', function() {
        <?php if ($_GET['status'] === 'success'): ?>
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data surat masuk berhasil disimpan.',
            confirmButtonColor: '#26B99A',
            confirmButtonText: 'OK'
          });
        <?php elseif ($_GET['status'] === 'error'): ?>
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?php echo htmlspecialchars($_GET["msg"] ?? "Terjadi kesalahan."); ?>',
            confirmButtonColor: '#e74c3c',
            confirmButtonText: 'Tutup'
          });
        <?php endif; ?>
      });
    <?php endif; ?>
  </script>
</body>

</html>