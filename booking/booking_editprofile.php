<?php
session_start();
include "login/ceksession.php";
include "../koneksi/koneksi.php";

$id = mysqli_real_escape_string($db, $_SESSION['id']);
$sql = "SELECT * FROM tb_admin WHERE id_admin='$id'";
$query = mysqli_query($db, $sql);
$data = mysqli_fetch_array($query);

if (!$data) {
    header("Location: booking_dashboard.php");
    exit();
}

$nama_admin     = htmlspecialchars($data['nama_admin'] ?? 'Administrator');
$username_admin = htmlspecialchars($data['username_admin'] ?? 'admin');
$avatar_file    = $data['gambar'] ?? '';
$has_avatar     = (!empty($avatar_file) && file_exists(__DIR__ . "/../admin/images/" . $avatar_file));
$initials       = strtoupper(substr($nama_admin, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profil - Sistem Booking Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f7f5;
      color: #1e3a2f;
      min-height: 100vh;
    }

    /* Content Layout */
    .booking-content {
      margin-left: 240px;
      padding-top: 58px;
      min-height: 100vh;
      transition: margin-left 0.25s;
    }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 32px 48px; max-width: 960px; margin: 0 auto; }

    /* Page Header Bar */
    .page-header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
    }
    .page-header-title h1 {
      font-family: 'Outfit', sans-serif;
      font-size: 24px;
      font-weight: 700;
      color: #142a20;
      margin: 0 0 4px 0;
      letter-spacing: -0.02em;
    }
    .page-header-title p {
      font-size: 13.5px;
      color: #527967;
      margin: 0;
    }
    .btn-back-link {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      border-radius: 9px;
      font-size: 13px;
      font-weight: 600;
      color: #2d5540;
      background: #ffffff;
      border: 1px solid #d8e5dd;
      text-decoration: none;
      transition: all 0.18s ease;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .btn-back-link:hover {
      background: #eef5f1;
      border-color: #bad3c3;
      color: #142a20;
    }

    /* Card Form */
    .form-card {
      background: #fff;
      border: 1px solid #e0ebe4;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(20, 42, 32, 0.05);
      overflow: hidden;
    }
    .form-card-header {
      padding: 18px 24px;
      border-bottom: 1px solid #edf3f0;
      background: #fafcfb;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .form-header-icon {
      width: 36px;
      height: 36px;
      border-radius: 9px;
      background: #eef5f1;
      color: #2d5540;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .form-card-header h2 {
      font-family: 'Outfit', sans-serif;
      font-size: 17px;
      font-weight: 700;
      color: #142a20;
      margin: 0;
    }
    .form-card-body {
      padding: 28px 26px;
    }

    /* Form Fields */
    .form-group {
      margin-bottom: 22px;
    }
    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #1e3a2f;
      margin-bottom: 8px;
    }
    .form-label .required {
      color: #dc2626;
      margin-left: 2px;
    }
    .form-control {
      width: 100%;
      padding: 10px 14px;
      border-radius: 9px;
      border: 1px solid #d4e3db;
      font-size: 14px;
      font-family: inherit;
      color: #142a20;
      background: #fff;
      transition: all 0.18s ease;
      outline: none;
    }
    .form-control:focus {
      border-color: #10b981;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
    .form-hint {
      font-size: 12px;
      color: #6b8f7e;
      margin-top: 6px;
      line-height: 1.4;
    }

    /* Photo Upload Area */
    .avatar-preview-container {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 8px;
      padding: 16px;
      background: #fbfdfc;
      border: 1px dashed #cfdfd6;
      border-radius: 12px;
    }
    .current-avatar-preview {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      background: #fff;
      border: 3px solid #10b981;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      flex-shrink: 0;
    }
    .current-avatar-fallback {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #2e7d4f;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: 700;
      border: 3px solid #10b981;
      flex-shrink: 0;
    }
    .avatar-upload-info {
      flex: 1;
      min-width: 220px;
    }
    .avatar-upload-info h4 {
      font-size: 13.5px;
      font-weight: 600;
      color: #142a20;
      margin-bottom: 4px;
    }
    .file-input-wrap input[type="file"] {
      font-size: 13px;
      color: #4a6358;
    }

    /* Form Actions */
    .form-actions-bar {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
      padding-top: 16px;
      border-top: 1px solid #edf3f0;
      margin-top: 10px;
    }
    .btn-cancel {
      display: inline-flex;
      align-items: center;
      padding: 10px 20px;
      border-radius: 9px;
      font-size: 13.5px;
      font-weight: 600;
      color: #527967;
      background: #f0f5f2;
      border: 1px solid #d8e5dd;
      text-decoration: none;
      transition: all 0.18s ease;
    }
    .btn-cancel:hover {
      background: #e2ede7;
      color: #142a20;
    }
    .btn-submit-save {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 24px;
      border-radius: 9px;
      font-size: 13.5px;
      font-weight: 600;
      color: #ffffff;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: 1px solid #059669;
      cursor: pointer;
      transition: all 0.18s ease;
      box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
    }
    .btn-submit-save:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <?php include 'booking_sidebar.php'; ?>

  <!-- Header -->
  <?php include 'booking_header.php'; ?>

  <!-- Main Content -->
  <main class="booking-content" id="bookingContent">
    <div class="content-inner">

      <!-- Page Header -->
      <div class="page-header-bar">
        <div class="page-header-title">
          <h1>Edit Profil Administrator</h1>
          <p>Perbarui nama lengkap, username login, dan foto profil Anda</p>
        </div>
        <div>
          <a href="booking_profile.php" class="btn-back-link" title="Kembali ke Profil">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
              <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Profil
          </a>
        </div>
      </div>

      <!-- Form Card -->
      <div class="form-card">
        <div class="form-card-header">
          <div class="form-header-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M15.232 5.232l3.536 3.536M9 11l6.364-6.364a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414z"/>
              <path d="M3 21h18"/>
            </svg>
          </div>
          <h2>Formulir Perubahan Profil Akun</h2>
        </div>

        <div class="form-card-body">
          <form action="proses/proses_editprofile.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
              <label class="form-label" for="nama_admin">Nama Lengkap Administrator <span class="required">*</span></label>
              <input type="text" class="form-control" id="nama_admin" name="nama_admin" value="<?php echo $nama_admin; ?>" required placeholder="Masukkan nama lengkap">
              <div class="form-hint">Nama ini akan ditampilkan pada navigasi header dan catatan riwayat tindakan.</div>
            </div>

            <div class="form-group">
              <label class="form-label" for="username_admin">Username Login <span class="required">*</span></label>
              <input type="text" class="form-control" id="username_admin" name="username_admin" value="<?php echo $username_admin; ?>" required placeholder="Masukkan username login">
              <div class="form-hint">Gunakan huruf kecil dan angka tanpa spasi untuk kredensial masuk ke sistem.</div>
            </div>

            <div class="form-group">
              <label class="form-label">Foto Profil Akun</label>
              <div class="avatar-preview-container">
                <?php if ($has_avatar): ?>
                  <img src="../admin/images/<?php echo htmlspecialchars($avatar_file); ?>" alt="Preview" id="avatarPreviewImg" class="current-avatar-preview">
                <?php else: ?>
                  <div class="current-avatar-fallback" id="avatarPreviewFallback"><?php echo $initials; ?></div>
                  <img src="" alt="Preview" id="avatarPreviewImg" class="current-avatar-preview" style="display:none;">
                <?php endif; ?>
                
                <div class="avatar-upload-info">
                  <h4>Unggah Foto Baru</h4>
                  <div class="file-input-wrap">
                    <input type="file" name="gambar" id="gambarInput" accept="image/jpeg,image/png,image/jpg" onchange="previewAvatar(event)">
                  </div>
                  <div class="form-hint">Format didukung: JPG, JPEG, PNG (Maksimal ukuran file 2 MB). Biarkan kosong jika tidak ingin mengubah foto.</div>
                </div>
              </div>
            </div>

            <div class="form-actions-bar">
              <a href="booking_profile.php" class="btn-cancel">Batal</a>
              <button type="submit" class="btn-submit-save">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                  <path d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </main>

  <script>
    function previewAvatar(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          var img = document.getElementById('avatarPreviewImg');
          var fallback = document.getElementById('avatarPreviewFallback');
          if (img) {
            img.src = e.target.result;
            img.style.display = 'block';
          }
          if (fallback) {
            fallback.style.display = 'none';
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>

  <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal_gambar'): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Unggah Gambar Gagal',
      text: 'Format foto tidak didukung atau ukuran file melebihi 2MB. Silakan gunakan JPG/PNG.',
      confirmButtonColor: '#dc2626'
    });
  </script>
  <?php endif; ?>

</body>
</html>
