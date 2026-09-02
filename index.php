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
    <link rel="shortcut icon" href="./img/icon.ico" type="image/x-icon"/>
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
            <!-- <a href="admin/login" class="masuk-btn">Masuk <i class="hgi hgi-stroke hgi-arrow-right-02"></i></a> -->
        </nav>

        <!-- -maincontent -->
        <main class="wrapper">
            <div class="home" id="home">
                <div class="before-copyright">
                    <div class="hero-section-main-header">
                        <div class="hero-heading">
                            <span class="heading1">Sistem Informasi Terpadu</span>
                            <span class="heading2">Arsip Surat & Rekap Booking</span>
                        </div>
                        <div class="hero-section-teks2">
                            <p>Platform digital untuk mengelola <strong>Surat Masuk & Surat Keluar</strong> sekaligus <strong>Rekap Data Booking</strong> Desa Wisata Candirejo Borobudur secara mudah dan terpusat.</p>
                        </div>
                        <div class="hero-feature-row">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="hgi hgi-stroke hgi-file-02"></i>
                                </div>
                                <div class="feature-text">
                                    <span class="feature-title">Arsip Surat</span>
                                    <span class="feature-desc">Kelola surat masuk & keluar secara digital</span>
                                </div>
                            </div>
                            <div class="feature-divider"></div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="hgi hgi-stroke hgi-invoice-01"></i>
                                </div>
                                <div class="feature-text">
                                    <span class="feature-title">Rekap Booking</span>
                                    <span class="feature-desc">Pantau & rekap data pemesanan wisatawan</span>
                                </div>
                            </div>
                        </div>
                        <div class="hero-chip">
                                <a href="admin/login/?from=booking" class="chip1">
                                <div class="left-layout">
                                    <i class="hgi hgi-stroke hgi-invoice-01"></i> Sistem Booking
                                </div>
                                <i class="hgi hgi-stroke hgi-arrow-right-01"></i>
                                </a>
                                <a href="admin/login/?from=arsip" class="chip2">
                                <div class="left-layout">
                                    <i class="hgi hgi-stroke hgi-file-02"></i> Arsip Surat
                                </div>
                                <i class="hgi hgi-stroke hgi-arrow-right-01"></i>
                                </a>
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

    <div class="watermark-floating">
        Diperbarui oleh Tim Pengabdian PTIK INTER UNNES '23
    </div>

</body>

</html>