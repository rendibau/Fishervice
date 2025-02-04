<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();

$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    echo "<div class='error'>Koneksi gagal: " . $conn->connect_error . "</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishervice - Reset Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 0;
        }
        .main-content {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        .main-content video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .content {
            position: relative;
            z-index: 1;
        }
        .header, .footer {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 30px;
            position: relative;
        }
        .header h1 img {
            width: 50px;
            margin-right: 10px;
        }
        .header h1 i, .header h1 .back-text {
            position: absolute;
            left: -65px;
            cursor: pointer;
            font-size: 20px;
        }
        .card {
            background-color: #ffffffa5;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h3 {
            color: #1e88e5;
            font-size: 24px;
            font-weight: bold;
        }
        .card p {
            font-size: 18px;
            color: #555;
        }
        .error, .success {
            font-size: 18px;
            color: #fff;
            background-color: #29b6f6;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .error {
            background-color: #e57373;
        }
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            color: #e57373;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <video autoplay muted loop>
            <source src="background/Dashboard Fishervice.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="content">
            <div class="header">
                <h1>
                    <a href="javascript:void(0);" onclick="redirectToSignIn()" style="color: black; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i>
                        <span class="back-text">Back</span>
                    </a>
                    <img alt="Fishervice Logo" src="https://iili.io/2Z190Cl.png" />
                    Fishervice
                </h1>
                <div class="user">Admin Fishervice</div>
            </div>
            <div class="card">
                <?php
                $db_check = $conn->select_db($dbname);
                if (!$db_check) {
                    echo "<div class='notification'>Database belum tersedia. Silakan buat akun terlebih dahulu.</div>";
                    exit;
                }

                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $sql_check_table = "SHOW TABLES LIKE 'users'";
                $result_check_table = mysqli_query($conn, $sql_check_table);
                if (mysqli_num_rows($result_check_table) == 0) {
                    echo "<div class='notification'>Tabel users belum ada. Harap buat akun terlebih dahulu.</div>";
                    exit;
                }

                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    $token = bin2hex(random_bytes(50));
                    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    $sql = "UPDATE users SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email'";

                    if (mysqli_query($conn, $sql)) {
                        $resetLink = "http://localhost:8002/reset_password_form.php?token=$token";

                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'fishervice4@gmail.com';
                            $mail->Password = '*'; #your app password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('fishervice4@gmail.com', 'Fishervice Company');
                            $mail->addAddress($email);

                            $mail->isHTML(true);
                            $mail->Subject = 'Reset Your Password';
                            $mail->Body = "
                                <p>Hello,</p>
                                <p>We received a request to reset your password. Please click the link below to reset your password:</p>
                                <p><a href='$resetLink'>Reset Your Password</a></p>
                                <p>Thank you,<br>Fishervice Team</p>
                            ";
                            $mail->send();
                            echo "<div class='success'>Reset password link has been sent to your email.</div>";
                        } catch (Exception $e) {
                            echo "<div class='error'>Mailer Error: {$mail->ErrorInfo}</div>";
                        }
                    } else {
                        echo "<div class='error'>Error updating token: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='error'>Email tidak ditemukan, daftar terlebih dahulu.</div>";
                }

                $conn->close();
                ?>
            </div>
        </div>
        <div class="footer">
            <p><i class="fas fa-envelope"></i> Email: Fishervice4@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Location: Telkom University, Bandung, Jawa Barat</p>
            <p><i class="fas fa-phone"></i> Phone: 089520701494 (Muhammad Hasbi Nurhadi)</p>
        </div>
    </div>
    <script>
        function redirectToSignIn() {
            window.location.href = 'signin.html';
        }
    </script>
</body>
</html>
