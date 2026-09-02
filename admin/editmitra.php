<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location='datamitra.php';</script>";
    exit;
}

$id_esc = mysqli_real_escape_string($db, $id);
$query  = mysqli_query($db, "SELECT * FROM tb_data_mitra WHERE id = '$id_esc'");
$data   = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='datamitra.php';</script>";
    exit;
}

// Ambil file legalitas dari tabel relasi
$existing_legalitas = [];
$res_l = mysqli_query($db, "SELECT nama_file FROM tb_foto_legalitas WHERE id_mitra = '$id_esc' ORDER BY id_file ASC");
while ($row = mysqli_fetch_assoc($res_l)) {
    $existing_legalitas[] = $row['nama_file'];
}

// Ambil foto kegiatan dari tabel relasi
$existing_kegiatan = [];
$res_k = mysqli_query($db, "SELECT nama_file FROM tb_foto_kegiatan WHERE id_mitra = '$id_esc' ORDER BY id_foto ASC");
while ($row = mysqli_fetch_assoc($res_k)) {
    $existing_kegiatan[] = $row['nama_file'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Mitra - Desa Candirejo</title>
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
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left"><h3>Data Mitra</h3></div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Data Mitra <small>Edit Data Mitra</small></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="proses/proses_editmitra.php" method="post"
                    class="form-horizontal form-label-left">

                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                    <!-- Nama Pemilik -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Nama Pemilik <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="nama_pemilik" required maxlength="100"
                          placeholder="Masukkan Nama Pemilik"
                          value="<?php echo htmlspecialchars($data['nama_pemilik']); ?>"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Nama Usaha -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Nama Usaha <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="nama_usaha" required maxlength="100"
                          placeholder="Masukkan Nama Usaha"
                          value="<?php echo htmlspecialchars($data['nama_usaha']); ?>"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Kategori Usaha -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Kategori Usaha <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select name="kategori_usaha" required class="form-control col-md-7 col-xs-12">
                          <option value="">Pilih Kategori Usaha</option>
                          <?php
                          foreach (['UMKM','Local Guide','Catering','Dokar','Homestay','Kerajinan'] as $k) {
                            $sel = ($data['kategori_usaha'] == $k) ? 'selected' : '';
                            echo "<option value=\"$k\" $sel>$k</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Alamat <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea name="alamat" required rows="3" class="form-control"
                          placeholder="Masukkan Alamat Lengkap"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                      </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Nomor Telepon <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="nomor_telp" required maxlength="20"
                          placeholder="Masukkan Nomor Telepon"
                          value="<?php echo htmlspecialchars($data['nomor_telp']); ?>"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- Legalitas Usaha -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        Legalitas Usaha <span class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="legalitas_usaha" required maxlength="100"
                          placeholder="Masukkan Legalitas Usaha"
                          value="<?php echo htmlspecialchars($data['legalitas_usaha']); ?>"
                          class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <!-- ===== FILE BUKTI LEGALITAS ===== -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">File Bukti Legalitas</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">

                        <?php if (!empty($existing_legalitas)): ?>
                        <p class="text-muted" style="margin-bottom:6px;">
                          <small>File saat ini — klik <i class="fa fa-times" style="color:red;"></i> untuk hapus:</small>
                        </p>
                        <div id="area-existing-legalitas" class="row">
                          <?php foreach ($existing_legalitas as $file):
                            $file_url = '/admin/uploads/mitra/' . htmlspecialchars($file);
                            $is_pdf   = strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf';
                          ?>
                          <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="<?= htmlspecialchars($file) ?>" style="margin-bottom:12px;">
                            <div style="position:relative; border:1px solid #ddd; border-radius:5px; height:100px; display:flex; align-items:center; justify-content:center; background:#f8f8f8; overflow:hidden; padding:5px;">
                              <?php if ($is_pdf): ?>
                                <a href="<?= $file_url ?>" target="_blank" style="text-align:center;">
                                  <i class="fa fa-file-pdf-o fa-2x" style="color:#d9534f; display:block;"></i>
                                  <small style="word-break:break-all;"><?= htmlspecialchars(basename($file)) ?></small>
                                </a>
                              <?php else: ?>
                                <a href="<?= $file_url ?>" target="_blank">
                                  <img src="<?= $file_url ?>" style="max-height:90px; max-width:100%; object-fit:cover; border-radius:4px;">
                                </a>
                              <?php endif; ?>
                              <button type="button" class="btn btn-danger btn-xs hapus-existing-legalitas"
                                style="position:absolute; top:4px; right:4px;" title="Hapus file ini">
                                <i class="fa fa-times"></i>
                              </button>
                            </div>
                          </div>
                          <?php endforeach; ?>
                        </div>
                        <hr style="margin:10px 0;">
                        <?php endif; ?>

                        <div id="area-preview-legalitas" class="row"></div>
                        <input type="file" id="input-legalitas" style="display:none;" accept="application/pdf,image/*">
                        <button type="button" class="btn btn-default" id="tombol-pilih-legalitas">
                          <i class="fa fa-plus"></i> Tambah File
                        </button>
                        <div id="pesan-upload-legalitas" style="margin-top:8px;"></div>

                        <input type="hidden" name="daftar_legalitas_existing" id="daftar_legalitas_existing"
                          value="<?= htmlspecialchars(implode(',', $existing_legalitas)) ?>">
                        <input type="hidden" name="daftar_legalitas_terupload" id="daftar_legalitas_terupload">
                        <small class="text-muted">PDF/Gambar, maksimal 10MB per file.</small>
                      </div>
                    </div>

                    <!-- ===== FOTO KEGIATAN USAHA ===== -->
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Foto Kegiatan Usaha</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">

                        <?php if (!empty($existing_kegiatan)): ?>
                        <p class="text-muted" style="margin-bottom:6px;">
                          <small>Foto saat ini — klik <i class="fa fa-times" style="color:red;"></i> untuk hapus:</small>
                        </p>
                        <div id="area-existing-kegiatan" class="row">
                          <?php foreach ($existing_kegiatan as $foto):
                            $foto_url = '/admin/uploads/mitra/' . htmlspecialchars($foto);
                          ?>
                          <div class="col-md-3 col-sm-4 col-xs-6" data-namafile="<?= htmlspecialchars($foto) ?>" style="margin-bottom:12px;">
                            <div style="position:relative;">
                              <a href="<?= $foto_url ?>" target="_blank">
                                <img src="<?= $foto_url ?>" style="width:100%; height:100px; object-fit:cover; border-radius:5px; border:1px solid #ddd;">
                              </a>
                              <button type="button" class="btn btn-danger btn-xs hapus-existing-kegiatan"
                                style="position:absolute; top:4px; right:4px;" title="Hapus foto ini">
                                <i class="fa fa-times"></i>
                              </button>
                            </div>
                          </div>
                          <?php endforeach; ?>
                        </div>
                        <hr style="margin:10px 0;">
                        <?php endif; ?>

                        <div id="area-preview-kegiatan" class="row"></div>
                        <input type="file" id="input-kegiatan" style="display:none;" accept="image/*">
                        <button type="button" class="btn btn-default" id="tombol-pilih-kegiatan">
                          <i class="fa fa-plus"></i> Tambah Foto
                        </button>
                        <div id="pesan-upload-kegiatan" style="margin-top:8px;"></div>

                        <input type="hidden" name="daftar_kegiatan_existing" id="daftar_kegiatan_existing"
                          value="<?= htmlspecialchars(implode(',', $existing_kegiatan)) ?>">
                        <input type="hidden" name="daftar_kegiatan_terupload" id="daftar_kegiatan_terupload">
                        <small class="text-muted">Upload satu per satu (Maksimal 2MB per foto).</small>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href="datamitra.php" class="btn btn-success">
                          <span class="glyphicon glyphicon-arrow-left"></span> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                          <i class="glyphicon glyphicon-floppy-disk"></i> Simpan
                        </button>
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
        <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
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
  $(document).ready(function () {

    // Hapus file LAMA (existing) dari daftar pertahankan
    $(document).on('click', '.hapus-existing-legalitas', function () {
      var item  = $(this).closest('[data-namafile]');
      var nama  = item.data('namafile');
      var field = $('#daftar_legalitas_existing');
      var arr   = field.val().split(',').filter(function (n) { return n && n !== nama; });
      field.val(arr.join(','));
      item.remove();
    });

    $(document).on('click', '.hapus-existing-kegiatan', function () {
      var item  = $(this).closest('[data-namafile]');
      var nama  = item.data('namafile');
      var field = $('#daftar_kegiatan_existing');
      var arr   = field.val().split(',').filter(function (n) { return n && n !== nama; });
      field.val(arr.join(','));
      item.remove();
    });

    // Fungsi generik AJAX upload file baru
    function setupAjaxUpload(config) {
      $(config.buttonSelector).on('click', function () {
        $(config.inputSelector).click();
      });

      $(config.inputSelector).on('change', function () {
        if (this.files.length === 0) return;
        var file     = this.files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('type', config.uploadType);

        $(config.messageSelector).html('<i class="fa fa-spinner fa-spin"></i> Mengunggah...');

        fetch('proses/ajax_upload_mitra.php', { method: 'POST', body: formData })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.sukses) {
              var namaFile = data.namaFile;
              var isImage  = /\.(jpe?g|png|gif)$/i.test(namaFile);
              var html;

              if (isImage) {
                html = '<div class="col-md-3 col-sm-4 col-xs-6" data-namafile="' + namaFile + '" style="margin-bottom:12px;">'
                  + '<div style="position:relative;">'
                  + '<img src="/admin/uploads/mitra/' + namaFile + '" style="width:100%; height:100px; object-fit:cover; border-radius:5px; border:1px solid #ddd;">'
                  + '<button type="button" class="btn btn-danger btn-xs ' + config.deleteClass + '" style="position:absolute; top:4px; right:4px;"><i class="fa fa-times"></i></button>'
                  + '</div></div>';
              } else {
                html = '<div class="col-md-3 col-sm-4 col-xs-6" data-namafile="' + namaFile + '" style="margin-bottom:12px;">'
                  + '<div style="position:relative; border:1px solid #ddd; border-radius:5px; padding:10px; height:100px; display:flex; align-items:center; justify-content:center; background:#f8f8f8;">'
                  + '<i class="fa fa-file-pdf-o fa-2x" style="color:#d9534f;"></i>'
                  + '<p style="margin-left:8px; word-break:break-all; font-size:12px;">' + namaFile + '</p>'
                  + '<button type="button" class="btn btn-danger btn-xs ' + config.deleteClass + '" style="position:absolute; top:4px; right:4px;"><i class="fa fa-times"></i></button>'
                  + '</div></div>';
              }

              $(config.previewAreaSelector).append(html);
              var arr = ($(config.hiddenListSelector).val() || '').split(',').filter(function(n){ return n; });
              arr.push(namaFile);
              $(config.hiddenListSelector).val(arr.join(','));
              $(config.messageSelector).html('<span style="color:green;"><i class="fa fa-check"></i> Upload berhasil!</span>');
            } else {
              $(config.messageSelector).html('<span style="color:red;"><i class="fa fa-times"></i> ' + data.pesan + '</span>');
            }
          })
          .catch(function () {
            $(config.messageSelector).html('<span style="color:red;">Terjadi kesalahan jaringan.</span>');
          });

        $(this).val('');
      });

      $(document).on('click', '.' + config.deleteClass, function () {
        var item = $(this).closest('[data-namafile]');
        var nama = item.data('namafile');
        var arr  = $(config.hiddenListSelector).val().split(',').filter(function (n) { return n !== nama; });
        $(config.hiddenListSelector).val(arr.join(','));
        item.remove();
      });
    }

    setupAjaxUpload({
      buttonSelector    : '#tombol-pilih-legalitas',
      inputSelector     : '#input-legalitas',
      previewAreaSelector: '#area-preview-legalitas',
      messageSelector   : '#pesan-upload-legalitas',
      hiddenListSelector: '#daftar_legalitas_terupload',
      deleteClass       : 'hapus-legalitas-baru',
      uploadType        : 'legalitas'
    });

    setupAjaxUpload({
      buttonSelector    : '#tombol-pilih-kegiatan',
      inputSelector     : '#input-kegiatan',
      previewAreaSelector: '#area-preview-kegiatan',
      messageSelector   : '#pesan-upload-kegiatan',
      hiddenListSelector: '#daftar_kegiatan_terupload',
      deleteClass       : 'hapus-kegiatan-baru',
      uploadType        : 'kegiatan'
    });

  });
  </script>
</body>
</html>