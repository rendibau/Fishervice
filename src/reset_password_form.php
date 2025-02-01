<?php
session_start();

// Koneksi ke database
$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah token ada di URL
if (!isset($_GET['token'])) {
    die("Invalid or missing token.");
}

$token = $_GET['token'];

// Validasi token di database
$query = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Invalid or expired token.");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/resetpassform.css">
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
                    <img src="https://iili.io/2Z190Cl.png" alt="Fishervice Logo"/>
                    Fishervice
                </h1>
                <div class="user">Admin Fishervice</div>
            </div>
        </div>
        <div class="pH">
            <div class="cards">
                <div class="card">
                    <h3>Reset Your Password</h3>
                    <div class="reset-form">
                        <form method="POST" action="reset_password.php">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <input type="password" id="password" name="password" placeholder="New Password" required>
                            <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
                            <button type="submit">Reset Password</button>
                        </form>
                    </div>
                </div>
                <div class="footer">
                <p><i class="fas fa-envelope"></i> Email: Fishervice4@gmail.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Location: Telkom University, Bandung, Jawa Barat</p>
                <p><i class="fas fa-phone"></i> Phone: 089520701494 (Muhammad Hasbi Nurhadi)</p>
            </div>
        </div>
    </div>
    <script>
        function redirectToSignIn() {
            window.location.href = 'signin.html'; // Mengarahkan pengguna ke signin.html
        }
    </script>
</body>
</html>
            </div>
        </div>
    </div>
</body>
</html>
