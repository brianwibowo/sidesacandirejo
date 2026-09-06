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
  <title>Input Data Pengurus - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Input Data Pengurus</h1>
            <p>Tambah data pengurus baru Desa Wisata Candirejo</p>
          </div>
          <a href="datapengurus.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Pengurus
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <h2>Data Pengurus Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputpengurus.php" method="post" enctype="multipart/form-data"
              id="demo-form2" data-parsley-validate>

              <!-- IDENTITAS PENGURUS -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Identitas Pengurus
              </div>

              <div class="form-row">
                <label class="form-label">Nama Lengkap <span class="req">*</span>
                  <small>Nama lengkap pengurus</small>
                </label>
                <div>
                  <input type="text" id="nama" name="nama" required maxlength="100"
                    placeholder="Masukkan Nama Lengkap" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">No. KTP <span class="req">*</span>
                  <small>16 digit nomor KTP</small>
                </label>
                <div>
                  <input type="text" id="no_ktp" name="no_ktp" required maxlength="20"
                    placeholder="Masukkan Nomor KTP" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Jabatan <span class="req">*</span>
                  <small>Jabatan dalam organisasi</small>
                </label>
                <div>
                  <input type="text" id="jabatan" name="jabatan" required maxlength="50"
                    placeholder="Masukkan Jabatan" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Periode <span class="req">*</span>
                  <small>Tahun masa jabatan</small>
                </label>
                <div>
                  <input type="text" id="periode" name="periode" required maxlength="20"
                    placeholder="Contoh: 2022-2025" class="form-input">
                </div>
              </div>

              <!-- KONTAK -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kontak & Domisili
              </div>

              <div class="form-row">
                <label class="form-label">Alamat <span class="req">*</span>
                  <small>Alamat lengkap pengurus</small>
                </label>
                <div>
                  <textarea id="alamat" name="alamat" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Alamat Lengkap"></textarea>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">No. Telepon <span class="req">*</span>
                  <small>Nomor HP aktif pengurus</small>
                </label>
                <div>
                  <input type="text" id="no_telp" name="no_telp" required maxlength="15"
                    placeholder="Masukkan Nomor Telepon" class="form-input">
                </div>
              </div>

              <!-- BERKAS -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas & Dokumen
              </div>

              <div class="form-row">
                <label class="form-label">Foto KTP
                  <small>Maks. 2 MB (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('foto_ktp').click()" style="padding:16px;">
                    <div class="upload-icon"><i class="fa fa-id-card"></i></div>
                    <div class="upload-text-main">Klik untuk upload foto KTP</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB</div>
                  </div>
                  <input name="foto_ktp" accept="image/*" type="file" id="foto_ktp" class="hidden-file-input" />
                  <div id="preview-ktp" style="margin-top:8px;"></div>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Pas Foto
                  <small>Maks. 2 MB (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('pas_foto').click()" style="padding:16px;">
                    <div class="upload-icon"><i class="fa fa-user-circle"></i></div>
                    <div class="upload-text-main">Klik untuk upload pas foto</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB</div>
                  </div>
                  <input name="pas_foto" accept="image/*" type="file" id="pas_foto" class="hidden-file-input" />
                  <div id="preview-pasfoto" style="margin-top:8px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" name="submit" value="Submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Data
                </button>
                <button type="reset" class="btn-reset">
                  <i class="fa fa-refresh"></i> Reset
                </button>
                <a href="datapengurus.php" class="btn-cancel">
                  <i class="fa fa-times"></i> Batal
                </a>
              </div>

            </form>
          </div>
        </div>
      </div>

      <footer>
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  function previewImage(inputId, previewId) {
    var input = document.getElementById(inputId);
    var preview = document.getElementById(previewId);
    input.addEventListener('change', function() {
      var file = this.files[0];
      if (!file) { preview.innerHTML = ''; return; }
      var reader = new FileReader();
      reader.onload = function(e) {
        preview.innerHTML =
          '<img src="' + e.target.result + '" style="max-width:120px;max-height:120px;border-radius:8px;border:1.5px solid #b5d5c0;object-fit:cover;">' +
          '<div style="font-size:11px;color:#6b8f7e;margin-top:4px;">' + file.name + '</div>';
      };
      reader.readAsDataURL(file);
    });
  }
  previewImage('foto_ktp', 'preview-ktp');
  previewImage('pas_foto', 'preview-pasfoto');
  </script>
</body>
</html>