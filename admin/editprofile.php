<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
$sql   = "SELECT * FROM tb_admin WHERE id_admin='" . $_SESSION['id'] . "'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profil - Arsip Desa Candirejo</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  <link href="css/modern_admin.css?v=2.3" rel="stylesheet">
  <link href="css/form_modern.css?v=1.0" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Profil</h1>
            <p>Perbarui informasi akun dan foto profil Anda</p>
          </div>
          <a href="profile.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Profil
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <h2>Informasi Akun</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_editprofile.php" method="post" enctype="multipart/form-data"
              name="updateadmin" id="demo-form2">

              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Identitas
              </div>

              <div class="form-row">
                <label class="form-label">Nama Lengkap <span class="req">*</span>
                  <small>Nama tampil di sistem</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['nama_admin']); ?>" type="text"
                    id="nama_admin" name="nama_admin" required maxlength="70"
                    placeholder="Masukkan Nama Lengkap" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Username Admin <span class="req">*</span>
                  <small>Nama pengguna login</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['username_admin']); ?>" type="text"
                    id="username_admin" name="username_admin" required maxlength="50"
                    placeholder="Masukkan Username" class="form-input">
                </div>
              </div>

              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Foto Profil
              </div>

              <?php if (!empty($data['gambar'])): ?>
                <div class="form-row">
                  <label class="form-label">Foto Saat Ini</label>
                  <div>
                    <div style="display:flex;align-items:center;gap:14px;background:#f6fbf8;border:1px solid #d5e6dc;border-radius:10px;padding:14px;">
                      <img src="images/<?php echo htmlspecialchars($data['gambar']); ?>"
                        alt="Foto Profil" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2.5px solid #b5d5c0;">
                      <div>
                        <div style="font-weight:600;color:#2a4535;font-size:14px;"><?php echo htmlspecialchars($data['nama_admin']); ?></div>
                        <div style="font-size:12px;color:#6b8f7e;"><?php echo htmlspecialchars($data['username_admin']); ?></div>
                        <div style="font-size:11px;color:#95a5a6;margin-top:2px;">Foto profil aktif</div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="form-row">
                <label class="form-label">Ganti Foto Profil
                  <small>Maks. 2 MB, JPG/PNG (opsional)</small>
                </label>
                <div>
                  <div class="upload-area-modern" onclick="document.getElementById('gambar').click()" style="padding:16px;">
                    <div class="upload-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </div>
                    <div class="upload-text-main">Klik untuk upload foto baru</div>
                    <div class="upload-text-sub">JPG, PNG, JPEG – Maks. 2 MB</div>
                  </div>
                  <input name="gambar" accept="image/png,image/jpeg,image/jpg" type="file" id="gambar"
                    class="hidden-file-input" autocomplete="off" />
                  <div id="preview-gambar" style="margin-top:8px;"></div>
                  <span class="form-hint"><i class="fa fa-info-circle"></i> Kosongkan jika tidak ingin mengubah foto.</span>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" name="input" value="Simpan" class="btn-submit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                  Simpan Perubahan
                </button>
                <a href="profile.php" class="btn-cancel">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                  Batal
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
  document.getElementById('gambar').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview-gambar').innerHTML =
        '<div style="display:inline-flex;align-items:center;gap:12px;background:#f6fbf8;border:1px solid #b5d5c0;border-radius:10px;padding:10px;">' +
        '<img src="' + e.target.result + '" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #b5d5c0;">' +
        '<div><div style="font-size:13px;font-weight:600;color:#2a4535;">Preview Foto Baru</div>' +
        '<div style="font-size:11px;color:#6b8f7e;">' + file.name + '</div></div>' +
        '</div>';
    };
    reader.readAsDataURL(file);
  });
  </script>
</body>
</html>