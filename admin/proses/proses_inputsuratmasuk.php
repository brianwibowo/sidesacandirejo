<?php
	session_start();
	include '../../koneksi/koneksi.php';

	// Mengambil data dari form
	$nomor_surat	                = mysqli_real_escape_string($db, $_POST['nomor_surat']);
	$tanggal_terima	                = mysqli_real_escape_string($db, $_POST['tanggal_terima']);
	$tanggal_surat	                = mysqli_real_escape_string($db, $_POST['tanggal_surat']);
	$pengirim	                    = mysqli_real_escape_string($db, $_POST['pengirim']);
	$perihal	                    = mysqli_real_escape_string($db, $_POST['perihal']);
	$kode	                        = mysqli_real_escape_string($db, $_POST['kode']);
	$keterangan	                    = mysqli_real_escape_string($db, $_POST['keterangan']);

	date_default_timezone_set('Asia/Jakarta'); 
	$tanggal_entry                  = date("Y-m-d H:i:s");
	$thnNow                         = date("Y");

	// Mengelola file yang diupload
	$nama_file_lengkap 		= $_FILES['file_surat']['name'];
	$ext_file		        = substr($nama_file_lengkap, strripos($nama_file_lengkap, '.'));
	$tipe_file 		        = $_FILES['file_surat']['type'];
	$ukuran_file 	        = $_FILES['file_surat']['size'];
	$tmp_file 		        = $_FILES['file_surat']['tmp_name'];

	// Validasi dan konversi tanggal
	$tgl_terima            = date('Y-m-d', strtotime($tanggal_terima));
	$tgl_surat             = date('Y-m-d', strtotime($tanggal_surat));

	// Validasi data sebelum dimasukkan ke database
	if (!empty($nomor_surat) && !empty($tgl_terima) && !empty($tgl_surat) && !empty($pengirim) && !empty($perihal) && !empty($kode) && !empty($nama_file_lengkap) && 
		$tipe_file == "application/pdf" && $ukuran_file <= 10340000) {
		
		// Penamaan file yang baru dengan format tahun_nomor_surat.pdf
		$nama_baru = $thnNow . '-' . $nomor_surat . $ext_file;
		$path = "../surat_masuk/" . $nama_baru;

		// Proses upload file
		move_uploaded_file($tmp_file, $path);

		// Insert data ke database
		$sql = "INSERT INTO arsip_surat_masuk (nomor_surat, tanggal_terima, tanggal_surat, pengirim, perihal, kode, keterangan, file_surat)
				VALUES ('$nomor_surat', '$tgl_terima', '$tgl_surat', '$pengirim', '$perihal', '$kode', '$keterangan', '$nama_baru')";
		$execute = mysqli_query($db, $sql);

		if ($execute) {
			echo "<Center><h2><br>Terima Kasih<br>Surat masuk Telah Dimasukkan</h2></center>
				<meta http-equiv='refresh' content='2;url=../datasuratmasuk.php'>";
		} else {
			echo "<Center><h2>Terjadi kesalahan saat memasukkan data</h2></center>
				<meta http-equiv='refresh' content='2;url=../inputsuratmasuk.php'>";
		}
	} else {
		echo "<Center><h2>Silahkan isi semua kolom dengan benar dan upload file PDF dengan ukuran maksimal 10MB<br>Terima Kasih</h2></center>
			<meta http-equiv='refresh' content='2;url=../inputsuratmasuk.php'>";
	}
?>
