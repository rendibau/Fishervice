<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Sesuaikan path ini dengan struktur direktori Anda

session_start();
$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Jakarta');

$email = mysqli_real_escape_string($conn, $_POST['email']);

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
            $mail->Username = 'kikifishervice@gmail.com';
            $mail->Password = '*'; //App Password bukan Password Asli
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kikifishervice@gmail.com', 'Fishervice Company');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $mail->Body = "
                <p>Hello,</p>
                <p>We received a request to reset your password. Please click the link below to reset your password:</p>
                <p><a href='$resetLink'>Reset Your Password</a></p>
                <p>If you did not request a password reset, please ignore this email.</p>
                <p>Thank you,<br>Fishervice Team</p>
            ";

            $mail->AltBody = "Hello,\n\nWe received a request to reset your password. Please use the link below to reset your password:\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThank you,\nFishervice Team";
            $mail->send();
            echo "Reset password link has been sent to your email.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error updating token: " . $conn->error;
    }
} else {
    echo "Email not found.";
}

$conn->close();
?>
