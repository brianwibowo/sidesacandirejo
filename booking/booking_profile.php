<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id    = $_SESSION['id'];
$sql   = "SELECT * FROM tb_admin WHERE id_admin='" . $id . "'";
$query = mysqli_query($db, $sql);
$data  = mysqli_fetch_array($query);

$nama     = $data['nama_admin'] ?? 'Admin';
$initials = strtoupper(substr($nama, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Admin – Sistem Booking Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f4f7f5; color: #1e3a2f; min-height: 100vh;
    }

    /* ── LAYOUT ── */
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 28px 40px; max-width: 860px; }

    /* ── PAGE TITLE ── */
    .page-title { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-title-left h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title-left p  { font-size: 13.5px; color: #6b8f7e; }

    /* ── PROFILE CARD ── */
    .profile-card {
      background: #fff; border: 1px solid #e5ede8; border-radius: 12px; overflow: hidden;
    }

    /* Hero / Avatar area */
    .profile-hero {
      background: linear-gradient(135deg, #1e3a2f 0%, #2d5540 100%);
      padding: 32px 28px 28px;
      display: flex; align-items: center; gap: 22px;
    }
    .profile-avatar-wrap {
      position: relative; flex-shrink: 0;
    }
    .profile-avatar {
      width: 80px; height: 80px; border-radius: 50%;
      border: 3px solid rgba(255,255,255,0.25);
      overflow: hidden; background: rgba(255,255,255,0.15);
      display: flex; align-items: center; justify-content: center;
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-avatar-initial {
      font-size: 30px; font-weight: 700; color: #fff;
    }
    .profile-hero-info { flex: 1; min-width: 0; }
    .profile-hero-name {
      font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 4px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .profile-hero-role {
      font-size: 13px; color: rgba(255,255,255,0.65);
    }

    /* Detail section */
    .profile-body { padding: 28px; }

    .section-title {
      font-size: 11.5px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .07em; color: #7a9e8e;
      margin: 0 0 18px; padding-bottom: 8px;
      border-bottom: 1px solid #eef4f1;
      display: flex; align-items: center; gap: 8px;
    }

    .detail-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px;
    }
    .detail-item {}
    .detail-label {
      font-size: 11.5px; font-weight: 600; color: #9ab5a8;
      text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px;
    }
    .detail-value {
      font-size: 14px; font-weight: 500; color: #1e3a2f;
      background: #f6fbf8; border: 1px solid #ddeee5;
      border-radius: 8px; padding: 9px 13px;
      word-break: break-word;
    }

    /* Edit form */
    .edit-section { margin-top: 4px; }
    .form-row { display: grid; grid-template-columns: 180px 1fr; gap: 10px 20px; align-items: start; margin-bottom: 14px; }
    .form-label { font-size: 13.5px; font-weight: 500; color: #2a4535; padding-top: 9px; }
    .form-label small { display: block; font-size: 11.5px; font-weight: 400; color: #9ab5a8; margin-top: 2px; }
    .req { color: #c0392b; margin-left: 2px; }
    .form-input {
      width: 100%; padding: 9px 13px; font-size: 13.5px; color: #1e3a2f;
      border: 1px solid #d6e6dc; border-radius: 8px; background: #fff;
      outline: none; transition: border-color 0.15s, box-shadow 0.15s; font-family: inherit;
    }
    .form-input:focus { border-color: #2e7d4f; box-shadow: 0 0 0 3px rgba(46,125,79,.1); }
    .form-input::placeholder { color: #b0cfc0; }
    .form-input[type="file"] { padding: 7px 10px; cursor: pointer; }

    /* Divider */
    .section-divider { height: 1px; background: #f0f5f2; margin: 24px 0; }

    /* Actions */
    .form-actions { display: flex; align-items: center; gap: 10px; padding-top: 22px; border-top: 1px solid #f0f5f2; margin-top: 10px; }
    .btn-submit {
      display: inline-flex; align-items: center; gap: 8px;
      background: #1e3a2f; color: #fff; border: none;
      border-radius: 8px; padding: 11px 22px; font-size: 14px; font-weight: 500;
      cursor: pointer; transition: background 0.15s; font-family: inherit;
    }
    .btn-submit:hover { background: #2d5540; }
    .btn-cancel {
      display: inline-flex; align-items: center; gap: 8px;
      background: #fff; color: #6b8f7e; border: 1px solid #d6e6dc;
      border-radius: 8px; padding: 10px 18px; font-size: 13.5px; font-weight: 500;
      cursor: pointer; transition: all 0.15s; font-family: inherit; text-decoration: none;
    }
    .btn-cancel:hover { background: #f4f7f5; }

    /* Flash */
    .flash {
      display: flex; align-items: center; justify-content: space-between;
      padding: 12px 16px; border-radius: 9px; font-size: 13.5px; margin-bottom: 18px;
    }
    .flash.success { background: #e4f5ec; color: #1e6b3c; border: 1px solid #a8d8bc; }
    .flash.error   { background: #fceaea; color: #8b2020; border: 1px solid #e8a0a0; }
    .flash-x { background: none; border: none; cursor: pointer; font-size: 18px; color: inherit; padding: 0 4px; }

    /* Avatar preview */
    .avatar-preview-wrap { display: flex; align-items: center; gap: 14px; }
    .avatar-preview {
      width: 52px; height: 52px; border-radius: 50%; border: 2px solid #d6e6dc;
      overflow: hidden; background: #1e3a2f;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-preview-initial { font-size: 20px; font-weight: 700; color: #fff; }

    /* Footer */
    .booking-footer {
      margin-top: 40px; padding: 14px 28px;
      font-size: 12px; color: #9ab5a8; border-top: 1px solid #e5ede8; text-align: center;
    }

    @media (max-width: 860px) {
      .booking-content { margin-left: 60px; }
      .detail-grid { grid-template-columns: 1fr; }
      .form-row { grid-template-columns: 1fr; }
      .form-label { padding-top: 0; }
    }
  </style>
</head>
<body>

<?php include 'booking_sidebar.php'; ?>
<?php include 'booking_header.php'; ?>

<main class="booking-content" id="bookingContent">
  <div class="content-inner">

    <div class="page-title">
      <div class="page-title-left">
        <h1>Profil Admin</h1>
        <p>Lihat dan ubah informasi akun Anda</p>
      </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
    <div class="flash <?php echo $_GET['msg'] === 'sukses' ? 'success' : 'error'; ?>" id="flashMsg">
      <span style="display:flex;align-items:center;gap:8px;">
        <?php if ($_GET['msg'] === 'sukses'): ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M5 13l4 4L19 7"/></svg>
          Profil berhasil diperbarui.
        <?php else: ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
          <?php echo htmlspecialchars($_GET['detail'] ?? 'Terjadi kesalahan.'); ?>
        <?php endif; ?>
      </span>
      <button class="flash-x" onclick="this.closest('.flash').style.display='none'">&times;</button>
    </div>
    <?php endif; ?>

    <div class="profile-card">

      <!-- Hero -->
      <div class="profile-hero">
        <div class="profile-avatar-wrap">
          <div class="profile-avatar">
            <?php if (!empty($data['gambar'])): ?>
              <img src="../admin/images/<?php echo htmlspecialchars($data['gambar']); ?>?t=<?php echo filemtime('../admin/images/' . $data['gambar']); ?>" alt="Avatar">
            <?php else: ?>
              <span class="profile-avatar-initial"><?php echo $initials; ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="profile-hero-info">
          <div class="profile-hero-name"><?php echo htmlspecialchars($nama); ?></div>
          <div class="profile-hero-role">Administrator &middot; Sistem Booking Desa Wisata Candirejo</div>
        </div>
      </div>

      <div class="profile-body">

        <!-- Info Detail -->
        <div class="section-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Informasi Akun
        </div>
        <div class="detail-grid">
          <div class="detail-item">
            <div class="detail-label">ID Admin</div>
            <div class="detail-value"><?php echo htmlspecialchars($data['id_admin']); ?></div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Nama Admin</div>
            <div class="detail-value"><?php echo htmlspecialchars($data['nama_admin']); ?></div>
          </div>
          <div class="detail-item">
            <div class="detail-label">Username</div>
            <div class="detail-value"><?php echo htmlspecialchars($data['username_admin']); ?></div>
          </div>
        </div>

        <div class="section-divider"></div>

        <!-- Form Edit -->
        <div class="section-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Profil
        </div>

        <form action="proses_profile_booking.php" method="POST" enctype="multipart/form-data" id="formProfile">
          <input type="hidden" name="id_admin" value="<?php echo htmlspecialchars($data['id_admin']); ?>">
          <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($data['gambar'] ?? ''); ?>">

          <div class="form-row">
            <label class="form-label">Nama Admin<span class="req">*</span></label>
            <input type="text" name="nama_admin" required maxlength="100" class="form-input"
              value="<?php echo htmlspecialchars($data['nama_admin']); ?>"
              placeholder="Nama lengkap admin">
          </div>

          <div class="form-row">
            <label class="form-label">Username<span class="req">*</span></label>
            <input type="text" name="username_admin" required maxlength="50" class="form-input"
              value="<?php echo htmlspecialchars($data['username_admin']); ?>"
              placeholder="Username login">
          </div>

          <div class="form-row">
            <label class="form-label">Foto Profil
              <small>Format: JPG, PNG. Maks 2MB</small>
            </label>
            <div>
              <div class="avatar-preview-wrap" style="margin-bottom:10px;">
                <div class="avatar-preview" id="avatarPreview">
                  <?php if (!empty($data['gambar'])): ?>
                    <img src="../admin/images/<?php echo htmlspecialchars($data['gambar']); ?>?t=<?php echo filemtime('../admin/images/' . $data['gambar']); ?>" id="avatarImg" alt="Preview">
                  <?php else: ?>
                    <span class="avatar-preview-initial" id="avatarInitial"><?php echo $initials; ?></span>
                  <?php endif; ?>
                </div>
                <span style="font-size:12.5px;color:#9ab5a8;">Preview foto</span>
              </div>
              <input type="file" name="gambar" id="inputGambar" class="form-input" accept="image/jpeg,image/png">
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-submit" id="btnSimpan">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
              Simpan Perubahan
            </button>
            <a href="booking_dashboard.php" class="btn-cancel">Batal</a>
          </div>

        </form>

      </div><!-- /.profile-body -->
    </div><!-- /.profile-card -->

  </div>
  <div class="booking-footer">PTIK INTER UNNES'23. All Rights Reserved.</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // Sidebar collapse state
  if (localStorage.getItem('sidebarCollapsed') === '1') {
    document.getElementById('bookingContent').classList.add('collapsed');
  }

  // Avatar live preview
  var inputGambar  = document.getElementById('inputGambar');
  var avatarPreview = document.getElementById('avatarPreview');
  if (inputGambar) {
    inputGambar.addEventListener('change', function () {
      var file = this.files[0];
      if (!file) return;
      var reader = new FileReader();
      reader.onload = function (e) {
        avatarPreview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
      };
      reader.readAsDataURL(file);
    });
  }

  // Submit confirm
  document.getElementById('btnSimpan').addEventListener('click', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Simpan Perubahan?',
      text: 'Data profil Anda akan diperbarui.',
      icon: 'question',
      iconColor: '#2e7d4f',
      showCancelButton: true,
      confirmButtonText: 'Ya, Simpan',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#2e7d4f',
      cancelButtonColor: '#aaa'
    }).then(function (r) {
      if (r.isConfirmed) document.getElementById('formProfile').submit();
    });
  });

});
</script>
</body>
</html>