<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
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
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
.file-item.pdf, .file-item.doc {
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
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Tambah Surat Keluar</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_inputsuratkeluar.php" name="formsuratkeluar" method="post"
                    enctype="multipart/form-data" id="demo-form2" data-parsley-validate
                    class="form-horizontal form-label-left">
                    <?php
                    include '../koneksi/koneksi.php';
                    // Get the last No from database
                    $query = "SELECT MAX(No) as last_no FROM tb_arsip_surat_keluar";
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_assoc($result);
                    $next_no = $row['last_no'] + 1;
                    ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nomor Urut <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="<?php echo $next_no; ?>" id="No" name="No" required="required" maxlength="4"
                          placeholder="Nomor Urut" class="form-control col-md-7 col-xs-12" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_keluar">Tanggal Keluar <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker4'>
                          <input type='text' id="tanggal_keluar" name="tanggal_keluar" required="required"
                            class="form-control" readonly="readonly" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_surat">Nomor Surat <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nomor_surat" name="nomor_surat" required="required" maxlength="50"
                          placeholder="Masukkan Nomor Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="penerima">Penerima <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="penerima" name="penerima" required="required"
                          placeholder="Masukkan Nama Penerima" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tempat_acara">Tempat Acara <span
                          class="required"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="tempat_acara" name="tempat_acara" maxlength="150"
                          placeholder="Masukkan Tempat Acara" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kegiatan">Tanggal Kegiatan <span
                          class="required"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker5'>
                          <input type='text' id="tanggal_kegiatan" name="tanggal_kegiatan"
                            class="form-control" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="perihal">Perihal <span
                          class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="perihal" name="perihal" required="required"
                          placeholder="Masukkan Perihal Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan <span
                          class="required"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3"
                          placeholder="Masukkan Keterangan Surat"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload File Surat <span class="fa fa-upload" style="color: #3498db;"></span> <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="upload-area" id="upload-area-surat" data-field="file_surat">
                          <div class="upload-icon"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk pilih</strong></div>
                          <small style="color: #7f8c8d;">PDF (1 file wajib)</small>
                        </div>
                        <input type="file" name="file_surat" id="file_surat" accept="application/pdf" class="hidden-file-input" />
                        <div class="file-list-preview" id="preview-surat"></div>
                        <div id="file-surat-required" class="upload-required-message">File Surat wajib diisi.</div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Wajib. Format PDF, max 10mb</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Absensi <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="upload-area" id="upload-area-absensi" data-field="file_absensi">
                          <div class="upload-icon"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk pilih</strong></div>
                          <small style="color: #7f8c8d;">PDF, JPG, PNG (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_absensi[]" id="file_absensi" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                        <div class="file-list-preview" id="preview-absensi"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload PDF atau foto</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Notulen <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="upload-area" id="upload-area-notulen" data-field="file_notulen">
                          <div class="upload-icon"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk pilih</strong></div>
                          <small style="color: #7f8c8d;">PDF, JPG, PNG (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_notulen[]" id="file_notulen" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                        <div class="file-list-preview" id="preview-notulen"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload PDF atau foto</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Foto/Dokumentasi <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="upload-area" id="upload-area-dokumentasi" data-field="file_dokumentasi">
                          <div class="upload-icon"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk pilih</strong></div>
                          <small style="color: #7f8c8d;">PDF, JPG, PNG, WebP, GIF (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_dokumentasi[]" id="file_dokumentasi" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                        <div class="file-list-preview" id="preview-dokumentasi"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload PDF atau foto/dokumentasi kegiatan</small>
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
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-datetimepicker -->
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- bootstrap-wysiwyg -->
  <script src="../assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
  <script src="../assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="../assets/vendors/google-code-prettify/src/prettify.js"></script>
  <!-- jQuery Tags Input -->
  <script src="../assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
  <!-- Switchery -->
  <script src="../assets/vendors/switchery/dist/switchery.min.js"></script>
  <!-- Select2 -->
  <script src="../assets/vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- Parsley -->
  <script src="../assets/vendors/parsleyjs/dist/parsley.min.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
  <!-- Moment.js -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script>
  $(document).ready(function() {
    $('#myDatepicker4').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD'
    });

    $('#myDatepicker5').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD'
    });

    // Multi-file upload handler dengan preview dan drag-drop
    const uploadFields = ['file_surat', 'file_absensi', 'file_notulen', 'file_dokumentasi'];
    const singleFileFields = ['file_surat'];
    const fileStorage = {
      file_surat: [],
      file_absensi: [],
      file_notulen: [],
      file_dokumentasi: []
    };
    const fileSuratArea = $('#upload-area-surat');
    const fileSuratRequiredMsg = $('#file-surat-required');

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

    $('#demo-form2').on('submit', function(e) {
      if (fileStorage.file_surat.length === 0) {
        e.preventDefault();
        showFileSuratRequiredError();
        return false;
      }
    });

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

        // Bersihkan jejak validasi Parsley (merah/hijau + pesan error).
        if ($form.parsley) {
          $form.parsley().reset();
        }
        $form.find('.parsley-error, .parsley-success').removeClass('parsley-error parsley-success');
        $form.find('.parsley-errors-list').remove();

        // Pastikan tidak ada fokus tersisa di field tanggal, lalu kembalikan posisi scroll.
        $('#tanggal_keluar, #tanggal_kegiatan').blur();
        $(window).scrollTop(currentScrollTop);
      }, 0);
    });
  });
  </script>
</body>

</html>