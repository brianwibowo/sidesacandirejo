<?php
session_start();
include '../../koneksi/koneksi.php';
$id				= $_SESSION['id'];
$nama	 		= mysqli_real_escape_string($db, $_POST['nama_admin']);
$username		= mysqli_real_escape_string($db, $_POST['username_admin']);
$gambar			= $_FILES['gambar']['name'];

$sql  		= "SELECT * FROM tb_admin where id_admin='" . $_SESSION['id'] . "'";
$query  	= mysqli_query($db, $sql);
$data 		= mysqli_fetch_array($query);

if ($gambar == '') {
	$ext			= substr($data['gambar'], strripos($data['gambar'], '.'));
	$nama_b  		= $username . $ext;
	rename("../images/" . $data['gambar'], "../images/" . $nama_b);
	$sql = "UPDATE tb_admin set 
						nama_admin 			= '$nama',
						username_admin		= '$username',
						gambar				= '$nama_b' 
				where id_admin = $id";

	$execute = mysqli_query($db, $sql);

	$_SESSION['nama'] = $nama;
	$_SESSION['username'] = $username;

	echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data anda telah terubah'
        }).then(() => {
            window.location = '../profile.php';
        });
        </script>
        </body>
        </html>
        ";
} else {

	$tipe_file 		= $_FILES['gambar']['type'];
	$ukuran_file 	= $_FILES['gambar']['size'];
	if (($tipe_file == "image/jpeg" || $tipe_file == "image/jpg" || $tipe_file == "image/png") and ($ukuran_file <= 2100000)) {
		unlink("../images/" . $data['gambar']);
		$ext_file		= substr($gambar, strripos($gambar, '.'));
		$tmp_file 		= $_FILES['gambar']['tmp_name'];

		$nama_baru = time() . '_' . $username . $ext_file;
		$path = "../images/" . $nama_baru;
		move_uploaded_file($tmp_file, $path);

		$sql = "UPDATE tb_admin set 
						nama_admin 			= '$nama',
						username_admin		= '$username',
						gambar				= '$nama_baru' 
				where id_admin = $id";

		$execute = mysqli_query($db, $sql);

		$_SESSION['nama'] = $nama;
		$_SESSION['username'] = $username;

		echo "
		<!DOCTYPE html>
		<html>
		<head>
			<title>Berhasil</title>
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		</head>
		<body>

		<script>
		Swal.fire({
			icon: 'success',
			title: 'Berhasil!',
			text: 'Data anda telah terubah',
			showConfirmButton: false,
			timer: 2000
		}).then(() => {
			window.location.href = '../profile.php';
		});
		</script>

		</body>
		</html>
		";
	} else {
		echo "
		<!DOCTYPE html>
		<html>
		<head>
			<title>Gagal</title>
			<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
		</head>
		<body>

		<script>
		Swal.fire({
			icon: 'error',
			title: 'Gagal!',
			text: 'Gambar yang anda masukkan tidak sesuai ketentuan',
			showConfirmButton: false,
			timer: 2000
		}).then(() => {
			window.location.href = '../editprofile.php';
		});
		</script>

		</body>
		</html>
		";
	}
}
