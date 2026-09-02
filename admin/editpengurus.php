<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

// Ambil ID dari URL dan validasi
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (empty($id)) {
    echo "<script>alert('ID tidak valid!'); window.location='datapengurus.php';</script>";
    exit;
}

// Ambil data pengurus DAN nama file fotonya menggunakan LEFT JOIN
$stmt = $db->prepare(
    "SELECT 
        p.*, 
        ktp.nama_file AS file_ktp,
        pas.nama_file AS file_pas_foto
     FROM 
        tb_data_pengurus p
     LEFT JOIN 
        tb_foto_pengurus ktp ON p.id = ktp.id_pengurus AND ktp.jenis_foto = 'KTP'
     LEFT JOIN 
        tb_foto_pengurus pas ON p.id = pas.id_pengurus AND pas.jenis_foto = 'Pas Foto'
     WHERE 
        p.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pengurus_data = $result->fetch_assoc();
$stmt->close();

if (!$pengurus_data) {
    echo "<script>alert('Data pengurus tidak ditemukan!'); window.location='datapengurus.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Data Pengurus - Desa Candirejo</title>
    
    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../assets/build/css/custom.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/icon.ico">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php include("sidebarmenu.php"); ?>
            <?php include("header.php"); ?>

            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Edit Data Pengurus</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />
                                    <form action="proses/proses_editpengurus.php" method="post" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                        
                                        <input type="hidden" name="id" value="<?php echo $pengurus_data['id']; ?>">

                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Nama <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="nama" required="required" class="form-control" value="<?php echo htmlspecialchars($pengurus_data['nama']); ?>"></div></div>
                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">No. KTP <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="no_ktp" required="required" class="form-control" value="<?php echo htmlspecialchars($pengurus_data['no_ktp']); ?>"></div></div>
                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="jabatan" required="required" class="form-control" value="<?php echo htmlspecialchars($pengurus_data['jabatan']); ?>"></div></div>
                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Periode <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="periode" required="required" class="form-control" value="<?php echo htmlspecialchars($pengurus_data['periode']); ?>"></div></div>
                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><textarea name="alamat" required="required" class="form-control" rows="3"><?php echo htmlspecialchars($pengurus_data['alamat']); ?></textarea></div></div>
                                        <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">No. Telepon <span class="required">*</span></label><div class="col-md-9 col-sm-9 col-xs-12"><input type="text" name="no_telp" required="required" class="form-control" value="<?php echo htmlspecialchars($pengurus_data['no_telp']); ?>"></div></div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto KTP</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <div id="area-preview-ktp" class="row">
                                                    <?php if(!empty($pengurus_data['file_ktp'])): ?>
                                                    <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="<?php echo htmlspecialchars($pengurus_data['file_ktp']); ?>" style="margin-bottom: 15px;">
                                                        <div style="position: relative;">
                                                            <img src="/admin/uploads/pengurus/<?php echo htmlspecialchars($pengurus_data['file_ktp']); ?>" style="width:100%; height: 100px; object-fit: cover; border-radius:5px; border: 1px solid #ddd;">
                                                            <button type="button" class="btn btn-danger btn-xs hapus-ktp" style="position:absolute; top:5px; right:5px;"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <input type="file" id="input-ktp" style="display: none;" accept="image/*">
                                                <button type="button" class="btn btn-default" id="tombol-pilih-ktp" style="<?php echo !empty($pengurus_data['file_ktp']) ? 'display:none;' : ''; ?>"><i class="fa fa-plus"></i> Tambah/Ganti Foto KTP</button>
                                                <div id="pesan-upload-ktp" style="margin-top:10px;"></div>
                                                <input type="hidden" name="daftar_ktp_terupload" id="daftar_ktp_terupload" value="<?php echo htmlspecialchars($pengurus_data['file_ktp'] ?? ''); ?>">
                                                <small class="text-muted">Upload 1 foto KTP (Maksimal 2MB).</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pas Foto</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <div id="area-preview-pasfoto" class="row">
                                                     <?php if(!empty($pengurus_data['file_pas_foto'])): ?>
                                                    <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="<?php echo htmlspecialchars($pengurus_data['file_pas_foto']); ?>" style="margin-bottom: 15px;">
                                                        <div style="position: relative;">
                                                            <img src="/admin/uploads/pengurus/<?php echo htmlspecialchars($pengurus_data['file_pas_foto']); ?>" style="width:100%; height: 100px; object-fit: cover; border-radius:5px; border: 1px solid #ddd;">
                                                            <button type="button" class="btn btn-danger btn-xs hapus-pasfoto" style="position:absolute; top:5px; right:5px;"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <input type="file" id="input-pasfoto" style="display: none;" accept="image/*">
                                                <button type="button" class="btn btn-default" id="tombol-pilih-pasfoto" style="<?php echo !empty($pengurus_data['file_pas_foto']) ? 'display:none;' : ''; ?>"><i class="fa fa-plus"></i> Tambah/Ganti Pas Foto</button>
                                                <div id="pesan-upload-pasfoto" style="margin-top:10px;"></div>
                                                <input type="hidden" name="daftar_pasfoto_terupload" id="daftar_pasfoto_terupload" value="<?php echo htmlspecialchars($pengurus_data['file_pas_foto'] ?? ''); ?>">
                                                <small class="text-muted">Upload 1 pas foto (Maksimal 2MB).</small>
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <a href="datapengurus.php" class="btn btn-primary">Batal</a>
                                                <button type="submit" name="update" class="btn btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <footer><div class="pull-right"></div><div class="clearfix"></div></footer>
        </div>
    </div>
    
    <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/vendors/nprogress/nprogress.js"></script>
    <script src="../assets/build/js/custom.min.js"></script>
    <script>
    $(document).ready(function() {
        // Logika Javascript ini sama persis dengan di halaman input
        function setupAjaxUpload(config) {
            $(config.buttonSelector).on('click', function() { $(config.inputSelector).click(); });
            $(config.inputSelector).on('change', function() {
                if (this.files.length > 0) {
                    let file = this.files[0];
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('type', config.uploadType);
                    $(config.messageSelector).html('<i class="fa fa-spinner fa-spin"></i> Mengunggah...');
                    fetch('proses/ajax_upload_pengurus.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sukses) {
                            let namaFile = data.namaFile;
                            let previewHtml = `<div class="col-md-3 col-sm-4 col-xs-6" data-namafile="${namaFile}" style="margin-bottom: 15px;"><div style="position: relative;"><img src="/admin/uploads/pengurus/${namaFile}" style="width:100%; height: 100px; object-fit: cover; border-radius:5px; border: 1px solid #ddd;"><button type="button" class="btn btn-danger btn-xs ${config.deleteClass}" style="position:absolute; top:5px; right:5px;"><i class="fa fa-times"></i></button></div></div>`;
                            $(config.previewAreaSelector).html(previewHtml);
                            $(config.hiddenListSelector).val(namaFile);
                            $(config.buttonSelector).hide();
                            $(config.messageSelector).html('<span style="color:green;">Upload berhasil!</span>');
                        } else {
                            $(config.messageSelector).html('<span style="color:red;">Error: ' + data.pesan + '</span>');
                        }
                    }).catch(error => { $(config.messageSelector).html('<span style="color:red;">Terjadi kesalahan jaringan.</span>'); });
                    $(this).val('');
                }
            });
            $(document).on('click', '.' + config.deleteClass, function() {
                $(this).closest('.col-md-3').remove();
                $(config.hiddenListSelector).val('');
                $(config.buttonSelector).show();
            });
        }
        setupAjaxUpload({ buttonSelector: '#tombol-pilih-ktp', inputSelector: '#input-ktp', previewAreaSelector: '#area-preview-ktp', messageSelector: '#pesan-upload-ktp', hiddenListSelector: '#daftar_ktp_terupload', deleteClass: 'hapus-ktp', uploadType: 'ktp' });
        setupAjaxUpload({ buttonSelector: '#tombol-pilih-pasfoto', inputSelector: '#input-pasfoto', previewAreaSelector: '#area-preview-pasfoto', messageSelector: '#pesan-upload-pasfoto', hiddenListSelector: '#daftar_pasfoto_terupload', deleteClass: 'hapus-pasfoto', uploadType: 'pasfoto' });
    });
    </script>
</body>
</html>