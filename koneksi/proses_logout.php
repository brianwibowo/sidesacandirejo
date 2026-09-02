<?php
session_start();
unset($_SESSION['r3su']);
unset($_SESSION['username']);
unset($_SESSION['gambar']);
unset($_SESSION['nama']);
unset($_SESSION['id']);
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar Sistem</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated background particles */
        .bg-particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float linear infinite;
        }

        @keyframes float {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }
    </style>
</head>
<body>

<div class="bg-particles" id="particles"></div>

<script>
    // Generate floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 20; i++) {
        const p = document.createElement('div');
        p.classList.add('particle');
        const size = Math.random() * 60 + 10;
        p.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            left: ${Math.random() * 100}%;
            animation-duration: ${Math.random() * 15 + 8}s;
            animation-delay: ${Math.random() * 10}s;
        `;
        container.appendChild(p);
    }

    // SweetAlert2 logout popup
    Swal.fire({
        title: 'Berhasil Keluar!',
        html: `
            <div style="font-family: 'Poppins', sans-serif;">
                <p style="color:#555; font-size:15px; margin-bottom:6px;">Anda telah keluar dari sistem.</p>
                <p style="color:#888; font-size:13px;">Mengalihkan ke halaman utama...</p>
            </div>
        `,
        icon: 'success',
        iconColor: '#4ade80',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        background: '#fff',
        color: '#1a1a2e',
        customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-custom-title',
        },
        didOpen: () => {
            // Customize timer progress bar color
            const timerBar = Swal.getTimerProgressBar();
            if (timerBar) {
                timerBar.style.background = 'linear-gradient(90deg, #4ade80, #22d3ee)';
                timerBar.style.height = '5px';
            }
        },
        willClose: () => {
            window.location.href = '../';
        }
    });
</script>

<style>
    /* SweetAlert2 custom overrides */
    .swal-custom-popup {
        border-radius: 20px !important;
        padding: 30px 20px !important;
        box-shadow: 0 25px 60px rgba(0,0,0,0.35) !important;
        font-family: 'Poppins', sans-serif !important;
        border: 1px solid rgba(74, 222, 128, 0.2) !important;
        animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }

    @keyframes popIn {
        from { transform: scale(0.7); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }

    .swal-custom-title {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 700 !important;
        font-size: 22px !important;
        color: #1a1a2e !important;
    }

    .swal2-icon.swal2-success {
        border-color: #4ade80 !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border-color: rgba(74, 222, 128, 0.3) !important;
    }
</style>

</body>
</html>