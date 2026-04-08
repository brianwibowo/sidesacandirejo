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
    /* Preview foto grid */
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
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
      background: rgba(220, 53, 69, 0.85);
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
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Tambah Surat Masuk</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_inputsuratmasuk.php" name="formsuratmasuk" method="post"
                    enctype="multipart/form-data" id="demo-form2" class="form-horizontal form-label-left">

                    <?php
                    include '../koneksi/koneksi.php';
                    $query = "SELECT MAX(No) as last_no FROM tb_arsip_surat_masuk";
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_assoc($result);
                    $next_no = ($row['last_no'] ?? 0) + 1;
                    ?>

                    <!-- Nomor Urut -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Urut <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" value="<?php echo $next_no; ?>" id="No" name="No"
                          required maxlength="4" placeholder="Nomor Urut"
                          class="form-control col-md-7 col-xs-12" readonly>
                      </div>
                    </div>

                    <!-- Tanggal Terima -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Terima <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group">
                          <input type="text" id="tanggal_terima" name="tanggal_terima" required
                            class="form-control datepicker" placeholder="Pilih Tanggal Terima" autocomplete="off" readonly />
                          <span class="input-group-addon" onclick="document.getElementById('tanggal_terima')._flatpickr.open()">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Tanggal Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group">
                          <input type="text" id="tanggal_surat" name="tanggal_surat" required
                            class="form-control datepicker" placeholder="Pilih Tanggal Surat" autocomplete="off" readonly />
                          <span class="input-group-addon" onclick="document.getElementById('tanggal_surat')._flatpickr.open()">
                            <i class="fa fa-calendar"></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- Nomor Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nomor_surat" name="nomor_surat" required maxlength="35"
                          placeholder="Masukkan Nomor Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Pengirim -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Pengirim <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="pengirim" name="pengirim" required
                          placeholder="Masukkan Nama Pengirim" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Penerima -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Penerima <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="penerima_surat" name="penerima_surat" required
                          placeholder="Masukkan Nama Penerima" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Disposisi -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Disposisi <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="disposisi" name="disposisi" required class="form-control"
                          rows="3" placeholder="Masukkan Disposisi Surat"></textarea>
                      </div>
                    </div>

                    <!-- Perihal -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Perihal <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="perihal" name="perihal" required
                          placeholder="Masukkan Perihal Surat" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="keterangan" name="keterangan" required class="form-control"
                          rows="3" placeholder="Masukkan Keterangan Surat"></textarea>
                      </div>
                    </div>

                    <!-- File Surat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">File Surat <span class="required">*</span></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input name="file_surat" accept="application/pdf" type="file"
                          id="file_surat" class="form-control" required />
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Format PDF, maks. 10 MB</small>
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
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Boleh pilih beberapa foto sekaligus. Format JPG/PNG/GIF, maks. 2 MB/foto. Foto akan otomatis dikonversi ke WebP.</small>
                        <!-- Preview container -->
                        <div id="foto-preview-container"></div>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-primary" onclick="resetFormInput()"><i class="fa fa-refresh"></i> Reset</button>
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
      dateFormat: "Y-m-d", // format yang dikirim ke server
      altInput: true, // tampilkan format manusia
      altFormat: "d F Y", // contoh: 22 Mei 2025
      allowInput: false,
      disableMobile: false
    });

    // ── Multi-foto preview ─────────────────────────────────────────────
    let selectedFiles = [];

    document.getElementById('lampiran_foto').addEventListener('change', function() {
      const files = Array.from(this.files);
      files.forEach(file => {
        // cegah duplikat nama
        if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
          selectedFiles.push(file);
        }
      });
      renderPreviews();
      syncFileInput();
    });

    function renderPreviews() {
      const container = document.getElementById('foto-preview-container');
      container.innerHTML = '';
      selectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
          const div = document.createElement('div');
          div.className = 'foto-preview-item';
          div.innerHTML = `
            <img src="${e.target.result}" alt="preview">
            <button type="button" class="remove-foto" onclick="removeFile(${idx})" title="Hapus">
              <i class="fa fa-times"></i>
            </button>`;
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    function removeFile(idx) {
      selectedFiles.splice(idx, 1);
      renderPreviews();
      syncFileInput();
    }

    function syncFileInput() {
      const dt = new DataTransfer();
      selectedFiles.forEach(f => dt.items.add(f));
      document.getElementById('lampiran_foto').files = dt.files;
    }

    // ── Reset form ─────────────────────────────────────────────────────
    function resetFormInput() {
      document.getElementById('demo-form2').reset();
      selectedFiles = [];
      document.getElementById('foto-preview-container').innerHTML = '';
      // Reset flatpickr
      document.getElementById('tanggal_terima')._flatpickr.clear();
      document.getElementById('tanggal_surat')._flatpickr.clear();
    }

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