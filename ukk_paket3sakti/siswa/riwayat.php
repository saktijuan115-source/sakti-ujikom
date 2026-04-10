<?php
session_start();
include '../config/koneksi.php';

// Cek login & role siswa
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$data = mysqli_query($conn, "SELECT * FROM pengaduan WHERE user_id='$user_id' ORDER BY id DESC");
$nama = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengaduan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --glass: rgba(255, 255, 255, 0.08);
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
            display: flex;
            overflow-x: hidden;
        }

        /* SIDEBAR GLASS */
        .sidebar {
            width: 260px;
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 40px 20px;
            position: fixed;
            height: 100vh;
            border-right: 1px solid var(--glass-border);
            z-index: 100;
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
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar a.logout {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            margin-top: 20px;
        }

        /* MAIN */
        .main {
            margin-left: 260px;
            width: 100%;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
        }

        .topbar .user {
            background: var(--glass);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            border: 1px solid var(--glass-border);
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
            -webkit-backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* TABLE WRAPPER FOR RESPONSIVE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th {
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            font-weight: 700;
            text-align: left;
        }

        th, td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--glass-border);
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* IMAGE */
        .img-preview {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            cursor: pointer;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid var(--glass-border);
        }

        .img-preview:hover {
            transform: scale(2);
            z-index: 10;
            position: relative;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        /* STATUS BADGE */
        .status {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .proses { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .selesai { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

        /* RESPONSIVE */
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
            }

            .sidebar h2 { display: none; }
            .sidebar a { margin-bottom: 0; padding: 12px; font-size: 12px; flex-direction: column; gap: 5px; }
            .sidebar a.logout { margin-top: 0; background: none; color: #f87171; }

            .main { margin-left: 0; padding-bottom: 80px; }
            .topbar { padding: 15px 20px; }
            .content { padding: 20px; }
            .card { padding: 20px; border-radius: 18px; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>📢 Siswa</h2>
    <a href="dashboard.php">🏠 <span>Dashboard</span></a>
    <a href="kirim.php">📤 <span>Kirim</span></a>
    <a href="riwayat.php" style="background: rgba(255,255,255,0.1); color: white;">📊 <span>Riwayat</span></a>
    <a href="../auth/logout.php" class="logout">🚪 <span>Keluar</span></a>
</div>

<div class="main">

    <div class="topbar">
        <div style="font-weight: 700;">📊 Riwayat Pengaduan</div>
        <div class="user">👤 <?= htmlspecialchars($nama); ?></div>
    </div>

    <div class="content">
        <div class="card">
            <h2>📋 Data Pengaduan Anda</h2>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Isi Laporan</th>
                            <th width="100">Foto</th>
                            <th width="150">Status</th>
                            <th width="150">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($d = mysqli_fetch_assoc($data)) { 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td style="line-height: 1.5; color: #e2e8f0;"><?= htmlspecialchars($d['isi']); ?></td>
                            <td>
                                <?php if ($d['foto']) { ?>
                                    <img src="../upload/<?= $d['foto']; ?>" class="img-preview">
                                <?php } else { echo "<span style='color:#64748b'>-</span>"; } ?>
                            </td>
                            <td>
                                <span class="status <?= $d['status']; ?>">
                                    ● <?= strtoupper($d['status']); ?>
                                </span>
                            </td>
                            <td style="color: #94a3b8; font-size: 14px;">
                                <?= date('d M Y', strtotime($d['tanggal'])); ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if(mysqli_num_rows($data) == 0) : ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b; padding: 40px;">
                                    Belum ada riwayat pengaduan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

</body>
</html>