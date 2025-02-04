<?php
session_start();
$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if ($new_password === $cpassword) {
        $query = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_password = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $update_password)) {
                $_SESSION['info'] = "Your password has been successfully updated.";
                echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Updated</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/resetpass.css">
    <script>
        var countdown = 5; // Set countdown to 5 seconds
        function updateCountdown() {
            if (countdown > 0) {
                document.getElementById('countdown').textContent = countdown + ' detik';
                countdown--;
            } else {
                window.location.href = 'signin.html'; // Redirect to signin after countdown reaches 0
            }
        }
        setInterval(updateCountdown, 1000);

        function redirectToSignIn() {
            window.location.href = 'signin.html'; // Redirect to signin.html after countdown
        }
    </script>
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
                    <img alt="Fishervice Logo" src="https://iili.io/2Z190Cl.png"/>
                    Fishervice
                </h1>
                <div class="user">Admin Fishervice</div>
            </div>
        </div>
        <div class="pH">
            <div class="cards">
                <div class="card">
                    <i class="fas fa-check-circle" style="font-size: 65px; color: #29b6f6; margin-bottom: 10px;"></i>
                    <h3>Password Berhasil Diperbarui</h3>
                    <div class="reset-form">
                        <form action="send_reset_link.php" method="POST">
                            <p class="subtitle">Anda akan diarahkan ke halaman login dalam <span id="countdown">5 detik</span></p>
                        </form>
                    </div> 
                </div>        
            </div>
        </div>
        <div class="footer">
            <p><i class="fas fa-envelope"></i>Email: Fishervice4@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i>Location: Telkom University, Bandung, Jawa Barat</p>
            <p><i class="fas fa-phone"></i>Phone: 089520701494 (Muhammad Hasbi Nurhadi)</p>
        </div>
    </div>
</body>
</html>
HTML;
            } else {
                echo "Error updating password: " . mysqli_error($conn);
            }
        } else {
            echo "Invalid or expired token.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>