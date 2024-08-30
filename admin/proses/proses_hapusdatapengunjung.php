<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_GET['kode_data'])) {
    $kode_data = $_GET['kode_data'];

    // Ambil data pengunjung berdasarkan kode_data
    $sql2 = "SELECT * FROM tb_data_pengunjung WHERE kode_data='".$kode_data."'";                        
    $query2 = mysqli_query($db, $sql2);
    $data2 = mysqli_fetch_array($query2);
    $total = mysqli_num_rows($query2);

    if ($total == 0) {
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Peringatan",
                    text: "Data tidak ditemukan!",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.history.back();
                });
              </script>';
    } else {
        // Hapus data dari tabel
        $sql = "DELETE FROM tb_data_pengunjung WHERE kode_data='".$kode_data."'";                        
        $query = mysqli_query($db, $sql);

        if ($query) {
            // Hapus file foto jika ada
            if (!empty($data2['foto_wisatawan']) && file_exists("../foto_wisatawan/" . $data2['foto_wisatawan'])) {
                unlink("../foto_wisatawan/" . $data2['foto_wisatawan']);
            }
            
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Sukses",
                        text: "Data Pengunjung telah Dihapus",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datapengunjung.php";
                    });
                  </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: "Gagal Menghapus Data Pengunjung",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "../datapengunjung.php";
                    });
                  </script>';
        }
    }
} else {
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Kode data tidak ditemukan!",
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.history.back();
            });
          </script>';
}
?>
