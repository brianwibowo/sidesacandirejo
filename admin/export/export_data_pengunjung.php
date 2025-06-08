<?php
require '../../dompdf/autoload.inc.php';  

use Dompdf\Dompdf;

include '../../koneksi/koneksi.php';

// Ambil data surat keluar dari database
$sql = "SELECT * FROM tb_data_pengunjung ORDER BY id ASC";
$query = mysqli_query($db, $sql);

// Start buffering output
ob_start();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Laporan Data Pengunjung</title>
  <style>
  body {
    font-family: Arial, sans-serif;
    font-size: 12px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  table,
  th,
  td {
    border: 1px solid black;
    padding: 5px;
  }

  th {
    background-color: #f2f2f2;
  }

  .title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
  }
  </style>
</head>

<body>
  <h2 class="title">Laporan Data Pengunjung</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Tanggal Kunjungan</th>
        <th>Nama</th>
        <th>Pilihan Paket Wisata</th>
        <th>Jenis Wisatawan</th>
        <th>Kota/Negara</th>
        <th>Pax (Jumlah Wisatawan)</th>
        <th>Agen Wisata</th>
        <th>Driver Agent Guide</th>
        <th>Local Guide</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; 
      while($data = mysqli_fetch_array($query)) { 
        // Suppress notices for potentially undefined array keys
        error_reporting(E_ALL & ~E_NOTICE);
        
        // Set default values for fields that might not exist
        $tanggal_kunjungan = isset($data['tanggal_kunjungan']) ? $data['tanggal_kunjungan'] : '';
        $jenis_wisatawan = isset($data['jenis_wisatawan']) ? $data['jenis_wisatawan'] : '';
        $kota = isset($data['kota']) ? $data['kota'] : '';
        $negara = isset($data['negara']) ? $data['negara'] : '';
        $pilihan_paket_wisata = isset($data['pilihan_paket_wisata']) ? $data['pilihan_paket_wisata'] : '';
        $opsi_makan_tour = isset($data['opsi_makan_tour']) ? $data['opsi_makan_tour'] : '';
        $jenis_makanan_paket = isset($data['jenis_makanan_paket']) ? $data['jenis_makanan_paket'] : '';
        $opsi_cooking_lesson = isset($data['opsi_cooking_lesson']) ? $data['opsi_cooking_lesson'] : '';
        $nama = isset($data['nama']) ? $data['nama'] : '';
        $pax = isset($data['pax']) ? $data['pax'] : '';
        $agen_wisata = isset($data['agen_wisata']) ? $data['agen_wisata'] : '';
        $driver_agent_guide = isset($data['driver_agent_guide']) ? $data['driver_agent_guide'] : '';
        $local_guide = isset($data['local_guide']) ? $data['local_guide'] : '';
 
        
        // Fields that might cause errors if they exist in the database but aren't used in the current page
        $jenis_kelamin = isset($data['jenis_kelamin']) ? $data['jenis_kelamin'] : '';
        $usia = isset($data['usia']) ? $data['usia'] : '';
        
        $lokasi = ($jenis_wisatawan == 'Domestik') ? $kota : $negara;
        
        // Format nama paket wisata untuk ditampilkan (sama seperti di datapengunjung.php)
        $paket_display = '';
        switch($pilihan_paket_wisata) {
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
        $paket_utama = $pilihan_paket_wisata;
        
        // Tambahkan detail ke dalam paket display jika ada
        if (in_array($paket_utama, ['cycling_tour', 'dokar_tour', 'walking_tour']) && !empty($opsi_makan_tour)) {
          $detail = ($opsi_makan_tour == 'with_lunch') ? 'With Lunch' : 'Without Lunch';
          $paket_display .= ' (' . $detail . ')';
        }
        elseif ($paket_utama == 'meal_only' && !empty($jenis_makanan_paket)) {
          $makanan_map = [
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch', 
            'dinner' => 'Dinner'
          ];
          $detail = $makanan_map[$jenis_makanan_paket] ?? $jenis_makanan_paket;
          $paket_display .= ' (' . $detail . ')';
        }
        elseif ($paket_utama == 'cooking_lesson' && !empty($opsi_cooking_lesson)) {
          $detail = ($opsi_cooking_lesson == 'lesson_with_tour') ? 'With Tour' : 'Lesson Only';
          $paket_display .= ' (' . $detail . ')';
        }
        ?>
      <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $tanggal_kunjungan; ?></td>
        <td><?php echo $nama; ?></td>
        <td><?php echo $paket_display; ?></td>
        <td><?php echo $jenis_wisatawan; ?></td>
        <td><?php echo $lokasi ?></td>
        <td><?php echo $pax; ?></td>
        <td><?php echo $agen_wisata; ?></td>
        <td><?php echo $driver_agent_guide; ?></td>
        <td><?php echo $local_guide; ?></td>

      </tr>
      <?php } ?>
    </tbody>
  </table>
</body>

</html>

<?php
$html = ob_get_clean();

// Initialize DomPDF and load the HTML content
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation (optional)
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF
$dompdf->stream("laporan_data_pengunjung.pdf", ["Attachment" => false]); // Set Attachment to true to force download