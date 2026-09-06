<?php
session_start();
include "login/ceksession.php";
include '../koneksi/koneksi.php';
$id             = mysqli_real_escape_string($db, $_GET['id']);
$sql            = "SELECT * FROM tb_data_penjualan_usaha WHERE id='$id'";
$query          = mysqli_query($db, $sql);
$data           = mysqli_fetch_array($query);
$produk_list    = ['Paket Wisata', 'Listrik', 'Pulsa'];
$selected_value = $data['produk'] ?? '';
if (!$data) {
  echo "<script>alert('Data tidak ditemukan');window.location.href='datapenjualanusaha.php';</script>"; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Data Penjualan - Arsip Desa Candirejo</title>
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>

      <div class="right_col" role="main">
        <div class="page-title-modern">
          <div class="page-title-left">
            <h1>Edit Data Penjualan</h1>
            <p>Perbarui data penjualan usaha Desa Wisata Candirejo</p>
          </div>
          <a href="datapenjualanusaha.php" class="btn-back-modern">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Kembali ke Data Penjualan
          </a>
        </div>

        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-header-left">
              <div class="hicon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              </div>
              <h2>Edit Data Penjualan</h2>
            </div>
          </div>

          <div class="form-card-body">
            <div class="info-bar-modern">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Mengedit data penjualan produk: <strong><?php echo htmlspecialchars($data['produk']); ?></strong>
            </div>

            <form action="proses/proses_editpenjualan.php" method="post" id="demo-form2">
              <input type="hidden" name="id_suratkeluar" value="<?php echo $id; ?>">
              <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

              <!-- INFORMASI PENJUALAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Informasi Penjualan
              </div>

              <div class="form-row">
                <label class="form-label">Produk <span class="req">*</span>
                  <small>Kategori produk yang dijual</small>
                </label>
                <div>
                  <select id="produk" name="produk" required class="form-select">
                    <?php foreach ($produk_list as $item): ?>
                      <option value="<?php echo $item; ?>" <?php echo ($item == $selected_value) ? 'selected' : ''; ?>>
                        <?php echo $item; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Sub: Pilihan Paket Wisata -->
              <div class="form-row" id="paket-wisata-group" style="<?php echo ($selected_value === 'Paket Wisata') ? 'display:grid;' : 'display:none;'; ?>">
                <label class="form-label">Pilihan Paket Wisata
                  <small>Spesifikasi paket wisata</small>
                </label>
                <div>
                  <select name="pilihan_paket_wisata" id="pilihan_paket_wisata" class="form-select">
                    <option value="">-- Pilih Paket --</option>
                    <option value="Paket Fun Game" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Paket Fun Game' ? 'selected' : ''; ?>>Paket Fun Game</option>
                    <option value="Paket Pelajar - Live In Candirejo" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Paket Pelajar - Live In Candirejo' ? 'selected' : ''; ?>>Paket Pelajar - Live In Candirejo</option>
                    <option value="Paket Pelajar – Field Trip One Day" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Paket Pelajar – Field Trip One Day' ? 'selected' : ''; ?>>Paket Pelajar – Field Trip One Day</option>
                    <option value="Paket Pelajar – Field Trip Half Day" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Paket Pelajar – Field Trip Half Day' ? 'selected' : ''; ?>>Paket Pelajar – Field Trip Half Day</option>
                    <option value="Cycling Village Tour Candirejo" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Cycling Village Tour Candirejo' ? 'selected' : ''; ?>>Cycling Village Tour Candirejo</option>
                    <option value="Traditional Dance" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Traditional Dance' ? 'selected' : ''; ?>>Traditional Dance</option>
                    <option value="Walking Around Village" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Walking Around Village' ? 'selected' : ''; ?>>Walking Around Village</option>
                    <option value="Stay At Local House In Candirejo Village (Homestay)" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Stay At Local House In Candirejo Village (Homestay)' ? 'selected' : ''; ?>>Stay At Local House In Candirejo Village (Homestay)</option>
                    <option value="Serenade At The Foot Of Menoreh Hill" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Serenade At The Foot Of Menoreh Hill' ? 'selected' : ''; ?>>Serenade At The Foot Of Menoreh Hill</option>
                    <option value="Cooking Lesson" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Cooking Lesson' ? 'selected' : ''; ?>>Cooking Lesson</option>
                    <option value="Village Experience" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Village Experience' ? 'selected' : ''; ?>>Village Experience</option>
                    <option value="Dokar Village Tour Candirejo" <?php echo ($data['pilihan_paket_wisata'] ?? '') === 'Dokar Village Tour Candirejo' ? 'selected' : ''; ?>>Dokar Village Tour Candirejo</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Jumlah <span class="req">*</span>
                  <small>Jumlah item/unit yang dijual</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['jumlah']); ?>" type="number"
                    id="jumlahkeluar" name="jumlah" required class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Harga <span class="req">*</span>
                  <small>Total harga penjualan (Rp)</small>
                </label>
                <div>
                  <input value="<?php echo htmlspecialchars($data['harga']); ?>" type="number"
                    id="kepada_suratkeluar" name="harga" required class="form-input">
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" name="update" value="Update" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <a href="datapenjualanusaha.php" class="btn-cancel">
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
  <script src="../assets/build/js/custom.min.js"></script>

  <script>
  document.getElementById('produk').addEventListener('change', function() {
    var group = document.getElementById('paket-wisata-group');
    group.style.display = (this.value === 'Paket Wisata') ? 'grid' : 'none';
  });
  </script>
</body>
</html>
