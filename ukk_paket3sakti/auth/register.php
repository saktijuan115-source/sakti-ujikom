<?php
session_start();
include '../config/koneksi.php';

$pesan = "";

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "❌ Username sudah digunakan!";
    } else {
        $query = "INSERT INTO users (nama, username, password, role) 
                  VALUES ('$nama','$username','$password','siswa')";
        
        if (mysqli_query($conn, $query)) {
            $pesan = "✅ Registrasi berhasil! Silakan login.";
        } else {
            $pesan = "❌ Gagal: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pengaduan Sekolah</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #3b82f6;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.12);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            background: radial-gradient(circle at top left, #4f46e5, #1e1b4b);
            color: var(--white);
            overflow-x: hidden;
        }

        /* Responsivitas Container Utama */
        .wrapper {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* LEFT SIDE */
        .left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            z-index: 1;
        }

        .left h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .left p {
            font-size: 1.1rem;
            color: #c7d2fe;
            max-width: 450px;
            line-height: 1.6;
        }

        /* RIGHT SIDE */
        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* CARD ELEGAN */
        .card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 24px;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        h2 {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        /* INPUT GROUP */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            outline: none;
            background: rgba(0, 0, 0, 0.2);
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            background: rgba(0, 0, 0, 0.3);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* BUTTON */
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: var(--white);
            color: var(--primary);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background: #f8fafc;
        }

        .btn:active {
            transform: translateY(0);
        }

        /* MESSAGE */
        .msg {
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            background: rgba(0,0,0,0.2);
        }

        .success { color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
        .error { color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #cbd5e1;
        }

        .footer a {
            color: var(--white);
            font-weight: 700;
            text-decoration: none;
            margin-left: 5px;
            transition: 0.2s;
        }

        .footer a:hover {
            text-decoration: underline;
            color: #a5b4fc;
        }

        /* RESPONSIVE BREAKPOINTS */
        @media (max-width: 968px) {
            .wrapper { flex-direction: column; text-align: center; padding: 40px 20px; }
            .left { padding: 0 0 40px 0; align-items: center; }
            .left p { margin: auto; }
            .card { max-width: 100%; }
        }

        @media (max-width: 480px) {
            .card { padding: 30px 20px; }
            .left h1 { font-size: 2.2rem; }
        }

        /* ANIMATIONS */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- LEFT -->
    <div class="left">
        <h1>📢 Pengaduan<br>Sekolah</h1>
        <p>Platform aspirasi siswa untuk menciptakan lingkungan sekolah yang lebih baik, aman, dan nyaman bagi kita semua.</p>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="card">
            <h2>📝 Register</h2>

            <?php if ($pesan != "") { ?>
                <div class="msg <?= strpos($pesan, 'berhasil') !== false ? 'success' : 'error' ?>">
                    <?= $pesan ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="input-group">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>

                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button class="btn" name="register">Daftar Sekarang</button>
            </form>

            <div class="footer">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>