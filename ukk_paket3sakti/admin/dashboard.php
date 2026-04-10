<?php
session_start();
include '../config/koneksi.php';

// Cek login & role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$nama = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Pengaduan Sekolah</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* SIDEBAR GLASS */
        .sidebar {
            width: 260px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 40px 20px;
            position: fixed;
            height: 100vh;
            border-right: 1px solid var(--glass-border);
            z-index: 1000;
            transition: 0.3s;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 40px;
            text-align: center;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #cbd5e1;
            border-radius: 12px;
            transition: 0.3s;
            gap: 12px;
        }

        .sidebar a:hover {
            background: var(--glass);
            color: white;
            transform: translateX(5px);
        }

        .sidebar a.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }

        /* MAIN SECTION */
        .main {
            margin-left: 260px;
            width: 100%;
            transition: 0.3s;
        }

        /* TOPBAR */
        .topbar {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar .user-info {
            background: var(--glass);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* CONTENT */
        .content {
            padding: 40px;
            animation: slideUp 0.6s ease;
        }

        /* CARD TABLE */
        .card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* TABLE RESPONSIVE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th {
            background: rgba(255, 255, 255, 0.03);
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            font-weight: 700;
            padding: 16px;
            text-align: left;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--glass-border);
            color: #e2e8f0;
            font-size: 14px;
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* ROLE BADGE */
        .role {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.5px;
        }

        .admin { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
        .petugas { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .siswa { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }

        /* ANIMATION */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* RESPONSIVE MOBILE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                bottom: 0;
                top: auto;
                padding: 10px;
                border-right: none;
                border-top: 1px solid var(--glass-border);
                flex-direction: row;
                display: flex;
                justify-content: space-around;
                background: rgba(15, 23, 42, 0.95);
            }

            .sidebar h2 { display: none; }
            .sidebar a { margin-bottom: 0; padding: 12px; font-size: 10px; flex-direction: column; gap: 4px; border-radius: 8px; }
            .sidebar a span { display: block; }
            .sidebar a i { font-size: 1.2rem; }

            .main { margin-left: 0; padding-bottom: 80px; }
            .topbar { padding: 15px 20px; }
            .content { padding: 20px; }
            .card { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>👑 Admin</h2>
    <a href="dashboard.php" class="active">
        <i class="fas fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>
    <a href="../auth/logout.php" style="color: #fca5a5; margin-top: auto; background: rgba(239, 68, 68, 0.1);">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

<div class="main">

    <div class="topbar">
        <div style="font-weight: 700; font-size: 1.1rem; letter-spacing: -0.5px;">👑 Admin Panel</div>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nama); ?></span>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-header">
                <h2>Kelola Pengguna</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role / Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($u = mysqli_fetch_assoc($users)) { 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($u['nama']); ?></td>
                            <td style="color: #94a3b8;">@<?= htmlspecialchars($u['username']); ?></td>
                            <td>
                                <span class="role <?= $u['role']; ?>">
                                    <i class="fas fa-circle" style="font-size: 6px; vertical-align: middle; margin-right: 5px;"></i>
                                    <?= strtoupper($u['role']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>