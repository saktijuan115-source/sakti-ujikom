<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];

    if ($role == 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } elseif ($role == 'petugas') {
        header("Location: petugas/dashboard.php");
        exit;
    } elseif ($role == 'siswa') {
        header("Location: siswa/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaduan Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px 40px;
            color: white;
        }

        .logo {
            font-weight: bold;
            font-size: 18px;
        }

        .nav-btn a {
            margin-left: 10px;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .login-btn {
            background: transparent;
            border: 1px solid white;
            color: white;
        }

        .register-btn {
            background: #3b82f6;
            color: white;
        }

        /* HERO */
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px;
            color: white;
        }

        .hero-text {
            max-width: 500px;
            animation: fadeIn 1s ease;
        }

        .hero-text h1 {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .hero-text p {
            color: #cbd5f5;
            margin-bottom: 20px;
        }

        .hero-btn a {
            margin-right: 10px;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #1e3a8a;
        }

        /* CARD INFO */
        .hero-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            width: 300px;
            animation: fadeUp 1s ease;
        }

        .hero-card h3 {
            margin-bottom: 10px;
        }

        .hero-card p {
            font-size: 14px;
            color: #e2e8f0;
        }

        /* ANIMATION */
        @keyframes fadeIn {
            from {opacity:0; transform:translateX(-20px);}
            to {opacity:1; transform:translateX(0);}
        }

        @keyframes fadeUp {
            from {opacity:0; transform:translateY(20px);}
            to {opacity:1; transform:translateY(0);}
        }

        /* RESPONSIVE */
        @media(max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-card {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">📢 Pengaduan Sekolah</div>
    <div class="nav-btn">
        <a href="auth/login.php" class="login-btn">Login</a>
        <a href="auth/register.php" class="register-btn">Register</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">

    <!-- LEFT -->
    <div class="hero-text">
        <h1>Laporkan Masalah Sekolah Secara Mudah & Cepat</h1>
        <p>Sistem pengaduan modern untuk siswa agar suara Anda didengar dan ditindaklanjuti dengan cepat.</p>

        <div class="hero-btn">
            <a href="auth/login.php" class="btn-primary">Mulai Sekarang</a>
            <a href="auth/register.php" class="btn-secondary">Daftar</a>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="hero-card">
        <h3>Kenapa gunakan sistem ini?</h3>
        <p>✔ Proses cepat<br>
           ✔ Transparan<br>
           ✔ Mudah digunakan<br>
           ✔ Bisa upload bukti</p>
    </div>

</div>

</body>
</html>