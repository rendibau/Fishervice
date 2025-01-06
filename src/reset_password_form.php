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
    // Token tidak valid
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
</head>
<body>
    <h2>Reset Your Password</h2>
    <form method="POST" action="reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="cpassword">Confirm Password:</label>
        <input type="password" id="cpassword" name="cpassword" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
