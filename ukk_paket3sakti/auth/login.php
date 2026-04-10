<?php
session_start();
include '../config/koneksi.php';

$pesan = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $data = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($data);

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user;

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
                exit;
            } elseif ($user['role'] == 'petugas') {
                header("Location: ../petugas/dashboard.php");
                exit;
            } else {
                header("Location: ../siswa/dashboard.php");
                exit;
            }

        } else {
            $pesan = "Password salah!";
        }
    } else {
        $pesan = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Pengaduan Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #6366f1;
        --secondary: #3b82f6;
        --accent: #f43f5e;
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
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at top left, #0f172a, #1e1b4b);
        padding: 20px;
        overflow-x: hidden;
    }

    /* BACKGROUND DECORATION */
    body::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        background: var(--primary);
        filter: blur(120px);
        border-radius: 50%;
        top: 10%;
        left: 10%;
        z-index: -1;
        opacity: 0.3;
    }

    /* WRAPPER */
    .container {
        display: flex;
        width: 950px;
        max-width: 100%;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.8s ease-out;
    }

    /* LEFT SIDE */
    .left {
        flex: 1.2;
        padding: 60px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(59, 130, 246, 0.05));
    }

    .left .icon-box {
        width: 60px;
        height: 60px;
        background: var(--primary);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 24px;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }

    .left h1 {
        font-size: 38px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 15px;
        background: linear-gradient(to right, #fff, #cbd5e1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .left p {
        color: #94a3b8;
        font-size: 16px;
        line-height: 1.6;
    }

    /* RIGHT SIDE */
    .right {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    /* CARD LOGIN */
    .card {
        width: 100%;
        max-width: 380px;
        padding: 0;
        color: white;
    }

    .card h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .card p.subtitle {
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 30px;
    }

    /* INPUT STYLING */
    .input-group {
        margin-bottom: 20px;
        position: relative;
    }

    .input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .input-group input {
        width: 100%;
        padding: 14px 15px 14px 45px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        outline: none;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .input-group input:focus {
        border-color: var(--primary);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .input-group input::placeholder {
        color: #64748b;
    }

    /* PASSWORD TOGGLE */
    .toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        transition: 0.3s;
    }
    .toggle:hover { color: white; }

    /* BUTTON */
    .btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.3);
        filter: brightness(1.1);
    }

    /* ALERT MESSAGE */
    .msg {
        background: rgba(244, 63, 94, 0.1);
        border-left: 4px solid var(--accent);
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        color: #fda4af;
        animation: shake 0.4s ease;
    }

    /* FOOTER */
    .footer {
        text-align: center;
        margin-top: 25px;
        font-size: 14px;
        color: #94a3b8;
    }

    .footer a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer a:hover { text-decoration: underline; }

    /* ANIMATIONS */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* RESPONSIVE */
    @media(max-width: 850px) {
        .container { flex-direction: column; width: 450px; }
        .left { padding: 40px; text-align: center; align-items: center; }
        .left h1 { font-size: 30px; }
        .right { padding: 40px 30px; }
    }

    @media(max-width: 480px) {
        body { padding: 15px; }
        .left h1 { font-size: 26px; }
        .left .icon-box { margin-bottom: 15px; }
    }
</style>
</head>

<body>

<div class="container">

    <div class="left">
        <div class="icon-box">
            <i class="fas fa-bullhorn"></i>
        </div>
        <h1>Pengaduan<br>Sekolah</h1>
        <p>Platform digital untuk menyampaikan aspirasi dan laporan demi lingkungan sekolah yang lebih baik.</p>
    </div>

    <div class="right">
        <div class="card">
            <h2>Selamat Datang</h2>
            <p class="subtitle">Silakan masuk dengan akun Anda</p>

            <?php if ($pesan != "") { ?>
                <div class="msg">
                    <i class="fas fa-exclamation-circle"></i> <?= $pesan ?>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="pass" name="password" placeholder="Password" required>
                    <span class="toggle" onclick="togglePass()">
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </span>
                </div>

                <button class="btn" name="login">Masuk ke Sistem</button>
            </form>

            <div class="footer">
                Belum punya akun? <a href="register.php">Daftar Sekarang</a>
            </div>
        </div>
    </div>

</div>

<script>
function togglePass(){
    const pass = document.getElementById("pass");
    const icon = document.getElementById("toggleIcon");
    if (pass.type === "password") {
        pass.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        pass.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>

</body>
</html>