<?php
session_start();
include "koneksi/ceksession.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Surat Desa Candirejo Borobudur</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="./img/samarinda.png" type="image/x-icon"/>
    <!-- --------- UNICONS ---------- -->
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css">
</head>

<body>
    <div class="container">
        <nav>
            <div class="logo">
                <a href="index.html">
                    <img src="./img/samarinda.png" alt="logo">
                </a>
            </div>
            <a href="admin/login" class="masuk-btn">Masuk <i class="hgi hgi-stroke hgi-arrow-right-02"></i></a>
        </nav>

        <!-- -maincontent -->
        <main class="wrapper">
            <div class="home" id="home">
                <div class="before-copyright">
                    <div class="hero-section-main-header">
                        <div class="hero-section-teks1">
                            <div class="hero-chip">
                                <div class="chip1"><i class="hgi hgi-stroke hgi-inbox-download"></i> Surat Masuk</div>
                                <div class="chip2"><i class="hgi hgi-stroke hgi-inbox-upload"></i> Surat Keluar</div>
                            </div>
                            <div class="hero-heading">
                                <span class="heading1">Sistem Informasi Pengarsipan Surat</span>
                                <span class="heading2">Desa Wisata Candirejo Borobudur</span>
                            </div>
                        </div>
                        <div class="hero-section-teks2">
                            <p>Website ini berguna untuk pengarsipan Surat Masuk dan Surat Keluar dari Desa Wisata Candirejo Borobudur</p>
                        </div>
                    </div>
                    <div class="hero-img">
                        <img src="./img/60c0b394da969.jpg.jpeg" alt="">
                    </div>
                </div>
                <div class="copyright">
                    <div class="left-copyright">
                        <p>Didanai oleh DRTPM KEMDIKBUDRISTEK 2024 - FEB UNNES</p>
                    </div>
                    <div class="right-copyright">
                        <p>© 2026 - Developed by</p><a href="https://www.instagram.com/vuriko.studio?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">Vuriko Studio</a>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body>

</html>