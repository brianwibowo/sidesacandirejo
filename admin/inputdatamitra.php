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
  <title>Input Data Mitra - Arsip Desa Candirejo</title>
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
            <h1>Input Data Mitra</h1>
            <p>Tambah data mitra baru Desa Wisata Candirejo</p>
          </div>
          <a href="datamitra.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Mitra
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              </div>
              <h2>Data Mitra Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputdatamitra.php" name="forminputdatamitra" method="post"
              id="demo-form2" data-parsley-validate enctype="multipart/form-data">

              <!-- IDENTITAS USAHA -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Identitas Usaha
              </div>

              <div class="form-row">
                <label class="form-label">Nama Pemilik <span class="req">*</span>
                  <small>Nama lengkap pemilik usaha</small>
                </label>
                <div>
                  <input type="text" id="nama_pemilik" name="nama_pemilik" required maxlength="100"
                    placeholder="Masukkan Nama Pemilik" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Nama Usaha <span class="req">*</span>
                  <small>Nama resmi usaha/unit</small>
                </label>
                <div>
                  <input type="text" id="nama_usaha" name="nama_usaha" required maxlength="100"
                    placeholder="Masukkan Nama Usaha" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Kategori Usaha <span class="req">*</span>
                  <small>Jenis atau bidang usaha</small>
                </label>
                <div>
                  <select id="kategori_usaha" name="kategori_usaha" required class="form-select">
                    <option value="">Pilih Kategori Usaha</option>
                    <option value="UMKM">UMKM</option>
                    <option value="Local Guide">Local Guide</option>
                    <option value="Catering">Catering</option>
                    <option value="Dokar">Dokar</option>
                    <option value="Homestay">Homestay</option>
                    <option value="Kerajinan">Kerajinan</option>
                  </select>
                </div>
              </div>

              <!-- KONTAK & LOKASI -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kontak & Lokasi
              </div>

              <div class="form-row">
                <label class="form-label">Alamat <span class="req">*</span>
                  <small>Alamat lengkap lokasi usaha</small>
                </label>
                <div>
                  <textarea id="alamat" name="alamat" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Alamat Lengkap"></textarea>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Nomor Telepon <span class="req">*</span>
                  <small>Nomor HP aktif</small>
                </label>
                <div>
                  <input type="text" id="nomor_telp" name="nomor_telp" required maxlength="20"
                    placeholder="Masukkan Nomor Telepon" class="form-input">
                </div>
              </div>

              <!-- LEGALITAS & DOKUMEN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Legalitas & Dokumen
              </div>

              <div class="form-row">
                <label class="form-label">Legalitas Usaha <span class="req">*</span>
                  <small>Nomor/keterangan legalitas</small>
                </label>
                <div>
                  <input type="text" id="legalitas_usaha" name="legalitas_usaha" required maxlength="100"
                    placeholder="Masukkan Legalitas Usaha (misal: No. NIB, PIRT)" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">File Bukti Legalitas
                  <small>PDF – Maks. 10 MB (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('bukti_legalitas').click()">
                    <div class="upload-icon"><i class="fa fa-file-pdf-o"></i></div>
                    <div class="upload-text-main">Klik untuk upload file legalitas</div>
                    <div class="upload-text-sub">Format PDF, maks. 10 MB</div>
                  </div>
                  <input name="bukti_legalitas" accept="application/pdf" type="file" id="bukti_legalitas"
                    class="hidden-file-input" autocomplete="off" />
                  <div id="preview-legalitas" style="margin-top:8px;"></div>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Foto Kegiatan Usaha
                  <small>Foto dokumentasi usaha (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('foto_kegiatan').click()">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Klik untuk upload foto kegiatan</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB per foto</div>
                  </div>
                  <input name="foto_kegiatan[]" accept="image/*" type="file" id="foto_kegiatan"
                    class="hidden-file-input" multiple />
                  <div id="foto-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Data
                </button>
                <button type="reset" class="btn-reset" id="btn-reset">
                  <i class="fa fa-refresh"></i> Reset
                </button>
                <a href="datamitra.php" class="btn-cancel">
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  $(document).ready(function () {
    // Foto preview
    var selectedFiles = [];

    function renderFotoPreview() {
      var preview = $('#foto-preview');
      preview.empty();
      selectedFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = (function(f, i) {
          return function(e) {
            preview.append(
              '<div style="position:relative;display:inline-block;">' +
              '<img src="' + e.target.result + '" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1.5px solid #b5d5c0;">' +
              '<div style="font-size:9px;text-align:center;color:#6b8f7e;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + f.name + '</div>' +
              '<button type="button" onclick="removeFoto(' + i + ')" style="position:absolute;top:-6px;right:-6px;background:#e74c3c;color:#fff;border:none;border-radius:50%;width:20px;height:20px;font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>' +
              '</div>'
            );
          };
        })(file, idx);
        reader.readAsDataURL(file);
      });
      var dt = new DataTransfer();
      selectedFiles.forEach(function(f) { dt.items.add(f); });
      document.getElementById('foto_kegiatan').files = dt.files;
    }

    window.removeFoto = function(idx) { selectedFiles.splice(idx,1); renderFotoPreview(); };

    $('#foto_kegiatan').on('change', function() {
      Array.from(this.files).forEach(function(f) {
        if(!selectedFiles.find(function(x){return x.name===f.name&&x.size===f.size;})) selectedFiles.push(f);
      });
      renderFotoPreview();
    });

    // PDF preview
    $('#bukti_legalitas').on('change', function() {
      var file = this.files[0];
      if (!file) return;
      $('#preview-legalitas').html(
        '<div style="display:inline-flex;align-items:center;gap:8px;background:#f6fbf8;border:1px solid #b5d5c0;border-radius:8px;padding:8px 14px;">' +
        '<i class="fa fa-file-pdf-o" style="color:#e74c3c;font-size:18px;"></i>' +
        '<span style="font-size:13px;color:#2a4535;">' + file.name + '</span>' +
        '</div>'
      );
    });

    $('#btn-reset').on('click', function() { selectedFiles = []; $('#foto-preview').empty(); $('#preview-legalitas').empty(); });
  });
  </script>
</body>
</html>
