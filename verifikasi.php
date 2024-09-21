<?php
include __DIR__ . '/query/koneksi.php';
include __DIR__ . '/query/utils.php';

// Example values (update with your actual values)
$encryption_key = 'your_secret_key_32_bytes'; // Ensure this is the same key used for encryption
$iv = 'your_iv_16_bytes'; // Ensure this is the same IV used for encryption

// Retrieve and decrypt values from URL
$encrypted_batchMutasi = isset($_GET['batchMutasi']) ? $_GET['batchMutasi'] : '';
$encrypted_emno = isset($_GET['emno']) ? $_GET['emno'] : '';

// Sanitize inputs
$encrypted_batchMutasi = mysqli_real_escape_string($koneksi3, $encrypted_batchMutasi);
$encrypted_emno = mysqli_real_escape_string($koneksi3, $encrypted_emno);

// Decrypt the values
$batchMutasi = decrypt($encrypted_batchMutasi, $encryption_key, $iv);
$emno = decrypt($encrypted_emno, $encryption_key, $iv);

// Sanitize decrypted values
$batchMutasi = mysqli_real_escape_string($koneksi3, $batchMutasi);
$emno = mysqli_real_escape_string($koneksi3, $emno);

// Query with decrypted values
$query = "SELECT * FROM mutasi WHERE batchMutasi = '$batchMutasi' AND emno = '$emno'";
$result = mysqli_query($koneksi3, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Query error: " . mysqli_error($koneksi3));
}

// Fetch the result as an associative array
$row = mysqli_fetch_assoc($result);

// Ensure $sectAsal and $sectBaru are properly defined before using them
$sectAsal = isset($row['sectAsal']) ? $row['sectAsal'] : '';
$sectBaru = isset($row['sectBaru']) ? $row['sectBaru'] : '';

// Fetch sectAsalDesc from the hrd_sect table using $koneksi2
$querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
$resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
if ($resultSectAsal) {
    $sectAsalData = mysqli_fetch_assoc($resultSectAsal);
    $row['sectAsalDesc'] = $sectAsalData['sectAsalDesc'];
} else {
    $row['sectAsalDesc'] = 'N/A'; // Default value or handle error
}

// Fetch sectBaruDesc from the hrd_sect table using $koneksi2
$querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
$resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
if ($resultSectBaru) {
    $sectBaruData = mysqli_fetch_assoc($resultSectBaru);
    $row['sectBaruDesc'] = $sectBaruData['sectBaruDesc'];
} else {
    $row['sectBaruDesc'] = 'N/A'; // Default value or handle error
}

// Process $row data as needed
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verifikasi Mutasi | <?php echo htmlspecialchars($row['nama']); ?></title>
    <link href="asset/img/k-logo.jpg" rel="icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="asset/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="asset/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="asset/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="asset/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="asset/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <h2>Mutasi dari Departemen
                <?php echo htmlspecialchars($row['cwocAsal']); ?> ke Departemen seksi
                <?php echo htmlspecialchars($row['sectAsalDesc']); ?>
                <?php echo htmlspecialchars($row['cwocBaru']); ?> Seksi
                <?php echo htmlspecialchars($row['sectBaruDesc']); ?>
            </h2>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <div class="card">
                <div class="card-body verification-container">
                    <div>
                        <img src="asset/img/kayaba-logo1.png" alt="Logo" width="200" height="auto">
                    </div>

                    <p>NPK:
                        <?php echo htmlspecialchars($row['emno']); ?>
                    </p>
                    <p>Nama:
                        <?php echo htmlspecialchars($row['nama']); ?>
                    </p>
                    <p class="details">
                        Dimutasikan dari Departemen
                        <?php echo htmlspecialchars($row['cwocAsal'], ENT_QUOTES, 'UTF-8'); ?>
                        seksi
                        <?php echo htmlspecialchars($row['sectAsalDesc'], ENT_QUOTES, 'UTF-8'); ?>
                        ke Departemen
                        <?php echo htmlspecialchars($row['cwocBaru'], ENT_QUOTES, 'UTF-8'); ?>
                        seksi
                        <?php echo htmlspecialchars($row['sectBaruDesc'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>Apakah dapat dimengerti atau dipahami?</p>

                    <div class="social-auth-links text-center">
                        <a id="verifikasiLink" class="btn btn-block btn-social btn-primary btn-flat">
                            <i class="fa fa-check"></i> Paham/Mengerti
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="asset/plugins/jquery/jquery.min.js"></script>
    <script src="asset/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        $('#verifikasiLink').click(function (event) {
            event.preventDefault(); // Prevent default link behavior

            // Data to send to the server
            var batchMutasi = '<?php echo $encrypted_batchMutasi; ?>'; // Use encrypted value
            var emno = '<?php echo $encrypted_emno; ?>'; // Use encrypted value

            // Add no-scroll class to html and body
            $('html').addClass('no-scroll');
            $('body').addClass('no-scroll');

            // Send AJAX request to update the database
            $.ajax({
                url: 'verifikasi1.php', // PHP file to handle the update
                type: 'POST',
                data: {
                    batchMutasi: batchMutasi,
                    emno: emno
                },
                success: function (response) {
                    console.log(response);
                    Swal.fire({
                        title: 'Verifikasi!',
                        text: 'Berhasil melakukan verifikasi mutasi',
                        icon: 'success',
                        timer: 1500, // Auto-close after 1.5 seconds
                        showConfirmButton: false, // Hide the confirm button
                    }).then(() => {
                        $('html').removeClass('no-scroll');
                        $('body').removeClass('no-scroll');
                    });
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred: ' + error,
                        icon: 'error',
                        timer: 1500, // Auto-close after 1.5 seconds
                        showConfirmButton: false, // Hide the confirm button
                    }).then(() => {
                        $('html').removeClass('no-scroll');
                        $('body').removeClass('no-scroll');
                    });
                }
            });
        });


    </script>


</body>

</html>

<style>
    .no-scroll {
        overflow: hidden;
        /* Ensure the body does not scroll */
    }

    .login-box-body {
        border-radius: 10px;
    }


    .btn-primary {
        display: flex;
        align-items: center;
        /* Center icon and text vertically */
        justify-content: center;
        /* Center icon and text horizontally */
        text-align: center;
        /* Center text */
        background-color: #007bff;
        /* Primary blue color */
        color: white;
        border: none;
        /* Remove default border */
    }

    .btn-primary i {
        margin-right: 10px;
        /* Space between icon and text */
    }

    /* Ensure the body takes full height */
    body {
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f4f4f4;
        /* Background color for better visibility */
    }

    .login-box {
        width: 100%;
        max-width: 600px;
        /* Adjust this width as needed */
    }

    .verification-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        /* Make it take full width of its container */
    }

    .check-icon {
        font-size: 5rem;
        color: #28a745;
        /* Green color */
        margin-bottom: 20px;
    }

    .social-auth-links {
        width: 100%;
        /* Make button container take full width */
    }

    .btn-primary {
        display: flex;
        /* Use flexbox for centering */
        align-items: center;
        /* Center items vertically */
        justify-content: center;
        /* Center items horizontally */
        text-align: center;
        /* Center text horizontally */
        background-color: #007bff;
        /* Primary blue color */
        color: white;
        /* Text color */
        border: none;
        /* Remove default border */
        padding: 10px 20px;
        /* Adjust padding for better button appearance */
        font-size: 1.2rem;
        /* Adjust font size if needed */
        border-radius: 4px;
        /* Optional: add rounded corners */
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        /* Smooth transition for hover effects */
        text-decoration: none;
        /* Remove underline from links */
    }

    .btn-primary i {
        margin-right: 10px;
        /* Space between icon and text */
    }

    .btn-primary:hover {
        background-color: #0056b3;
        /* Darker blue color on hover */
        color: #fff;
        /* White text color on hover */
        border: 2px solid #007bff;
        /* Primary border color on hover */
    }

    #verifikasiLink {
        display: block;
        text-align: center;
        padding: 10px;
        font-size: 16px;
        color: #fff;
        /* Primary text color */
        background-color: #007bff;
        /* Default white background */
        border: 2px solid #fff;
        /* Primary border color */
        border-radius: 4px;
        text-decoration: none;
        /* Remove underline from links */
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        /* Smooth transition effect */
    }

    #verifikasiLink:hover {
        background-color: #fff;
        /* Primary background color on hover */
        color: #007bff;
        /* White text color on hover */
        border-color: #007bff;
        /* Primary border color on hover */
    }

    #verifikasiLink i {
        color: #fff;
        /* Primary color for icon */
    }

    #verifikasiLink:hover i {
        color: #007bff;
        /* White color for icon on hover */
    }

    .verification-container p {
        font-size: 1.8rem;
        /* Atur ukuran font sesuai kebutuhan */
    }

    .details {
        font-size: 1.1rem;
        font-weight: bold;
        color: #343a40;
        text-align: center;
        /* Center the text horizontally */
    }


    .login-logo h2 {
        font-size: 2.5rem;
        /* Ukuran font yang lebih besar */
        color: #343a40;
        /* Warna teks */
        margin-bottom: 10px;
        /* Jarak bawah */
        font-weight: bolder;
        /* Membuat teks menjadi tebal */
    }

    .card {
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .card-body {
        padding: 20px;
    }
</style>