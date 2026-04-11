<?php
/**
 * proses/proses_booking.php
 * Handler INSERT (tambah) dan UPDATE (edit) tabel tb_booking
 */

session_start();
include "../../koneksi/koneksi.php";
include "../login/ceksession.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../booking_dashboard.php");
    exit;
}

function redir($url, $msg, $detail = '') {
    $q = '?msg=' . urlencode($msg);
    if ($detail) $q .= '&detail=' . urlencode($detail);
    header("Location: $url$q");
    exit;
}

function san($val, $max = 255) {
    global $db;
    return mysqli_real_escape_string($db, substr(trim($val ?? ''), 0, $max));
}

function inEnum($val, array $allowed) {
    return in_array($val, $allowed, true) ? $val : null;
}

function sqlStr($val) {
    if ($val === null || $val === '') return 'NULL';
    global $db;
    return "'" . mysqli_real_escape_string($db, $val) . "'";
}

/* ── Ambil & bersihkan input ────────────────────────────── */
$aksi     = trim($_POST['aksi'] ?? '');
$tanggal  = san($_POST['tanggal_kunjungan'] ?? '');
$paket    = san($_POST['pilihan_paket_wisata'] ?? '');
$nama     = san($_POST['nama'] ?? '', 100);
$jenis_w  = inEnum($_POST['jenis_wisatawan'] ?? '', ['Domestik','Mancanegara']);
$kota     = san($_POST['kota'] ?? '', 100);
$negara   = san($_POST['negara'] ?? '', 100);
$pax      = max(1, (int)($_POST['pax'] ?? 1));
$agen     = san($_POST['agen_wisata'] ?? '', 100);
$driver   = san($_POST['driver_agent_guide'] ?? 'Belum Ada', 100);
$lg       = san($_POST['local_guide'] ?? 'Belum Ada', 100);

$opsi_makan  = inEnum($_POST['opsi_makan_tour'] ?? '', ['without_lunch','with_lunch']);
$jns_makanan = inEnum($_POST['jenis_makanan_paket'] ?? '', ['breakfast','lunch','dinner']);
$opsi_cook   = inEnum($_POST['opsi_cooking_lesson'] ?? '', ['lesson_only','lesson_with_tour']);
$opsi_gml    = san($_POST['opsi_gamelan'] ?? '', 50);

// Nullkan opsi yang tidak relevan
if (!in_array($paket, ['cycling_tour','dokar_tour','walking_tour'])) $opsi_makan  = null;
if ($paket !== 'meal_only')      $jns_makanan = null;
if ($paket !== 'cooking_lesson') $opsi_cook   = null;
if ($paket !== 'gamelan_class')  $opsi_gml    = null;

// Nullkan kota/negara sesuai jenis wisatawan
if ($jenis_w === 'Domestik')    $negara = '';
if ($jenis_w === 'Mancanegara') $kota   = '';

/* ── Validasi dasar ─────────────────────────────────────── */
$back_tambah = '../tambah_booking.php';
if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal))
    redir($back_tambah, 'gagal', 'Tanggal kunjungan tidak valid.');
if (!$paket)   redir($back_tambah, 'gagal', 'Paket wisata harus dipilih.');
if (!$nama)    redir($back_tambah, 'gagal', 'Nama pengunjung tidak boleh kosong.');
if (!$jenis_w) redir($back_tambah, 'gagal', 'Jenis wisatawan harus dipilih.');

/* ══════════════════════════════
   TAMBAH
══════════════════════════════ */
if ($aksi === 'tambah') {
    $sql = "
        INSERT INTO tb_booking
            (tanggal_kunjungan, pilihan_paket_wisata, opsi_makan_tour, jenis_makanan_paket,
             opsi_cooking_lesson, opsi_gamelan, jenis_wisatawan, kota, negara,
             nama, pax, agen_wisata, driver_agent_guide, local_guide, status, created_at)
        VALUES (
            '$tanggal', '$paket',
            " . sqlStr($opsi_makan) . ", " . sqlStr($jns_makanan) . ",
            " . sqlStr($opsi_cook)  . ", " . sqlStr($opsi_gml)    . ",
            '$jenis_w', " . sqlStr($kota) . ", " . sqlStr($negara) . ",
            '$nama', $pax, " . sqlStr($agen) . ", '$driver', '$lg',
            'pending', NOW()
        )
    ";
    if (mysqli_query($db, $sql)) {
        redir('../booking_dashboard.php', 'sukses_tambah');
    } else {
        redir($back_tambah, 'gagal', 'DB error: ' . mysqli_error($db));
    }
}

/* ══════════════════════════════
   EDIT
══════════════════════════════ */
elseif ($aksi === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) redir('../booking_dashboard.php', 'gagal', 'ID tidak valid.');

    $cek = mysqli_fetch_assoc(mysqli_query($db, "SELECT id, status FROM tb_booking WHERE id=$id LIMIT 1"));
    if (!$cek) redir('../booking_dashboard.php', 'gagal', 'Booking tidak ditemukan.');
    if ($cek['status'] !== 'pending') redir('../booking_dashboard.php', 'tidak_bisa_edit');

    $sql = "
        UPDATE tb_booking SET
            tanggal_kunjungan    = '$tanggal',
            pilihan_paket_wisata = '$paket',
            opsi_makan_tour      = " . sqlStr($opsi_makan) . ",
            jenis_makanan_paket  = " . sqlStr($jns_makanan) . ",
            opsi_cooking_lesson  = " . sqlStr($opsi_cook) . ",
            opsi_gamelan         = " . sqlStr($opsi_gml) . ",
            jenis_wisatawan      = '$jenis_w',
            kota                 = " . sqlStr($kota) . ",
            negara               = " . sqlStr($negara) . ",
            nama                 = '$nama',
            pax                  = $pax,
            agen_wisata          = " . sqlStr($agen) . ",
            driver_agent_guide   = '$driver',
            local_guide          = '$lg'
        WHERE id = $id AND status = 'pending'
    ";
    if (mysqli_query($db, $sql)) {
        redir('../booking_dashboard.php', 'sukses_edit');
    } else {
        redir("../edit_booking.php?id=$id", 'gagal', 'DB error: ' . mysqli_error($db));
    }
}

else {
    redir('../booking_dashboard.php', 'gagal', 'Aksi tidak dikenali.');
}