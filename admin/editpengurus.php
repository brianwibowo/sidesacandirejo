<?php
session_start();
include "login/ceksession.php";
ob_start();
include '../koneksi/koneksi.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM tb_data_pengurus WHERE id = $id";
$query = mysqli_query($db, $sql);
$data_pengurus = mysqli_fetch_array($query, MYSQLI_ASSOC);
if (!$data_pengurus) {
  echo "<script>alert('Data tidak ditemukan');window.location.href='datapengurus.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Pengurus - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <style>
    .foto-existing-preview {
      max-width: 130px; max-height: 130px; object-fit: contain;
      border-radius: 8px; border: 1.5px solid #b5d5c0; margin-bottom: 8px;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Data Pengurus</h1>
            <p>Perbarui data pengurus Desa Wisata Candirejo</p>
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
              <h2>Edit Data Pengurus</h2>
            </div>
          </div>

          <div class="form-card-body">
            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Mengedit data pengurus: <strong><?php echo htmlspecialchars($data_pengurus['nama'] ?? ''); ?></strong>
            </div>

            <form action="proses/proses_editpengurus.php" method="post" enctype="multipart/form-data" id="demo-form2">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <!-- IDENTITAS -->
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
                    placeholder="Masukkan Nama Lengkap" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengurus['nama'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">No. KTP <span class="req">*</span>
                  <small>Nomor KTP pengurus</small>
                </label>
                <div>
                  <input type="text" id="no_ktp" name="no_ktp" required maxlength="20"
                    placeholder="Masukkan Nomor KTP" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengurus['no_ktp'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Jabatan <span class="req">*</span>
                  <small>Jabatan dalam organisasi</small>
                </label>
                <div>
                  <input type="text" id="jabatan" name="jabatan" required maxlength="50"
                    placeholder="Masukkan Jabatan" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengurus['jabatan'] ?? ''); ?>">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Periode <span class="req">*</span>
                  <small>Masa jabatan (contoh: 2022-2025)</small>
                </label>
                <div>
                  <input type="text" id="periode" name="periode" required maxlength="20"
                    placeholder="Masukkan Periode" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengurus['periode'] ?? ''); ?>">
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
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Alamat"><?php echo htmlspecialchars($data_pengurus['alamat'] ?? ''); ?></textarea>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">No. Telepon <span class="req">*</span>
                  <small>Nomor HP aktif</small>
                </label>
                <div>
                  <input type="text" id="no_telp" name="no_telp" required maxlength="15"
                    placeholder="Masukkan Nomor Telepon" class="form-input"
                    value="<?php echo htmlspecialchars($data_pengurus['no_telp'] ?? ''); ?>">
                </div>
              </div>

              <!-- BERKAS -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Berkas & Dokumen
              </div>

              <div class="form-row">
                <label class="form-label">Foto KTP
                  <small>Kosongkan jika tidak mengganti</small>
                </label>
                <div>
                  <?php if (!empty($data_pengurus['foto_ktp'])): ?>
                    <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data_pengurus['foto_ktp']); ?>"
                      alt="Foto KTP" class="foto-existing-preview"><br>
                    <span class="form-hint" style="margin-bottom:8px;display:block;">Foto KTP saat ini</span>
                  <?php endif; ?>
                  <div class="upload-area-modern" onclick="document.getElementById('foto_ktp').click()" style="padding:16px;">
                    <div class="upload-icon"><i class="fa fa-id-card"></i></div>
                    <div class="upload-text-main">Klik untuk ganti foto KTP</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB</div>
                  </div>
                  <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*" class="hidden-file-input" />
                  <div id="preview-ktp" style="margin-top:8px;"></div>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Pas Foto
                  <small>Kosongkan jika tidak mengganti</small>
                </label>
                <div>
                  <?php if (!empty($data_pengurus['pas_foto'])): ?>
                    <img src="../admin/uploads/pengurus/<?php echo htmlspecialchars($data_pengurus['pas_foto']); ?>"
                      alt="Pas Foto" class="foto-existing-preview"><br>
                    <span class="form-hint" style="margin-bottom:8px;display:block;">Pas foto saat ini</span>
                  <?php endif; ?>
                  <div class="upload-area-modern" onclick="document.getElementById('pas_foto').click()" style="padding:16px;">
                    <div class="upload-icon"><i class="fa fa-user-circle"></i></div>
                    <div class="upload-text-main">Klik untuk ganti pas foto</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB</div>
                  </div>
                  <input type="file" id="pas_foto" name="pas_foto" accept="image/*" class="hidden-file-input" />
                  <div id="preview-pasfoto" style="margin-top:8px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
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
    document.getElementById(inputId).addEventListener('change', function() {
      var file = this.files[0];
      if (!file) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(previewId).innerHTML =
          '<img src="' + e.target.result + '" style="max-width:130px;max-height:130px;border-radius:8px;border:1.5px solid #b5d5c0;object-fit:cover;">' +
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
<?php ob_end_flush(); ?>