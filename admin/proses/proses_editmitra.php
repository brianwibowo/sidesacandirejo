<?php 
include '../../koneksi/koneksi.php';

$id = $_POST['id'];
$nama_pemilik = $_POST['nama_pemilik'];
$nama_usaha = $_POST['nama_usaha'];
$kategori_usaha = $_POST['kategori_usaha'];
$alamat = $_POST['alamat'];
$nomor_telp = $_POST['nomor_telp'];
$legalitas_usaha = $_POST['legalitas_usaha'];

// Get existing data
$query_get_data = "SELECT bukti_legalitas, foto_kegiatan FROM tb_data_mitra WHERE id ='$id'";
$result = mysqli_query($db, $query_get_data);
$row = mysqli_fetch_assoc($result);
$file_lama = $row['bukti_legalitas'];
$foto_lama = $row['foto_kegiatan'];

// Handle bukti legalitas upload
if (isset($_FILES['bukti_legalitas']) && $_FILES['bukti_legalitas']['error'] == UPLOAD_ERR_OK) {
  $jam = date('H-i-s');
  $nama_file = strtolower(str_replace(' ', '_', $nama_usaha)) . "_{$jam}.pdf";
    $target_dir = '../uploads/';
    
    // Buat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $destination = $target_dir . $nama_file;

    if (move_uploaded_file($_FILES['bukti_legalitas']['tmp_name'], $destination)) {
        if(!empty($file_lama)) {
            $old_file = str_replace('../', '', $file_lama);
            if(file_exists("../" . $old_file)) {
                unlink("../" . $old_file);
            }
        }
        $bukti_legalitas_update = ", bukti_legalitas='uploads/" . $nama_file . "'";
  } else {
        echo "<script>alert('File bukti legalitas gagal diupload!'); window.location='../datamitra.php';</script>";
    exit;
  }
} else {
    $bukti_legalitas_update = "";
}

// Handle foto kegiatan upload
$foto_paths = array();
if (isset($_FILES['foto_kegiatan'])) {
    $target_dir = '../uploads/foto_kegiatan/';
    
    // Buat direktori jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    foreach ($_FILES['foto_kegiatan']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['foto_kegiatan']['error'][$key] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['foto_kegiatan']['name'][$key];
            $file_size = $_FILES['foto_kegiatan']['size'][$key];
            $file_tmp = $_FILES['foto_kegiatan']['tmp_name'][$key];
            $file_type = $_FILES['foto_kegiatan']['type'][$key];

            // Validasi tipe file
            $allowed_types = array('image/jpeg', 'image/png', 'image/jpg');
            if (!in_array($file_type, $allowed_types)) {
                echo "<script>alert('Tipe file tidak didukung! Hanya JPG, JPEG, dan PNG yang diperbolehkan.'); window.location='../datamitra.php';</script>";
                exit;
            }

            // Validasi ukuran file (2MB)
            if ($file_size > 2097152) {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB per foto.'); window.location='../datamitra.php';</script>";
                exit;
            }

            $jam = date('H-i-s');
            $new_file_name = strtolower(str_replace(' ', '_', $nama_usaha)) . "_kegiatan_{$key}_{$jam}.jpg";
            $file_path = $target_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $foto_paths[] = 'uploads/foto_kegiatan/' . $new_file_name;
            }
        }
    }
}

// Update foto kegiatan
if (!empty($foto_paths)) {
    // Hapus foto lama jika ada
    if (!empty($foto_lama)) {
        $old_fotos = explode(',', $foto_lama);
        foreach ($old_fotos as $old_foto) {
            $old_foto = str_replace('../', '', $old_foto);
            if (file_exists("../" . $old_foto)) {
                unlink("../" . $old_foto);
            }
        }
    }
    $foto_kegiatan_update = ", foto_kegiatan='" . implode(',', $foto_paths) . "'";
} else {
    $foto_kegiatan_update = "";
}

// Update database
$query = "UPDATE tb_data_mitra SET 
    nama_pemilik='$nama_pemilik', 
    nama_usaha='$nama_usaha', 
    kategori_usaha='$kategori_usaha',
    alamat='$alamat',
    nomor_telp='$nomor_telp',
    legalitas_usaha='$legalitas_usaha'
    $bukti_legalitas_update
    $foto_kegiatan_update 
    WHERE id = '$id'";

if (mysqli_query($db, $query)) {
  echo "<script>alert('Data berhasil diedit'); window.location='../datamitra.php';</script>";
} else {
  echo "Error: " . mysqli_error($db);
}