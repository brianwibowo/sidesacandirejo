<?php
session_start();
include "ceksessionn.php";

if (isset($_GET['from'])) {
    $_SESSION['login_from'] = $_GET['from'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIDESA Candirejo</title>
  <link rel="shortcut icon" href="../../img/icon.ico">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('background2.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .login-card {
      background: rgba(255,255,255,0.97);
      border-radius: 20px;
      padding: 28px 36px 28px;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.13);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .lock-icon-wrapper {
      width: 70px; height: 70px;
      border-radius: 50%;
      background: #f0f5f0;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }

    h1 { font-size: 24px; font-weight: 700; color: #222; margin-bottom: 4px; text-align: center; }
    .subtitle { font-size: 13px; color: #888; margin-bottom: 20px; text-align: center; }

    .alert-danger {
      width: 100%;
      background: #fff0f0; border: 1px solid #f5c6cb; color: #721c24;
      border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px;
    }

    .form-group { width: 100%; margin-bottom: 14px; }
    .form-group label { display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 7px; }

    .input-wrapper { position: relative; display: flex; align-items: center; }
    .input-wrapper .input-icon { position: absolute; left: 14px; color: #aaa; display: flex; }
    .input-wrapper .input-icon svg { width: 17px; height: 17px; }

    .input-wrapper input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1.5px solid #e0e0e0;
      border-radius: 10px;
      font-size: 14px; color: #333;
      background: #fafafa; outline: none;
      transition: border-color 0.2s;
    }
    .input-wrapper input:focus { border-color: #4caf50; background: #fff; }
    .input-wrapper input::placeholder { color: #bbb; }

    .toggle-password {
      position: absolute; right: 14px;
      background: none; border: none; cursor: pointer; color: #aaa; display: flex;
    }
    .toggle-password svg { width: 18px; height: 18px; }

    .btn-masuk {
      width: 100%; padding: 12px;
      background: #3d8b3d; color: #fff;
      border: none; border-radius: 10px;
      font-size: 15px; font-weight: 600; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-bottom: 10px; transition: background 0.2s;
    }
    .btn-masuk:hover { background: #2e6e2e; }
    .btn-masuk svg { width: 18px; height: 18px; }

    .btn-kembali {
      width: 100%; padding: 13px;
      background: #fff; color: #333;
      border: 1.5px solid #d0d0d0; border-radius: 10px;
      font-size: 15px; font-weight: 500; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-bottom: 20px; text-decoration: none;
      transition: background 0.2s, border-color 0.2s;
    }
    .btn-kembali:hover { background: #f5f5f5; color: #333; text-decoration: none; border-color: #aaa; }
    .btn-kembali svg { width: 17px; height: 17px; }

    .card-footer { width: 100%; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
    .footer-title {
      font-size: 14px; font-weight: 700; color: #333;
      display: flex; align-items: center; justify-content: center; gap: 7px; margin-bottom: 4px;
    }
    .footer-title svg { width: 16px; height: 16px; }
    .footer-sub { font-size: 12px; color: #999; }
  </style>
</head>

<body>
  <img src="../../img/samarinda.png" alt="Logo Desa Wisata Candirejo" style="position:fixed; top:24px; left:28px; height:60px; width:auto; z-index:10;">
  <div class="login-card">

    <div class="lock-icon-wrapper">
      <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
        <rect x="5" y="11" width="14" height="10" rx="2.5" fill="#3d8b3d"/>
        <path d="M8 11V7.5a4 4 0 0 1 8 0V11" stroke="#3d8b3d" stroke-width="2" stroke-linecap="round"/>
        <circle cx="12" cy="16" r="1.5" fill="white"/>
        <line x1="12" y1="17.5" x2="12" y2="19" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </div>

    <h1>Selamat Datang</h1>
    <p class="subtitle">Silakan masuk untuk melanjutkan</p>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert-danger"><?= $_SESSION['error']; ?></div>
    <?php endif; ?>

    <form action="proses_login.php" id="login" name="login" method="post" style="width:100%">

      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-wrapper">
          <span class="input-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </span>
          <input type="text" id="username" name="username_admin" autocomplete="off" maxlength="50"
            placeholder="Masukkan username Anda"
            value="<?= isset($_SESSION['old_username']) ? htmlspecialchars($_SESSION['old_username']) : '' ?>" required/>
        </div>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-wrapper">
          <span class="input-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </span>
          <input type="password" id="password" name="password" autocomplete="off" maxlength="50"
            placeholder="Masukkan password Anda" required/>
          <button type="button" class="toggle-password" onclick="togglePassword()">
            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
              <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
              <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-masuk">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Masuk
      </button>
    </form>

    <a href="../../index.php" class="btn-kembali">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/>
        <polyline points="12 19 5 12 12 5"/>
      </svg>
      Kembali ke Beranda
    </a>

    <div class="card-footer">
      <div class="footer-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/>
          <line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/>
          <line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/>
        </svg>
        DESA WISATA CANDIREJO
      </div>
      <p class="footer-sub">Tim Pengabdian DRTPM UNNES. All Right Reserved</p>
    </div>

  </div>

  <?php
  unset($_SESSION['error']);
  unset($_SESSION['old_username']);
  ?>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
      }
    }
  </script>
</body>
</html>