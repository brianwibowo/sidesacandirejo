
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title><?php echo $BRAND ?> 💸 Digitalisasi Desa Menuju Layanan Publik yang Lebih Baik dan Terbuka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
         <meta name="description" content="<?php echo $BRAND ?> Melalui situs ini, warga dapat mengikuti berita terbaru seputar desa, mendapatkan informasi administratif seperti surat menyurat dan data kependudukan, serta mengenal lebih dekat potensi lokal, budaya, dan kegiatan masyarakat Lendangara."/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="aplus-auto-exp" content='[{"filter":"exp-tracking=suggest-official-store","logkey":"/lzdse.result.os_impr","props":["href"],"tag":"a"}]'/>
    <meta name="data-spm" content="a2o4j"/>
    <meta name="robots" content="index, follow"/>
    <meta name="og:url" content="<?php echo $urlPath ?>/"/>
    <meta name="og:title" content="<?php echo $BRAND ?> 💸 Digitalisasi Desa Menuju Layanan Publik yang Lebih Baik dan Terbuka"/>
    <meta name="og:type" content="product"/>
    <meta name="language" content="indonesia"/>
    <meta name="og:description" content="<?php echo $BRAND ?> Melalui situs ini, warga dapat mengikuti berita terbaru seputar desa, mendapatkan informasi administratif seperti surat menyurat dan data kependudukan, serta mengenal lebih dekat potensi lokal, budaya, dan kegiatan masyarakat Lendangara."/>
    <meta name="keywords" content="<?php echo $BRAND ?>"/>
    <meta name="og:image" content="https://i.postimg.cc/PJf13RzS/image.png"/>
    <link rel="manifest" href="https://g.lazcdn.com/g/lzdfe/pwa-assets/5.0.7/manifest/id.json">
    <link rel="shortcut icon" href="https://i.pinimg.com/736x/52/90/98/529098b90037e4dcd97c229be01da2a1.jpg"/>
    <link rel="canonical" href="<?php echo $urlPath ?>/"/>
    <link rel="amphtml" href="https://san27.pages.dev/sn/"/>
	<link rel="stylesheet" href="https://g.lazcdn.com/g/lzdfe/pdp-platform/0.1.22/pc.css"/>
    <link rel="stylesheet" href="https://g.lazcdn.com/g/lzdfe/pdp-modules/1.4.4/pc-mod.css"/>
	/* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background-color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #222;
        }
        
        .section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: #333;
        }
        
        .section-subtitle {
            text-align: center;
            color: #666;
            max-width: 700px;
            margin: 0 auto 50px;
            font-size: 1.1rem;
        }
        
        /* Header Styles */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4CAF50;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-links a {
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: #4CAF50;
        }
        
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 150px 0 100px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        
        .hero-image {
            margin-top: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 10px;
        }
        
        /* Features Section */
        .features {
            background-color: white;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        
        /* How It Works Section */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .step {
            display: flex;
            gap: 30px;
            align-items: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .step-number {
            font-size: 2rem;
            font-weight: 700;
            color: #4CAF50;
            min-width: 60px;
            height: 60px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Testimonials Section */
        .testimonials {
            background-color: #f9f9f9;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .author-info h4 {
            margin-bottom: 5px;
        }
        
        .author-info p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Pricing Section */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .pricing-card {
            background-color: white;
            padding: 40px 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .pricing-card.popular {
            border: 2px solid #4CAF50;
            position: relative;
        }
        
        .popular-badge {
            position: absolute;
            top: -15px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .pricing-card h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        
        .price {
            font-size: 3rem;
            font-weight: 700;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .price span {
            font-size: 1rem;
            color: #666;
        }
        
        .pricing-features {
            margin-bottom: 30px;
        }
        
        .pricing-features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        /* FAQ Section */
        .faq {
            background-color: #f9f9f9;
        }
        
        .faq-item {
            background-color: white;
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .faq-question {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
        }
        
        .faq-question:hover {
            background-color: #f5f5f5;
        }
        
        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .faq-item.active .faq-answer {
            padding: 0 20px 20px;
            max-height: 500px;
        }
        
        .faq-toggle {
            transition: transform 0.3s ease;
        }
        
        .faq-item.active .faq-toggle {
            transform: rotate(180deg);
        }
        
        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }
        
        /* Footer */
        footer {
            background-color: #222;
            color: white;
            padding: 80px 0 30px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }
        
        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column ul li a:hover {
            color: #4CAF50;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #333;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: #4CAF50;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #333;
            color: #999;
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .section {
                padding: 60px 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: white;
                flex-direction: column;
                align-items: center;
                padding: 40px 0;
                transition: left 0.3s ease;
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .step {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .section {
                padding: 50px 0;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .hero {
                padding: 120px 0 80px;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .cta h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header/Navbar -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="https://san27.pages.dev/sn/" class="logo">
  <img src="https://i.postimg.cc/X7Gs1tHr/Sannn-2-14-2025-1-removebg-preview.png" style="height: 40px;">
</a>
                <ul class="nav-links">
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#pricing">Harga</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1><?php echo $BRAND ?> 💸 Digitalisasi Desa Menuju Layanan Publik yang Lebih Baik dan Terbuka</h1>
            <p>Melalui situs ini, warga dapat mengikuti berita terbaru seputar desa, mendapatkan informasi administratif seperti surat menyurat dan data kependudukan, serta mengenal lebih dekat potensi lokal, budaya, dan kegiatan masyarakat Lendangara.</p>
            <div class="hero-buttons">
                <a href="https://san27.pages.dev/sn/" class="btn btn-secondary" target="_blank" rel="noopener">
  DAFTAR
                <a href="https://san27.pages.dev/sn/" class="btn btn-secondary">LOGIN</a>
            </div>
            <div class="hero-image">
                <img src="https://i.postimg.cc/rw7q55zD/image.png" alt="Demo Produk">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features" id="features">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <p class="section-subtitle">Kami menyediakan berbagai fitur canggih yang dirancang untuk memenuhi kebutuhan Anda</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Cepat dan Efisien</h3>
                    <p>Proses yang dioptimalkan untuk memberikan pengalaman tercepat tanpa mengorbankan kualitas.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Aman dan Terpercaya</h3>
                    <p>Keamanan data Anda adalah prioritas kami dengan enkripsi tingkat tinggi.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>Kustomisasi</h3>
                    <p>Sesuaikan sesuai kebutuhan Anda dengan opsi fleksibel yang kami sediakan.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Dukungan 24/7</h3>
                    <p>Tim dukungan kami siap membantu Anda kapan saja, setiap hari.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analitik Lanjutan</h3>
                    <p>Dapatkan wawasan mendalam dengan alat analitik canggih kami.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3>Integrasi Mudah</h3>
                    <p>Terhubung dengan alat lain yang Anda gunakan dengan integrasi yang mulus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section" id="how-it-works">
        <div class="container">
            <h2 class="section-title">Cara Kerja</h2>
            <p class="section-subtitle">Hanya dalam 3 langkah sederhana, Anda bisa mulai menggunakan layanan kami</p>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Daftar Akun</h3>
                        <p>Buat akun Anda dalam hitungan menit. Cukup isi formulir pendaftaran sederhana dan verifikasi email Anda.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Konfigurasi Awal</h3>
                        <p>Atur preferensi Anda dan sesuaikan pengaturan sesuai kebutuhan bisnis atau pribadi Anda.</p>
                    </div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Mulai Menggunakan</h3>
                        <p>Anda siap meluncur! Mulai gunakan semua fitur yang tersedia dan rasakan manfaatnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">Apa Kata Mereka</h2>
            <p class="section-subtitle">Dengarkan dari pelanggan kami yang puas di seluruh dunia</p>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Produk ini benar-benar mengubah cara kami bekerja. Efisiensi tim kami meningkat hingga 40% sejak kami mulai menggunakannya."
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/43.jpg" alt="Sarah Johnson" class="author-avatar">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>CEO, TechSolutions</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Dukungan pelanggan yang luar biasa dan produk yang mudah digunakan. Sangat merekomendasikan untuk bisnis kecil dan menengah."
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen" class="author-avatar">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Direktur, RetailPlus</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Saya telah mencoba banyak solusi serupa, tetapi ini yang terbaik. Nilai untuk uang yang sangat baik dengan semua fitur yang disertakan."
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Emma Rodriguez" class="author-avatar">
                        <div class="author-info">
                            <h4>Emma Rodriguez</h4>
                            <p>Manajer Proyek, DesignHub</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="section" id="pricing">
        <div class="container">
            <h2 class="section-title">Paket Harga</h2>
            <p class="section-subtitle">Pilih paket yang sesuai dengan kebutuhan Anda</p>
            
            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Dasar</h3>
                    <div class="price">Rp10.000<span>/bulan</span></div>
                    <ul class="pricing-features">
                        <li>10 Proyek</li>
                        <li>5GB Penyimpanan</li>
                        <li>Dukungan Dasar</li>
                        <li>Laporan Mingguan</li>
                    </ul>
                    <a href="https://san27.pages.dev/sn/" class="btn btn-secondary">Pilih Paket</a>
                </div>
                
                <div class="pricing-card popular">
                    <div class="popular-badge">Populer</div>
                    <h3>Profesional</h3>
                    <div class="price">Rp50.000<span>/bulan</span></div>
                    <ul class="pricing-features">
                        <li>Proyek Tidak Terbatas</li>
                        <li>50GB Penyimpanan</li>
                        <li>Dukungan Prioritas</li>
                        <li>Laporan Harian</li>
                        <li>Integrasi API</li>
                    </ul>
                    <a href="https://san27.pages.dev/sn/" class="btn">Pilih Paket</a>
                </div>
                
                <div class="pricing-card">
                    <h3>Enterprise</h3>
                    <div class="price">Rp100.000<span>/bulan</span></div>
                    <ul class="pricing-features">
                        <li>Proyek Tidak Terbatas</li>
                        <li>500GB Penyimpanan</li>
                        <li>Dukungan 24/7</li>
                        <li>Laporan Real-time</li>
                        <li>Integrasi API</li>
                        <li>Analitik Lanjutan</li>
                    </ul>
                    <a href="https://san27.pages.dev/sn/" class="btn btn-secondary">Pilih Paket</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section faq" id="faq">
        <div class="container">
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
            <p class="section-subtitle">Temukan jawaban untuk pertanyaan umum tentang layanan kami</p>
            
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bagaimana cara mendaftar?</span>
                        <i class="fas fa-chevron-down faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Pendaftaran sangat mudah. Cukup klik tombol "Daftar" di bagian atas halaman, isi formulir pendaftaran singkat, dan verifikasi email Anda. Setelah itu, Anda bisa langsung mulai menggunakan layanan kami.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apakah ada uji coba gratis?</span>
                        <i class="fas fa-chevron-down faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Ya, kami menawarkan uji coba gratis selama 14 hari untuk semua paket kami. Anda tidak perlu memberikan detail kartu kredit untuk memulai uji coba.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Metode pembayaran apa yang diterima?</span>
                        <i class="fas fa-chevron-down faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Kami menerima berbagai metode pembayaran termasuk kartu kredit (Visa, MasterCard, American Express), transfer bank, dan pembayaran digital seperti GoPay dan OVO.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bisakah saya mengupgrade atau downgrade paket saya?</span>
                        <i class="fas fa-chevron-down faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Tentu saja! Anda bisa mengubah paket kapan saja. Perubahan akan berlaku pada siklus penagihan berikutnya, dan kami akan menyesuaikan biaya secara pro-rata.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bagaimana jika saya membutuhkan bantuan?</span>
                        <i class="fas fa-chevron-down faq-toggle"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Tim dukungan kami siap membantu 24/7 melalui live chat, email, atau telepon. Kami juga memiliki pusat bantuan yang lengkap dengan panduan dan tutorial.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta">
        <div class="container">
            <h2>Siap Memulai?</h2>
            <p>Bergabunglah dengan ribuan pelanggan yang puas dan rasakan perbedaannya hari ini.</p>
            <a href="https://san27.pages.dev/sn/" class="btn">Daftar Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Tentang Kami</h3>
                    <p>Kami adalah perusahaan teknologi yang berdedikasi untuk menyediakan solusi inovatif yang membantu bisnis tumbuh dan berkembang.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Tautan Cepat</h3>
                    <ul>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#how-it-works">Cara Kerja</a></li>
                        <li><a href="#pricing">Harga</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Produk</h3>
                    <ul>
                        <li><a href="#">Versi Terbaru</a></li>
                        <li><a href="#">Pembaruan</a></li>
                        <li><a href="#">Integrasi</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Aplikasi Mobile</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Kontak</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Contoh No. 123, Jakarta</li>
                        <li><i class="fas fa-phone"></i> +62 123 4567 890</li>
                        <li><i class="fas fa-envelope"></i> info@contoh.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 Nama Perusahaan. Semua Hak Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
        
        // Smooth Scrolling for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Close mobile menu if open
                    navLinks.classList.remove('active');
                    
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // FAQ Accordion
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
            });
        });
        
        // Sticky Header on Scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>