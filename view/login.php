<?php
require_once(__DIR__ . '/../query/koneksi.php');
require __DIR__ . '/../asset/library/autoload.php';
include __DIR__ . '/../asset/phpPasswordHashing/passwordLib.php';

session_start();

function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize variables
$npk = $password = $captcha = "";
$error_msg = "";
$show_otp_modal = false;
$otp_code = "";
$redirect_url = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npk = input($_POST['npk']);
    $password = input($_POST['password']);
    $captcha = input($_POST['captcha']); // Input for captcha

    if (empty($npk) || empty($password) || empty($captcha)) {
        $error_msg = "All fields are required!";
    } else {
        // Query for user authentication
        $sql = "SELECT * FROM ct_users WHERE npk='$npk'";
        $result = mysqli_query($koneksi2, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['pwd'])) {
                if ($_SESSION["captcha_code"] !== $captcha) {
                    $error_msg = "Your CAPTCHA code is wrong!";
                    $captcha = ""; // Clear captcha input if incorrect
                } else {
                    // Generate OTP
// Generate a 6-digit OTP code
                    $otp_code = sprintf('%06d', mt_rand(0, 999999));
                    $_SESSION["otp_code"] = $otp_code;

                    // Fetch no_hp from isd table
                    $sql_no_hp = "SELECT no_hp 
              FROM hp  
              WHERE npk = '$npk'";
                    $result_no_hp = mysqli_query($koneksi4, $sql_no_hp);

                    if ($no_hp_row = mysqli_fetch_assoc($result_no_hp)) {
                        $no_hp = $no_hp_row['no_hp'];
                    } else {
                        $no_hp = ''; // Set no_hp to empty if no data found
                    }

                    // Check if npk exists in the otp table
                    $sql_check = "SELECT COUNT(*) as count FROM otp WHERE npk = '$npk'";
                    $result_check = mysqli_query($koneksi3, $sql_check);
                    $check_row = mysqli_fetch_assoc($result_check);

                    if ($check_row['count'] > 0) {
                        // npk exists, perform an update
                        $sql_update = "UPDATE otp 
                   SET otp = '$otp_code', no_hp = '$no_hp', send = '2', `use` = '2' 
                   WHERE npk = '$npk'";
                        if (mysqli_query($koneksi3, $sql_update)) {
                            // Optionally log success or perform additional actions
                        } else {
                            echo "Error updating OTP: " . mysqli_error($koneksi3) . "<br>";
                        }
                    } else {
                        // npk doesn't exist, perform an insert
                        $sql_insert = "INSERT INTO otp (npk, otp, no_hp, send, `use`) 
                   VALUES ('$npk', '$otp_code', '$no_hp', '2', '2')";
                        if (mysqli_query($koneksi3, $sql_insert)) {
                            // Optionally log success or perform additional actions
                        } else {
                            echo "Error inserting OTP: " . mysqli_error($koneksi3) . "<br>";
                        }
                    }



                    // Set session variables
                    $_SESSION["npk"] = $row["npk"];
                    $_SESSION["golongan"] = $row["golongan"];
                    $_SESSION["name"] = $row["full_name"];
                    $_SESSION["dept"] = $row["dept"];

                    $otpSentTime = time(); // Waktu pengiriman OTP saat ini dalam detik
                    $_SESSION['otp_sent_time'] = $otpSentTime; // Simpan waktu pengiriman OTP di session


                    $gol = $row['golongan'];
                    $act = $row['acting'];
                    $dept = $row['dept'];

                    $sql_hrd = "SELECT * FROM hrd_so WHERE npk='$npk'";
                    $result_hrd = mysqli_query($koneksi2, $sql_hrd);

                    $tipe = ''; // Initialize $tipe variable
                    if ($hrd_row = mysqli_fetch_assoc($result_hrd)) {
                        $tipe = $hrd_row['tipe']; // Assign value to $tipe
                        $_SESSION["tipe"] = $hrd_row["tipe"];

                        if ($tipe == '3') {
                            $_SESSION['role'] = "Kepala Divisi";
                            $redirect_url = "FormMutasi/dashboard.php";
                            $_SESSION["redirect_url"] = $redirect_url;
                            $show_otp_modal = true; // Pastikan ini diatur ke true
                        }
                    }

                    // Set role based on conditions
                    if ($tipe == 1 && $dept == 'HRD IR') {
                        $_SESSION['role'] = "Kepala Departemen HRD";
                        $redirect_url = "MPS/dashboard.php";
                    } elseif ($tipe == 1 && $dept != 'HRD IR') {
                        $_SESSION['role'] = "Kepala Departemen";
                        $redirect_url = "FormMutasi/dashboard.php";
                    } elseif ($gol == 3 && $act == 2 && $dept == 'HRD IR') {
                        $_SESSION['role'] = "Foreman HRD";
                        $redirect_url = "MPS/dashboard.php";
                    } elseif ($gol == 4 && $act == 2 && $dept == 'HRD IR') {
                        $_SESSION['role'] = "Supervisor HRD";
                        $redirect_url = "MPS/dashboard.php";
                    } elseif ($gol == 3 && $act == 2 && $dept != 'HRD IR') {
                        $_SESSION['role'] = "Foreman";
                        $redirect_url = "FormMutasi/dashboard.php";
                    } elseif ($gol == 4 && $act == 2 && $dept != 'HRD IR') {
                        $_SESSION['role'] = "Supervisor";
                        $redirect_url = "FormMutasi/dashboard.php";
                    } elseif ($dept == 'BOD PLANT') {
                        $_SESSION['role'] = "Direktur Plant";
                        $redirect_url = "FormMutasi/dashboard.php";
                    } elseif ($dept == 'BOD Non Plant') {
                        $_SESSION['role'] = "Direktur Non Plant";
                        $redirect_url = "FormMutasi/dashboard.php";
                    } elseif ($gol <= 2) {
                        $_SESSION['role'] = "Operator";
                        $redirect_url = "forbidden.php";
                    }
                    $_SESSION["redirect_url"] = $redirect_url;

                    // Show OTP modal
                    $show_otp_modal = true;
                }
            } else {
                $error_msg = "Wrong Password!";
                $password = ""; // Clear password and captcha input if password is incorrect
                $captcha = "";
            }
        } else {
            $error_msg = "NPK not found!";
            $npk = $password = $captcha = ""; // Clear all inputs if NPK not found
        }
    }
}
mysqli_close($koneksi2);
?>

<?php
// Function to mask the phone number
function maskPhoneNumber($phone)
{
    // Get the first 4 characters
    $firstPart = substr($phone, 0, 4);
    // Get the rest of the characters and replace them with asterisks
    $maskedPart = str_repeat('*', strlen($phone) - 4);
    return $firstPart . $maskedPart;
}
?>

<?php
// Other PHP code...

// Check for OTP error message
$otp_error_msg = isset($_SESSION['otp_error']) ? $_SESSION['otp_error'] : "";
unset($_SESSION['otp_error']); // Clear the message after displaying it

// Other PHP code...
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Real Time Man Power | Login</title>
    <link href="../asset/img/k-logo.jpg" rel="icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../asset/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../asset/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-danger">
            <div class="card-header text-center">
                <img src="../asset/img/kayaba-logo.png" alt=""
                    style="width: 260px; margin-bottom: -60px; margin-top: -50px;">
            </div>
            <div class="card-body">
                <p class="login-box-msg">Mutasi Karyawan</p>

                <?php if (!empty($error_msg)): ?>
                <div id="error-alert" class="alert alert-danger"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="npk" class="form-control" placeholder="NPK" maxlength="5"
                            value="<?php echo htmlspecialchars($npk); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password-input" class="form-control"
                            placeholder="Password" value="<?php echo htmlspecialchars($password); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye" id="password-toggle" style="cursor: pointer;"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="captcha-container">
                            <img src="../captcha.php" alt="Captcha Code" id="captchaImage" class="captcha-image"
                                style="height: 55px; margin-right: 5px;">
                            <div class="input-group">
                                <input type="text" class="form-control" name="captcha"
                                    placeholder="Masukkan kode captcha" maxlength="6"
                                    value="<?php echo htmlspecialchars($captcha); ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-sync-alt" id="refreshCaptcha"
                                            style="cursor: pointer;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button type="submit" class="btn btn-block btn-danger">MASUK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($show_otp_modal): ?>
    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Masukan OTP</h5>
                </div>
                <div class="modal-body">
                    <p>Silakan masukkan kode OTP</p>
                    <div class="alert alert-info">OTP telah dikirim ke:
                        <strong><?php echo htmlspecialchars(maskPhoneNumber($no_hp)); ?></strong>
                        <strong><?php echo htmlspecialchars($otp_code); ?></strong>
                    </div>
                    <form id="otpForm" method="POST">
                        <div class="otp-container">
                            <input type="text" name="otp1" maxlength="1" class="otp-field" required>
                            <input type="text" name="otp2" maxlength="1" class="otp-field" required>
                            <input type="text" name="otp3" maxlength="1" class="otp-field" required>
                            <input type="text" name="otp4" maxlength="1" class="otp-field" required>
                            <input type="text" name="otp5" maxlength="1" class="otp-field" required>
                            <input type="text" name="otp6" maxlength="1" class="otp-field" required>
                        </div>

                        <div id="otpError" class="text-danger"></div>
                        <div id="countdown" class="text-primary">300 detik tersisa</div>
                        <div id="resendOtp" class="text-danger d-none" style="cursor: pointer;">Kirim Ulang OTP</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="verifyBtn" class="btn btn-primary btn-disabled" disabled>Verifkasi
                        OTP</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="../asset/plugins/jquery/jquery.min.js"></script>
    <script src="../asset/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../asset/dist/js/adminlte.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#otpModal').modal('show');

        // Ambil waktu pengiriman OTP dari PHP
        var otpSentTime = <?php echo $_SESSION['otp_sent_time']; ?>;
        var otpExpireTime = otpSentTime + 300; // 60 detik
        var now = Math.floor(Date.now() / 1000);
        // Fungsi untuk menghitung dan menampilkan countdown
        function updateCountdown() {
            var timeRemaining = otpExpireTime - Math.floor(Date.now() / 1000);

            if (timeRemaining <= 0) {
                $('#countdown').text('OTP has expired.');
                $('#resendOtp').removeClass('d-none').off('click').on('click', function() {
                    $.ajax({
                        type: 'POST',
                        url: 'resend_otp.php',
                        data: {
                            npk: '<?php echo htmlspecialchars($npk); ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#countdown').text('New OTP has been sent.');
                                otpSentTime = Math.floor(Date.now() /
                                    1000); // Update waktu pengiriman OTP
                                otpExpireTime = otpSentTime +
                                    300; // Update waktu kadaluarsa
                                $('#resendOtp').addClass('d-none');
                                updateCountdown(); // Mulai countdown baru
                            } else {
                                $('#otpError').text(response.message).removeClass('d-none');
                            }
                        },
                        error: function() {
                            $('#otpError').text('An error occurred. Please try again.')
                                .removeClass('d-none');
                            setTimeout(function() {
                                $('#otpError').addClass('d-none');
                            }, 3000);
                        }
                    });
                });
            } else {
                $('#countdown').text(timeRemaining + ' detik tersisa');
                setTimeout(updateCountdown, 1000); // Update countdown setiap detik
            }
        }

        updateCountdown();

        // Aktifkan tombol "Verify OTP" hanya jika semua field OTP diisi
        $('#otpForm').on('input', function() {
            let allFilled = true;
            $('#otpForm .otp-field').each(function() {
                if ($(this).val().length === 0) {
                    allFilled = false;
                }
            });
            $('#verifyBtn').prop('disabled', !allFilled);
            $('#verifyBtn').toggleClass('btn-disabled', !allFilled);
        });

        // Opsional: Fokus otomatis ke field OTP berikutnya
        document.querySelectorAll('.otp-field').forEach((field, index, fields) => {
            field.addEventListener('input', () => {
                if (field.value.length === 1 && index < fields.length - 1) {
                    fields[index + 1].focus();
                }
            });
        });

        $('#otpForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'verify_otp.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirect_url;
                    } else {
                        // Reset field OTP
                        $('#otpForm .otp-field').val('');
                        $('#otpForm .otp-field').first().focus();
                        // Tampilkan pesan kesalahan dan sembunyikan setelah 3 detik
                        $('#otpError').text(response.message).removeClass('d-none');
                        setTimeout(function() {
                            $('#otpError').addClass('d-none');
                        }, 3000);
                    }
                },
                error: function() {
                    $('#otpError').text('An error occurred. Please try again.').removeClass(
                        'd-none');
                    setTimeout(function() {
                        $('#otpError').addClass('d-none');
                    }, 3000);
                }
            });
        });
    });
    </script>


    <script>
    // Function to refresh the captcha image
    function refreshCaptcha() {
        const captchaImage = document.getElementById('captchaImage');
        const timestamp = new Date().getTime();
        captchaImage.src = '../captcha.php?ts=' + timestamp;
    }

    document.getElementById('refreshCaptcha').addEventListener('click', refreshCaptcha);

    $(document).ready(function() {
        const passwordInput = document.getElementById('password-input');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'password') {
                passwordToggle.classList.remove('fas', 'fa-eye-slash');
                passwordToggle.classList.add('fas', 'fa-eye');
            } else {
                passwordToggle.classList.remove('fas', 'fa-eye');
                passwordToggle.classList.add('fas', 'fa-eye-slash');
            }
        });
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alert = document.getElementById('error-alert');
            if (alert) {
                alert.style.opacity = 0;
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500); // Allow some time for the fade-out effect
            }
        }, 2000); // 3 seconds
    });
    </script>

    <?php include '../layout/footer.php'; ?>

</body>

</html>

<style>
/* CSS untuk tata letak captcha */
.captcha-container {
    display: flex;
    align-items: center;
}

.captcha-image {
    padding: 4px;
    /* Tambahkan padding agar ada ruang di sekitar gambar captcha */
}

.captcha-input-container {
    display: flex;
    align-items: center;
    height: 40px;
}

.captcha-input-container input {
    margin-right: 0px;
    height: 100%;
}

.captcha-input-container button {
    height: 100%;
}

@media screen and (max-width: 1000px) {
    .captcha-input-container {
        flex-direction: column;
        align-items: flex-start;
        height: auto;
    }

    .captcha-input-container input {
        margin-right: 0;
        margin-bottom: 5px;
    }
}

.modal-content {
    border-radius: 10px;
}

.modal-header {
    border-bottom: none;
}

.otp-field {
    width: 50px;
    height: 50px;
    text-align: center;
    font-size: 1.5rem;
    margin: 5px;
    border: 1px solid #ced4da;
    border-radius: 5px;
}

.modal-footer {
    border-top: none;
    text-align: center;
}

.otp-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.otp-field+.otp-field {
    margin-left: 10px;
}

.btn-disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

#error-alert {
    transition: opacity 0.5s ease-out;
}
</style>