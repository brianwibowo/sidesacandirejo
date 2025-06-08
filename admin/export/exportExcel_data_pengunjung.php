<?php
session_start();
include '../../koneksi/koneksi.php'; // Pastikan koneksi ke database benar

// Ambil data dari database
$sql = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Format timestamp untuk nama file
$timestamp = date("Ymd_His"); // Format: YYYYMMDD_HHMMSS
$filename = "Data_Pengunjung_$timestamp.xls"; // Nama file dengan timestamp

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel untuk data Excel
echo "
<center>
        <h1>Data Pengunjung</h1>
    </center>
<table border='1'>
<tr>
    <th>No</th>
    <th>Tanggal Kunjungan</th>
    <th>Pilihan Paket Wisata</th>
    <th>Jenis Wisatawan</th>
    <th>Kota/Negara</th>
    <th>Nama</th>
    <th>Pax (Jumlah Wisatawan)</th>
    <th>Agen Wisata</th>
    <th>Driver Agent Guide</th>
    <th>Local Guide</th>
</tr>";

while ($data = mysqli_fetch_array($query)) {
    $lokasi = ($data['jenis_wisatawan'] == 'Domestik') ? $data['kota'] : $data['negara'];
    
    // Format nama paket wisata untuk ditampilkan (sama seperti di datapengunjung.php)
    $paket_display = '';
    switch($data['pilihan_paket_wisata']) {
        case 'meal_only':
            $paket_display = 'Breakfast/Lunch/Dinner Only';
            break;
        case 'studi_banding':
            $paket_display = 'Studi Banding';
            break;
        case 'fun_game':
            $paket_display = 'Paket Fun Game';
            break;
        case 'pelajar_live_in':
            $paket_display = 'Paket Pelajar - Live In Candirejo';
            break;
        case 'pelajar_field_trip_one_day':
            $paket_display = 'Paket Pelajar – Field Trip One Day';
            break;
        case 'pelajar_field_trip_half_day':
            $paket_display = 'Paket Pelajar – Field Trip Half Day';
            break;
        case 'cycling_tour':
            $paket_display = 'Cycling Village Tour Candirejo';
            break;
        case 'traditional_dance':
            $paket_display = 'Traditional Dance';
            break;
        case 'walking_tour':
            $paket_display = 'Walking Around Village';
            break;
        case 'homestay':
            $paket_display = 'Stay At Local House In Candirejo Village (Homestay)';
            break;
        case 'serenade':
            $paket_display = 'Serenade At The Foot Of Menoreh Hill';
            break;
        case 'cooking_lesson':
            $paket_display = 'Cooking Lesson';
            break;
        case 'village_experience':
            $paket_display = 'Village Experience';
            break;
        case 'dokar_tour':
            $paket_display = 'Dokar Village Tour Candirejo';
            break;
        default:
            $paket_display = htmlspecialchars($data['pilihan_paket_wisata']);
    }
    
    // Format detail paket berdasarkan opsi tambahan
    $detail_paket = '';
    $paket_utama = $data['pilihan_paket_wisata'];
    
    // Tambahkan detail ke dalam paket display jika ada
    if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($data['opsi_makan_tour'])) {
        $detail = ($data['opsi_makan_tour'] == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
        $paket_display .= ' (' . $detail . ')';
    }
    elseif ($paket_utama == 'meal_only' && !empty($data['jenis_makanan_paket'])) {
        $makanan_map = [
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch', 
            'dinner' => 'Dinner'
        ];
        $detail = $makanan_map[$data['jenis_makanan_paket']] ?? $data['jenis_makanan_paket'];
        $paket_display .= ' (' . $detail . ')';
    }
    elseif ($paket_utama == 'cooking_lesson' && !empty($data['opsi_cooking_lesson'])) {
        $detail = ($data['opsi_cooking_lesson'] == 'lesson_with_tour') ? 'With Tour' : 'Lesson Only';
        $paket_display .= ' (' . $detail . ')';
    }
    
    echo "<tr>
        <td>" . htmlspecialchars($data['id']) . "</td>
        <td>" . htmlspecialchars($data['tanggal_kunjungan']) . "</td>
        <td>" . $paket_display . "</td>
        <td>" . htmlspecialchars($data['jenis_wisatawan']) . "</td>
        <td>" . htmlspecialchars($lokasi) . "</td>
        <td>" . htmlspecialchars($data['nama']) . "</td>
        <td>" . htmlspecialchars($data['pax']) . "</td>
        <td>" . htmlspecialchars($data['agen_wisata']) . "</td>
        <td>" . htmlspecialchars($data['driver_agent_guide']) . "</td>
        <td>" . htmlspecialchars($data['local_guide']) . "</td>
    </tr>";
}

echo "</table>";
?>