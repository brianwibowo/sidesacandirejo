<?php
	session_start();
	include '../../koneksi/koneksi.php';
	
	$nomor_surat        = mysqli_real_escape_string($db, $_POST['nomor_surat']);
	$tanggal_terima     = mysqli_real_escape_string($db, $_POST['tanggal_terima']);
	$tanggal_surat      = mysqli_real_escape_string($db, $_POST['tanggal_surat']);
	$pengirim           = mysqli_real_escape_string($db, $_POST['pengirim']);
	$perihal            = mysqli_real_escape_string($db, $_POST['perihal']);
	$kode               = mysqli_real_escape_string($db, $_POST['kode']);
	$keterangan         = mysqli_real_escape_string($db, $_POST['keterangan']);
	
	date_default_timezone_set('Asia/Jakarta'); 
	$tanggal_entry      = date("Y-m-d H:i:s");
	$thnNow             = date("Y");

	// Konversi tanggal ke format yang sesuai
	$tgl_terima         = date('Y-m-d', strtotime($tanggal_terima));
	$tgl_surat          = date('Y-m-d', strtotime($tanggal_surat));

	// Mengambil informasi file dari database berdasarkan nomor_surat
	$sql                = "SELECT * FROM arsip_surat_masuk WHERE nomor_surat='$nomor_surat'";
	$query              = mysqli_query($db, $sql);
	$data               = mysqli_fetch_array($query);

	// Jika tidak ada file yang diupload
	if ($_FILES['file_surat']['name'] == '') {
		$ext                = substr($data['file_surat'], strripos($data['file_surat'], '.'));	
		$nama_baru          = $thnNow . '-' . $nomor_surat . $ext;
		rename("../surat_masuk/" . $data['file_surat'], "../surat_masuk/" . $nama_baru);
		
		$sql = "UPDATE arsip_surat_masuk SET 
					tanggal_terima = '$tgl_terima',
					tanggal_surat = '$tgl_surat',
					pengirim = '$pengirim',
					perihal = '$perihal',
					kode = '$kode',
					keterangan = '$keterangan',
					file_surat = '$nama_baru' 
				WHERE nomor_surat = '$nomor_surat'";
				
		$execute = mysqli_query($db, $sql);			

		echo "<Center><h2><br>Data surat masuk telah diubah</h2></center>
			<meta http-equiv='refresh' content='2;url=../detail-suratmasuk.php?nomor_surat=".$nomor_surat."'>";
	} 
	else {
		$tipe_file          = $_FILES['file_surat']['type'];
		$ukuran_file        = $_FILES['file_surat']['size'];
		$ext_file           = substr($_FILES['file_surat']['name'], strripos($_FILES['file_surat']['name'], '.'));			
		$tmp_file           = $_FILES['file_surat']['tmp_name'];
		
		$nama_baru          = $thnNow . '-' . $nomor_surat . $ext_file;
		$path               = "../surat_masuk/" . $nama_baru;

		// Validasi file yang diupload
		if ($tipe_file == "application/pdf" && $ukuran_file <= 10340000) {
			unlink("../surat_masuk/" . $data['file_surat']);
			move_uploaded_file($tmp_file, $path);
			
			$sql = "UPDATE arsip_surat_masuk SET 
						tanggal_terima = '$tgl_terima',
						tanggal_surat = '$tgl_surat',
						pengirim = '$pengirim',
						perihal = '$perihal',
						kode = '$kode',
						keterangan = '$keterangan',
						file_surat = '$nama_baru' 
					WHERE nomor_surat = '$nomor_surat'";
					
			$execute = mysqli_query($db, $sql);			

			echo "<Center><h2><br>Data surat masuk telah diubah</h2></center>
				<meta http-equiv='refresh' content='2;url=../detail-suratmasuk.php?nomor_surat=".$nomor_surat."'>";			
		} else {
			echo "<Center><h2><br>File yang Anda masukkan tidak sesuai ketentuan<br>Silahkan ulangi</h2></center>
				<meta http-equiv='refresh' content='2;url=../editsuratmasuk.php?nomor_surat=".$nomor_surat."'>";
		}
	}
?>
