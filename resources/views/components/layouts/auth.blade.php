<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EzzyIndustri - Login</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a2463 0%, #1e88e5 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 20px;
        }

        /* ================= CONTAINER ================= */
        .login-container {
            flex: 1;
            display: flex;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 1000px;
            max-width: 100%;
            margin: auto;
        }

        /* ================= LEFT SIDE ================= */
        .login-image {
            flex: 1;
            background: linear-gradient(45deg, #1a237e, #0d47a1);
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect x='10' y='30' width='15' height='25' fill='%23ffffff33'/%3E%3Crect x='35' y='20' width='15' height='35' fill='%23ffffff33'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .night-scene {
            position: relative;
            text-align: center;
            color: #fff;
            z-index: 1;
            width: 100%;
            height: 100%;
        }

       /* ================= FOUNDER NGINTIP ================= */
        .founder-peek {
            position: absolute;
            opacity: 0.95;
            filter: drop-shadow(0 12px 25px rgba(0,0,0,0.45));
            transition: transform 0.4s ease;
            z-index: 2;
        }

        /* 👀 NGINTIP BAWAH KIRI */
        .founder-bottom {
            bottom: -100px;
            left: 0px;
            width: 300px;
        }

        /* 👀 NGINTIP TENGAH KANAN */
        .founder-top {
            bottom: 90px;
            right: -87px;
            width: 200px;
        }

        /* HOVER EFFECT */
        .login-image:hover .founder-bottom {
            transform: translateY(-12px) rotate(-2deg);
        }

        .login-image:hover .founder-top {
            transform: translateY(-8px) rotate(2deg);
        }

        /* ================= MOON & TEXT ================= */
        .moon {
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 0 25px rgba(255,255,255,.8);
            margin: 60px auto 30px;
        }

        .factory h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .factory p {
            opacity: 0.85;
            font-size: 15px;
        }

        /* ================= RIGHT SIDE ================= */
        .login-form {
            flex: 1;
            padding: 50px 40px;
            background: #fff;
        }

        /* ================= DOC BUTTON ================= */
        .doc-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            backdrop-filter: blur(8px);
            font-weight: 500;
            transition: all .3s ease;
            z-index: 100;
        }

        .doc-button:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        /* ================= FOOTER ================= */
        .login-footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: rgba(255,255,255,0.8);
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .login-image {
                display: none;
            }

            .login-container {
                max-width: 420px;
            }

            .login-form {
                padding: 40px 25px;
            }
        }
    </style>
</head>

<body>

    <a href="{{ route('documentation') }}" class="doc-button">
        Documentation
    </a>

    <div class="login-container">
        <div class="login-image">
            <div class="night-scene">

                <!-- 👀 BOS NGINTIP BAWAH -->
                    <img src="/assets/img/user_bottom.png"
                        alt="Founder Bottom"
                        class="founder-peek founder-bottom">

                    <!-- 👀 BOS NGINTIP ATAS -->
                    <img src="/assets/img/user_top.png"
                        alt="Founder Top"
                        class="founder-peek founder-top">



                <div class="moon"></div>

                <div class="factory">
                    <h2>EzzyIndustri</h2>
                    <p>Sistem Manajemen Produksi Modern</p>
                </div>
            </div>
        </div>

        <!-- SLOT FORM LOGIN -->
        <div class="login-form">
            {{ $slot }}
        </div>
    </div>

    <footer class="login-footer">
        <p>
            &copy; 2025 Ezzy Industri — Abul Azizurrohman<br>
            Built with ❤️ + AI assistance
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
