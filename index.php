<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="asset/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="asset/dist/css/adminlte.min.css">
    <title>Real Time Man Power | Mutasi Karyawan</title>
    <link href="asset/img/k-logo.jpg" rel="icon">
    <style>
        .header {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
        }

        .header img {
            width: 150px;
            margin-right: 20px;
            margin-left: 140px;
        }

        .header h1 {
            font-size: 1.9rem;
            font-weight: bold;
        }

        .home-illustration {
            padding: 100px 150px;
            /* Add padding to move content down and to the right */
        }

        .home-illustration .content {
            margin-top: 20px;
        }

        .smaller-image {
            width: 40%;
            margin-left: 100px;
        }

        .red-line::after {
            content: "";
            display: block;
            width: 65%;
            height: 5px;
            background-color: red;
            margin-top: 5px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                padding: 10px;
            }

            .header img {
                width: 100px;
                margin-left: 0;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .home-illustration {
                padding: 50px 20px;
                text-align: center;
            }

            .home-illustration .content {
                margin-top: 10px;
            }

            .smaller-image {
                width: 80%;
                margin-left: 0;
            }

            .nav-link {
                width: 100%;
                text-align: center;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="asset/img/kayaba-logo.png" alt="Logo"> <!-- Adjust the src to your logo image path -->
        <h1>PT KAYABA INDONESIA</h1>
    </div>

    <!-- Main Content -->
    <div class="d-flex align-items-center home-illustration">
        <div class="w-50 content">
            <div class="d-flex align-items-center">
                <h1>
                    <span class="fw-bold red-line" style="font-size: 3rem">Selamat Datang Di</span>
                    <hr class="w-25 main-hr mb-2 mt-3" />
                    <span class="fw-normal fs-3">Mutasi Karyawan PT Kayaba Indonesia</span>
                </h1>
            </div>
            <div class="mt-2 w-25 d-flex align-items-center">
                <a href="view/login.php" class="nav-link px-4 py-2 btn btn-danger text-light"
                    style="width: 150px;">MASUK</a>
            </div>
        </div>
        <img src="asset/img/loh.jpg" alt="Gambarr" class="smaller-image">
    </div>

    <footer class="main-footer">
        <?php include 'layout/footer.php'; ?>
    </footer>

</body>

</html>