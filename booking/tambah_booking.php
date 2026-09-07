<?php
session_start();
include "login/ceksession.php";

$ref = isset($_GET['ref']) ? $_GET['ref'] : 'booking_dashboard.php';
$allowed_refs = ['booking_semua.php', 'booking_pending.php', 'booking_checkin.php', 'booking_tidakdatang.php', 'booking_dashboard.php', 'booking_kalender.php'];
if (!in_array($ref, $allowed_refs)) {
    $ref = 'booking_dashboard.php';
}
$back_labels = [
    'booking_dashboard.php'   => 'Kembali ke Dashboard',
    'booking_semua.php'       => 'Kembali ke Semua Booking',
    'booking_pending.php'     => 'Kembali ke Booking Pending',
    'booking_checkin.php'     => 'Kembali ke Booking Check-in',
    'booking_tidakdatang.php' => 'Kembali ke Booking Tidak Datang',
    'booking_kalender.php'    => 'Kembali ke Kalender',
];
$back_label = $back_labels[$ref] ?? 'Kembali';
$val_tgl    = (isset($_GET['tgl']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['tgl'])) ? $_GET['tgl'] : date('Y-m-d');
$back_url   = $ref;
if ($ref === 'booking_kalender.php' && $val_tgl) {
    $ts_tgl = strtotime($val_tgl);
    if ($ts_tgl) {
        $back_url = 'booking_kalender.php?bulan=' . date('n', $ts_tgl) . '&tahun=' . date('Y', $ts_tgl) . '&tgl=' . $val_tgl;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Booking – Desa Wisata Candirejo</title>
  <link rel="shortcut icon" href="img/iconbooking.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f7f5; color: #1e3a2f; min-height: 100vh; }
    h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }
    .booking-content { margin-left: 240px; padding-top: 58px; min-height: 100vh; transition: margin-left 0.25s; }
    .booking-content.collapsed { margin-left: 60px; }
    .content-inner { padding: 28px 28px 40px; }
    .page-title { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-title-left h1 { font-size: 22px; font-weight: 600; color: #1e3a2f; margin-bottom: 2px; }
    .page-title-left p  { font-size: 13.5px; color: #6b8f7e; }
    .btn-back { display: inline-flex; align-items: center; gap: 7px; background: #fff; color: #4a6a57; border: 1px solid #d6e6dc; border-radius: 8px; padding: 9px 16px; font-size: 13.5px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.15s; }
    .btn-back:hover { background: #f0f7f3; border-color: #b5d5c0; color: #1e3a2f; }
    .form-card { background: #fff; border: 1px solid #e5ede8; border-radius: 12px; width: 100%; }
    .form-card-header { padding: 18px 24px 14px; border-bottom: 1px solid #f0f5f2; display: flex; align-items: center; gap: 10px; }
    .form-card-header .hicon { width: 36px; height: 36px; background: #e4f5ec; border-radius: 9px; display: flex; align-items: center; justify-content: center; color: #2e7d4f; flex-shrink: 0; }
    .form-card-header h2 { font-size: 16px; font-weight: 600; color: #1e3a2f; }
    .form-card-body { padding: 24px; }
    .section-title { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #7a9e8e; margin: 28px 0 16px; padding-bottom: 8px; border-bottom: 1px solid #eef4f1; display: flex; align-items: center; gap: 8px; }
    .section-title:first-child { margin-top: 0; }
    .form-row { display: grid; grid-template-columns: 200px 1fr; gap: 10px 20px; align-items: start; margin-bottom: 14px; }
    .form-label { font-size: 13.5px; font-weight: 500; color: #2a4535; padding-top: 9px; line-height: 1.4; }
    .form-label small { display: block; font-size: 11.5px; font-weight: 400; color: #9ab5a8; margin-top: 2px; }
    .req { color: #c0392b; margin-left: 2px; }
    .form-input, .form-select { width: 100%; padding: 9px 13px; font-size: 13.5px; color: #1e3a2f; border: 1px solid #d6e6dc; border-radius: 8px; background: #fff; outline: none; transition: border-color 0.15s, box-shadow 0.15s; font-family: inherit; }
    .form-input:focus, .form-select:focus { border-color: #2e7d4f; box-shadow: 0 0 0 3px rgba(46,125,79,.1); }
    .form-input::placeholder { color: #b0cfc0; }
    .form-select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a9e8e' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 34px; }
    .input-sm { max-width: 110px; }
    .radio-group { display: flex; gap: 8px; flex-wrap: wrap; padding-top: 5px; }
    .radio-pill { display: flex; align-items: center; gap: 7px; padding: 8px 14px; border: 1.5px solid #d6e6dc; border-radius: 8px; cursor: pointer; font-size: 13.5px; transition: all 0.15s; background: #fff; user-select: none; }
    .radio-pill:hover { border-color: #2e7d4f; background: #f4fbf6; }
    .radio-pill input[type="radio"] { accent-color: #2e7d4f; width: 14px; height: 14px; cursor: pointer; }
    .radio-pill.active { border-color: #2e7d4f; background: #edf8f2; color: #1e6b3c; font-weight: 500; }
    .sub-field { display: none; grid-template-columns: 200px 1fr; gap: 10px 20px; align-items: start; margin-bottom: 14px; }
    .sub-field.visible { display: grid; }
    .sub-field-label { font-size: 13.5px; font-weight: 500; color: #2a4535; padding-top: 9px; line-height: 1.4; }
    .sub-field-content { background: #f6fbf8; border: 1px solid #ddeee5; border-radius: 9px; padding: 14px 16px; }
    .sub-field-content .form-input { background: #fff; }
    .sub-label { font-size: 11px; font-weight: 700; color: #6b8f7e; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 10px; }
    .form-actions { display: flex; align-items: center; gap: 10px; padding-top: 22px; border-top: 1px solid #f0f5f2; margin-top: 26px; }
    .btn-submit { display: inline-flex; align-items: center; gap: 8px; background: #1e3a2f; color: #fff; border: none; border-radius: 8px; padding: 11px 22px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.15s; font-family: inherit; }
    .btn-submit:hover { background: #2d5540; }
    .btn-reset { display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #6b8f7e; border: 1px solid #d6e6dc; border-radius: 8px; padding: 10px 18px; font-size: 13.5px; font-weight: 500; cursor: pointer; transition: all 0.15s; font-family: inherit; }
    .btn-reset:hover { background: #f4f7f5; }
    .flash { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 9px; font-size: 13.5px; margin-bottom: 18px; }
    .flash.error { background: #fceaea; color: #8b2020; border: 1px solid #e8a0a0; }
    .flash-x { background: none; border: none; cursor: pointer; font-size: 18px; color: inherit; padding: 0 4px; line-height: 1; }
    .booking-footer { text-align: right; padding: 16px 28px; font-size: 12.5px; color: #9ab5a8; border-top: 1px solid #e5ede8; margin-top: 20px; }
    @media (max-width: 860px) {
      .booking-content { margin-left: 60px; }
      .form-row, .sub-field { grid-template-columns: 1fr; }
      .form-label, .sub-field-label { padding-top: 0; }
      .sub-field-label { display: none; }
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
        <h1>Tambah Booking</h1>
        <p>Isi data booking baru pengunjung Desa Wisata Candirejo</p>
      </div>
      <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        <?php echo htmlspecialchars($back_label); ?>
      </a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'gagal'): ?>
    <div class="flash error" id="flashMsg">
      <span style="display:flex;align-items:center;gap:8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
        <?php echo htmlspecialchars($_GET['detail'] ?? 'Terjadi kesalahan.'); ?>
      </span>
      <button class="flash-x" id="btnCloseFlash">&times;</button>
    </div>
    <?php endif; ?>

    <div class="form-card">
      <div class="form-card-header">
        <div class="hicon">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h2>Form Booking Baru</h2>
      </div>

      <div class="form-card-body">
        <form action="proses/proses_booking.php" method="POST" id="formTambah">
          <input type="hidden" name="aksi" value="tambah">
          <input type="hidden" name="ref" value="<?php echo htmlspecialchars($ref); ?>">

          <!-- INFORMASI KUNJUNGAN -->
          <div class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
            Informasi Kunjungan
          </div>

          <div class="form-row">
            <label class="form-label">Tanggal Kunjungan<span class="req">*</span></label>
            <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" required class="form-input" style="max-width:200px;" value="<?php echo htmlspecialchars($val_tgl); ?>">
          </div>

          <div class="form-row">
            <label class="form-label">Paket Wisata<span class="req">*</span></label>
            <select name="pilihan_paket_wisata" id="pilihan_paket_wisata" required class="form-select">
              <option value="">-- Pilih Paket --</option>
              <option value="meal_only">Breakfast / Lunch / Dinner Only</option>
              <option value="studi_banding">Studi Banding</option>
              <option value="fun_game">Paket Fun Game</option>
              <option value="pelajar_live_in">Paket Pelajar - Live In Candirejo</option>
              <option value="pelajar_field_trip_one_day">Paket Pelajar - Field Trip One Day</option>
              <option value="pelajar_field_trip_half_day">Paket Pelajar - Field Trip Half Day</option>
              <option value="cycling_tour">Cycling Village Tour with/without Lunch</option>
              <option value="traditional_dance">Traditional Dance</option>
              <option value="walking_tour">Walking Around Village with/without Lunch</option>
              <option value="homestay">Homestay - Stay At Local House</option>
              <option value="serenade">Serenade At The Foot Of Menoreh Hill</option>
              <option value="cooking_lesson">Cooking Lesson with/without Tour</option>
              <option value="gamelan_class">Gamelan Class with/without Lunch</option>
              <option value="village_experience">Village Experience</option>
              <option value="dokar_tour">Dokar Village Tour with/without Lunch</option>
              <option value="inspection">Inspection</option>
              <option value="lainnya">Lainnya</option>
            </select>
          </div>

          <!-- Sub: Opsi Makan Tour -->
          <div class="sub-field" id="sub_makan_tour">
            <div class="sub-field-label">Opsi Makan</div>
            <div class="sub-field-content">
              <div class="sub-label">Pilih Opsi Makan</div>
              <div class="radio-group">
                <label class="radio-pill">
                  <input type="radio" name="opsi_makan_tour" value="without_lunch">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  Without Lunch
                </label>
                <label class="radio-pill">
                  <input type="radio" name="opsi_makan_tour" value="with_lunch">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                  With Lunch
                </label>
              </div>
            </div>
          </div>

          <!-- Sub: Jenis Makanan -->
          <div class="sub-field" id="sub_jenis_makanan">
            <div class="sub-field-label">Jenis Makanan</div>
            <div class="sub-field-content">
              <div class="sub-label">Pilih Jenis Makanan</div>
              <div class="radio-group">
                <label class="radio-pill">
                  <input type="radio" name="jenis_makanan_paket" value="breakfast">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2v4M4.93 4.93l2.83 2.83M2 12h4M4.93 19.07l2.83-2.83M12 18v4M19.07 19.07l-2.83-2.83M22 12h-4M19.07 4.93l-2.83 2.83"/></svg>
                  Breakfast
                </label>
                <label class="radio-pill">
                  <input type="radio" name="jenis_makanan_paket" value="lunch">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>
                  Lunch
                </label>
                <label class="radio-pill">
                  <input type="radio" name="jenis_makanan_paket" value="dinner">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                  Dinner
                </label>
              </div>
            </div>
          </div>

          <!-- Sub: Opsi Cooking Lesson -->
          <div class="sub-field" id="sub_cooking">
            <div class="sub-field-label">Opsi Cooking</div>
            <div class="sub-field-content">
              <div class="sub-label">Pilih Opsi Cooking Lesson</div>
              <div class="radio-group">
                <label class="radio-pill">
                  <input type="radio" name="opsi_cooking_lesson" value="lesson_only">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                  Lesson Only
                </label>
                <label class="radio-pill">
                  <input type="radio" name="opsi_cooking_lesson" value="lesson_with_tour">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/></svg>
                  Lesson with Tour
                </label>
              </div>
            </div>
          </div>

          <!-- Sub: Opsi Gamelan -->
          <div class="sub-field" id="sub_gamelan">
            <div class="sub-field-label">Opsi Gamelan</div>
            <div class="sub-field-content">
              <div class="sub-label">Pilih Opsi Gamelan Class</div>
              <div class="radio-group">
                <label class="radio-pill">
                  <input type="radio" name="opsi_gamelan" value="without_lunch">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  Without Lunch
                </label>
                <label class="radio-pill">
                  <input type="radio" name="opsi_gamelan" value="with_lunch">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                  With Lunch
                </label>
              </div>
            </div>
          </div>

          <!-- DATA WISATAWAN -->
          <div class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Data Wisatawan
          </div>

          <div class="form-row">
            <label class="form-label">Nama Wisatawan<span class="req">*</span></label>
            <input type="text" name="nama" id="nama" required maxlength="100" class="form-input" placeholder="Nama pengunjung atau nama wisatawan">
          </div>

          <div class="form-row">
            <label class="form-label">Jenis Wisatawan<span class="req">*</span></label>
            <div class="radio-group">
              <label class="radio-pill" id="pill_domestik">
                <input type="radio" name="jenis_wisatawan" value="Domestik" id="jw_domestik" required>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                Domestik
              </label>
              <label class="radio-pill" id="pill_mancanegara">
                <input type="radio" name="jenis_wisatawan" value="Mancanegara" id="jw_mancanegara">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                Mancanegara
              </label>
            </div>
          </div>

          <!-- Sub: Kota (Domestik) -->
          <div class="sub-field" id="sub_kota">
            <div class="sub-field-label">Kota Asal<span class="req">*</span></div>
            <div class="sub-field-content">
              <input type="text" name="kota" id="kota" maxlength="100" class="form-input" placeholder="Contoh: Yogyakarta, Semarang, Jakarta">
            </div>
          </div>

          <!-- Sub: Negara (Mancanegara) -->
          <div class="sub-field" id="sub_negara">
            <div class="sub-field-label">Negara Asal<span class="req">*</span></div>
            <div class="sub-field-content">
              <input type="text" name="negara" id="negara" maxlength="100" class="form-input" placeholder="Contoh: Australia, Netherlands, Japan">
            </div>
          </div>

          <div class="form-row">
            <label class="form-label">Jumlah Pax<span class="req">*</span>
              <small>Jumlah orang/peserta</small>
            </label>
            <input type="number" name="pax" required min="0" max="9999" value="0" class="form-input input-sm">
          </div>

          <!-- AGEN & PEMANDU -->
          <div class="section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/></svg>
            Agen &amp; Pemandu
          </div>

          <div class="form-row">
            <label class="form-label">Agen Wisata
              <small>Opsional, default 'Belum Ada'</small>
            </label>
            <input type="text" name="agen_wisata" id="agen_wisata" maxlength="100" class="form-input" placeholder="Nama agen wisata (opsional)">
          </div>

          <div class="form-row">
            <label class="form-label">Driver / Agent Guide
              <small>Opsional, default 'Belum Ada'</small>
            </label>
            <input type="text" name="driver_agent_guide" maxlength="100" value="Belum Ada" class="form-input" placeholder="Belum Ada">
          </div>

          <div class="form-row">
            <label class="form-label">Local Guide
              <small>Opsional, default 'Belum Ada'</small>
            </label>
            <input type="text" name="local_guide" maxlength="100" value="Belum Ada" class="form-input" placeholder="Belum Ada">
          </div>

          <div class="form-row">
            <label class="form-label">Catatan
              <small>Catatan tambahan (opsional)</small>
            </label>
            <textarea name="catatan" id="catatan" rows="3" class="form-input" style="resize:vertical;min-height:85px;line-height:1.5;" placeholder="Tuliskan catatan khusus atau keterangan tambahan..."></textarea>
          </div>

          <!-- TOMBOL -->
          <div class="form-actions">
            <button type="submit" class="btn-submit" id="btnSubmit">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
              Simpan Booking
            </button>
            <button type="button" class="btn-reset" id="btnReset">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0115.93-4.36M20 15a9 9 0 01-15.93 4.36"/></svg>
              Reset
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
  <div class="booking-footer">Apriansyah Wibowo. All Rights Reserved.</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* Sidebar state */
  if (localStorage.getItem('sidebarCollapsed') === '1') {
    document.getElementById('bookingContent').classList.add('collapsed');
  }

  /* Flash close */
  var btnFlash = document.getElementById('btnCloseFlash');
  if (btnFlash) {
    btnFlash.addEventListener('click', function () {
      var el = document.getElementById('flashMsg');
      if (el) el.style.display = 'none';
    });
  }

  /* Show/hide sub-field helpers */
  function showSub(id) {
    document.getElementById(id).classList.add('visible');
  }
  function hideSub(id) {
    var el = document.getElementById(id);
    el.classList.remove('visible');
    el.querySelectorAll('input[type="radio"]').forEach(function (r) {
      r.checked = false;
      var pill = r.closest('.radio-pill');
      if (pill) pill.classList.remove('active');
    });
  }

  /* Paket Wisata */
  var paketSubs = ['sub_makan_tour', 'sub_jenis_makanan', 'sub_cooking', 'sub_gamelan'];
  var paketMap  = {
    cycling_tour:   'sub_makan_tour',
    dokar_tour:     'sub_makan_tour',
    walking_tour:   'sub_makan_tour',
    meal_only:      'sub_jenis_makanan',
    cooking_lesson: 'sub_cooking',
    gamelan_class:  'sub_gamelan'
  };

  document.getElementById('pilihan_paket_wisata').addEventListener('change', function () {
    paketSubs.forEach(hideSub);
    if (paketMap[this.value]) showSub(paketMap[this.value]);
  });

  /* Jenis Wisatawan */
  function updateJenis(jenis) {
    document.getElementById('pill_domestik').classList.toggle('active', jenis === 'Domestik');
    document.getElementById('pill_mancanegara').classList.toggle('active', jenis === 'Mancanegara');
    var kota   = document.getElementById('kota');
    var negara = document.getElementById('negara');
    if (jenis === 'Domestik') {
      hideSub('sub_negara');
      showSub('sub_kota');
      kota.required   = true;
      negara.required = false;
      negara.value    = '';
    } else {
      hideSub('sub_kota');
      showSub('sub_negara');
      negara.required = true;
      kota.required   = false;
      kota.value      = '';
    }
  }

  document.getElementById('jw_domestik').addEventListener('change', function () {
    if (this.checked) updateJenis('Domestik');
  });
  document.getElementById('jw_mancanegara').addEventListener('change', function () {
    if (this.checked) updateJenis('Mancanegara');
  });

  /* Radio pill highlight */
  document.addEventListener('change', function (e) {
    if (e.target.type !== 'radio') return;
    var grp = e.target.closest('.radio-group');
    if (!grp) return;
    grp.querySelectorAll('.radio-pill').forEach(function (p) { p.classList.remove('active'); });
    var pill = e.target.closest('.radio-pill');
    if (pill) pill.classList.add('active');
  });

  /* Reset */
  document.getElementById('btnReset').addEventListener('click', function () {
    document.getElementById('formTambah').reset();
    paketSubs.forEach(hideSub);
    hideSub('sub_kota');
    hideSub('sub_negara');
    document.getElementById('kota').required   = false;
    document.getElementById('negara').required = false;
    document.querySelectorAll('.radio-pill').forEach(function (p) { p.classList.remove('active'); });
  });

  /* Submit confirm */
  document.getElementById('btnSubmit').addEventListener('click', function (e) {
    e.preventDefault();
    var form = document.getElementById('formTambah');
    if (!form.reportValidity()) return;

    var nama = document.getElementById('agen_wisata').value || document.getElementById('nama').value || 'data ini';
    Swal.fire({
      title: 'Konfirmasi Simpan Booking',
      html: '<p style="font-size:14.5px;color:#555;">Simpan data booking baru untuk <strong>' + nama + '</strong>?</p>',
      icon: 'question',
      iconColor: '#2e7d4f',
      showCancelButton: true,
      confirmButtonText: 'Ya, Simpan Booking',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#1e3a2f',
      cancelButtonColor: '#9ab5a8',
      reverseButtons: true
    }).then(function (r) {
      if (r.isConfirmed) form.submit();
    });
  });

});
</script>
</body>
</html>