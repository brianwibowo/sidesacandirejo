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
  <title>Edit Surat Masuk - Arsip Desa Candirejo</title>

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
    /* Override existing foto preview styles for modern look */
    .foto-existing-card {
      position: relative;
      width: 90px;
      height: 90px;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid #b5d5c0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .foto-existing-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .foto-existing-card .badge-existing {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(30,58,47,0.75);
      color: #fff;
      font-size: 9px;
      text-align: center;
      padding: 2px;
    }
    .foto-existing-card .remove-foto {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .foto-preview-item {
      position: relative;
      width: 90px;
      height: 90px;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid #d6e6dc;
    }
    .foto-preview-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .foto-preview-item .remove-foto {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      font-size: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
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
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Surat Masuk</h1>
            <p>Perbarui data arsip surat masuk Desa Wisata Candirejo</p>
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
              <h2>Edit Data Surat Masuk</h2>
            </div>
          </div>

          <div class="form-card-body">
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

            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Anda sedang mengedit Surat Masuk Nomor <strong><?php echo htmlspecialchars($data['No']); ?></strong>. Semua perubahan akan disimpan saat klik Simpan.
            </div>

            <form action="proses/proses_editsuratmasuk.php" method="post"
              enctype="multipart/form-data" id="edit-form">

              <input type="hidden" name="No" value="<?php echo htmlspecialchars($data['No']); ?>">
              <!-- Kirim foto lama yang masih dipertahankan -->
              <input type="hidden" name="foto_lama_json" id="foto_lama_json"
                value="<?php echo htmlspecialchars(json_encode($existing_fotos)); ?>">

              <!-- INFORMASI SURAT -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Informasi Surat
              </div>

              <!-- Nomor Urut -->
              <div class="form-row">
                <label class="form-label">Nomor Urut <span class="req">*</span>
                  <small>Nomor urut surat</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['No']); ?>" type="text"
                    id="nomorurut_suratmasuk" name="No_display" readonly
                    class="form-input input-sm">
                </div>
              </div>

              <!-- Tanggal Masuk -->
              <div class="form-row">
                <label class="form-label">Tanggal Masuk <span class="req">*</span>
                  <small>Tanggal surat diterima</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggal_masuk" name="tanggal_masuk" required
                      value="<?php echo htmlspecialchars($data['tanggal_terima']); ?>"
                      class="form-input datepicker" placeholder="Pilih Tanggal" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggal_masuk')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Tanggal Surat -->
              <div class="form-row">
                <label class="form-label">Tanggal Surat <span class="req">*</span>
                  <small>Tanggal yang tertera di surat</small>
                </label>
                <div>
                  <div class="input-group-modern">
                    <input type="text" id="tanggalsurat_suratmasuk" name="tanggalsurat_suratmasuk" required
                      value="<?php echo htmlspecialchars($data['tanggal_surat']); ?>"
                      class="form-input datepicker" placeholder="Pilih Tanggal" autocomplete="off" readonly />
                    <span class="input-group-addon-modern" onclick="document.getElementById('tanggalsurat_suratmasuk')._flatpickr.open()">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Nomor Surat -->
              <div class="form-row">
                <label class="form-label">Nomor Surat <span class="req">*</span>
                  <small>Nomor resmi surat</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['nomor_surat']); ?>" type="text"
                    id="nomor_suratmasuk" name="nomor_suratmasuk" required maxlength="35"
                    placeholder="Masukkan Nomor Surat" class="form-input">
                </div>
              </div>

              <!-- Pengirim -->
              <div class="form-row">
                <label class="form-label">Pengirim <span class="req">*</span>
                  <small>Asal/instansi pengirim surat</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['pengirim']); ?>" type="text"
                    id="pengirim" name="pengirim" required
                    placeholder="Masukkan Asal/Pengirim Surat" class="form-input">
                </div>
              </div>

              <!-- Penerima -->
              <div class="form-row">
                <label class="form-label">Penerima <span class="req">*</span>
                  <small>Nama penerima surat</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['penerima_surat']); ?>" type="text"
                    id="penerima_surat" name="penerima_surat" required
                    placeholder="Masukkan Nama Penerima" class="form-input">
                </div>
              </div>

              <!-- Disposisi -->
              <div class="form-row">
                <label class="form-label">Disposisi <span class="req">*</span>
                  <small>Disposisi surat</small>
                </label>
                <div>
                  <textarea id="disposisi" name="disposisi" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Disposisi Surat"><?php echo htmlspecialchars($data['disposisi']); ?></textarea>
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-row">
                <label class="form-label">Perihal <span class="req">*</span>
                  <small>Maksud/isi pokok surat</small>
                </label>
                <div>
                  <textarea id="perihal_suratmasuk" name="perihal" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Perihal Surat"><?php echo htmlspecialchars($data['perihal']); ?></textarea>
                </div>
              </div>

              <!-- Keterangan -->
              <div class="form-row">
                <label class="form-label">Keterangan
                  <small>Catatan tambahan (opsional)</small>
                </label>
                <div>
                  <textarea id="keterangan" name="keterangan" class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Keterangan Surat"><?php echo htmlspecialchars($data['keterangan']); ?></textarea>
                </div>
              </div>

              <!-- BERKAS -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas & Lampiran
              </div>

              <!-- File Surat -->
              <div class="form-row">
                <label class="form-label">File Surat
                  <small>PDF, maks. 10 MB</small>
                </label>
                <div>
                  <?php if (!empty($data['file_surat'])): ?>
                    <div style="margin-bottom:10px;">
                      <a href="<?php echo htmlspecialchars('uploads/' . $data['file_surat']); ?>" target="_blank"
                        class="btn-back-modern" style="display:inline-flex; gap:6px;">
                        <i class="fa fa-file-pdf-o" style="color:#e74c3c;"></i> Lihat File Surat Saat Ini
                      </a>
                    </div>
                  <?php endif; ?>
                  <input name="file_surat" accept="application/pdf" type="file"
                    id="file_suratmasuk" class="form-input" style="padding:7px;" />
                  <span class="form-hint"><i class="fa fa-info-circle"></i> Kosongkan jika tidak ingin mengubah file.</span>
                </div>
              </div>

              <!-- Lampiran Foto -->
              <div class="form-row">
                <label class="form-label">Lampiran Foto
                  <small>Foto pendukung (opsional)</small>
                </label>
                <div>
                  <?php if (!empty($existing_fotos)): ?>
                    <p class="form-hint" style="margin-bottom:8px;">Foto saat ini <small>(klik × untuk hapus saat simpan)</small>:</p>
                    <div id="existing-foto-container" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
                      <?php foreach ($existing_fotos as $fotoPath): ?>
                        <div class="foto-existing-card" id="existing-<?php echo md5($fotoPath); ?>">
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

                  <div class="upload-area-modern" onclick="document.getElementById('lampiran_foto').click()"
                    style="max-width:100%;">
                    <div class="upload-icon"><i class="fa fa-image"></i></div>
                    <div class="upload-text-main">Klik untuk tambah foto baru</div>
                    <div class="upload-text-sub">JPG, PNG, WebP – Foto akan dikonversi ke WebP otomatis</div>
                  </div>
                  <input name="lampiran_foto[]" accept="image/*" type="file"
                    id="lampiran_foto" class="hidden-file-input" multiple />
                  <!-- Preview foto baru -->
                  <div id="foto-preview-container" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:12px;"></div>
                </div>
              </div>

              <!-- Operator -->
              <div class="form-row">
                <label class="form-label">Operator
                  <small>Auto-isi dari sesi login</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?>" type="text"
                    id="operator" name="operator" readonly class="form-input input-md">
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" name="update" value="Update" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn-reset" onclick="resetEditForm()">
                  <i class="fa fa-refresh"></i> Reset
                </button>
                <a href="datasuratmasuk.php" class="btn-cancel">
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