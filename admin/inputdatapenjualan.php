<?php
session_start();
include "login/ceksession.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Input Data Penjualan - Arsip Desa Candirejo</title>
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
            <h1>Input Data Penjualan</h1>
            <p>Tambah data penjualan usaha Desa Wisata Candirejo</p>
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
              <h2>Data Penjualan Baru</h2>
            </div>
          </div>

          <div class="form-card-body">
            <form action="proses/proses_inputdatapenjualan.php" name="forminputdatapenjualan" method="post"
              id="demo-form2" data-parsley-validate>

              <!-- INFORMASI PENJUALAN -->
              <div class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Informasi Penjualan
              </div>

              <div class="form-row">
                <label class="form-label">Jenis Produk <span class="req">*</span>
                  <small>Kategori produk yang dijual</small>
                </label>
                <div>
                  <select id="jenis_produk" name="produk" required class="form-select">
                    <option value="Listrik">Listrik</option>
                    <option value="Pulsa">Pulsa</option>
                    <option value="Paket Wisata">Paket Wisata</option>
                  </select>
                </div>
              </div>

              <!-- Sub: Pilihan Paket Wisata (muncul jika Paket Wisata dipilih) -->
              <div class="form-row" id="paket-wisata-group" style="display:none;">
                <label class="form-label">Pilihan Paket Wisata
                  <small>Spesifikasi paket wisata yang dijual</small>
                </label>
                <div>
                  <select id="pilihan_paket_wisata" name="pilihan_paket_wisata" class="form-select">
                    <option value="">-- Pilih Paket --</option>
                    <option value="Paket Fun Game">Paket Fun Game</option>
                    <option value="Paket Pelajar - Live In Candirejo">Paket Pelajar - Live In Candirejo</option>
                    <option value="Paket Pelajar – Field Trip One Day">Paket Pelajar – Field Trip One Day</option>
                    <option value="Paket Pelajar – Field Trip Half Day">Paket Pelajar – Field Trip Half Day</option>
                    <option value="Cycling Village Tour Candirejo">Cycling Village Tour Candirejo</option>
                    <option value="Traditional Dance">Traditional Dance</option>
                    <option value="Walking Around Village">Walking Around Village</option>
                    <option value="Stay At Local House In Candirejo Village (Homestay)">Stay At Local House In Candirejo Village (Homestay)</option>
                    <option value="Serenade At The Foot Of Menoreh Hill">Serenade At The Foot Of Menoreh Hill</option>
                    <option value="Cooking Lesson">Cooking Lesson</option>
                    <option value="Village Experience">Village Experience</option>
                    <option value="Dokar Village Tour Candirejo">Dokar Village Tour Candirejo</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Jumlah <span class="req">*</span>
                  <small>Jumlah item/unit yang dijual</small>
                </label>
                <div>
                  <input type="number" id="jumlah" name="jumlah" required min="1"
                    placeholder="Masukkan Jumlah" class="form-input">
                </div>
              </div>

              <div class="form-row">
                <label class="form-label">Harga <span class="req">*</span>
                  <small>Total harga penjualan (Rp)</small>
                </label>
                <div>
                  <input type="number" id="harga" name="harga" required min="0"
                    placeholder="Masukkan Total Harga" class="form-input">
                </div>
              </div>

              <!-- ACTIONS -->
              <div class="form-actions">
                <button type="submit" class="btn-submit">
                  <i class="fa fa-save"></i> Simpan Data
                </button>
                <button type="reset" class="btn-reset">
                  <i class="fa fa-refresh"></i> Reset
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
  document.getElementById('jenis_produk').addEventListener('change', function() {
    var group = document.getElementById('paket-wisata-group');
    group.style.display = (this.value === 'Paket Wisata') ? 'grid' : 'none';
  });
  </script>
</body>
</html>
