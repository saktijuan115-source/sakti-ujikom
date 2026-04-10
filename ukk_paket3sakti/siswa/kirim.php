<?php
session_start();
include '../config/koneksi.php';

// Cek login & role siswa
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'siswa') {
    header("Location: ../auth/login.php");
    exit;
}

$pesan = "";

if (isset($_POST['kirim'])) {
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $user_id = $_SESSION['user']['id'];

    $foto = "";

    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {
            $foto = time() . "_" . $nama_file;
            move_uploaded_file($tmp, "../upload/" . $foto);
        } else {
            $pesan = "❌ Format foto harus JPG/JPEG/PNG!";
        }
    }

    if ($pesan == "") {
        $query = "INSERT INTO pengaduan (user_id, isi, foto, status) 
                  VALUES ('$user_id','$isi','$foto','pending')";

        if (mysqli_query($conn, $query)) {
            $pesan = "✅ Pengaduan berhasil dikirim!";
        } else {
            $pesan = "❌ Gagal: " . mysqli_error($conn);
        }
    }
}

$nama = $_SESSION['user']['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Pengaduan</title>

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
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
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
            transition: all 0.3s;
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

        /* MAIN CONTENT */
        .main {
            margin-left: 260px;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        /* CONTENT WRAPPER */
        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        /* FORM CARD */
        .card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.6s ease;
        }

        h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
        }

        /* INPUTS */
        textarea {
            width: 100%;
            height: 150px;
            padding: 16px;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            color: white;
            font-size: 15px;
            margin-bottom: 20px;
            resize: none;
            transition: 0.3s;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        input[type="file"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #94a3b8;
        }

        /* PREVIEW IMAGE */
        #preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 14px;
            margin-bottom: 20px;
            display: none;
            border: 2px solid var(--glass-border);
        }

        /* BUTTON */
        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 14px;
            background: #fff;
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            background: #f1f5f9;
        }

        /* ALERT MESSAGES */
        .msg {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            background: rgba(0,0,0,0.2);
        }
        .success { color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.4); }
        .error { color: #f87171; border: 1px solid rgba(248, 113, 113, 0.4); }

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
            .sidebar a { margin-bottom: 0; padding: 10px; font-size: 12px; flex-direction: column; gap: 5px; }
            .sidebar a.logout { margin-top: 0; background: none; color: #f87171; }

            .main { margin-left: 0; padding-bottom: 80px; }
            .topbar { padding: 15px 20px; }
            .card { padding: 25px; border-radius: 0; max-width: 100%; height: 100%; border: none; background: transparent; box-shadow: none; }
            .content { padding: 0; align-items: flex-start; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>📢 Siswa</h2>
    <a href="dashboard.php">🏠 <span>Dashboard</span></a>
    <a href="kirim.php" style="background: rgba(255,255,255,0.1); color: white;">📤 <span>Kirim</span></a>
    <a href="riwayat.php">📊 <span>Riwayat</span></a>
    <a href="../auth/logout.php" class="logout">🚪 <span>Keluar</span></a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div style="font-weight: 700;">📤 Form Pengaduan</div>
        <div class="user">👤 <?= htmlspecialchars($nama); ?></div>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="card">
            <h2>Sampaikan Laporan</h2>

            <?php if ($pesan != "") { ?>
                <div class="msg <?= strpos($pesan, 'berhasil') !== false ? 'success' : 'error' ?>">
                    <?= $pesan ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <textarea name="isi" placeholder="Ceritakan detail masalah atau saran Anda di sini..." required></textarea>

                <label style="font-size: 12px; color: #94a3b8; display: block; margin-bottom: 8px;">Lampirkan Foto (Opsional):</label>
                <input type="file" name="foto" id="file-input" onchange="preview(event)">
                
                <img id="preview" alt="Preview Foto">

                <button name="kirim">Kirim Laporan Sekarang</button>
            </form>
        </div>
    </div>

</div>

<script>
function preview(e){
    const img = document.getElementById('preview');
    if(e.target.files[0]){
        img.src = URL.createObjectURL(e.target.files[0]);
        img.style.display = 'block';
    }
}
</script>

</body>
</html>