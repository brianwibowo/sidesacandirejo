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

  <title>Arsip Surat Desa Candirejo Borobudur </title>

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
    #tanggalkeluar_suratkeluar {
      text-align: left;
    }
    .upload-area-required-error {
      border-color: #e74c3c !important;
      background-color: #fdecea !important;
      box-shadow: 0 0 10px rgba(231, 76, 60, 0.25) !important;
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
          <div class="page-title">
            <div class="title_left">
              <h3>Surat Keluar</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Surat Keluar ><small>Edit Surat Keluar</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_editsuratkeluar.php" method="post" enctype="multipart/form-data"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                    <?php include '../koneksi/koneksi.php';
                            $id			= mysqli_real_escape_string($db,$_GET['id']);
                            $sql  		= "SELECT * FROM tb_arsip_surat_keluar where No='".$id."'";                        
                            $query  	= mysqli_query($db, $sql);
                            $data 		= mysqli_fetch_array($query);
                          ?>

                    <input type=hidden name="id_suratkeluar" value="<?php echo $id;?>">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Keluar <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker4'>
                          <input value="<?php echo $data['tanggal_keluar'] ?>" type='text' id="tanggal_keluar"
                            name="tanggal_keluar" required="required" class="form-control" readonly="readonly" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                     
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nomor Urut <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" id="No" name="No" value="<?php echo $data['No']; ?>" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nomor Surat <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['nomor_surat'];?>" type="text" id="nomor_suratkeluar"
                          name="nomor_surat" required="required" maxlength="35" placeholder="Masukkan Nomor Surat"
                          class="form-control col-md-7 col-xs-12">
                        <br>4 Digit Awal merupakan Nomor Surat (Pastikan Lihat Nomor Sebelumnya)</br>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Kepada <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['penerima'];?>" type="text" id="kepada_suratkeluar"
                          name="penerima" required="required" placeholder="Masukkan Tujuan Surat"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tempat_acara">Tempat Acara
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $data['tempat_acara'];?>" type="text" id="tempat_acara"
                          name="tempat_acara" maxlength="150" placeholder="Masukkan Tempat Acara"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kegiatan">Tanggal Kegiatan
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class='input-group date' id='myDatepicker5'>
                          <input value="<?php echo $data['tanggal_kegiatan'];?>" type='text' id="tanggal_kegiatan"
                            name="tanggal_kegiatan" class="form-control" />
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Perihal <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="perihal_suratkeluar" name="perihal" required="required" class="form-control"
                          rows="3" placeholder='Masukkan Perihal Surat'><?php echo $data['perihal'];?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="keterangan_suratkeluar" name="keterangan" class="form-control"
                          rows="3" placeholder='Masukkan Keterangan Surat'><?php echo $data['keterangan'];?></textarea>
                      </div>
                    </div>
                    <?php
                      // Set base path untuk lampiran
                      $lampiran_base = '../uploads/';
                      
                      $absensi_files = json_decode($data['lampiran_absensi'] ?? '[]', true);
                      if (!is_array($absensi_files)) $absensi_files = [];
                      $notulen_files = json_decode($data['lampiran_notulen'] ?? '[]', true);
                      if (!is_array($notulen_files)) $notulen_files = [];
                      $dokumentasi_files = json_decode($data['dokumentasi_foto'] ?? '[]', true);
                      if (!is_array($dokumentasi_files)) $dokumentasi_files = [];
                    ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Absensi <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($absensi_files)) { ?>
                          <div style="background: #f0f4f8; border-left: 4px solid #3498db; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                            <strong style="color: #2c3e50;">File Yang Ada:</strong>
                            <div style="margin-top: 10px; max-width: 220px;">
                              <?php foreach ($absensi_files as $idx => $filename) {
                                $fileUrl = 'uploads/' . rawurlencode($filename);
                                ?>
                                <input type="checkbox" id="delete-absensi-<?php echo $idx; ?>" name="delete_absensi[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                                <div id="existing-absensi-<?php echo $idx; ?>" style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                  <a href="<?php echo $fileUrl; ?>" class="btn btn-xs btn-primary" style="flex:1;" download title="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-download"></i> File <?php echo $idx + 1; ?></a>
                                  <button type="button" class="btn btn-xs btn-danger" title="Hapus file ini" onclick="markExistingFileForDeletion('delete-absensi-<?php echo $idx; ?>', 'existing-absensi-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        <?php } ?>
                        <div style="border: 2px dashed #3498db; border-radius: 8px; padding: 30px 20px; text-align: center; cursor: pointer; background-color: #f8f9fa; transition: all 0.3s ease;" id="upload-area-absensi" data-field="file_absensi">
                          <div style="font-size: 32px; color: #3498db; margin-bottom: 10px;"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk tambah</strong></div>
                          <small style="color: #7f8c8d;">PDF, JPG, PNG (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_absensi[]" id="file_absensi" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple style="display: none;" />
                        <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px;" id="preview-absensi"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload PDF atau foto tambahan</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Notulen <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($notulen_files)) { ?>
                          <div style="background: #f0f4f8; border-left: 4px solid #3498db; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                            <strong style="color: #2c3e50;">File Yang Ada:</strong>
                            <div style="margin-top: 10px; max-width: 220px;">
                              <?php foreach ($notulen_files as $idx => $filename) {
                                $fileUrl = 'uploads/' . rawurlencode($filename);
                                ?>
                                <input type="checkbox" id="delete-notulen-<?php echo $idx; ?>" name="delete_notulen[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                                <div id="existing-notulen-<?php echo $idx; ?>" style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                  <a href="<?php echo $fileUrl; ?>" class="btn btn-xs btn-primary" style="flex:1;" download title="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-download"></i> File <?php echo $idx + 1; ?></a>
                                  <button type="button" class="btn btn-xs btn-danger" title="Hapus file ini" onclick="markExistingFileForDeletion('delete-notulen-<?php echo $idx; ?>', 'existing-notulen-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        <?php } ?>
                        <div style="border: 2px dashed #3498db; border-radius: 8px; padding: 30px 20px; text-align: center; cursor: pointer; background-color: #f8f9fa; transition: all 0.3s ease;" id="upload-area-notulen" data-field="file_notulen">
                          <div style="font-size: 32px; color: #3498db; margin-bottom: 10px;"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk tambah</strong></div>
                          <small style="color: #7f8c8d;">PDF, JPG, PNG (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_notulen[]" id="file_notulen" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple style="display: none;" />
                        <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px;" id="preview-notulen"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload PDF atau foto tambahan</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload Foto/Dokumentasi <span class="fa fa-upload" style="color: #3498db;"></span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php if (!empty($dokumentasi_files)) { ?>
                          <div style="background: #f0f4f8; border-left: 4px solid #3498db; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                            <strong style="color: #2c3e50;">File Dokumentasi Yang Ada:</strong>
                            <div style="margin-top: 10px; max-width: 220px;">
                              <?php foreach ($dokumentasi_files as $idx => $filename) {
                                $fileUrl = 'uploads/' . rawurlencode($filename);
                                ?>
                                <input type="checkbox" id="delete-dokumentasi-<?php echo $idx; ?>" name="delete_dokumentasi[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                                <div id="existing-dokumentasi-<?php echo $idx; ?>" style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                                  <a href="<?php echo $fileUrl; ?>" class="btn btn-xs btn-primary" style="flex:1;" download title="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-download"></i> File <?php echo $idx + 1; ?></a>
                                  <button type="button" class="btn btn-xs btn-danger" title="Hapus file ini" onclick="markExistingFileForDeletion('delete-dokumentasi-<?php echo $idx; ?>', 'existing-dokumentasi-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        <?php } ?>
                        <div style="border: 2px dashed #3498db; border-radius: 8px; padding: 30px 20px; text-align: center; cursor: pointer; background-color: #f8f9fa; transition: all 0.3s ease;" id="upload-area-dokumentasi" data-field="file_dokumentasi">
                          <div style="font-size: 32px; color: #3498db; margin-bottom: 10px;"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk tambah</strong></div>
                          <small style="color: #7f8c8d;">JPG, PNG, WebP, GIF (Bisa lebih dari 1 file)</small>
                        </div>
                        <input type="file" name="file_dokumentasi[]" id="file_dokumentasi" accept="image/jpeg,image/png,image/webp,image/gif" multiple style="display: none;" />
                        <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px;" id="preview-dokumentasi"></div>
                        <small class="text-muted" style="display: block; margin-top: 5px;">*Opsional. Upload foto/dokumentasi kegiatan (JPG, PNG, WebP, GIF)</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">File <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="hidden" id="has-existing-file-surat" value="<?php echo !empty($data['file_surat']) ? '1' : '0'; ?>">
                        <?php if (!empty($data['file_surat'])) { ?>
                          <?php $fileSuratUrl = preg_replace('/^\.\.\//', '', $data['file_surat']); ?>
                          <div style="max-width: 220px; margin-bottom: 10px;">
                            <div id="existing-file-surat" style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                              <a href="<?php echo htmlspecialchars($fileSuratUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-xs btn-primary" style="flex:1;" download title="Unduh File Surat"><i class="fa fa-download"></i> File 1</a>
                            </div>
                          </div>
                        <?php } ?>
                        <div style="border: 2px dashed #3498db; border-radius: 8px; padding: 30px 20px; text-align: center; cursor: pointer; background-color: #f8f9fa; transition: all 0.3s ease;" id="upload-area-surat" data-field="file_surat">
                          <div style="font-size: 32px; color: #3498db; margin-bottom: 10px;"><span class="fa fa-cloud-upload"></span></div>
                          <div><strong>Drag file atau klik untuk tambah</strong></div>
                          <small style="color: #7f8c8d;">PDF (1 file)</small>
                        </div>
                        <input name="file_surat" accept="application/pdf" type="file" id="file_surat" style="display: none;" autocomplete="off" />
                        <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px;" id="preview-surat"></div>
                        <div id="file-surat-required-edit" class="upload-required-message">File Surat wajib diisi.</div>
                        <small class="text-muted" style="display:block; margin-top: 5px;">Hanya bisa ganti file surat (1 file). Upload file baru jika ingin mengganti file lama. (Maksimal 10 MB)</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Operator <span
                          class="required"></span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo $_SESSION['nama'];?>" type="text" id="operator" name="operator"
                          required="required" readonly="readonly" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                 
                    <input type="hidden" value="<?= $data['No']?>" name="id">
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="datasuratkeluar.php" class="btn btn-success"><span
                            class="glyphicon glyphicon-arrow-left"></span> Batal</a>
                        <button type="submit" name="update" value="Update" class="btn btn-primary"><i
                            class="glyphicon glyphicon-plus"></i> Simpan</button>
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
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-datetimepicker -->
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Parsley -->
  <script src="../assets/vendors/parsleyjs/dist/parsley.min.js"></script>
  <!-- Autosize -->
  <script src="../assets/vendors/autosize/dist/autosize.min.js"></script>
  <!-- jQuery autocomplete -->
  <script src="../assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
  <!-- starrr -->
  <script src="../assets/vendors/starrr/dist/starrr.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
  <!-- Initialize datetimepicker -->
  <script>
  $('#myDatepicker').datetimepicker();

  $('#myDatepicker2').datetimepicker({
    format: 'DD.MM.YYYY'
  });

  $('#myDatepicker3').datetimepicker({
    format: 'hh:mm A'
  });

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
    const hasExistingFileSuratInitially = $('#has-existing-file-surat').val() === '1';
    const fileSuratArea = $('#upload-area-surat');
    const fileSuratRequiredMsg = $('#file-surat-required-edit');

    function hasValidFileSurat() {
      const hasNewFile = fileStorage.file_surat.length > 0;
      return hasExistingFileSuratInitially || hasNewFile;
    }

    function showFileSuratRequiredError() {
      fileSuratArea.addClass('upload-area-required-error');
      fileSuratRequiredMsg.addClass('show');
      $('html, body').animate({
        scrollTop: fileSuratArea.offset().top - 120
      }, 350);
    }

    function clearFileSuratRequiredError() {
      fileSuratArea.removeClass('upload-area-required-error');
      fileSuratRequiredMsg.removeClass('show');
    }
    // Note: Existing files dari DB hanya untuk display, tidak perlu di fileStorage karena akan di-merge di backend

    uploadFields.forEach(fieldName => {
      const uploadArea = $('[data-field="' + fieldName + '"]');
      const fileInput = $('#' + fieldName);
      const previewContainer = $('#preview-' + fieldName.replace('file_', ''));

      // Drag-drop events
      uploadArea.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css({backgroundColor: '#d4e8f5', borderColor: '#2980b9', boxShadow: '0 0 10px rgba(52, 152, 219, 0.3)'});
      });

      uploadArea.on('dragleave', function(e) {
        $(this).css({backgroundColor: '#f8f9fa', borderColor: '#3498db', boxShadow: 'none'});
      });

      uploadArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css({backgroundColor: '#f8f9fa', borderColor: '#3498db', boxShadow: 'none'});
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
      if (singleFileFields.includes(fieldName)) {
        fileStorage[fieldName] = files.length > 0 ? [files[0]] : [];
        clearFileSuratRequiredError();
      } else {
        // APPEND files ke existing list, bukan replace
        fileStorage[fieldName] = fileStorage[fieldName].concat(Array.from(files));
      }
      updatePreview(fieldName, previewContainer);
      updateFileInput(fieldName);
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

        let fileItemHtml = '<div style="position: relative; display: inline-block; background: white; border: 1px solid #ddd; border-radius: 5px; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); ' + (isImage ? 'width: 80px; height: 80px; padding: 4px;' : 'min-width: 120px;') + '" data-index="' + index + '">';
        fileItemHtml += '<div style="position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 14px;" onclick="removeFileByIndex(\'' + fieldName + '\', ' + index + ')" title="Hapus file ini">×</div>';

        if (isImage) {
          fileItemHtml += '<img src="" alt="preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 3px;" />';
        } else if (isPdf) {
          fileItemHtml += '<div style="text-align: center; padding: 15px;"><span class="fa fa-file-pdf-o" style="font-size: 32px; color: #e74c3c;"></span>';
          fileItemHtml += '<div style="font-size: 11px; margin-top: 5px; word-break: break-word;">' + file.name.substring(0, 15) + (file.name.length > 15 ? '...' : '') + '</div>';
          fileItemHtml += '<span style="display: inline-block; background: #3498db; color: white; padding: 3px 6px; border-radius: 3px; font-size: 10px; margin-top: 4px;">PDF</span></div>';
        } else {
          fileItemHtml += '<div style="text-align: center; padding: 10px;"><span class="fa fa-file" style="font-size: 24px; color: #3498db;"></span>';
          fileItemHtml += '<div style="font-size: 11px; margin-top: 5px; word-break: break-word;">' + file.name.substring(0, 15) + (file.name.length > 15 ? '...' : '') + '</div>';
          fileItemHtml += '<span style="display: inline-block; background: #3498db; color: white; padding: 3px 6px; border-radius: 3px; font-size: 10px; margin-top: 4px;">' + ext.toUpperCase() + '</span></div>';
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
      // Hapus file by index dari fileStorage
      fileStorage[fieldName].splice(index, 1);
      // Update file input untuk reflect perubahan
      updateFileInput(fieldName);
      // Update preview
      const previewContainer = $('#preview-' + fieldName.replace('file_', ''));
      updatePreview(fieldName, previewContainer);
    };

    window.removeFile = function(fieldName, index) {
      // Backward compatibility - alias untuk removeFileByIndex
      removeFileByIndex(fieldName, index);
    };

    window.markExistingFileForDeletion = function(checkboxId, wrapperId) {
      const checkbox = document.getElementById(checkboxId);
      const wrapper = document.getElementById(wrapperId);
      if (checkbox) checkbox.checked = true;
      if (wrapper) wrapper.style.display = 'none';
    };

    $('#demo-form2').on('submit', function(e) {
      if (!hasValidFileSurat()) {
        e.preventDefault();
        showFileSuratRequiredError();
        return false;
      }
      clearFileSuratRequiredError();
    });
  });

  // Re-initialize preview untuk file yang sudah ada
  $(document).ready(function() {
    // Handler drag-drop tidak perlu reinit, tapi untuk jaga-jaga atau future use
  });

  $('#datetimepicker6').datetimepicker();

  $('#datetimepicker7').datetimepicker({
    useCurrent: false
  });

  $("#datetimepicker6").on("dp.change", function(e) {
    $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
  });

  $("#datetimepicker7").on("dp.change", function(e) {
    $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
  });
  </script>
  <script language='javascript'>
  function validAngka(a) {
    if (!/^[0-9.]+$/.test(a.value)) {
      a.value = a.value.substring(0, a.value.length - 1000);
    }
  }
  </script>
</body>

</html>