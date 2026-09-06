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

$is_ajax = !empty($_POST['is_ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

function redir($url, $msg, $detail = '') {
    global $is_ajax;
    if ($is_ajax) {
        $status = ($msg === 'gagal' || $msg === 'tidak_bisa_edit') ? 'error' : 'success';
        if ($status === 'error') http_response_code(400);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => $status, 'message' => $detail ?: $msg]);
        exit;
    }
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
$pax      = max(0, (int)($_POST['pax'] ?? 0));

// Agen, driver, dan local guide bersifat opsional, jika kosong default ke 'Belum Ada'
$agen     = san($_POST['agen_wisata'] ?? '', 100);
if ($agen === '') $agen = 'Belum Ada';

$driver   = san($_POST['driver_agent_guide'] ?? '', 100);
if ($driver === '') $driver = 'Belum Ada';

$lg       = san($_POST['local_guide'] ?? '', 100);
if ($lg === '') $lg = 'Belum Ada';

$catatan  = trim($_POST['catatan'] ?? '');

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
             nama, pax, agen_wisata, driver_agent_guide, local_guide, catatan, status, created_at)
        VALUES (
            '$tanggal', '$paket',
            " . sqlStr($opsi_makan) . ", " . sqlStr($jns_makanan) . ",
            " . sqlStr($opsi_cook)  . ", " . sqlStr($opsi_gml)    . ",
            '$jenis_w', " . sqlStr($kota) . ", " . sqlStr($negara) . ",
            '$nama', $pax, " . sqlStr($agen) . ", '$driver', '$lg',
            " . sqlStr($catatan) . ",
            'pending', NOW()
        )
    ";
    if (mysqli_query($db, $sql)) {
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => 'success',
                'message' => 'Data booking baru berhasil disimpan.',
                'id'      => mysqli_insert_id($db)
            ]);
            exit;
        }
        $ref = !empty($_POST['ref']) ? trim($_POST['ref']) : '';
        $safe_pages = ['booking_semua.php', 'booking_pending.php', 'booking_checkin.php', 'booking_tidakdatang.php', 'booking_dashboard.php'];
        $target_tambah = (in_array(basename($ref), $safe_pages)) ? '../' . basename($ref) : '../booking_dashboard.php';
        redir($target_tambah, 'sukses_tambah');
    } else {
        redir($back_tambah, 'gagal', 'DB error: ' . mysqli_error($db));
    }
}

/* ══════════════════════════════
   EDIT
══════════════════════════════ */
elseif ($aksi === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'ID booking tidak valid.']);
            exit;
        }
        redir('../booking_semua.php', 'gagal', 'ID tidak valid.');
    }

    $cek = mysqli_fetch_assoc(mysqli_query($db, "SELECT id, status FROM tb_booking WHERE id=$id LIMIT 1"));
    if (!$cek) {
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Data booking tidak ditemukan.']);
            exit;
        }
        redir('../booking_semua.php', 'gagal', 'Booking tidak ditemukan.');
    }

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
            local_guide          = '$lg',
            catatan              = " . sqlStr($catatan) . "
        WHERE id = $id
    ";
    if (mysqli_query($db, $sql)) {
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => 'success',
                'message' => 'Perubahan data booking berhasil diperbarui.',
                'id'      => $id
            ]);
            exit;
        }
        $ref = !empty($_POST['ref']) ? trim($_POST['ref']) : '';
        $safe_pages = ['booking_semua.php', 'booking_pending.php', 'booking_checkin.php', 'booking_tidakdatang.php', 'booking_dashboard.php'];
        $target_edit = (in_array(basename($ref), $safe_pages)) ? '../' . basename($ref) : '../booking_semua.php';
        redir($target_edit, 'sukses_edit');
    } else {
        if ($is_ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'DB error: ' . mysqli_error($db)]);
            exit;
        }
        redir("../edit_booking.php?id=$id", 'gagal', 'DB error: ' . mysqli_error($db));
    }
}

else {
    redir('../booking_dashboard.php', 'gagal', 'Aksi tidak dikenali.');
}