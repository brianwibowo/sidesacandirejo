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
  <title>Edit Surat Keluar - Arsip Desa Candirejo</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    .flatpickr-input { background: #fff !important; }
    .upload-required-message { display: none; color: #e74c3c; font-weight: 600; margin-top: 6px; font-size: 13px; }
    .upload-required-message.show { display: block; }
    .upload-area-required-error {
      border-color: #e74c3c !important;
      background-color: #fdf2f2 !important;
      box-shadow: 0 0 0 3px rgba(231,76,60,0.12) !important;
    }
    /* Existing file list inside card */
    .existing-file-list {
      background: #f6fbf8;
      border: 1px solid #d5e6dc;
      border-radius: 9px;
      padding: 12px 14px;
      margin-bottom: 12px;
    }
    .existing-file-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
    }
    .existing-file-row:last-child { margin-bottom: 0; }
    .existing-file-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ffffff;
      color: #2e7d4f;
      border: 1px solid #b5d5c0;
      border-radius: 7px;
      padding: 6px 14px;
      font-size: 12.5px;
      font-weight: 500;
      text-decoration: none;
      flex: 1;
      transition: all 0.15s;
    }
    .existing-file-link:hover { background: #edf7f2; text-decoration: none; color: #1e3a2f; }
    .btn-hapus-file {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: #fff0f0;
      color: #e74c3c;
      border: 1px solid #f5c6cb;
      border-radius: 7px;
      padding: 5px 10px;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-hapus-file:hover { background: #ffe0e0; }
    .upload-area-modern.mini {
      padding: 16px 14px !important;
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
            <h1>Edit Surat Keluar</h1>
            <p>Perbarui data arsip surat keluar Desa Wisata Candirejo</p>
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
              <h2>Edit Data Surat Keluar</h2>
            </div>
          </div>

          <div class="form-card-body">
            <?php
            include '../koneksi/koneksi.php';
            $id         = mysqli_real_escape_string($db, $_GET['id']);
            $sql        = "SELECT * FROM tb_arsip_surat_keluar where No='" . $id . "'";
            $query      = mysqli_query($db, $sql);
            $data       = mysqli_fetch_array($query);
            $jenis_surat = strtolower($data['jenis_surat'] ?? 'keterangan');
            if (!in_array($jenis_surat, ['keterangan', 'undangan'], true)) {
              $jenis_surat = 'keterangan';
            }

            // Set base path untuk lampiran
            $absensi_files     = json_decode($data['lampiran_absensi'] ?? '[]', true);
            if (!is_array($absensi_files)) $absensi_files = [];
            $notulen_files     = json_decode($data['lampiran_notulen'] ?? '[]', true);
            if (!is_array($notulen_files)) $notulen_files = [];
            $dokumentasi_files = json_decode($data['dokumentasi_foto'] ?? '[]', true);
            if (!is_array($dokumentasi_files)) $dokumentasi_files = [];
            ?>

            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Anda sedang mengedit Surat Keluar Nomor <strong><?php echo htmlspecialchars($data['No']); ?></strong>.
            </div>

            <form action="proses/proses_editsuratkeluar.php" method="post" enctype="multipart/form-data"
              id="demo-form2">

              <input type="hidden" name="id_suratkeluar" value="<?php echo $id; ?>">
              <input type="hidden" value="<?= $data['No']?>" name="id">
              <input type="hidden" id="has-existing-file-surat" value="<?php echo !empty($data['file_surat']) ? '1' : '0'; ?>">

              <!-- INFORMASI SURAT -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Informasi Surat
              </div>

              <!-- Tanggal Keluar -->
              <div class="form-row">
                <label class="form-label">Tanggal Keluar <span class="req">*</span>
                  <small>Tanggal penerbitan surat</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input value="<?php echo $data['tanggal_keluar'] ?>" type="text" id="tanggal_keluar"
                      name="tanggal_keluar" required class="form-input datepicker"
                      placeholder="Pilih Tanggal Keluar" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_keluar')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Nomor Urut -->
              <div class="form-row">
                <label class="form-label">Nomor Urut <span class="req">*</span>
                  <small>Nomor urut surat</small>
                </label>
                <div>
                  <input type="text" class="form-input input-sm" id="No" name="No"
                    value="<?php echo $data['No']; ?>" readonly>
                </div>
              </div>

              <!-- Jenis Surat -->
              <div class="form-row">
                <label class="form-label">Jenis Surat <span class="req">*</span>
                  <small>Keterangan atau Undangan</small>
                </label>
                <div>
                  <select id="jenis_surat" name="jenis_surat" required class="form-select input-md">
                    <option value="keterangan" <?php echo $jenis_surat === 'keterangan' ? 'selected' : ''; ?>>Surat Keterangan</option>
                    <option value="undangan" <?php echo $jenis_surat === 'undangan' ? 'selected' : ''; ?>>Surat Undangan</option>
                  </select>
                </div>
              </div>

              <!-- Nomor Surat -->
              <div class="form-row">
                <label class="form-label">Nomor Surat <span class="req">*</span>
                  <small>4 digit awal = nomor surat</small>
                </label>
                <div>
                  <input value="<?php echo $data['nomor_surat'];?>" type="text" id="nomor_suratkeluar"
                    name="nomor_surat" required maxlength="35" placeholder="Masukkan Nomor Surat"
                    class="form-input">
                </div>
              </div>

              <!-- Kepada -->
              <div class="form-row">
                <label class="form-label">Kepada <span class="req">*</span>
                  <small>Tujuan surat</small>
                </label>
                <div>
                  <input value="<?php echo $data['penerima'];?>" type="text" id="kepada_suratkeluar"
                    name="penerima" required placeholder="Masukkan Tujuan Surat"
                    class="form-input">
                </div>
              </div>

              <!-- DETAIL KEGIATAN (Undangan only) -->
              <div class="form-row undangan-field" style="display:none;">
                <label class="form-label">Tempat Acara
                  <small>Lokasi acara undangan</small>
                </label>
                <div>
                  <input value="<?php echo $data['tempat_acara'];?>" type="text" id="tempat_acara"
                    name="tempat_acara" maxlength="150" placeholder="Masukkan Tempat Acara"
                    class="form-input">
                </div>
              </div>

              <div class="form-row undangan-field" style="display:none;">
                <label class="form-label">Tanggal Kegiatan
                  <small>Tanggal pelaksanaan acara</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input value="<?php echo $data['tanggal_kegiatan'];?>" type="text" id="tanggal_kegiatan"
                      name="tanggal_kegiatan" class="form-input datepicker"
                      placeholder="Pilih Tanggal Kegiatan" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_kegiatan')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-row undangan-field" style="display:none;">
                <label class="form-label">Jam Kegiatan
                  <small>Waktu mulai acara</small>
                </label>
                <div>
                  <input value="<?php echo !empty($data['jam_kegiatan']) ? htmlspecialchars(substr($data['jam_kegiatan'], 0, 5), ENT_QUOTES, 'UTF-8') : ''; ?>"
                    type="time" id="jam_kegiatan" name="jam_kegiatan" class="form-input input-sm">
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-row">
                <label class="form-label">Perihal <span class="req">*</span>
                  <small>Ringkasan isi surat</small>
                </label>
                <div>
                  <textarea id="perihal_suratkeluar" name="perihal" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Perihal Surat"><?php echo $data['perihal'];?></textarea>
                </div>
              </div>

              <!-- Keterangan -->
              <div class="form-row">
                <label class="form-label">Keterangan
                  <small>Catatan tambahan (opsional)</small>
                </label>
                <div>
                  <textarea id="keterangan_suratkeluar" name="keterangan" class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Keterangan Surat"><?php echo $data['keterangan'];?></textarea>
                </div>
              </div>

              <!-- Operator -->
              <div class="form-row">
                <label class="form-label">Operator
                  <small>Auto dari sesi login</small>
                </label>
                <div>
                  <input value="<?php echo $_SESSION['nama'];?>" type="text" id="operator" name="operator"
                    required readonly class="form-input input-md">
                </div>
              </div>

              <!-- BERKAS & LAMPIRAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas & Lampiran
              </div>

              <!-- File Surat -->
              <div class="form-row">
                <label class="form-label">File Surat <span class="req">*</span>
                  <small>PDF, maks. 10 MB</small>
                </label>
                <div>
                  <?php if (!empty($data['file_surat'])) {
                    $fileSuratUrl = preg_replace('/^\.\.\//', '', $data['file_surat']); ?>
                    <div class="existing-file-list" style="margin-bottom:10px;">
                      <div class="existing-file-row" id="existing-file-surat">
                        <a href="<?php echo htmlspecialchars($fileSuratUrl, ENT_QUOTES, 'UTF-8'); ?>" class="existing-file-link" download>
                          <i class="fa fa-download"></i> File Surat Saat Ini
                        </a>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="upload-area-modern mini" id="upload-area-surat" data-field="file_surat">
                    <div class="upload-icon"><i class="fa fa-file-pdf-o"></i></div>
                    <div class="upload-text-main">Klik untuk ganti file surat</div>
                    <div class="upload-text-sub">PDF – Upload file baru jika ingin mengganti (maks. 10 MB)</div>
                  </div>
                  <input name="file_surat" accept="application/pdf" type="file" id="file_surat" class="hidden-file-input" />
                  <div id="preview-surat" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:10px;"></div>
                  <div id="file-surat-required-edit" class="upload-required-message">File Surat wajib diisi.</div>
                </div>
              </div>

              <!-- Upload Absensi (Undangan only) -->
              <div class="form-row undangan-field" style="display:none;">
                <label class="form-label">Absensi
                  <small>File absensi kegiatan</small>
                </label>
                <div>
                  <?php if (!empty($absensi_files)) { ?>
                    <div class="existing-file-list">
                      <div style="font-size:12px;font-weight:600;color:#2a4535;margin-bottom:6px;">File yang ada:</div>
                      <?php foreach ($absensi_files as $idx => $filename) {
                        $fileUrl = 'uploads/' . rawurlencode($filename); ?>
                        <input type="checkbox" id="delete-absensi-<?php echo $idx; ?>" name="delete_absensi[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                        <div class="existing-file-row" id="existing-absensi-<?php echo $idx; ?>">
                          <a href="<?php echo $fileUrl; ?>" class="existing-file-link" download><i class="fa fa-download"></i> File <?php echo $idx + 1; ?></a>
                          <button type="button" class="btn-hapus-file" onclick="markExistingFileForDeletion('delete-absensi-<?php echo $idx; ?>', 'existing-absensi-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                        </div>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="upload-area-modern mini" id="upload-area-absensi" data-field="file_absensi">
                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                    <div class="upload-text-main">Tambah file absensi</div>
                    <div class="upload-text-sub">PDF, JPG, PNG</div>
                  </div>
                  <input type="file" name="file_absensi[]" id="file_absensi" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div id="preview-absensi" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:10px;"></div>
                </div>
              </div>

              <!-- Upload Notulen (Undangan only) -->
              <div class="form-row undangan-field" style="display:none;">
                <label class="form-label">Notulen
                  <small>File notulen rapat</small>
                </label>
                <div>
                  <?php if (!empty($notulen_files)) { ?>
                    <div class="existing-file-list">
                      <div style="font-size:12px;font-weight:600;color:#2a4535;margin-bottom:6px;">File yang ada:</div>
                      <?php foreach ($notulen_files as $idx => $filename) {
                        $fileUrl = 'uploads/' . rawurlencode($filename); ?>
                        <input type="checkbox" id="delete-notulen-<?php echo $idx; ?>" name="delete_notulen[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                        <div class="existing-file-row" id="existing-notulen-<?php echo $idx; ?>">
                          <a href="<?php echo $fileUrl; ?>" class="existing-file-link" download><i class="fa fa-download"></i> File <?php echo $idx + 1; ?></a>
                          <button type="button" class="btn-hapus-file" onclick="markExistingFileForDeletion('delete-notulen-<?php echo $idx; ?>', 'existing-notulen-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                        </div>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="upload-area-modern mini" id="upload-area-notulen" data-field="file_notulen">
                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                    <div class="upload-text-main">Tambah file notulen</div>
                    <div class="upload-text-sub">PDF, JPG, PNG</div>
                  </div>
                  <input type="file" name="file_notulen[]" id="file_notulen" accept=".pdf,image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div id="preview-notulen" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:10px;"></div>
                </div>
              </div>

              <!-- Upload Dokumentasi -->
              <div class="form-row">
                <label class="form-label">Dokumentasi Foto
                  <small>Foto kegiatan (opsional)</small>
                </label>
                <div>
                  <?php if (!empty($dokumentasi_files)) { ?>
                    <div class="existing-file-list">
                      <div style="font-size:12px;font-weight:600;color:#2a4535;margin-bottom:6px;">File yang ada:</div>
                      <?php foreach ($dokumentasi_files as $idx => $filename) {
                        $fileUrl = 'uploads/' . rawurlencode($filename); ?>
                        <input type="checkbox" id="delete-dokumentasi-<?php echo $idx; ?>" name="delete_dokumentasi[]" value="<?php echo htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'); ?>" style="display:none;">
                        <div class="existing-file-row" id="existing-dokumentasi-<?php echo $idx; ?>">
                          <a href="<?php echo $fileUrl; ?>" class="existing-file-link" download><i class="fa fa-download"></i> Foto <?php echo $idx + 1; ?></a>
                          <button type="button" class="btn-hapus-file" onclick="markExistingFileForDeletion('delete-dokumentasi-<?php echo $idx; ?>', 'existing-dokumentasi-<?php echo $idx; ?>')"><i class="fa fa-times"></i></button>
                        </div>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="upload-area-modern mini" id="upload-area-dokumentasi" data-field="file_dokumentasi">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Tambah foto dokumentasi</div>
                    <div class="upload-text-sub">JPG, PNG, WebP, GIF</div>
                  </div>
                  <input type="file" name="file_dokumentasi[]" id="file_dokumentasi" accept="image/jpeg,image/png,image/webp,image/gif" multiple class="hidden-file-input" />
                  <div id="preview-dokumentasi" style="margin-top:10px;display:flex;flex-wrap:wrap;gap:10px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" name="update" value="Update" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <a href="datasuratkeluar.php" class="btn-cancel">
                  <i class="fa fa-times"></i> Batal
                </a>
              </div>

            </form>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <footer>
        <div class="pull-right"></div>
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
  <!-- Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Theme Scripts -->
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

    $(document).ready(function() {
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
      const hasExistingFileSuratInitially = $('#has-existing-file-surat').val() === '1';
      const fileSuratArea = $('#upload-area-surat');
      const fileSuratRequiredMsg = $('#file-surat-required-edit');

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
      }

      function hasValidFileSurat() {
        return hasExistingFileSuratInitially || fileStorage.file_surat.length > 0;
      }

      function showFileSuratRequiredError() {
        fileSuratArea.addClass('upload-area-required-error');
        fileSuratRequiredMsg.addClass('show');
        $('html, body').animate({ scrollTop: fileSuratArea.offset().top - 120 }, 350);
      }

      function clearFileSuratRequiredError() {
        fileSuratArea.removeClass('upload-area-required-error');
        fileSuratRequiredMsg.removeClass('show');
      }

      uploadFields.forEach(fieldName => {
        const uploadArea = $('[data-field="' + fieldName + '"]');
        const fileInput = $('#' + fieldName);
        const previewContainer = $('#preview-' + fieldName.replace('file_', ''));

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

      toggleJenisSuratFields();
      jenisSuratSelect.on('change', toggleJenisSuratFields);

      function handleFileSelect(files, fieldName, previewContainer) {
        if (singleFileFields.includes(fieldName)) {
          fileStorage[fieldName] = files.length > 0 ? [files[0]] : [];
          clearFileSuratRequiredError();
        } else {
          fileStorage[fieldName] = fileStorage[fieldName].concat(Array.from(files));
        }
        updatePreview(fieldName, previewContainer);
        updateFileInput(fieldName);
      }

      function updateFileInput(fieldName) {
        const fileInput = $('#' + fieldName);
        const dataTransfer = new DataTransfer();
        fileStorage[fieldName].forEach(file => dataTransfer.items.add(file));
        fileInput[0].files = dataTransfer.files;
      }

      function updatePreview(fieldName, previewContainer) {
        previewContainer.empty();
        fileStorage[fieldName].forEach((file, index) => {
          const ext = file.name.split('.').pop().toLowerCase();
          const isImage = ['jpg','jpeg','png','webp','gif'].includes(ext);
          const isPdf = ext === 'pdf';

          let html = '<div style="position:relative;display:inline-block;background:#fff;border:1px solid #d6e6dc;border-radius:8px;padding:8px;box-shadow:0 1px 3px rgba(0,0,0,0.05);' + (isImage ? 'width:80px;height:80px;padding:4px;' : 'min-width:120px;') + '">';
          html += '<div style="position:absolute;top:-8px;right:-8px;background:#e74c3c;color:#fff;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;" onclick="removeFileByIndex(\'' + fieldName + '\',' + index + ')">×</div>';
          if (isImage) {
            html += '<img src="" alt="preview" style="width:100%;height:100%;object-fit:cover;border-radius:5px;">';
          } else if (isPdf) {
            html += '<div style="text-align:center;padding:12px;"><i class="fa fa-file-pdf-o" style="font-size:28px;color:#e74c3c;"></i><div style="font-size:10px;margin-top:4px;">' + file.name.substring(0,14) + (file.name.length>14?'...':'') + '</div></div>';
          } else {
            html += '<div style="text-align:center;padding:12px;"><i class="fa fa-file" style="font-size:28px;color:#3498db;"></i><div style="font-size:10px;margin-top:4px;">' + file.name.substring(0,14) + '</div></div>';
          }
          html += '</div>';

          const $item = $(html);
          previewContainer.append($item);
          if (isImage) {
            const reader = new FileReader();
            reader.onload = e => $item.find('img').attr('src', e.target.result);
            reader.readAsDataURL(file);
          }
        });
      }

      window.removeFileByIndex = function(fieldName, index) {
        fileStorage[fieldName].splice(index, 1);
        updateFileInput(fieldName);
        updatePreview(fieldName, $('#preview-' + fieldName.replace('file_', '')));
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
  </script>
</body>
</html>