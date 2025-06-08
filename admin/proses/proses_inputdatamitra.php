<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $nama_pemilik = $_POST['nama_pemilik'];
    $nama_usaha = $_POST['nama_usaha'];
    $kategori_usaha = $_POST['kategori_usaha'];
    $alamat = $_POST['alamat'];
    $nomor_telp = $_POST['nomor_telp'];
    $legalitas = $_POST['legalitas_usaha'];

    // Initialize the variable for the file path
    $destination = null;
    $foto_paths = array();

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
            $bukti_legalitas = 'uploads/' . $nama_file;
        } else {
            echo "<script>alert('File bukti legalitas gagal diupload!'); window.location='../datamitra.php';</script>";
            exit;
        }
    }

    // Handle foto kegiatan upload
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

    // Prepare SQL query
    $foto_kegiatan = !empty($foto_paths) ? implode(',', $foto_paths) : null;
    
    $query = "INSERT INTO tb_data_mitra (nama_pemilik, nama_usaha, kategori_usaha, alamat, nomor_telp, legalitas_usaha" .
        (isset($bukti_legalitas) ? ", bukti_legalitas" : "") . 
        ($foto_kegiatan ? ", foto_kegiatan" : "") . 
        ") VALUES ('$nama_pemilik', '$nama_usaha', '$kategori_usaha', '$alamat', '$nomor_telp', '$legalitas'" . 
        (isset($bukti_legalitas) ? ", '$bukti_legalitas'" : "") . 
        ($foto_kegiatan ? ", '$foto_kegiatan'" : "") . 
        ")";

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan'); window.location='../datamitra.php';</script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location='../datamitra.php';</script>";
}