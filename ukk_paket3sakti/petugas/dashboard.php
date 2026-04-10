<?php
session_start();
include '../config/koneksi.php';

// Cek login & role petugas
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'petugas') {
    header("Location: ../auth/login.php");
    exit;
}

// Update status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    mysqli_query($conn, "UPDATE pengaduan SET status='$status' WHERE id='$id'");
    header("Location: dashboard.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM pengaduan ORDER BY id DESC");
$nama = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas | Sistem Pengaduan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #3b82f6;
            --accent: #f43f5e;
            --bg-dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.03);
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
        }

        /* SIDEBAR */
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
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar h2 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #94a3b8;
            border-radius: 12px;
            transition: 0.3s;
            gap: 12px;
            font-weight: 500;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--glass);
            color: white;
            transform: translateX(5px);
        }

        /* MAIN */
        .main {
            margin-left: 260px;
            width: 100%;
            transition: 0.3s;
        }

        /* TOPBAR */
        .topbar {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(10px);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .user-pill {
            background: var(--glass);
            padding: 8px 16px;
            border-radius: 30px;
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        /* CONTENT */
        .content { padding: 40px; animation: fadeIn 0.8s ease; }

        .card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .card h2 { font-size: 1.5rem; margin-bottom: 25px; font-weight: 700; }

        /* TABLE CUSTOM */
        .table-container { overflow-x: auto; border-radius: 16px; }

        table { width: 100%; border-collapse: collapse; min-width: 800px; }

        th {
            background: rgba(255, 255, 255, 0.05);
            padding: 16px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
        }

        td {
            padding: 20px 16px;
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
            font-size: 14px;
        }

        /* IMAGE HOVER */
        .img-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 10px;
            cursor: zoom-in;
            transition: 0.3s;
            border: 2px solid var(--glass-border);
        }

        .img-preview:hover { transform: scale(2.5); z-index: 10; position: relative; box-shadow: 0 10px 20px rgba(0,0,0,0.5); }

        /* STATUS BADGE */
        .status {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .proses { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .selesai { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

        /* ACTION BUTTONS */
        .btn-group { display: flex; gap: 8px; justify-content: center; }

        .btn {
            padding: 8px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-proses { background: var(--secondary); color: white; }
        .btn-selesai { background: #10b981; color: white; }
        .btn:hover { transform: translateY(-2px); filter: brightness(1.2); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }

        /* ANIMATION */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 40px 10px;
            }
            .sidebar h2, .sidebar a span { display: none; }
            .sidebar a { justify-content: center; padding: 15px; }
            .main { margin-left: 70px; }
            .topbar { padding: 15px 20px; }
            .content { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🛠️ <span>Petugas</span></h2>
    <a href="dashboard.php" class="active">
        <i class="fas fa-th-large"></i>
        <span>Dashboard</span>
    </a>
    <a href="../auth/logout.php" style="margin-top: auto; color: #f87171;">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

<div class="main">
    <div class="topbar">
        <div style="font-weight: 700; color: #94a3b8;">SISTEM PENGADUAN</div>
        <div class="user-pill">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nama); ?></span>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <h2>Daftar Laporan Masuk</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Isi Laporan</th>
                            <th width="100">Foto</th>
                            <th width="120">Status</th>
                            <th width="200" style="text-align: center;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($d = mysqli_fetch_assoc($data)) { 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td style="line-height: 1.6;"><?= htmlspecialchars($d['isi']); ?></td>
                            <td>
                                <?php if ($d['foto']) { ?>
                                    <img class="img-preview" src="../upload/<?= $d['foto']; ?>" alt="Lampiran">
                                <?php } else { echo "<span style='color:#64748b'>-</span>"; } ?>
                            </td>
                            <td>
                                <span class="status <?= $d['status']; ?>">
                                    <?= strtoupper($d['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-proses" href="?id=<?= $d['id']; ?>&status=proses">
                                        <i class="fas fa-spinner"></i> Proses
                                    </a>
                                    <a class="btn btn-selesai" href="?id=<?= $d['id']; ?>&status=selesai">
                                        <i class="fas fa-check-double"></i> Selesai
                                    </a>
                                </div>
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