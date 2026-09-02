<!DOCTYPE html>
<?php
session_start();
include "login/ceksession.php";
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Input Data Mitra - Desa Candirejo</title>

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php
      include("sidebarmenu.php");
      ?>
      <?php
      include("header.php");
      ?>
      <div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Input Data Mitra</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_inputdatamitra.php" name="forminputdatamitra" method="post"
                    id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_pemilik">Nama Pemilik<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama_pemilik" name="nama_pemilik" required="required" maxlength="100"
                          placeholder="Masukkan Nama Pemilik" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_usaha">Nama Usaha<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nama_usaha" name="nama_usaha" required="required" maxlength="100"
                          placeholder="Masukkan Nama Usaha" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori_usaha">Kategori Usaha<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="kategori_usaha" name="kategori_usaha" required="required" class="form-control col-md-7 col-xs-12">
                          <option value="">Pilih Kategori Usaha</option>
                          <option value="UMKM">UMKM</option>
                          <option value="Local Guide">Local Guide</option>
                          <option value="Catering">Catering</option>
                          <option value="Dokar">Dokar</option>
                          <option value="Homestay">Homestay</option>
                          <option value="Kerajinan">Kerajinan</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="alamat" name="alamat" required="required" class="form-control" rows="3" placeholder="Masukkan Alamat Lengkap"></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_telp">Nomor Telepon<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="nomor_telp" name="nomor_telp" required="required" maxlength="20"
                          placeholder="Masukkan Nomor Telepon" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="legalitas_usaha">Legalitas Usaha<span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" id="legalitas_usaha" name="legalitas_usaha" required="required" maxlength="100"
                          placeholder="Masukkan Legalitas Usaha" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">File Bukti Legalitas</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div id="area-preview-legalitas" class="row"></div>
                            <input type="file" id="input-legalitas" style="display: none;" accept="application/pdf,image/*">
                            <button type="button" class="btn btn-default" id="tombol-pilih-legalitas"><i class="fa fa-plus"></i> Tambah File</button>
                            <div id="pesan-upload-legalitas" style="margin-top:10px;"></div>
                            <input type="hidden" name="daftar_legalitas_terupload" id="daftar_legalitas_terupload">
                            <small class="text-muted">Upload satu per satu (bisa PDF/Gambar, Maksimal 10MB per file).</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto Kegiatan Usaha</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div id="area-preview-kegiatan" class="row"></div>
                            <input type="file" id="input-kegiatan" style="display: none;" accept="image/*" >
                            <button type="button" class="btn btn-default" id="tombol-pilih-kegiatan"><i class="fa fa-plus"></i> Tambah Foto</button>
                            <div id="pesan-upload-kegiatan" style="margin-top:10px;"></div>
                            <input type="hidden" name="daftar_kegiatan_terupload" id="daftar_kegiatan_terupload">
                            <small class="text-muted">Upload satu per satu (Maksimal 2MB per foto).</small>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-primary">Reset</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer>
        <div class="pull-right">
          Arsip Surat Desa Candirejo Borobudur
        </div>
        <div class="clearfix"></div>
      </footer>
      </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  $(document).ready(function() {
    
    // --- FUNGSI GENERIC UNTUK UPLOAD ---
    function setupAjaxUpload(config) {
        $(config.buttonSelector).on('click', function() {
            $(config.inputSelector).click();
        });

        $(config.inputSelector).on('change', function() {
            if (this.files.length > 0) {
                let file = this.files[0];
                let formData = new FormData();
                formData.append('file', file);
                formData.append('type', config.uploadType); // 'legalitas' atau 'kegiatan'

                $(config.messageSelector).html('<i class="fa fa-spinner fa-spin"></i> Mengunggah...');

                fetch('proses/ajax_upload_mitra.php', { // Ganti dengan nama file prosesor AJAX Anda
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sukses) {
                        let namaFile = data.namaFile;
                        let isImage = /\.(jpe?g|png|gif)$/i.test(namaFile);
                        let previewHtml;

                        if (isImage) {
                             previewHtml = `
                                <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="${namaFile}" style="margin-bottom: 15px;">
                                    <div style="position: relative;">
                                        <img src="/admin/uploads/mitra/${namaFile}" style="width:100%; height: 100px; object-fit: cover; border-radius:5px; border: 1px solid #ddd;">
                                        <button type="button" class="btn btn-danger btn-xs ${config.deleteClass}" style="position:absolute; top:5px; right:5px;"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>`;
                        } else {
                             previewHtml = `
                                <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="${namaFile}" style="margin-bottom: 15px;">
                                    <div style="position: relative; border: 1px solid #ddd; border-radius: 5px; padding: 10px; height: 100px; display: flex; align-items: center; justify-content: center; background: #f8f8f8;">
                                        <i class="fa fa-file-pdf-o fa-2x" style="color: #d9534f;"></i>
                                        <p style="margin-left: 10px; word-break: break-all;">${namaFile}</p>
                                        <button type="button" class="btn btn-danger btn-xs ${config.deleteClass}" style="position:absolute; top:5px; right:5px;"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>`;
                        }
                        
                        $(config.previewAreaSelector).append(previewHtml);

                        let daftarFile = $(config.hiddenListSelector).val();
                        let arrayFile = daftarFile ? daftarFile.split(',').filter(n => n) : [];
                        arrayFile.push(namaFile);
                        $(config.hiddenListSelector).val(arrayFile.join(','));
                        
                        $(config.messageSelector).html('<span style="color:green;">Upload berhasil!</span>');
                    } else {
                        $(config.messageSelector).html('<span style="color:red;">Error: ' + data.pesan + '</span>');
                    }
                })
                .catch(error => {
                    $(config.messageSelector).html('<span style="color:red;">Terjadi kesalahan jaringan.</span>');
                    console.error('Error:', error);
                });
                
                $(this).val('');
            }
        });

        $(document).on('click', '.' + config.deleteClass, function() {
            let itemDihapus = $(this).closest('.col-md-3');
            let namaFileDihapus = itemDihapus.data('namafile');

            let daftarFile = $(config.hiddenListSelector).val();
            let arrayFile = daftarFile.split(',');
            let arrayBaru = arrayFile.filter(nama => nama !== namaFileDihapus);
            $(config.hiddenListSelector).val(arrayBaru.join(','));

            itemDihapus.remove();
        });
    }

    // --- Inisialisasi untuk BUKTI LEGALITAS ---
    setupAjaxUpload({
        buttonSelector: '#tombol-pilih-legalitas',
        inputSelector: '#input-legalitas',
        previewAreaSelector: '#area-preview-legalitas',
        messageSelector: '#pesan-upload-legalitas',
        hiddenListSelector: '#daftar_legalitas_terupload',
        deleteClass: 'hapus-legalitas',
        uploadType: 'legalitas' // Tipe untuk backend
    });

    // --- Inisialisasi untuk FOTO KEGIATAN ---
    setupAjaxUpload({
        buttonSelector: '#tombol-pilih-kegiatan',
        inputSelector: '#input-kegiatan',
        previewAreaSelector: '#area-preview-kegiatan',
        messageSelector: '#pesan-upload-kegiatan',
        hiddenListSelector: '#daftar_kegiatan_terupload',
        deleteClass: 'hapus-kegiatan',
        uploadType: 'kegiatan' // Tipe untuk backend
    });

  });
  </script>
  </body>

</html>