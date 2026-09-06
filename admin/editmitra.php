<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
$id         = mysqli_real_escape_string($db, $_GET['id']);
$sql        = "SELECT * FROM tb_data_mitra WHERE id='$id'";
$query      = mysqli_query($db, $sql);
$data       = mysqli_fetch_array($query);
if (!$data) {
  echo "<script>alert('Data tidak ditemukan');window.location.href='datamitra.php';</script>";
  exit;
}
// Foto existing
$existing_fotos = [];
if (!empty($data['foto_kegiatan'])) {
  // Support both JSON array and comma-separated
  $decoded = json_decode($data['foto_kegiatan'], true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    $existing_fotos = $decoded;
  } else {
    $existing_fotos = array_filter(explode(',', $data['foto_kegiatan']));
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Mitra - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    .foto-thumb {
      position: relative; display: inline-block;
    }
    .foto-thumb img {
      width: 80px; height: 80px; object-fit: cover;
      border-radius: 8px; border: 1.5px solid #b5d5c0;
    }
    .foto-thumb .nama-file {
      font-size: 9px; text-align: center; color: #6b8f7e;
      max-width: 80px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .foto-thumb .btn-remove {
      position: absolute; top: -6px; right: -6px;
      background: #e74c3c; color: white; border: none;
      border-radius: 50%; width: 20px; height: 20px;
      font-size: 11px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Data Mitra</h1>
            <p>Perbarui data mitra Desa Wisata Candirejo</p>
          </div>
          <a href="datamitra.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Mitra
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              </div>
              <h2>Edit Data Mitra</h2>
            </div>
          </div>

          <div class="form-card-body">
            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Mengedit data mitra: <strong><?php echo htmlspecialchars($data['nama_usaha']); ?></strong>
            </div>

            <form action="proses/proses_editmitra.php" method="post" enctype="multipart/form-data" id="demo-form2">
              <input type="hidden" name="id_suratkeluar" value="<?php echo $id; ?>">
              <input type="hidden" name="foto_lama_json" id="foto_lama_json"
                value="<?php echo htmlspecialchars(json_encode($existing_fotos)); ?>">

              <!-- IDENTITAS USAHA -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Identitas Usaha
              </div>

              <div class="form-row">
                <label class="form-label">Nama Pemilik <span class="req">*</span>
                  <small>Nama lengkap pemilik usaha</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['nama_pemilik']); ?>" type="text"
                    id="nama_pemilikkeluar" name="nama_pemilik" required maxlength="35"
                    placeholder="Masukkan Nama Pemilik" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Nama Usaha <span class="req">*</span>
                  <small>Nama resmi usaha</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['nama_usaha']); ?>" type="text"
                    id="nama_usaha" name="nama_usaha" required maxlength="100"
                    placeholder="Masukkan Nama Usaha" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Kategori Usaha <span class="req">*</span>
                  <small>Jenis atau bidang usaha</small>
                </label>
                <div>
                  <select id="kategori_usaha" name="kategori_usaha" required class="form-select">
                    <option value="">Pilih Kategori Usaha</option>
                    <option value="UMKM" <?php echo ($data['kategori_usaha'] == 'UMKM') ? 'selected' : ''; ?>>UMKM</option>
                    <option value="Local Guide" <?php echo ($data['kategori_usaha'] == 'Local Guide') ? 'selected' : ''; ?>>Local Guide</option>
                    <option value="Catering" <?php echo ($data['kategori_usaha'] == 'Catering') ? 'selected' : ''; ?>>Catering</option>
                    <option value="Dokar" <?php echo ($data['kategori_usaha'] == 'Dokar') ? 'selected' : ''; ?>>Dokar</option>
                    <option value="Homestay" <?php echo ($data['kategori_usaha'] == 'Homestay') ? 'selected' : ''; ?>>Homestay</option>
                    <option value="Kerajinan" <?php echo ($data['kategori_usaha'] == 'Kerajinan') ? 'selected' : ''; ?>>Kerajinan</option>
                  </select>
                </div>
              </div>

              <!-- KONTAK & LOKASI -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Kontak & Lokasi
              </div>

              <div class="form-row">
                <label class="form-label">Alamat <span class="req">*</span>
                  <small>Alamat lengkap lokasi usaha</small>
                </label>
                <div>
                  <textarea id="alamat" name="alamat" required class="form-input"
                    rows="3" style="resize:vertical;min-height:75px;" placeholder="Masukkan Alamat Lengkap"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Nomor Telepon <span class="req">*</span>
                  <small>Nomor HP aktif</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['nomor_telp']); ?>" type="text"
                    id="nomor_telp" name="nomor_telp" required maxlength="20"
                    placeholder="Masukkan Nomor Telepon" class="form-input">
                </div>
              </div>

              <!-- LEGALITAS & DOKUMEN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Legalitas & Dokumen
              </div>

              <div class="form-row">
                <label class="form-label">Legalitas Usaha <span class="req">*</span>
                  <small>Nomor/keterangan legalitas</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['legalitas_usaha']); ?>" type="text"
                    id="legalitas_usaha" name="legalitas_usaha" required
                    placeholder="Masukkan Legalitas Usaha" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">File Bukti Legalitas
                  <small>PDF – Maks. 10 MB</small>
                </label>
                <div>
                  <?php if (!empty($data['bukti_legalitas'])): ?>
                    <?php $bukti_url = str_replace('../', '', $data['bukti_legalitas']); ?>
                    <div style="margin-bottom:10px;">
                      <a href="<?php echo htmlspecialchars($bukti_url); ?>" target="_blank" class="btn-back-modern"
                        style="display:inline-flex;gap:6px;">
                        <i class="fa fa-file-pdf-o" style="color:#e74c3c;"></i> Lihat File Legalitas Saat Ini
                      </a>
                    </div>
                  <?php endif; ?>
                  <div class="upload-area-modern" onclick="document.getElementById('bukti_legalitas').click()" style="padding:16px;">
                    <div class="upload-icon"><i class="fa fa-file-pdf-o"></i></div>
                    <div class="upload-text-main">Klik untuk ganti file legalitas</div>
                    <div class="upload-text-sub">PDF – kosongkan jika tidak ingin mengganti</div>
                  </div>
                  <input name="bukti_legalitas" accept="application/pdf" type="file" id="bukti_legalitas"
                    class="hidden-file-input" autocomplete="off" />
                  <div id="preview-legalitas" style="margin-top:8px;"></div>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Foto Kegiatan Usaha
                  <small>Tambah/perbarui foto dokumentasi usaha</small>
                </label>
                <div>
                  <?php if (!empty($existing_fotos)): ?>
                    <p class="form-hint" style="margin-bottom:8px;">Foto saat ini <small>(klik ✕ untuk hapus)</small>:</p>
                    <div id="existing-foto-container" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
                      <?php foreach($existing_fotos as $fotoPath):
                        $fotoPath = trim($fotoPath);
                        $fotoUrl  = str_replace('../', '', $fotoPath);
                        $fotoHash = md5($fotoPath); ?>
                        <div class="foto-thumb" id="existing-<?php echo $fotoHash; ?>">
                          <img src="<?php echo htmlspecialchars($fotoUrl); ?>" alt="Foto">
                          <div class="nama-file"><?php echo htmlspecialchars(basename($fotoPath)); ?></div>
                          <button type="button" class="btn-remove"
                            onclick="removeExistingFoto('<?php echo htmlspecialchars($fotoPath); ?>','<?php echo $fotoHash; ?>')"
                            title="Hapus">✕</button>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <div class="upload-area-modern" onclick="document.getElementById('foto_kegiatan').click()">
                    <div class="upload-icon"><i class="fa fa-camera"></i></div>
                    <div class="upload-text-main">Klik untuk tambah foto baru</div>
                    <div class="upload-text-sub">JPG, PNG – Maks. 2 MB per foto</div>
                  </div>
                  <input name="foto_kegiatan[]" accept="image/*" type="file" id="foto_kegiatan"
                    class="hidden-file-input" multiple />
                  <div id="foto-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;"></div>
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <a href="datamitra.php" class="btn-cancel">
                  <i class="fa fa-times"></i> Batal
                </a>
              </div>

            </form>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  // Existing foto management
  var retainedFotos = <?php echo json_encode($existing_fotos); ?>;

  function removeExistingFoto(fotoPath, hashId) {
    Swal.fire({
      icon: 'warning', title: 'Hapus Foto?',
      text: 'Foto ini akan dihapus saat disimpan.',
      showCancelButton: true, confirmButtonColor: '#e74c3c',
      cancelButtonColor: '#95a5a6', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) {
        retainedFotos = retainedFotos.filter(function(f) { return f !== fotoPath; });
        document.getElementById('foto_lama_json').value = JSON.stringify(retainedFotos);
        var el = document.getElementById('existing-' + hashId);
        if (el) el.remove();
      }
    });
  }

  $(document).ready(function () {
    var selectedFiles = [];

    function renderFotoPreview() {
      var preview = $('#foto-preview');
      preview.empty();
      selectedFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = (function(f, i) {
          return function(e) {
            preview.append(
              '<div class="foto-thumb" id="fpreview-' + i + '">' +
              '<img src="' + e.target.result + '">' +
              '<div class="nama-file">' + f.name + '</div>' +
              '<button type="button" class="btn-remove" onclick="removeNewFoto(' + i + ')">✕</button>' +
              '</div>'
            );
          };
        })(file, idx);
        reader.readAsDataURL(file);
      });
      var dt = new DataTransfer();
      selectedFiles.forEach(function(f) { dt.items.add(f); });
      document.getElementById('foto_kegiatan').files = dt.files;
    }

    window.removeNewFoto = function(idx) { selectedFiles.splice(idx,1); renderFotoPreview(); };

    $('#foto_kegiatan').on('change', function() {
      Array.from(this.files).forEach(function(f) {
        if(!selectedFiles.find(function(x){return x.name===f.name&&x.size===f.size;})) selectedFiles.push(f);
      });
      renderFotoPreview();
    });

    $('#bukti_legalitas').on('change', function() {
      var file = this.files[0];
      if (!file) return;
      $('#preview-legalitas').html(
        '<div style="display:inline-flex;align-items:center;gap:8px;background:#f6fbf8;border:1px solid #b5d5c0;border-radius:8px;padding:8px 14px;">' +
        '<i class="fa fa-file-pdf-o" style="color:#e74c3c;font-size:18px;"></i>' +
        '<span style="font-size:13px;color:#2a4535;">' + file.name + '</span>' +
        '</div>'
      );
    });
  });
  </script>
</body>
</html>
