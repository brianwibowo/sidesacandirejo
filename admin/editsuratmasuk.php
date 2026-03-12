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
  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    #foto-preview-container {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 10px;
    }
    .foto-preview-item {
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 6px;
      overflow: hidden;
      border: 2px solid #ddd;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .foto-preview-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .foto-preview-item .remove-foto {
      position: absolute;
      top: 3px;
      right: 3px;
      background: rgba(220,53,69,0.85);
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 13px;
      line-height: 20px;
      text-align: center;
      cursor: pointer;
      padding: 0;
    }
    .foto-existing-item {
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 6px;
      overflow: hidden;
      border: 2px solid #26B99A;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .foto-existing-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .foto-existing-item .badge-existing {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(38,185,154,0.8);
      color: #fff;
      font-size: 10px;
      text-align: center;
      padding: 2px;
    }
    .input-group .flatpickr-input {
      border-radius: 4px 0 0 4px;
    }
    .input-group-addon { cursor: pointer; }
    .flatpickr-input { background: #fff !important; }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left"><h3>Surat Masuk</h3></div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Surat Masuk &rsaquo; <small>Edit Surat Masuk</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />

                  <?php
                  include '../koneksi/koneksi.php';
                  $id    = mysqli_real_escape_string($db, $_GET['id']);
                  $sql   = "SELECT * FROM tb_arsip_surat_masuk WHERE No='$id'";
                  $query = mysqli_query($db, $sql);
                  $data  = mysqli_fetch_array($query);

                  // Ambil semua foto lampiran (JSON array atau single string lama)
                  $existing_fotos = [];
                  if (!empty($data['lampiran_foto'])) {
                    $decoded = json_decode($data['lampiran_foto'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                      $existing_fotos = $decoded;
                    } else {
                      $existing_fotos = [$data['lampiran_foto']]; // backward compat
                    }
                  }
                  ?>

                  <form action="proses/proses_editsuratmasuk.php" method="post"
                    enctype="multipart/form-data" id="edit-form" class="form-horizontal form-label-left">

                    <input type="hidden" name="No" value="<?php echo htmlspecialchars($data['No']); ?>">
                    <!-- Kirim foto lama yang masih dipertahankan -->
                    <input type="hidden" name="foto_lama_json" id="foto_lama_json"
                      value="<?php echo htmlspecialchars(json_encode($existing_fotos)); ?>">

                    <!-- Tanggal Masuk -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Masuk <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group">
                          <input type="text" id="tanggal_masuk" name="tanggal_masuk" required
                            value="<?php echo htmlspecialchars($data['tanggal_terima']); ?>"
                            class="form-control datepicker" placeholder="Pilih Tanggal" autocomplete="off" readonly />
                          <span class="input-group-addon" onclick="document.getElementById('tanggal_masuk')._flatpickr.open()">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Kode Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($data['kode']); ?>" type="text"
                          id="kode_suratmasuk" name="kode" required maxlength="20"
                          placeholder="Masukkan Kode Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Nomor Urut -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Urut <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($data['No']); ?>" type="text"
                          id="nomorurut_suratmasuk" name="No_display" readonly
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Nomor Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($data['nomor_surat']); ?>" type="text"
                          id="nomor_suratmasuk" name="nomor_suratmasuk" required maxlength="35"
                          placeholder="Masukkan Nomor Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Tanggal Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group">
                          <input type="text" id="tanggalsurat_suratmasuk" name="tanggalsurat_suratmasuk" required
                            value="<?php echo htmlspecialchars($data['tanggal_surat']); ?>"
                            class="form-control datepicker" placeholder="Pilih Tanggal" autocomplete="off" readonly />
                          <span class="input-group-addon" onclick="document.getElementById('tanggalsurat_suratmasuk')._flatpickr.open()">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Pengirim -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Pengirim <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($data['pengirim']); ?>" type="text"
                          id="pengirim" name="pengirim" required
                          placeholder="Masukkan Asal/Pengirim Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Penerima -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Penerima <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($data['penerima_surat']); ?>" type="text"
                          id="penerima_surat" name="penerima_surat" required
                          placeholder="Masukkan Nama Penerima" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Disposisi -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Disposisi <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="disposisi" name="disposisi" required class="form-control"
                          rows="3" placeholder="Masukkan Disposisi Surat"><?php echo htmlspecialchars($data['disposisi']); ?></textarea>
                      </div>
                    </div>

                    <!-- Perihal -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Perihal <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="perihal_suratmasuk" name="perihal" required class="form-control"
                          rows="3" placeholder="Masukkan Perihal Surat"><?php echo htmlspecialchars($data['perihal']); ?></textarea>
                      </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="keterangan" name="keterangan" class="form-control"
                          rows="3" placeholder="Masukkan Keterangan Surat"><?php echo htmlspecialchars($data['keterangan']); ?></textarea>
                      </div>
                    </div>

                    <!-- File Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">File Surat</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input name="file_surat" accept="application/pdf" type="file"
                          id="file_suratmasuk" class="form-control" />
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Kosongkan jika tidak ingin mengubah file. Maks. 10 MB.</small>
                        <?php if (!empty($data['file_surat'])): ?>
                          <br><a href="<?php echo htmlspecialchars('uploads/' . $data['file_surat']); ?>" target="_blank" class="btn btn-xs btn-default">
                            <i class="fa fa-file-pdf-o"></i> Lihat File Saat Ini
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>

                    <!-- Lampiran Foto (multi, opsional) -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Lampiran Foto
                        <small class="text-muted">(opsional)</small>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input name="lampiran_foto[]" accept="image/*" type="file"
                          id="lampiran_foto" class="form-control" multiple />
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Pilih foto baru untuk ditambahkan. Foto akan dikonversi ke WebP otomatis.</small>

                        <!-- Foto yang sudah ada -->
                        <?php if (!empty($existing_fotos)): ?>
                          <p class="text-muted" style="margin-top:10px; margin-bottom:4px;"><small>Foto saat ini (klik × untuk hapus):</small></p>
                          <div id="existing-foto-container" style="display:flex; flex-wrap:wrap; gap:10px;">
                            <?php foreach ($existing_fotos as $fotoPath): ?>
                              <div class="foto-existing-item" id="existing-<?php echo md5($fotoPath); ?>">
                                <img src="<?php echo htmlspecialchars('uploads/' . $fotoPath); ?>" alt="foto">
                                <button type="button" class="remove-foto"
                                  onclick="removeExistingFoto('<?php echo htmlspecialchars($fotoPath); ?>', '<?php echo md5($fotoPath); ?>')"
                                  title="Hapus foto ini">
                                  <i class="fa fa-times"></i>
                                </button>
                                <div class="badge-existing">Tersimpan</div>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>

                        <!-- Preview foto baru -->
                        <div id="foto-preview-container"></div>
                      </div>
                    </div>

                    <!-- Operator -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Operator</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input value="<?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?>" type="text"
                          id="operator" name="operator" readonly class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="datasuratmasuk.php" class="btn btn-success">
                          <i class="glyphicon glyphicon-arrow-left"></i> Batal
                        </a>
                        <button type="submit" name="update" value="Update" class="btn btn-primary">
                          <i class="fa fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-warning" onclick="resetEditForm()">
                          <i class="fa fa-refresh"></i> Reset
                        </button>
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
    // ── Flatpickr ──────────────────────────────────────────────────────
    flatpickr(".datepicker", {
      locale: "id",
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "d F Y",
      allowInput: false,
      disableMobile: false
    });

    // ── Existing foto removal ──────────────────────────────────────────
    var retainedFotos = <?php echo json_encode($existing_fotos); ?>;

    function removeExistingFoto(fotoPath, hashId) {
      Swal.fire({
        icon: 'warning',
        title: 'Hapus Foto?',
        text: 'Foto ini akan dihapus saat disimpan.',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then(function(result) {
        if (result.isConfirmed) {
          retainedFotos = retainedFotos.filter(function(f) { return f !== fotoPath; });
          document.getElementById('foto_lama_json').value = JSON.stringify(retainedFotos);
          var el = document.getElementById('existing-' + hashId);
          if (el) el.remove();
        }
      });
    }

    // ── Multi-foto preview (foto baru) ─────────────────────────────────
    var selectedFiles = [];

    document.getElementById('lampiran_foto').addEventListener('change', function () {
      var files = Array.from(this.files);
      files.forEach(function(file) {
        if (!selectedFiles.find(function(f) { return f.name === file.name && f.size === file.size; })) {
          selectedFiles.push(file);
        }
      });
      renderPreviews();
      syncFileInput();
    });

    function renderPreviews() {
      var container = document.getElementById('foto-preview-container');
      container.innerHTML = '';
      selectedFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = function(e) {
          var div = document.createElement('div');
          div.className = 'foto-preview-item';
          div.innerHTML =
            '<img src="' + e.target.result + '" alt="preview">' +
            '<button type="button" class="remove-foto" onclick="removeNewFile(' + idx + ')" title="Hapus">' +
            '<i class="fa fa-times"></i></button>';
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    function removeNewFile(idx) {
      selectedFiles.splice(idx, 1);
      renderPreviews();
      syncFileInput();
    }

    function syncFileInput() {
      var dt = new DataTransfer();
      selectedFiles.forEach(function(f) { dt.items.add(f); });
      document.getElementById('lampiran_foto').files = dt.files;
    }

    // ── Reset ──────────────────────────────────────────────────────────
    function resetEditForm() {
      document.getElementById('edit-form').reset();
      selectedFiles = [];
      document.getElementById('foto-preview-container').innerHTML = '';
    }

    // ── SweetAlert dari PHP redirect ───────────────────────────────────
    <?php if (isset($_GET['status'])): ?>
    window.addEventListener('DOMContentLoaded', function () {
      <?php if ($_GET['status'] === 'success'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data surat masuk berhasil diperbarui.',
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