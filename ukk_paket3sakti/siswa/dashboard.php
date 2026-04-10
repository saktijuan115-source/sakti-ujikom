<?php
session_start();

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek role siswa
if ($_SESSION['user']['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$nama = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #6366f1;
            --accent: #f43f5e;
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* Animated Background Gradients */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.15) 0%, transparent 40%);
            z-index: -1;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR MODERN */
        .sidebar {
            width: 280px;
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 40px 20px;
            position: fixed;
            height: 100vh;
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 40px;
            padding-left: 15px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            margin-bottom: 8px;
            text-decoration: none;
            color: #cbd5e1;
            border-radius: 14px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(5px);
        }

        /* Logout khusus */
        .sidebar a.logout {
            margin-top: auto;
            color: #fca5a5;
            border: 1px solid rgba(252, 165, 165, 0.1);
        }

        .sidebar a.logout:hover {
            background: rgba(244, 63, 94, 0.1);
            border-color: rgba(244, 63, 94, 0.3);
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 40px;
            animation: fadeIn 0.8s ease;
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 15px 30px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            font-weight: 600;
            color: #94a3b8;
        }

        .topbar span.user-name {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
        }

        /* WELCOME SECTION */
        .welcome {
            margin-bottom: 40px;
        }

        .welcome h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .welcome p {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        /* GRID SYSTEM */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        /* CARD PREMIUM */
        .card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.03), transparent);
            transform: translateX(-100%);
            transition: 0.6s;
        }

        .card:hover::before {
            transform: translateX(100%);
        }

        .card:hover {
            transform: translateY(-12px);
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .card a {
            text-decoration: none;
            color: white;
            display: block;
        }

        .icon {
            font-size: 55px;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));
            display: inline-block;
            transition: 0.3s;
        }

        .card:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: block;
        }

        .desc {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.5;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 40px 10px; align-items: center; }
            .sidebar h2, .sidebar a span { display: none; }
            .main { margin-left: 80px; width: calc(100% - 80px); }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                bottom: 0;
                top: auto;
                flex-direction: row;
                justify-content: space-around;
                padding: 15px;
                border-right: none;
                border-top: 1px solid var(--glass-border);
                border-radius: 25px 25px 0 0;
            }

            .sidebar h2 { display: none; }
            .sidebar a { margin-bottom: 0; padding: 12px; }
            .sidebar a.logout { margin-top: 0; }

            .main {
                margin-left: 0;
                width: 100%;
                padding: 25px;
                padding-bottom: 100px; /* space for mobile nav */
            }

            .topbar {
                font-size: 0.8rem;
                padding: 12px 20px;
            }

            .welcome h2 { font-size: 1.8rem; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="wrapper">

    <div class="sidebar">
        <h2>📢 <span>Siswa</span></h2>

        <a href="dashboard.php">🏠 <span>Dashboard</span></a>
        <a href="kirim.php">📤 <span>Kirim Laporan</span></a>
        <a href="riwayat.php">📊 <span>Riwayat</span></a>
        
        <a href="../auth/logout.php" class="logout">Door <span>Logout</span></a>
    </div>

    <div class="main">

        <div class="topbar">
            <div>🎓 Dashboard</div>
            <div class="user-name">👤 <?= htmlspecialchars($nama); ?></div>
        </div>

        <div class="welcome">
            <h2>Selamat datang, <?= explode(' ', htmlspecialchars($nama))[0]; ?>! 👋</h2>
            <p>Ada aspirasi atau masalah? Laporkan sekarang untuk sekolah yang lebih baik.</p>
        </div>

        <div class="grid">

            <div class="card">
                <a href="kirim.php">
                    <div class="icon">📤</div>
                    <span class="title">Kirim Pengaduan</span>
                    <p class="desc">Sampaikan keluhan atau saran Anda secara langsung dan aman.</p>
                </a>
            </div>

            <div class="card">
                <a href="riwayat.php">
                    <div class="icon">📊</div>
                    <span class="title">Riwayat Laporan</span>
                    <p class="desc">Pantau status dan tanggapan admin terhadap laporan Anda.</p>
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html>