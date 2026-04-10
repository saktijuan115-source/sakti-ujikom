<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Berhasil</title>
    <meta http-equiv="refresh" content="2;url=login.php">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            color: white;
            overflow: hidden;
        }

        /* Animated Background Gradients */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 70% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 30% 70%, rgba(59, 130, 246, 0.15) 0%, transparent 40%);
            z-index: -1;
        }

        /* CARD GLASSMORPHISM */
        .card {
            background: var(--glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 50px 40px;
            border-radius: 30px;
            text-align: center;
            width: 90%;
            max-width: 380px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* ICON CHECKMARK */
        .icon-container {
            width: 80px;
            height: 80px;
            background: rgba(34, 197, 94, 0.2);
            border: 2px solid rgba(34, 197, 94, 0.5);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
            font-size: 35px;
            color: #4ade80;
            animation: popCheck 0.5s 0.3s both ease;
        }

        h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        p {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 25px;
        }

        /* MODERN LOADING DOTS */
        .dots {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .dots span {
            width: 10px;
            height: 10px;
            background: #6366f1;
            border-radius: 50%;
            display: inline-block;
            animation: dotWave 1.4s infinite ease-in-out both;
        }

        .dots span:nth-child(1) { animation-delay: -0.32s; }
        .dots span:nth-child(2) { animation-delay: -0.16s; }

        /* ANIMATIONS */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes popCheck {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes dotWave {
            0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* RESPONSIVE */
        @media (max-width: 480px) {
            .card {
                padding: 40px 30px;
                border-radius: 24px;
            }
            h2 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon-container">
        ✓
    </div>

    <h2>Logout Berhasil</h2>
    <p>Sesi Anda telah diakhiri secara aman.<br>Mengarahkan Anda ke halaman login...</p>

    <div class="dots">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

</body>
</html>