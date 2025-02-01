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
    die("Koneksi database gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    header('Content-Type: application/json');
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek database dan tabel users
    $db_check = $conn->select_db($dbname);
    if (!$db_check) {
        echo json_encode(['status' => 'error', 'message' => 'Database tidak tersedia. Harap buat akun terlebih dahulu.']);
        exit;
    }

    $sql_check_table = "SHOW TABLES LIKE 'users'";
    $result_check_table = mysqli_query($conn, $sql_check_table);
    if (mysqli_num_rows($result_check_table) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tabel users belum ada. Harap buat akun terlebih dahulu.']);
        exit;
    }

    // Cek apakah email ada di tabel users
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql_update = "UPDATE users SET reset_token = '$token', reset_token_expiry = '$expiry' WHERE email = '$email'";

        if (mysqli_query($conn, $sql_update)) {
            $resetLink = "http://localhost:8002/reset_password_form.php?token=$token";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'fishervice4@gmail.com';
                $mail->Password = 'xkqhagrxqfkppdec';
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
                echo json_encode(['status' => 'success', 'message' => 'Link reset password telah dikirim ke email Anda.']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email. Silakan coba lagi.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memperbarui token.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak ditemukan.']);
    }
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishervice</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/forgotpassword.css">
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
        <div class="user" style="white-space: nowrap;"> 
         Admin Fishervice
        </div>
       </div>
    </div>
    <div class="pH">
     <div class="cards">
     <div class="card">
    <h3>Anda Lupa Password?</h3>
    <div class="reset-form">
        <form id="resetForm">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
    <p class="subtitle">Atau Hubungi Nomor Di Bawah</p>
    <a href="https://wa.me/6289520701494" target="_blank" class="whatsapp-link">
        <p class="whatsapp-box">
            <i class="fab fa-whatsapp"></i> 089520701494
        </p>
    </a>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        showPopup(data.message, data.status === 'success' ? 'success' : 'error');
    })
    .catch(() => {
        showPopup('Terjadi kesalahan, silakan coba lagi.', 'error');
    });
});

function showPopup(message, type) {
    const popup = document.createElement('div');
    popup.className = 'popup';
    popup.innerHTML = `
        <div class="popup-content">
            <p>${message}</p>
            <button onclick="closePopup()">OK</button>
        </div>
    `;
    document.body.appendChild(popup);
}

function closePopup() {
    const popup = document.querySelector('.popup');
    if (popup) {
        popup.remove();
    }
}
</script>
      <div class="footer">
        <p>
            <i class="fas fa-envelope">
            </i>
            Email: Fishervice4@gmail.com
           </p>
           <p>
            <i class="fas fa-map-marker-alt">
            </i>
            Location: Telkom University, Bandung, Jawa Barat
           </p>
           <p>
            <i class="fas fa-phone">
            </i>
            Phone: 089520701494 (Muhammad Hasbi Nurhadi)
           </p>
      </div>
  </div>
  <script>
    function redirectToSignIn() {
    window.location.href = 'signin.html'; // Mengarahkan pengguna ke signin.html
}
  </script>
 </body>
</html>
