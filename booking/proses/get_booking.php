<?php
/**
 * proses/get_booking.php
 * Endpoint API internal untuk mengambil data booking berdasarkan ID (JSON)
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

include "../../koneksi/koneksi.php";
include "../login/ceksession.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID booking tidak valid.']);
    exit;
}

$stmt = $db->prepare("SELECT * FROM tb_booking WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Data booking tidak ditemukan.']);
    exit;
}

// Format default 'Belum Ada' jika kosong
$data['agen_wisata']        = !empty(trim($data['agen_wisata'] ?? '')) ? trim($data['agen_wisata']) : 'Belum Ada';
$data['driver_agent_guide'] = !empty(trim($data['driver_agent_guide'] ?? '')) ? trim($data['driver_agent_guide']) : 'Belum Ada';
$data['local_guide']        = !empty(trim($data['local_guide'] ?? '')) ? trim($data['local_guide']) : 'Belum Ada';
$data['catatan']            = $data['catatan'] ?? '';

echo json_encode([
    'status' => 'success',
    'data'   => $data
]);
exit;
